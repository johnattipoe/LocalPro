<?php

namespace App\Http\Controllers\PaymentController;

use App\Http\Controllers\Controller\Controller;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Services\PaymentService\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $payments)
    {
    }

    public function index(Request $request)
    {
        $payments = Payment::with(['booking.serviceRequest', 'booking.provider.user'])
            ->where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return view('customer.payments.index', compact('payments'));
    }

    public function checkout(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless((int) $serviceRequest->customer_id === (int) $request->user()->id, 404);
        abort_unless($serviceRequest->status === 'completed' && $serviceRequest->booking, 422);

        $booking = $serviceRequest->booking;
        $amount = (int) round(((float) $booking->final_price) * 100);
        abort_unless($amount > 0, 422, 'The provider has not added a final price for this job.');
        abort_if($booking->payments()->where('status', 'paid')->exists(), 409, 'This job has already been paid.');

        if (! config('services.paystack.secret_key')) {
            return back()->withErrors(['payment' => 'Online payments are not available right now. Please contact LocalPro support for help.']);
        }

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'customer_id' => $request->user()->id,
            'amount' => number_format($amount / 100, 2, '.', ''),
            'currency' => 'GHS',
            'provider_reference' => 'LP-' . strtoupper(Str::random(24)),
            'status' => 'pending',
        ]);

        $checkoutUrl = $this->payments->startCheckout(
            $payment,
            $request->user()->email,
            route('customer.payments.callback'),
            [
                'payment_id' => $payment->id,
                'booking_id' => $booking->id,
                'service_request_id' => $serviceRequest->id,
                'customer_id' => $request->user()->id,
            ]
        );

        if (! $checkoutUrl) {
            return back()->withErrors(['payment' => 'We could not start secure checkout. Please try again in a moment.']);
        }

        return redirect()->away($checkoutUrl);
    }

    public function callback(Request $request)
    {
        $payment = Payment::where('provider_reference', $request->query('reference'))
            ->where('customer_id', $request->user()->id)
            ->firstOrFail();

        if (! $this->payments->confirm($payment)) {
            return redirect()->route('customer.payments.index')->withErrors([
                'payment' => 'Payment is not confirmed. If you were charged, contact support with reference ' . $payment->provider_reference . '.',
            ]);
        }

        return redirect()->route('customer.payments.index')->with('status', 'Your payment was confirmed.');
    }

    public function webhook(Request $request)
    {
        $secret = config('services.paystack.secret_key');
        $signature = $request->header('x-paystack-signature');
        if (! $secret || ! $signature) {
            return response('Unauthorized', 401);
        }

        $expected = hash_hmac('sha512', $request->getContent(), $secret);
        if (! hash_equals($expected, $signature)) {
            return response('Invalid signature', 401);
        }

        $event = $request->json()->all();
        if (($event['event'] ?? null) !== 'charge.success') {
            return response()->json(['received' => true]);
        }

        $payment = Payment::where('provider_reference', data_get($event, 'data.reference'))->first();
        if (! $payment || $payment->status === 'paid') {
            return response()->json(['received' => true]);
        }

        return $this->payments->confirm($payment)
            ? response()->json(['received' => true])
            : response()->json(['error' => 'Transaction verification failed'], 400);
    }
}
