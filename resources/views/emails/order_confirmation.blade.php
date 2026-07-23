<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: #7367f0; color: #ffffff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px; }
        .alert-box { background: #f8f8fb; border-left: 4px solid #7367f0; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        .order-info { margin-bottom: 25px; }
        .order-info table { width: 100%; border-collapse: collapse; }
        .order-info td { padding: 6px 0; font-size: 14px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        .items-table th { background: #f3f2f7; text-align: left; padding: 10px; font-size: 13px; text-transform: uppercase; color: #5e5873; }
        .items-table td { padding: 12px 10px; border-bottom: 1px solid #ebe9f1; font-size: 14px; }
        .totals-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .totals-table td { padding: 6px 10px; font-size: 14px; }
        .totals-table tr.grand-total td { font-size: 16px; font-weight: bold; color: #7367f0; border-top: 2px solid #7367f0; padding-top: 10px; }
        .footer { background: #f8f8fb; text-align: center; padding: 20px; font-size: 12px; color: #b9b9c3; border-top: 1px solid #ebe9f1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'Karmjeet Portal') }}</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">
                @if($recipientType === 'admin')
                    New Order Received!
                @else
                    Thank you for your order!
                @endif
            </p>
        </div>
        <div class="content">
            <div class="alert-box">
                @if($recipientType === 'admin')
                    <strong>Admin Notice:</strong> A new order #{{ $order->order_number }} has been placed on the site.
                @else
                    <strong>Order Confirmation:</strong> Your order #{{ $order->order_number }} has been successfully placed. Here are your order details:
                @endif
            </div>

            <div class="order-info">
                <table>
                    <tr>
                        <td><strong>Order Number:</strong> #{{ $order->order_number }}</td>
                        <td style="text-align: right;"><strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method:</strong> {{ strtoupper($order->payment_method ?? 'Stripe') }}</td>
                        <td style="text-align: right;"><strong>Payment Status:</strong> <span style="text-transform: capitalize; color: #28c76f; font-weight: bold;">{{ $order->payment_status }}</span></td>
                    </tr>
                    @if($order->user || $order->guest_name)
                    <tr>
                        <td colspan="2" style="padding-top: 10px;">
                            <strong>Customer Details:</strong><br>
                            Name: {{ $order->user->name ?? $order->guest_name }}<br>
                            Email: {{ $order->user->email ?? $order->guest_email }}<br>
                            @if($order->guest_phone) Phone: {{ $order->guest_phone }} @endif
                        </td>
                    </tr>
                    @endif
                </table>
            </div>

            <h3 style="margin-bottom: 10px; color: #5e5873;">Order Summary</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th style="text-align: center;">Qty</th>
                        <th style="text-align: right;">Price</th>
                        <th style="text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td style="text-align: center;">{{ $item->quantity }}</td>
                            <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                            <td style="text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <table class="totals-table">
                <tr>
                    <td style="text-align: right; width: 70%;">Subtotal:</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</td>
                </tr>
                @if($order->gst_amount > 0)
                <tr>
                    <td style="text-align: right;">GST Tax:</td>
                    <td style="text-align: right;">${{ number_format($order->gst_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->pst_amount > 0)
                <tr>
                    <td style="text-align: right;">PST Tax:</td>
                    <td style="text-align: right;">${{ number_format($order->pst_amount, 2) }}</td>
                </tr>
                @endif
                @if($order->discount_amount > 0)
                <tr>
                    <td style="text-align: right; color: #ea5455;">Discount:</td>
                    <td style="text-align: right; color: #ea5455;">-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="grand-total">
                    <td style="text-align: right;">Total Paid:</td>
                    <td style="text-align: right;">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Karmjeet Portal') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
