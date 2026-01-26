<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download($id)
    {
        $purchase = Purchase::with(['user', 'ressource.user','publication.user'])->findOrFail($id);
        
        // Sécurité : Seul l'admin, l'acheteur ou le vendeur (prof) peut voir la facture
        $currentUser = auth()->user();

        $sellerId = null;
        if ($purchase->ressource) {
            $sellerId = $purchase->ressource->user_id;
        } elseif ($purchase->publication) {
            $sellerId = $purchase->publication->user_id;
        }

        if ($currentUser->role !== 'admin' &&
            $currentUser->id !== $purchase->user_id &&
            ($sellerId === null || $currentUser->id !== $sellerId)) {
            abort(403);
        }

        $pdf = Pdf::loadView('invoices.template', compact('purchase'));
        return $pdf->download('facture-MIO-' . $purchase->id . '.pdf');
    }
}