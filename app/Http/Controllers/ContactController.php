<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'nullable|string|max:255',
            'industry' => 'required|string|max:255',
            'message' => 'nullable|string|max:1000',
        ]);

        try {
            // Log the contact form submission
            Log::info('Contact form submitted', $validated);

            // Here you could send an email, save to database, etc.
            // For now, we'll just log it and redirect with success

            return redirect('/#contact')->with('success', 'Thank you for your message! We\'ll be in touch soon.');
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return redirect('/#contact')->with('error', 'There was an error sending your message. Please try again.');
        }
    }
}
