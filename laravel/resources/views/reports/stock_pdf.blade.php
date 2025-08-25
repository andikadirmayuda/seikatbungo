<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Stok Produk - Seikat Bungo</title>
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
            grid-template-columns: repeat(4, 1fr);
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
            font-size: 18px;
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
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">Seikat Bungo</h1>
        <h2 class="report-title">Laporan Stok Produk</h2>
        <div class="period">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-card">
                <h3>{{ $products->count() }}</h3>
                <p>Total Produk</p>
            </div>
            <div class="summary-card">
                <h3>{{ $products->sum(fn($p) => $rekap[$p->id]['masuk'] ?? 0) }}</h3>
                <p>Total Stok Masuk</p>
            </div>
            <div class="summary-card">
                <h3>{{ $products->sum(fn($p) => $rekap[$p->id]['keluar'] ?? 0) }}</h3>
                <p>Total Stok Keluar</p>
            </div>
            <div class="summary-card">
                <h3>{{ $products->sum(fn($p) => $rekap[$p->id]['penyesuaian'] ?? 0) }}</h3>
                <p>Total Penyesuaian</p>
            </div>
        </div>
    </div>

    <!-- Rekap Stok Table -->
    <div class="section-title">Rekap Stok Produk</div>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th class="text-center">Stok Masuk</th>
                <th class="text-center">Stok Keluar</th>
                <th class="text-center">Penyesuaian</th>
                <th class="text-center">Stok Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td class="text-center">{{ $rekap[$product->id]['masuk'] ?? 0 }}</td>
                    <td class="text-center">{{ $rekap[$product->id]['keluar'] ?? 0 }}</td>
                    <td class="text-center">{{ $rekap[$product->id]['penyesuaian'] ?? 0 }}</td>
                    <td class="text-center">{{ $rekap[$product->id]['stok_akhir'] ?? $product->current_stock }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($logs->count() > 0)
        <div class="page-break"></div>
        <div class="section-title">Log Perubahan Stok (50 Terbaru)</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th class="text-center">Perubahan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->product->name ?? '-' }}</td>
                        <td class="text-center">{{ $log->qty > 0 ? '+' : '' }}{{ $log->qty }}</td>
                        <td>{{ $log->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Laporan Stok Produk - Seikat Bungo | Dicetak pada {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>

</html>