<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Seikat Bungo</title>
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

        .summary-item {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
        }

        th {
            background: #f8f9fa;
            color: #333;
            font-weight: bold;
            padding: 12px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .page-number {
            text-align: right;
            font-size: 10px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">Seikat Bungo</h1>
        <p class="report-title">Laporan Penjualan</p>
    </div>

    <div class="period">
        Periode: {{ \Carbon\Carbon::parse($start)->format('d F Y') }} s/d
        {{ \Carbon\Carbon::parse($end)->format('d F Y') }}
    </div>

    <div class="summary">
        <div class="summary-item">Total Transaksi: <strong>{{ $totalTransaksi }}</strong> transaksi</div>
        <div class="summary-item">Total Pendapatan:
            <strong>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 20%">No. Transaksi</th>
                <th style="width: 15%">Metode Bayar</th>
                <th style="width: 25%">Items</th>
                <th style="width: 20%" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $sale->order_number }}</td>
                    <td>{{ ucfirst($sale->payment_method) }}</td>
                    <td>
                        @foreach($sale->items as $item)
                            {{ $item->product ? $item->product->name : 'Produk Dihapus' }} ({{ $item->quantity }}x)<br>
                        @endforeach
                    </td>
                    <td class="text-right">Rp{{ number_format($sale->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total Pendapatan</strong></td>
                <td class="text-right"><strong>Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dicetak pada {{ now()->format('d F Y H:i') }} WIB</p>
    </div>

    <div class="page-number">
        Halaman 1
    </div>
</body>

</html>