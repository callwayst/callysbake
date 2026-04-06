<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CallysBake — Reports</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #260101;
            padding: 24px;
            background: #fff;
        }

        /* ── WATERMARK ── */
        .watermark {
            position: fixed;
            top: 50%; left: 50%;
            width: 280px;
            opacity: 0.04;
            transform: translate(-50%,-50%);
            z-index: -1;
        }

        /* ── HEADER ── */
        .header {
            text-align: center;
            padding-bottom: 16px;
            border-bottom: 2px solid #A65005;
            margin-bottom: 24px;
        }
        .header img.logo { height: 48px; margin-bottom: 8px; }
        .header h1 { font-size: 20px; color: #A65005; margin-bottom: 4px; }
        .header p  { font-size: 10px; color: #D99C79; }

        /* ── SECTION TITLE ── */
        h2 {
            font-size: 13px;
            color: #fff;
            background: linear-gradient(90deg, #A65005, #592202);
            padding: 6px 12px;
            border-radius: 6px;
            margin: 20px 0 10px 0;
        }
        h3 {
            font-size: 11px;
            color: #A65005;
            margin: 14px 0 6px 0;
            border-left: 3px solid #A65005;
            padding-left: 8px;
        }

        /* ── SUMMARY CARDS ── */
        .summary-grid {
            display: table;
            width: 100%;
            border-spacing: 8px;
            margin-bottom: 8px;
        }
        .summary-row { display: table-row; }
        .summary-card {
            display: table-cell;
            width: 25%;
            background: #F2D4C2;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        .summary-card .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #592202;
            margin-bottom: 4px;
        }
        .summary-card .value {
            font-size: 14px;
            font-weight: bold;
            color: #260101;
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10px;
        }
        thead tr {
            background: #A65005;
            color: #fff;
        }
        thead th {
            padding: 7px 8px;
            text-align: left;
            font-size: 10px;
            letter-spacing: 0.3px;
        }
        tbody td {
            padding: 6px 8px;
            border-bottom: 1px solid #F2D4C2;
            color: #260101;
        }
        tbody tr:nth-child(even) { background: #FFF8F3; }
        tbody tr:last-child td   { border-bottom: none; }

        /* ── BADGE ── */
        .badge-ok  { background:#D99C79; color:#260101; padding:2px 6px; border-radius:3px; font-size:9px; }
        .badge-low { background:#800000; color:#fff;    padding:2px 6px; border-radius:3px; font-size:9px; }

        /* ── FOOTER ── */
        .footer {
            margin-top: 32px;
            padding-top: 10px;
            border-top: 1px solid #F2D4C2;
            text-align: center;
            font-size: 9px;
            color: #D99C79;
        }
    </style>
</head>
<body>

    <img src="{{ $logoPath }}" class="watermark" alt="">

    {{-- HEADER --}}
    <div class="header">
        <img src="{{ $logoPath }}" class="logo" alt="CallysBake">
        <h1>CallysBake — Laporan</h1>
        <p>Periode: {{ $from->format('d M Y') }} &mdash; {{ $to->format('d M Y') }}</p>
        <p>Digenerate: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    {{-- SUMMARY --}}
    @if(in_array('summary', $sections))
    <h2>📊 Summary</h2>
    <div class="summary-grid">
        <div class="summary-row">
            @foreach([
                ['Total Sales',  'Rp '.number_format($summary['sales']      ?? 0)],
                ['Total Profit', 'Rp '.number_format($summary['profit']     ?? 0)],
                ['Total Orders', $summary['ordersCount'] ?? 0],
                ['Total Users',  $summary['usersCount']  ?? 0],
            ] as [$label, $val])
            <div class="summary-card">
                <div class="label">{{ $label }}</div>
                <div class="value">{{ $val }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ANALYTICS --}}
    @if(in_array('analytics', $sections))
    <h2>📈 Analytics</h2>

    @if(!empty($analytics['dailyLabels']))
    <h3>Daily Sales</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Total Penjualan</th>
                <th>Jumlah Order</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analytics['dailyLabels'] as $i => $date)
            <tr>
                <td>{{ $date }}</td>
                <td>Rp {{ number_format($analytics['dailyTotals'][$i] ?? 0) }}</td>
                <td>{{ $analytics['dailyOrders'][$i] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(!empty($analytics['topProducts']))
    <h3>Top Products</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Terjual (qty)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($analytics['topProducts'] as $p)
            <tr>
                <td>{{ $p['product_name'] ?? '-' }}</td>
                <td>{{ $p['total'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    @endif

    {{-- INVENTORY --}}
    @if(in_array('inventory', $sections) && !empty($inventory['products']))
    <h2>📦 Inventory</h2>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Stok</th>
                <th>Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory['products'] as $p)
            <tr>
                <td>{{ $p['name'] ?? '-' }}</td>
                <td>{{ $p['stock'] ?? 0 }}</td>
                <td>
                    @if(($p['stock'] ?? 0) <= 5)
                        <span class="badge-low">Low Stock</span>
                    @else
                        <span class="badge-ok">OK</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="font-size:10px;color:#800000;font-weight:bold;">
        ⚠ Produk Low Stock: {{ $inventory['lowStock'] ?? 0 }}
    </p>
    @endif

    {{-- ORDERS DETAIL --}}
    @if(in_array('orders', $sections) && !empty($ordersDetail))
    <h2>🧾 Orders Detail</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Email</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ordersDetail as $o)
            <tr>
                <td>{{ $o['id'] ?? '-' }}</td>
                <td>{{ $o['user_name'] ?? '-' }}</td>
                <td>{{ $o['email'] ?? '-' }}</td>
                <td>Rp {{ number_format($o['total'] ?? 0) }}</td>
                <td>{{ ucfirst($o['status'] ?? '-') }}</td>
                <td>{{ \Carbon\Carbon::parse($o['created_at'] ?? now())->format('d M Y') }}</td>
                <td>{{ $o['items'] ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        CallysBake Admin — Dokumen ini digenerate otomatis &bull; {{ now()->format('d M Y H:i') }}
    </div>

</body>
</html>