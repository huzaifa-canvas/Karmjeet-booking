<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Subscription Confirmation</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 0; color: #333; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: #28c76f; color: #ffffff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px; }
        .alert-box { background: #f2faf5; border-left: 4px solid #28c76f; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .details-table td { padding: 10px; border-bottom: 1px solid #ebe9f1; font-size: 14px; }
        .details-table td.label { font-weight: bold; color: #5e5873; width: 40%; }
        .footer { background: #f8f8fb; text-align: center; padding: 20px; font-size: 12px; color: #b9b9c3; border-top: 1px solid #ebe9f1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name', 'Karmjeet Portal') }}</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">
                @if($recipientType === 'admin')
                    New Subscription Alert!
                @else
                    Subscription Confirmed!
                @endif
            </p>
        </div>
        <div class="content">
            <div class="alert-box">
                @if($recipientType === 'admin')
                    <strong>Admin Notice:</strong> A user has purchased a new subscription plan on the site.
                @else
                    <strong>Subscription Activated:</strong> Your subscription has been successfully activated. Details are below:
                @endif
            </div>

            <h3 style="margin-bottom: 15px; color: #5e5873;">Subscription Details</h3>
            <table class="details-table">
                <tr>
                    <td class="label">Customer Name:</td>
                    <td>{{ $subscription->user->name ?? 'Customer' }}</td>
                </tr>
                <tr>
                    <td class="label">Customer Email:</td>
                    <td>{{ $subscription->user->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Class / Plan:</td>
                    <td><strong>{{ $subscription->martialArtsClass->name ?? 'Membership Plan' }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Package Type:</td>
                    <td><span style="text-transform: capitalize;">{{ $subscription->package_type ?? 'Standard' }}</span></td>
                </tr>
                @if($subscription->selected_location)
                <tr>
                    <td class="label">Location:</td>
                    <td>{{ $subscription->selected_location }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Status:</td>
                    <td><span style="color: #28c76f; font-weight: bold; text-transform: uppercase;">{{ $subscription->status }}</span></td>
                </tr>
                @if($subscription->next_payment_date)
                <tr>
                    <td class="label">Next Billing Date:</td>
                    <td>{{ \Carbon\Carbon::parse($subscription->next_payment_date)->format('M d, Y') }}</td>
                </tr>
                @endif
                @if($subscription->payments->count() > 0)
                <tr>
                    <td class="label">Amount Paid:</td>
                    <td style="font-size: 16px; font-weight: bold; color: #28c76f;">${{ number_format($subscription->payments->last()->amount, 2) }}</td>
                </tr>
                @endif
            </table>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Karmjeet Portal') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
