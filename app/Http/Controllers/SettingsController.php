<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        return view('systemsetting');
    }

    /**
     * Send a test email using the provided details.
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'email-body' => 'required|string',
        ]);

        try {
            $toEmail = $request->test_email;
            $subject = $request->subject;
            $body = $request->input('email-body');

            Mail::raw($body, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)
                    ->subject($subject);
            });

            return redirect()->back()->with('success', 'Test email sent successfully to ' . $toEmail);
        } catch (\Exception $e) {
            Log::error('Test Email Failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
