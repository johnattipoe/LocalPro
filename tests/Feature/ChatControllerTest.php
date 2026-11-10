<?php

namespace Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    public function test_it_uses_the_gemini_interactions_api_and_returns_model_output(): void
    {
        Config::set('services.gemini.api_key', 'test-api-key');
        Config::set('services.gemini.model', 'gemini-3-flash-preview');
        Config::set('services.gemini.ca_bundle', null);
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'status' => 'completed',
                'steps' => [
                    ['type' => 'thought', 'content' => []],
                    ['type' => 'model_output', 'content' => [
                        ['type' => 'text', 'text' => 'You can find services on the Services page.'],
                    ]],
                ],
            ]),
        ]);

        $this->postJson(route('assistant.chat'), [
            'messages' => [
                ['role' => 'user', 'text' => 'How do I find a service?'],
                ['role' => 'model', 'text' => 'What kind of service do you need?'],
                ['role' => 'user', 'text' => 'A plumber.'],
            ],
        ])
            ->assertOk()
            ->assertJson([
                'reply' => 'You can find services on the Services page.',
            ]);

        Http::assertSent(fn (Request $request) => $request->url() === 'https://generativelanguage.googleapis.com/v1beta/interactions'
            && $request->hasHeader('Api-Revision', '2026-05-20')
            && $request['model'] === 'gemini-3-flash-preview'
            && $request['input'] === "Customer:\nHow do I find a service?\n\nLocalPro Help:\nWhat kind of service do you need?\n\nCustomer:\nA plumber."
            && $request['generation_config']['thinking_level'] === 'minimal');
    }

    public function test_it_returns_a_safe_unavailable_message_when_gemini_rejects_the_request(): void
    {
        Config::set('services.gemini.api_key', 'test-api-key');
        Config::set('services.gemini.model', 'gemini-3-flash-preview');
        Config::set('services.gemini.ca_bundle', null);
        Http::fake([
            'generativelanguage.googleapis.com/v1beta/interactions' => Http::response([
                'error' => ['message' => 'Private provider response'],
            ], 503),
        ]);

        $this->postJson(route('assistant.chat'), [
            'messages' => [['role' => 'user', 'text' => 'Hello']],
        ])
            ->assertStatus(502)
            ->assertJson([
                'message' => 'The assistant is temporarily unavailable. Please try again or contact support.',
            ])
            ->assertDontSee('Private provider response');
    }

    public function test_it_does_not_call_gemini_when_the_last_message_is_not_from_the_user(): void
    {
        Config::set('services.gemini.api_key', 'test-api-key');
        Http::fake();

        $this->postJson(route('assistant.chat'), [
            'messages' => [['role' => 'model', 'text' => 'Previous reply']],
        ])
            ->assertStatus(422)
            ->assertJson(['message' => 'Send a new message to continue the chat.']);

        Http::assertNothingSent();
    }
}
