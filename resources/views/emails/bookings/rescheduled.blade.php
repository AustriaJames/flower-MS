<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Rescheduled</title>
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
            background: linear-gradient(135deg, #ffa726 0%, #ff7043 100%);
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
            border-left: 4px solid #ffa726;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #ffa726;
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
            background: #ffa726;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .contact-info {
            background: #fff3e0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔄 Booking Rescheduled</h1>
        <p>Your event booking has been updated</p>
    </div>

    <div class="content">
        <h2>Hello {{ $booking->customer_name }},</h2>

        <p>Your event booking has been <strong>rescheduled</strong>. Please review the updated details below.</p>

        <div class="booking-details">
            <h3>📋 Updated Booking Details</h3>
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
                    <td style="padding: 8px 0; font-weight: bold;">New Event Date:</td>
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
            <h3>📞 Questions About the Reschedule?</h3>
            <p>If you have any questions about the rescheduled date or need to make further changes, please contact us:</p>
            <ul>
                <li><strong>Phone:</strong> 0955 644 6048</li>
                <li><strong>Email:</strong> bookings@flowershop.com</li>
                <li><strong>Hours:</strong> Monday - Saturday, 8:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn">View Updated Booking</a>
        </div>

        <p><strong>Important Notes:</strong></p>
        <ul>
            <li>Please confirm the new date within 24 hours</li>
            <li>Any further changes may incur additional fees</li>
            <li>We'll contact you 1 week before the new event date</li>
        </ul>

        <p>We apologize for any inconvenience and look forward to serving you on the new date!</p>

        <p>Best regards,<br>
        <strong>The Flower Shop Team</strong></p>
    </div>

    <div class="footer">
        <p>🌸 Flower Shop Management System</p>
        <p>This is an automated notification email. Please do not reply to this message.</p>
    </div>
</body>
</html>
