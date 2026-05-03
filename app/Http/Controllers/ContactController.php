<?php

namespace App\Http\Controllers;

use App\Jobs\SendContactMessageJob;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'message' => 'required|string|max:2000',
        ]);


        // Stocker le message en base
        \App\Models\ContactMessage::create($validated);

        SendContactMessageJob::dispatch($validated['nom'], $validated['email'], $validated['message']);

        return back()->with('contact_success', 'Votre message a bien été envoyé. Merci !');
    }
}
