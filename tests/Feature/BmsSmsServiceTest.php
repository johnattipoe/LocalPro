<?php

namespace Tests\Feature;

use App\Services\NotificationService\SmsService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BmsSmsServiceTest extends TestCase
{
    public function test_it_sends_sms_using_the_bms_quick_sms_api(): void
    {
        Config::set('services.sms.api_key', 'test-api-key');
        Config::set('services.sms.sender', 'LocalPro');
        Http::fake([
            'api.mnotify.com/api/sms/quick?*' => Http::response([
                'status' => 'success',
                'code' => '2000',
                'summary' => ['_id' => 'campaign-123'],
            ]),
        ]);

        $result = (new SmsService())->send('0241234567', 'Booking confirmed');

        $this->assertSame(['success' => true, 'message_id' => 'campaign-123'], $result);
        Http::assertSent(fn (Request $request) => $request->url() === 'https://api.mnotify.com/api/sms/quick?key=test-api-key'
            && $request['recipient'] === ['0241234567']
            && $request['sender'] === 'LocalPro'
            && $request['message'] === 'Booking confirmed'
            && $request['is_schedule'] === false
            && $request['schedule_date'] === '');
    }

    public function test_it_reports_bms_api_rejections_without_exposing_provider_details(): void
    {
        Config::set('services.sms.api_key', 'test-api-key');
        Config::set('services.sms.sender', 'LocalPro');
        Http::fake([
            'api.mnotify.com/api/sms/quick?*' => Http::response([
                'status' => 'error',
                'code' => '4000',
                'message' => 'Sensitive provider response',
            ]),
        ]);

        $result = (new SmsService())->send('0241234567', 'Booking confirmed');

        $this->assertSame(['success' => false, 'error' => 'SMS send failed.'], $result);
        Http::assertSentCount(1);
    }
}
