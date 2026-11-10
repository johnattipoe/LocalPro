<?php

namespace Tests\Feature;

use Minishlink\WebPush\VAPID;
use Tests\TestCase;

class GenerateVapidKeysTest extends TestCase
{
    public function test_command_persists_a_valid_keypair_without_printing_the_private_key(): void
    {
        $directory = sys_get_temp_dir().DIRECTORY_SEPARATOR.'localpro-vapid-'.bin2hex(random_bytes(6));
        mkdir($directory);
        $environmentPath = $directory.DIRECTORY_SEPARATOR.'.env';
        file_put_contents($environmentPath, "VAPID_PUBLIC_KEY=\r\nVAPID_PRIVATE_KEY=\r\n");
        $originalBasePath = $this->app->basePath();

        try {
            $this->app->setBasePath($directory);
            $this->artisan('webpush:generate-vapid')
                ->expectsOutputToContain('VAPID keys were generated and saved to .env.')
                ->assertSuccessful();

            $contents = file_get_contents($environmentPath);
            preg_match('/^VAPID_PUBLIC_KEY=(.+)$/m', $contents, $publicKey);
            preg_match('/^VAPID_PRIVATE_KEY=(.+)$/m', $contents, $privateKey);
            $this->assertNotEmpty($publicKey[1] ?? null);
            $this->assertNotEmpty($privateKey[1] ?? null);

            VAPID::validate([
                'subject' => 'mailto:test@example.com',
                'publicKey' => $publicKey[1],
                'privateKey' => $privateKey[1],
            ]);
        } finally {
            $this->app->setBasePath($originalBasePath);
            unlink($environmentPath);
            rmdir($directory);
        }
    }
}
