<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_user_receives_a_reset_link_and_can_set_a_new_password(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'customer@example.test']);
        $resetToken = null;

        $this->post('/forgot-password', ['email' => $user->email])
            ->assertRedirect()
            ->assertSessionHas('status', 'If an account exists for that email, a reset link will be sent shortly.');

        $this->get('/forgot-password')
            ->assertSuccessful()
            ->assertSee('Check your inbox')
            ->assertSee('role="status"', false)
            ->assertSee('If an account exists for that email, a reset link will be sent shortly.');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use (&$resetToken) {
            $resetToken = $notification->token;
            return true;
        });

        $this->get(route('password.reset', ['token' => $resetToken, 'email' => $user->email]))
            ->assertSuccessful()
            ->assertSee('Choose a new password');

        $this->post('/reset-password', [
            'token' => $resetToken,
            'email' => $user->email,
            'password' => 'NewStrongPass123',
            'password_confirmation' => 'NewStrongPass123',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('NewStrongPass123', $user->fresh()->password));
        $this->assertDatabaseMissing('password_resets', ['email' => $user->email]);
    }

    public function test_unknown_email_gets_the_same_confirmation_without_sending_a_notification(): void
    {
        Notification::fake();

        $this->post('/forgot-password', ['email' => 'unknown@example.test'])
            ->assertRedirect()
            ->assertSessionHas('status', 'If an account exists for that email, a reset link will be sent shortly.')
            ->assertSessionMissing('errors');

        Notification::assertNothingSent();
    }

    public function test_local_log_mailer_explains_where_the_reset_link_is_written(): void
    {
        Config::set('mail.default', 'log');
        Notification::fake();

        $this->post('/forgot-password', ['email' => 'local@example.test'])
            ->assertRedirect()
            ->assertSessionHas('status', 'If an account exists for that email, reset instructions were written to the local application log.');

        $this->get('/forgot-password')->assertSee('Reset link saved locally');

        Notification::assertNothingSent();
    }

    public function test_reset_rejects_a_password_that_does_not_match_confirmation(): void
    {
        $this->from('/reset-password/test-token')->post('/reset-password', [
            'token' => 'test-token',
            'email' => 'person@example.test',
            'password' => 'NewStrongPass123',
            'password_confirmation' => 'DifferentPass123',
        ])->assertRedirect('/reset-password/test-token')->assertSessionHasErrors('password');
    }
}
