<?php

namespace App\Http\Controllers;

use App\Models\OfierArchitectsProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OfierArchitectsController extends Controller
{
    public function projects()
    {
        return response()->json([
            'projects' => OfierArchitectsProject::orderBy('created_at', 'desc')
                ->get(),
            'message' => 'Projects retrieved successfully',
        ])->setStatusCode(200, 'OK', [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
        ]);
    }

    public function contactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        $sub = new \App\Models\OfierArchitectsContactSub();
        $sub->submission = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'message' => $request->input('message'),
        ];
        $sub->save();

        // send an email to the site owner
        $data = [
            'subject' => 'New Contact Form Submission',
            'body' => "Name: {$request->input('name')}\n" .
                "Email: {$request->input('email')}\n" .
                "Phone: {$request->input('phone')}\n" .
                "Message: {$request->input('message')}",
        ];
        Mail::mailer('smtp_2')->to('urisaul36@gmail.com')->send(new \App\Mail\GeneralEmail($data));
        return response()->json(['message' => 'Contact form submitted successfully']);
    }
}
