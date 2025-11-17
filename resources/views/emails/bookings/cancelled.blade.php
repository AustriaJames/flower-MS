<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #ef5350 0%, #d32f2f 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .booking-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #ef5350;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #ef5350;
            color: white;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #ef5350;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .contact-info {
            background: #ffebee;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>❌ Booking Cancelled</h1>
        <p>Your event booking has been cancelled</p>
    </div>

    <div class="content">
        <h2>Hello {{ $booking->customer_name }},</h2>

        <p>We regret to inform you that your event booking has been <strong>cancelled</strong>.</p>

        <div class="booking-details">
            <h3>📋 Cancelled Booking Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Booking ID:</td>
                    <td style="padding: 8px 0;">#{{ $booking->id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Event Type:</td>
                    <td style="padding: 8px 0;">{{ ucfirst($booking->event_type) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Event Date:</td>
                    <td style="padding: 8px 0;">{{ $booking->event_date->format('l, F d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Event Time:</td>
                    <td style="padding: 8px 0;">{{ $booking->event_time }}</td>
                </tr>
                @if($booking->venue)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Venue:</td>
                    <td style="padding: 8px 0;">{{ $booking->venue }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;">
                        <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="contact-info">
            <h3>📞 Need to Rebook?</h3>
            <p>If you'd like to make a new booking or have any questions, please contact us:</p>
            <ul>
                <li><strong>Phone:</strong> 0955 644 6048</li>
                <li><strong>Email:</strong> bookings@flowershop.com</li>
                <li><strong>Hours:</strong> Monday - Saturday, 8:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.bookings.index') }}" class="btn">Make New Booking</a>
        </div>

        <p><strong>Important Notes:</strong></p>
        <ul>
            <li>Any deposits paid may be refunded according to our cancellation policy</li>
            <li>You can make a new booking at any time</li>
            <li>We're happy to help you find an alternative date</li>
        </ul>

        <p>We apologize for any inconvenience and hope to serve you in the future!</p>

        <p>Best regards,<br>
        <strong>The Flower Shop Team</strong></p>
    </div>

    <div class="footer">
        <p>🌸 Flower Shop Management System</p>
        <p>This is an automated notification email. Please do not reply to this message.</p>
    </div>
</body>
</html>
