@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<x-app-layout>
    <x-slot name="title">Payment</x-slot>

    <div class="max-w-md mx-auto text-center py-10">

        <h2 class="text-xl font-semibold mb-2">
            Pembayaran di Kasir
        </h2>

        <p class="text-gray-600 mb-6">
            Tunjukkan QR ini ke kasir untuk melakukan pembayaran
        </p>

        <div class="flex justify-center mb-6">
            {!! QrCode::size(250)->generate($payment->qr_token) !!}
        </div>

        <div class="bg-gray-100 rounded-lg p-4 text-left">
            <p><strong>No. Order:</strong> {{ $payment->order->order_number }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($payment->amount) }}</p>
            <div id="payment-status" class="mt-6">
                <p class="text-gray-500">
                    Menunggu pembayaran di kasir...
                </p>
            </div>
        </div>

    </div>

    <script>
        const statusUrl = "{{ route('order.cash.status', $payment->qr_token) }}";
        const summaryUrl = "{{ route('order.summary', $payment->order_id) }}";

        const checkPaymentStatus = async () => {
            try {
                const response = await fetch(statusUrl);

                if (!response.ok) {
                    return;
                }

                const data = await response.json();

                if (data.paid) {
                    window.location.href = summaryUrl;
                }

            } catch (error) {
                console.error('Gagal mengecek status pembayaran:', error);
            }
        };

        setInterval(checkPaymentStatus, 3000);
    </script>
</x-app-layout>
