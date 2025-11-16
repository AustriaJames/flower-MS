<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Placed - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
        <td style="background-color: #5D2B4C; color: #ffffff; padding: 16px 24px; text-align: center;">
            <h2 style="margin: 0;">Thank You for Your Order!</h2>
        </td>
    </tr>
    <tr>
        <td style="padding: 24px;">
            <p>Hi {{ $user->name ?? trim($user->first_name . ' ' . $user->last_name) }},</p>
            <p>We have received your order. Below are your order details:</p>

            <h3 style="margin-top: 24px; color: #5D2B4C;">Order {{ $order->order_number }}</h3>
            <ul style="padding-left: 18px;">
                <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
                <li><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</li>
                <li><strong>Delivery Type:</strong> {{ ucfirst($order->delivery_type) }}</li>
                <li><strong>Delivery / Pickup Date:</strong> {{ \Carbon\Carbon::parse($order->delivery_date)->format('F d, Y') }}</li>
            </ul>

            <p style="margin-top: 24px;">We will notify you as soon as your order status changes.</p>

            <p style="margin-top: 24px;">Best regards,<br>{{ config('app.name') }}</p>
        </td>
    </tr>
</table>
</body>
</html>
