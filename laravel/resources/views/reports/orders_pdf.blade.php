<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan Online - Seikat Bungo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin: 0;
        }

        .report-title {
            font-size: 18px;
            color: #666;
            margin: 5px 0;
        }

        .period {
            background: #f5f5f5;
            padding: 8px;
            border-radius: 4px;
            margin: 15px 0;
            text-align: center;
        }

        .summary {
            margin: 20px 0;
            padding: 15px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
        }

        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: #333;
        }

        .summary-card p {
            margin: 0;
            font-size: 11px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #666;
            padding: 10px;
            border-top: 1px solid #ddd;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 25px 0 15px 0;
            color: #333;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        .currency {
            font-weight: bold;
        }

        .status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.completed {
            background: #cce5ff;
            color: #004085;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-status {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .payment-status.belum_bayar {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-status.dp {
            background: #fff3cd;
            color: #856404;
        }

        .payment-status.lunas {
            background: #d4edda;
            color: #155724;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">Seikat Bungo</h1>
        <h2 class="report-title">Laporan Pesanan Online</h2>
        <div class="period">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-card">
                <h3>{{ $totalOrder }}</h3>
                <p>Total Pemesanan Online</p>
            </div>
            <div class="summary-card">
                <h3 class="currency">Rp{{ number_format($totalNominal, 0, ',', '.') }}</h3>
                <p>Total Nilai Pesanan</p>
            </div>
            <div class="summary-card">
                <h3>{{ $totalLunas }}</h3>
                <p>Sudah Dibayar Lunas</p>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="section-title">Detail Pesanan Online</div>
    <table>
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Customer</th>
                <th>Tanggal Ambil</th>
                <th>Status</th>
                <th>Status Bayar</th>
                <th>Metode Bayar</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                @php
                    $orderTotal = $order->items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                    $paymentMethod = $order->payment_method ? ucfirst($order->payment_method) : '-';
                    $paymentClass = match (strtolower($order->payment_method)) {
                        'cash' => 'background: #d4edda; color: #155724;',
                        'transfer' => 'background: #cce5ff; color: #004085;',
                        'qris' => 'background: #f3e8ff; color: #6f42c1;',
                        'cod' => 'background: #fff3cd; color: #856404;',
                        default => 'background: #f5f5f5; color: #333;',
                    };
                @endphp
                <tr>
                    <td>{{ $order->public_code ?? 'PO-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->pickup_date)->format('d M Y') }}</td>
                    <td>
                        <span class="status {{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="payment-status {{ $order->payment_status ?? 'belum_bayar' }}">
                            {{ $order->payment_status ? ucfirst(str_replace('_', ' ', $order->payment_status)) : 'Belum Bayar' }}
                        </span>
                    </td>
                    <td>
                        <span
                            style="padding:2px 6px; border-radius:3px; font-size:10px; font-weight:bold; {{ $paymentClass }}">
                            {{ $paymentMethod }}
                        </span>
                    </td>
                    <td class="text-right currency">Rp{{ number_format($orderTotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pesanan online</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->count() > 0)
        <div class="page-break"></div>
        <div class="section-title">Detail Item Pesanan</div>
        @foreach($orders as $order)
            @if($order->items->count() > 0)
                <h4 style="margin: 20px 0 10px 0;">{{ $order->public_code ?? 'PO-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) }} -
                    {{ $order->customer_name }}
                </h4>
                <table style="margin-bottom: 20px;">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-right">Harga</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-right currency">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="text-right currency">Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        <tr style="background-color: #f0f0f0; font-weight: bold;">
                            <td colspan="3"><strong>TOTAL PESANAN</strong></td>
                            <td class="text-right currency">
                                Rp{{ number_format($order->items->sum(function ($item) {
                            return $item->quantity * $item->price; }), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @endforeach
    @endif

    <div class="footer">
        <p>Laporan Pesanan Online - Seikat Bungo | Dicetak pada {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>

</html>