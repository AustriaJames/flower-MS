<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset - {{ config('app.name') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="background-color: #5D2B4C; color: #ffffff; padding: 16px 24px; text-align: center;">
                <h2 style="margin: 0;">{{ config('app.name') }}</h2>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <p>Hi {{ $user->first_name ?? $user->name ?? 'Customer' }},</p>
                <p>You are receiving this email because we received a password reset request for your account.</p>
                <p style="text-align: center; margin: 24px 0;">
                    <a href="{{ $resetUrl }}" style="background-color: #5D2B4C; color: #ffffff; padding: 12px 24px; border-radius: 4px; text-decoration: none; display: inline-block;">Reset Password</a>
                </p>
                <p>If you did not request a password reset, no further action is required.</p>
                <p style="margin-top: 24px;">Best regards,<br>{{ config('app.name') }}</p>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f0f0f0; color: #777777; padding: 12px 24px; font-size: 12px; text-align: center;">
                <p style="margin: 0;">If the button above does not work, copy and paste this link into your browser:</p>
                <p style="margin: 4px 0; word-break: break-all;">
                    <a href="{{ $resetUrl }}" style="color: #5D2B4C;">{{ $resetUrl }}</a>
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
