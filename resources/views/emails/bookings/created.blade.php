<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Received - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
    <tr>
        <td style="background-color: #5D2B4C; color: #ffffff; padding: 16px 24px; text-align: center;">
            <h2 style="margin: 0;">Booking Received</h2>
        </td>
    </tr>
    <tr>
        <td style="padding: 24px;">
            <p>Hi {{ $booking->customer_name ?? ($booking->user->first_name . ' ' . $booking->user->last_name) }},</p>
            <p>Thank you for booking your event with {{ config('app.name') }}. We have received your booking request and our team will review the details shortly.</p>

            <h3 style="margin-top: 24px; color: #5D2B4C;">Booking Details</h3>
            <ul style="padding-left: 18px;">
                <li><strong>Event Type:</strong> {{ ucfirst($booking->event_type) }}</li>
                <li><strong>Event Date:</strong> {{ $booking->formatted_event_date ?? $booking->event_date->format('F d, Y') }}</li>
                <li><strong>Event Time:</strong> {{ $booking->formatted_event_time ?? $booking->event_time }}</li>
                <li><strong>Guest Count:</strong> {{ $booking->guest_count }}</li>
                <li><strong>Venue Address:</strong> {{ $booking->venue_address }}</li>
                <li><strong>Contact Person:</strong> {{ $booking->contact_person }}</li>
                <li><strong>Contact Phone:</strong> {{ $booking->contact_phone }}</li>
            </ul>

            @if($booking->special_requirements)
                <p><strong>Special Requirements:</strong><br>{{ $booking->special_requirements }}</p>
            @endif

            <p style="margin-top: 24px;">We will contact you soon to confirm the details and discuss any additional requirements.</p>

            <p style="margin-top: 24px;">Best regards,<br>{{ config('app.name') }}</p>
        </td>
    </tr>
</table>
</body>
</html>
