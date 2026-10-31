<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $order->order_number }}</title>
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #000;
            background: #f3f4f6;
        }

        /* Receipt Container */
        .receipt {
            width: 80mm;
            max-width: 100%;
            margin: 20px auto;
            padding: 12mm 6mm;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }

        .receipt-header .shop-name {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .receipt-header .shop-tagline {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* Info Section */
        .receipt-info {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
            font-size: 11px;
        }

        .receipt-info .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        /* Items Table */
        .receipt-items {
            width: 100%;
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }

        .receipt-items .item-row {
            margin-bottom: 4px;
        }

        .receipt-items .item-name {
            font-size: 12px;
            font-weight: bold;
        }

        .receipt-items .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding-left: 10px;
        }

        /* Totals */
        .receipt-totals {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 1px dashed #000;
        }

        .receipt-totals .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .receipt-totals .grand-total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 4px;
            padding-top: 4px;
            border-top: 1px solid #000;
        }

        /* Footer */
        .receipt-footer {
            text-align: center;
            font-size: 11px;
            margin-top: 10px;
        }

        .receipt-footer .thank-you {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        /* Print Controls (hidden when printing) */
        .print-controls {
            text-align: center;
            margin: 20px auto;
            max-width: 80mm;
        }

        .print-controls button {
            padding: 10px 24px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin: 0 4px;
            transition: all 0.2s;
        }

        .btn-print {
            background: #16a34a;
            color: white;
        }

        .btn-print:hover {
            background: #15803d;
        }

        .btn-back {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-back:hover {
            background: #d1d5db;
        }

        /* Print Styles */
        @media print {
            body {
                background: #fff;
            }

            .receipt {
                box-shadow: none;
                margin: 0;
                padding: 2mm;
                width: 80mm;
            }

            .print-controls {
                display: none !important;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Print Controls --}}
    <div class="print-controls">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Struk
        </button>
        <button class="btn-back" onclick="window.close(); window.history.back();">
            ← Kembali
        </button>
    </div>

    {{-- Receipt --}}
    <div class="receipt">

        {{-- Header --}}
        <div class="receipt-header">
            <div class="shop-name">KOPI AJ</div>
            <div class="shop-tagline">Terima Kasih Atas Kunjungan Anda</div>
        </div>

        {{-- Order Info --}}
        <div class="receipt-info">
            <div class="info-row">
                <span>No. Order</span>
                <span>#{{ $order->order_number }}</span>
            </div>
            <div class="info-row">
                <span>Meja</span>
                <span>{{ $order->table->table_number }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal</span>
                <span>{{ $order->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span>Waktu</span>
                <span>{{ $order->created_at->format('H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Bayar</span>
                <span>{{ $order->payment->payment_method === 'cash' ? 'Cash' : 'Online' }}</span>
            </div>
            <div class="info-row">
                <span>Kasir</span>
                <span>{{ auth()->user()->name ?? '-' }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="receipt-items">
            @foreach ($order->items as $item)
                <div class="item-row">
                    <div class="item-name">{{ $item->menu->name }}</div>
                    <div class="item-detail">
                        <span>{{ $item->qty }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span>{{ number_format($item->price * $item->qty, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Totals --}}
        <div class="receipt-totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            @if ($order->pb1 > 0)
                <div class="total-row">
                    <span>PB1 (10%)</span>
                    <span>{{ number_format($order->pb1, 0, ',', '.') }}</span>
                </div>
            @endif
            @if ($order->service_charge > 0)
                <div class="total-row">
                    <span>Service</span>
                    <span>{{ number_format($order->service_charge, 0, ',', '.') }}</span>
                </div>
            @endif
            @if ($order->rounding > 0)
                <div class="total-row">
                    <span>Pembulatan</span>
                    <span>-{{ number_format($order->rounding, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <div class="thank-you">Terima Kasih!</div>
            <div>Selamat menikmati ☕</div>
            @if ($order->note)
                <div style="margin-top: 6px; font-size: 10px; color: #666;">
                    Catatan: {{ $order->note }}
                </div>
            @endif
        </div>

    </div>

</body>

</html>

