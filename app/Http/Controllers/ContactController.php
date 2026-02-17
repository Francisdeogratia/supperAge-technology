<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle the contact form submission
     */
    public function submit(Request $request)
    {
        // Inside submit method, before sending email:
// ContactMessage::create($validated);
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'subject.required' => 'Please select a subject',
            'message.required' => 'Please enter your message',
            'message.min' => 'Message must be at least 10 characters',
        ]);

        try {
            // Send email to admin (info@supperage.com)
            Mail::to('info@supperage.com')->send(new ContactFormMail($validated, false));

            // Send confirmation email to the user
            Mail::to($validated['email'])->send(new ContactFormMail($validated, true));

            // Log successful submission
            Log::info('Contact form submitted', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'],
            ]);

            return redirect()->route('contact')->with('success', 'Thank you for contacting us! We have received your message and will get back to you within 24-48 hours.');
            
        } catch (\Exception $e) {
            // Log the error
            Log::error('Contact form error: ' . $e->getMessage());
            
            return redirect()->route('contact')
                ->withInput()
                ->with('error', 'Oops! Something went wrong while sending your message. Please try again or email us directly at info@supperage.com');
        }
    }
}