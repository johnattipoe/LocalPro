<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\ServiceRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function __construct(private NotificationService $notifications)
    {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->is_active,403);
        $userId = $request->user()->id;
        $latestMessageIds = Message::selectRaw('MAX(id)')
            ->where(fn ($query) => $query->where('sender_id', $userId)->orWhere('recipient_id', $userId))
            ->groupBy('service_request_id');

        $messages = Message::with([
                'serviceRequest.provider.user',
                'serviceRequest.customer',
                'serviceRequest.category',
                'sender',
            ])
            ->whereIn('id', $latestMessageIds)
            ->orderByDesc('id')
            ->paginate(20);

        return view('customer.messages.index',compact('messages'));
    }

    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        $user = $request->user();
        abort_unless($user->is_active,403);
        $profile = $user->providerProfile;
        $isCustomer = $serviceRequest->customer_id === $user->id;
        $isProvider = $profile && $serviceRequest->provider_profile_id === $profile->id;
        abort_unless($isCustomer || $isProvider, 404);

        $otherUser = $isCustomer
            ? $serviceRequest->provider()->with('user')->firstOrFail()->user
            : $serviceRequest->customer;

        Message::where('service_request_id', $serviceRequest->id)
            ->where('recipient_id', $user->id)->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = Message::with('sender')->where('service_request_id', $serviceRequest->id)
            ->orderBy('created_at')->get();

        return view('messages.index', compact('serviceRequest', 'otherUser', 'messages'));
    }

    public function store(Request $request, ServiceRequest $serviceRequest)
    {
        $user = $request->user();
        abort_unless($user->is_active,403);
        $profile = $user->providerProfile;
        $isCustomer = $serviceRequest->customer_id === $user->id;
        $isProvider = $profile && $serviceRequest->provider_profile_id === $profile->id;
        abort_unless($isCustomer || $isProvider, 404);

        $data = $request->validate(['body' => ['required', 'string', 'min:1', 'max:4000']]);
        $recipient = $isCustomer
            ? $serviceRequest->provider()->with('user')->firstOrFail()->user
            : $serviceRequest->customer;

        DB::transaction(function () use ($serviceRequest, $user, $recipient, $data) {
            Message::create([
                'service_request_id' => $serviceRequest->id,
                'sender_id' => $user->id,
                'recipient_id' => $recipient->id,
                'body' => $data['body'],
            ]);

            $this->notifications->send(
                $recipient,
                'New message from ' . $user->name,
                'You have a new message about service request #' . $serviceRequest->id . '.'
            );
        });

        return redirect()->route('messages.show', $serviceRequest)->with('status', 'Message sent.');
    }

    public function updates(Request $request, ServiceRequest $serviceRequest): JsonResponse
    {
        $user = $request->user();
        abort_unless($user->is_active, 403);
        $profile = $user->providerProfile;
        $isCustomer = $serviceRequest->customer_id === $user->id;
        $isProvider = $profile && $serviceRequest->provider_profile_id === $profile->id;
        abort_unless($isCustomer || $isProvider, 404);

        $after = $request->validate(['after' => ['nullable', 'integer', 'min:0']])['after'] ?? 0;
        $messages = Message::with('sender')
            ->where('service_request_id', $serviceRequest->id)
            ->where('id', '>', $after)
            ->orderBy('id')
            ->limit(100)
            ->get();

        Message::whereIn('id', $messages->where('recipient_id', $user->id)->pluck('id'))
            ->whereNull('read_at')->update(['read_at' => now()]);

        return response()->json([
            'messages' => $messages->map(fn (Message $message) => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_name' => $message->sender_id === $user->id ? 'You' : $message->sender->name,
                'body' => $message->body,
                'created_at' => $message->created_at->format('M j, g:i A'),
            ])->values(),
        ]);
    }
}
