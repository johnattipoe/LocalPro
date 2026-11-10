<?php

namespace App\Http\Controllers\ChatController;

use App\Http\Controllers\Controller\Controller;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function reply(Request $request): JsonResponse
    {
        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:10'],
            'messages.*.role' => ['required', 'in:user,model'],
            'messages.*.text' => ['required', 'string', 'max:1000'],
        ]);

        $apiKey = config('services.gemini.api_key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            return response()->json([
                'message' => 'The LocalPro AI assistant is not configured yet. Please contact support.',
            ], 503)->header('Cache-Control', 'no-store');
        }

        $lastMessage = $data['messages'][array_key_last($data['messages'])];
        if ($lastMessage['role'] !== 'user') {
            return response()->json(['message' => 'Send a new message to continue the chat.'], 422)
                ->header('Cache-Control', 'no-store');
        }

        $model = config('services.gemini.model', 'gemini-3-flash-preview');
        $transcript = collect($data['messages'])
            ->map(fn (array $message) => ($message['role'] === 'user' ? 'Customer' : 'LocalPro Help').":\n".$message['text'])
            ->implode("\n\n");

        try {
            $request = Http::timeout(25);
            $caBundle = config('services.gemini.ca_bundle');
            if (is_string($caBundle) && $caBundle !== '') {
                $request = $request->withOptions(['verify' => $caBundle]);
            }

            $upstream = $request
                ->withHeaders([
                    'x-goog-api-key' => $apiKey,
                    'Api-Revision' => '2026-05-20',
                ])
                ->post('https://generativelanguage.googleapis.com/v1beta/interactions', [
                    'model' => $model,
                    'input' => $transcript,
                    'system_instruction' => 'You are LocalPro Help, a friendly assistant for LocalPro, a marketplace connecting customers with local service professionals in Ho and the Volta Region of Ghana. Help with finding providers, profile details, service requests, bookings, messages, payments, verification, and account access. Be concise and practical. Do not claim you can see or change a user account, booking, payment, or provider status. Never ask for passwords, one-time codes, payment card numbers, or API keys. Explain that prices and availability should be confirmed directly with the professional. For account-specific problems or complaints, direct the user to LocalPro support. If unsure, say so rather than inventing LocalPro policies.',
                    'generation_config' => [
                        'thinking_level' => 'minimal',
                        'max_output_tokens' => 400,
                    ],
                ]);
        } catch (ConnectionException $exception) {
            Log::warning('Gemini chatbot request failed.', ['exception' => $exception::class]);

            return response()->json([
                'message' => 'I could not reach the assistant right now. Please try again or contact support.',
            ], 502)->header('Cache-Control', 'no-store');
        }

        if (! $upstream->successful()) {
            Log::warning('Gemini chatbot returned an unsuccessful response.', [
                'status' => $upstream->status(),
            ]);

            return response()->json([
                'message' => 'The assistant is temporarily unavailable. Please try again or contact support.',
            ], 502)->header('Cache-Control', 'no-store');
        }

        $reply = collect($upstream->json('steps', []))
            ->where('type', 'model_output')
            ->flatMap(fn (array $step) => $step['content'] ?? [])
            ->where('type', 'text')
            ->pluck('text')
            ->filter(fn ($text) => is_string($text))
            ->implode("\n");

        if ($reply === '') {
            return response()->json([
                'message' => 'I could not prepare a reply. Please try rephrasing your question or contact support.',
            ], 502)->header('Cache-Control', 'no-store');
        }

        return response()->json(['reply' => $reply])->header('Cache-Control', 'no-store');
    }
}
