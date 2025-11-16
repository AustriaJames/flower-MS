<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Status Updated - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
        <td style="background-color: #5D2B4C; color: #ffffff; padding: 16px 24px; text-align: center;">
            <h2 style="margin: 0;">Order Status Updated</h2>
        </td>
    </tr>
    <tr>
        <td style="padding: 24px;">
            <p>Hi {{ $user->name ?? trim($user->first_name . ' ' . $user->last_name) }},</p>
            <p>Your order <strong>{{ $order->order_number }}</strong> status has been updated.</p>

            <ul style="padding-left: 18px;">
                @if(!empty($oldStatus))
                    <li><strong>Previous Status:</strong> {{ ucfirst($oldStatus) }}</li>
                @endif
                <li><strong>Current Status:</strong> {{ ucfirst($order->status) }}</li>
                <li><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</li>
            </ul>

            @if($order->tracking)
                <h3 style="margin-top: 24px; color: #5D2B4C;">Tracking Information</h3>
                <ul style="padding-left: 18px;">
                    <li><strong>Tracking Number:</strong> {{ $order->tracking->tracking_number }}</li>
                    <li><strong>Carrier:</strong> {{ $order->tracking->carrier }}</li>
                    <li><strong>Status:</strong> {{ ucfirst($order->tracking->status) }}</li>
                </ul>
            @endif

            <p style="margin-top: 24px;">You can view your order details anytime in your account under "My Orders".</p>

            <p style="margin-top: 24px;">Best regards,<br>{{ config('app.name') }}</p>
        </td>
    </tr>
</table>
</body>
</html>
