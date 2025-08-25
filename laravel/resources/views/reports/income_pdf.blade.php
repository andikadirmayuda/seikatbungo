<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pendapatan - Seikat Bungo</title>
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

        .summary-card.total {
            background: #e3f2fd;
            border-color: #2196f3;
        }

        .summary-card.total h3 {
            color: #1976d2;
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
    </style>
</head>

<body>
    <div class="header">
        <h1 class="company-name">Seikat Bungo</h1>
        <h2 class="report-title">Laporan Pendapatan</h2>
        <div class="period">
            Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-grid">
            <div class="summary-card">
                <h3 class="currency">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                <p>Total Penjualan Langsung</p>
            </div>
            <div class="summary-card">
                <h3 class="currency">Rp{{ number_format($totalPemesanan, 0, ',', '.') }}</h3>
                <p>Total Pemesanan Online</p>
            </div>
            <div class="summary-card total">
                <h3 class="currency">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p>Total Pendapatan</p>
            </div>
        </div>
    </div>

    <!-- Pendapatan Harian -->
    <div class="section-title">Pendapatan Harian</div>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th class="text-right">Penjualan</th>
                <th class="text-right">Pemesanan</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotalPenjualanHarian = 0; $grandTotalPemesananHarian = 0; @endphp
            @forelse($harian as $tgl => $row)
                @php 
                    $grandTotalPenjualanHarian += $row['penjualan']; 
                    $grandTotalPemesananHarian += $row['pemesanan']; 
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}</td>
                    <td class="text-right currency">Rp{{ number_format($row['penjualan'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp{{ number_format($row['pemesanan'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp{{ number_format($row['penjualan'] + $row['pemesanan'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data pendapatan harian</td>
                </tr>
            @endforelse
            @if(count($harian) > 0)
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td><strong>TOTAL</strong></td>
                    <td class="text-right currency">Rp{{ number_format($grandTotalPenjualanHarian, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp{{ number_format($grandTotalPemesananHarian, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp{{ number_format($grandTotalPenjualanHarian + $grandTotalPemesananHarian, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan Pendapatan - Seikat Bungo | Dicetak pada {{ now()->format('d M Y H:i') }}</p>
    </div>
</body>

</html>
