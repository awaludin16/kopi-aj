<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verifikasi token callback webhook
        $callbackToken = $request->header('x-callback-token');

        if ($callbackToken !== config('services.xendit.callback_token')) {
            Log::warning('Xendit webhook invalid token', [
                'header' => $callbackToken
            ]);

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit Callback Received', $payload);

        $externalId = $payload['external_id'] ?? null;
        if (!$externalId) {
            return response()->json(['message' => 'Invalid payload: external_id missing'], 400);
        }

        // 2. Cari payment berdasarkan external_id
        $payment = Payment::where('external_id', $externalId)->first();

        if (!$payment) {
            Log::error('Payment not found for webhook', $payload);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $status = strtoupper($payload['status'] ?? '');

        if (in_array($status, ['PAID', 'SETTLED'])) {
            $paymentMethod = $payload['payment_method'] ?? 'Online';
            if (!empty($payload['payment_channel'])) {
                $paymentMethod = $payload['payment_channel'];
            }

            DB::transaction(function () use ($payment, $payload, $paymentMethod) {
                $payment->update([
                    'payment_method' => $paymentMethod,
                    'status'         => PaymentStatus::PAID,
                    'paid_at'        => now(),
                    'reference_id'   => $payload['id'] ?? $payment->reference_id,
                    'payload'        => $payload,
                ]);

                if ($payment->order) {
                    $payment->order->update([
                        'status' => OrderStatus::PAID,
                    ]);
                }
            });

            Log::info("Webhook Xendit sukses memproses pembayaran untuk Payment #{$payment->id}");
        } elseif ($status === 'EXPIRED') {
            $payment->update([
                'status'  => PaymentStatus::EXPIRED,
                'payload' => $payload,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Webhook processed successfully'
        ], 200);
    }
}
