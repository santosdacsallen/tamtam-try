<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #4F46E5;
            font-size: 24px;
        }
        .header .period {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        .summary {
            background-color: #f9fafb;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #4F46E5;
        }
        .summary h2 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-row {
            display: table-row;
        }
        .summary-label {
            display: table-cell;
            padding: 5px 10px 5px 0;
            font-weight: bold;
            width: 150px;
        }
        .summary-value {
            display: table-cell;
            padding: 5px 0;
        }
        h3 {
            margin: 20px 0 10px 0;
            font-size: 14px;
            color: #4F46E5;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }
        .no-data {
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p class="period">{{ $data['from'] }} to {{ $data['to'] }}</p>
        <p class="period" style="font-size: 11px; margin-top: 2px;">Timezone: {{ $data['timezone'] }}</p>
    </div>

    <div class="summary">
        <h2>Summary</h2>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-label">Total Sales:</div>
                <div class="summary-value">₱{{ $data['sales'] }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Total Orders:</div>
                <div class="summary-value">{{ $data['orders'] }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Average Order:</div>
                <div class="summary-value">₱{{ $data['avg_order'] }}</div>
            </div>
            @if($data['voided'] > 0)
            <div class="summary-row">
                <div class="summary-label">Voided Carts:</div>
                <div class="summary-value">{{ $data['voided'] }}</div>
            </div>
            @endif
        </div>
    </div>

    <h3>Top Sellers (by Quantity)</h3>
    @if(count($data['top_sellers']) > 0)
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-right">Quantity Sold</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['top_sellers'] as $item)
            <tr>
                <td>{{ $item['label'] }}</td>
                <td class="text-right">{{ $item['qty'] }}</td>
                <td class="text-right">₱{{ $item['revenue'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">No sales recorded for this period.</div>
    @endif

    @if(count($data['daily']) > 1)
    <h3>Daily Breakdown</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Orders</th>
                <th class="text-right">Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['daily'] as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td class="text-right">{{ $day['orders'] }}</td>
                <td class="text-right">₱{{ $day['sales'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->setTimezone($data['timezone'])->format('F j, Y g:i A') }}</p>
        <p>{{ config('app.name') }}</p>
    </div>
</body>
</html>
