<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'player_name' => [
                'required',
                'string',
                'max:255',
            ],

            'grad_year' => [
                'required',
                'integer',
                'min:2026',
                'max:2050',
            ],

            'parent_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'user_message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        try {

            Mail::send('emails.contact', $data, function ($mail) use ($data) {

                $mail->to(
                    'info@encorelacrosse.com',
                    'ACES Lacrosse'
                );

                $mail->replyTo(
                    $data['email'],
                    $data['parent_name']
                );

                $mail->subject(
                    'ACES Contact: ' . $data['subject']
                );
            });

            Log::info('ACES contact email sent', [
                'to' => 'info@encorelacrosse.com',
                'from_user' => $data['email'],
                'subject' => $data['subject'],
            ]);

            return back()->with(
                'success',
                'Thank you! Your message has been sent successfully.'
            );

        } catch (Throwable $e) {

            Log::error('ACES contact email failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Email could not be sent: ' . $e->getMessage()
                );
        }
    }
}
