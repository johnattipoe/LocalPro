<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'webpush:generate-vapid {--force : Replace existing VAPID keys}';

    protected $description = 'Generate and save Web Push VAPID keys in the local environment file';

    public function handle(): int
    {
        $environmentPath = base_path('.env');
        $contents = file_get_contents($environmentPath);

        if ($contents === false) {
            $this->error('Could not read the .env file.');

            return self::FAILURE;
        }

        $lines = preg_split('/\r\n|\n|\r/', $contents);
        $keys = ['VAPID_PUBLIC_KEY', 'VAPID_PRIVATE_KEY'];

        foreach ($keys as $key) {
            $matches = [];
            foreach ($lines as $index => $line) {
                if (preg_match('/^\s*'.preg_quote($key, '/').'\s*=\s*(.*)$/', $line, $match)) {
                    $matches[$index] = trim($match[1], " \t\"'");
                }
            }

            if (count($matches) > 1) {
                $this->error("The .env file contains duplicate {$key} entries.");

                return self::FAILURE;
            }

            if ($matches !== [] && reset($matches) !== '' && ! $this->option('force')) {
                $this->error('VAPID keys already exist. Use --force only if you intend to replace them.');

                return self::FAILURE;
            }
        }

        try {
            $vapidKeys = VAPID::createVapidKeys();
        } catch (\Throwable $e) {
            report($e);
            $this->error('VAPID key generation failed.');

            return self::FAILURE;
        }

        $newline = str_contains($contents, "\r\n") ? "\r\n" : "\n";
        foreach ([
            'VAPID_PUBLIC_KEY' => $vapidKeys['publicKey'],
            'VAPID_PRIVATE_KEY' => $vapidKeys['privateKey'],
        ] as $key => $value) {
            $found = false;
            foreach ($lines as $index => $line) {
                if (preg_match('/^\s*'.preg_quote($key, '/').'\s*=/', $line)) {
                    $lines[$index] = $key.'='.$value;
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                $lines[] = $key.'='.$value;
            }
        }

        if (file_put_contents($environmentPath, implode($newline, $lines), LOCK_EX) === false) {
            $this->error('Could not write VAPID keys to the .env file.');

            return self::FAILURE;
        }

        $this->call('config:clear');
        $this->info('VAPID keys were generated and saved to .env. Keep VAPID_PRIVATE_KEY secret.');

        return self::SUCCESS;
    }
}
