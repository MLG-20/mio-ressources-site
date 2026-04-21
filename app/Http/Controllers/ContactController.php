<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        // Envoi d'un email à l'admin
        try {
            Mail::raw(
                "Message de contact depuis le site MIO\n\n" .
                "Nom : {$validated['nom']}\n" .
                "Email : {$validated['email']}\n" .
                "Message :\n{$validated['message']}",
                function ($message) use ($validated) {
                    $message->to('mlamine.gueye1@univ-thies.sn')
                            ->subject('Nouveau message de contact MIO')
                            ->replyTo($validated['email'], $validated['nom']);
                }
            );
        } catch (\Exception $e) {
            Log::error('Erreur envoi contact: ' . $e->getMessage());
            return back()->withErrors(['message' => "Erreur lors de l'envoi. Veuillez réessayer."])->withInput();
        }

        return back()->with('contact_success', 'Votre message a bien été envoyé. Merci !');
    }
}
