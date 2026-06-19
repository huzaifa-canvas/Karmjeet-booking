<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $payment->id }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; margin: 0; padding: 20px; font-size: 14px; }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .header { display: table; width: 100%; border-bottom: 2px solid #ddd; padding-bottom: 20px; margin-bottom: 20px; }
        .header-left { display: table-cell; text-align: left; }
        .header-right { display: table-cell; text-align: right; }
        .title { font-size: 32px; font-weight: bold; color: #555; }
        .details { display: table; width: 100%; margin-bottom: 30px; }
        .details-col { display: table-cell; width: 50%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f8f8f8; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; font-size: 16px; background-color: #f4f4f4; }
        .tax-row { color: #666; font-size: 13px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="header-left">
                <div class="title">INVOICE</div>
                <div>Invoice #: {{ $payment->id }}</div>
                <div>Date: {{ $payment->created_at->format('M d, Y') }}</div>
            </div>
            <div class="header-right">
                <h2>{{ config('app.name') }}</h2>
                <p>123 Martial Arts Ave.<br>City, State, 12345</p>
            </div>
        </div>

        <div class="details">
            <div class="details-col">
                <strong>Billed To:</strong><br>
                {{ $payment->subscription->user->name }}<br>
                {{ $payment->subscription->user->email }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{ $class->name }} Subscription<br>
                        <small>{{ $class->category }} - {{ $class->level }}</small>
                    </td>
                    <td class="text-right">${{ number_format($taxDetails['subtotal'], 2) }}</td>
                </tr>
                @if($taxDetails['gst_rate'] > 0)
                <tr class="tax-row">
                    <td>GST ({{ $taxDetails['gst_rate'] }}%)</td>
                    <td class="text-right">${{ number_format($taxDetails['gst_amount'], 2) }}</td>
                </tr>
                @endif
                @if($taxDetails['pst_rate'] > 0)
                <tr class="tax-row">
                    <td>PST ({{ $taxDetails['pst_rate'] }}%)</td>
                    <td class="text-right">${{ number_format($taxDetails['pst_amount'], 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td>Total Paid @if($taxDetails['is_inclusive']) <small>(Tax Inclusive)</small> @endif</td>
                    <td class="text-right">${{ number_format($taxDetails['total'], 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 40px; text-align: center; color: #777;">
            <p>Thank you for your business!</p>
            <p><small>Paid via Stripe (ID: {{ $payment->stripe_payment_id }})</small></p>
        </div>
    </div>
</body>
</html>
