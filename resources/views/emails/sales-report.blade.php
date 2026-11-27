<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .summary {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .summary-item {
            margin: 10px 0;
        }
        .summary-item strong {
            display: inline-block;
            width: 120px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Sales Report</h1>
            <p style="margin: 5px 0 0 0;">{{ $data['from'] }} to {{ $data['to'] }}</p>
        </div>

        <p>Hello,</p>
        <p>Please find attached your sales report for the period <strong>{{ $data['from'] }}</strong> to <strong>{{ $data['to'] }}</strong>.</p>

        <div class="summary">
            <h3 style="margin-top: 0;">Summary</h3>
            <div class="summary-item">
                <strong>Total Sales:</strong> ₱{{ $data['sales'] }}
            </div>
            <div class="summary-item">
                <strong>Orders:</strong> {{ $data['orders'] }}
            </div>
            <div class="summary-item">
                <strong>Avg Order:</strong> ₱{{ $data['avg_order'] }}
            </div>
            @if($data['voided'] > 0)
            <div class="summary-item">
                <strong>Voided Carts:</strong> {{ $data['voided'] }}
            </div>
            @endif
        </div>

        <p>The complete report is attached as a PDF file.</p>

        <div class="footer">
            <p>This is an automated email from {{ config('app.name') }}.</p>
            <p>Report generated on {{ now()->setTimezone($data['timezone'])->format('F j, Y g:i A') }}</p>
        </div>
    </div>
</body>
</html>
