<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PhpMailerException;

class PhpMailerService
{
    public function send(string $toEmail, string $toName, string $subject, string $htmlBody): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
            $mail->Port       = env('MAIL_PORT', 587);

            // From
            $fromAddress = env('MAIL_FROM_ADDRESS', 'no-reply@localhost');
            $fromName    = env('MAIL_FROM_NAME', config('app.name', 'Laravel'));
            $mail->setFrom($fromAddress, $fromName);

            // Recipient
            $mail->addAddress($toEmail, $toName ?: $toEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            return $mail->send();
        } catch (PhpMailerException $e) {
            // You can log the error if needed
            \Log::error('PHPMailer error: ' . $e->getMessage());
            return false;
        }
    }
}
