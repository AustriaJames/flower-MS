<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Services\PhpMailerService;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * The PHPMailer service instance.
     */
    protected PhpMailerService $mailer;

    public function __construct(PhpMailerService $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle sending a reset link to the given user using PHPMailer.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        /** @var User|null $user */
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => trans(Password::INVALID_USER)]);
        }

        // Generate password reset token using the default broker
        $token = app('auth.password.broker')->createToken($user);

        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        $subject = 'Password Reset Request - ' . config('app.name');

        $htmlBody = view('emails.auth.password_reset', [
            'user' => $user,
            'resetUrl' => $resetUrl,
        ])->render();

        $sent = $this->mailer->send($user->email, $user->name ?? ($user->first_name . ' ' . $user->last_name ?? ''), $subject, $htmlBody);

        if (!$sent) {
            return back()->withErrors(['email' => 'Failed to send reset email. Please try again later.']);
        }

        return back()->with('status', trans(Password::RESET_LINK_SENT));
    }
}
