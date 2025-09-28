<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-left: 4px solid #667eea;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #28a745;
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
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
        }
        .contact-info {
            background: #e8f4fd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌸 Booking Confirmation</h1>
        <p>Thank you for choosing our flower shop!</p>
    </div>

    <div class="content">
        <h2>Hello {{ $booking->customer_name }},</h2>

        <p>Your event booking has been <strong>confirmed</strong>! We're excited to help make your special day beautiful.</p>

        <div class="booking-details">
            <h3>📋 Booking Details</h3>
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
                    <td style="padding: 8px 0; font-weight: bold;">Budget Range:</td>
                    <td style="padding: 8px 0;">{{ $booking->budget_range }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;">
                        <span class="status-badge">{{ ucfirst($booking->status) }}</span>
                    </td>
                </tr>
            </table>
        </div>

        @if($booking->requirements)
        <div class="booking-details">
            <h3>📝 Special Requirements</h3>
            <p>{{ $booking->requirements }}</p>
        </div>
        @endif

        <div class="contact-info">
            <h3>📞 Need to Make Changes?</h3>
            <p>If you need to modify your booking or have any questions, please contact us:</p>
            <ul>
                <li><strong>Phone:</strong> +63 123 456 7890</li>
                <li><strong>Email:</strong> bookings@flowershop.com</li>
                <li><strong>Hours:</strong> Monday - Saturday, 8:00 AM - 6:00 PM</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn">View Booking Details</a>
        </div>

        <p><strong>Important Notes:</strong></p>
        <ul>
            <li>Please confirm your booking details 48 hours before your event</li>
            <li>Any changes must be made at least 24 hours in advance</li>
            <li>Final payment is due on the day of the event</li>
            <li>We'll contact you 1 week before your event for final arrangements</li>
        </ul>

        <p>We look forward to creating beautiful flowers for your special day!</p>

        <p>Best regards,<br>
        <strong>The Flower Shop Team</strong></p>
    </div>

    <div class="footer">
        <p>🌸 Flower Shop Management System</p>
        <p>This is an automated confirmation email. Please do not reply to this message.</p>
    </div>
</body>
</html>
