<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $purchase->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #334155; }
        .header { text-align: center; margin-bottom: 40px; padding-top: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #2563eb; text-transform: uppercase; }
        .details { margin-bottom: 30px; line-height: 1.6; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background-color: #f8fafc; text-align: left; text-transform: uppercase; font-size: 11px; color: #64748b; }
        .table th, .table td { border: 1px solid #e2e8f0; padding: 12px; }
        .total { text-align: right; font-size: 20px; font-weight: bold; margin-top: 30px; color: #0f172a; }
        .footer { text-align: center; margin-top: 60px; font-size: 10px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">MIO RESSOURCES</div>
        <p style="margin-top: 5px;">Plateforme - L'humilité mon choix, l'excellence ma voie !</p>
        <p style="font-weight: bold; color: #64748b;">REÇU DE PAIEMENT OFFICIEL</p>
    </div>

    <div class="details">
        <p><strong>Référence :</strong> #{{ $purchase->payment_id }}</p>
        <p><strong>Date d'achat :</strong> {{ $purchase->created_at->format('d/m/Y à H:i') }}</p>

        @if($purchase->user)
            <p><strong>Client :</strong> {{ $purchase->user->name }} ({{ $purchase->user->email }})</p>
        @else
            <p><strong>Client :</strong> {{ $purchase->guest_email ?? 'Client invité' }}</p>
        @endif

        @php
            // On identifie l'objet acheté
            $item = $purchase->ressource ?? $purchase->publication;
            $vendeur = $item ? $item->user : null;
        @endphp

        @if($vendeur)
            <p><strong>Vendeur / Auteur :</strong> {{ $vendeur->name }}</p>
        @endif
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Désignation du document</th>
                <th>Type</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <!-- CORRECTION CRUCIALE ICI -->
                    @if($purchase->ressource)
                        {{ $purchase->ressource->titre }}
                    @elseif($purchase->publication)
                        {{ $purchase->publication->titre }}
                    @else
                        Document MIO
                    @endif
                </td>
                <td>
                    {{ $item ? $item->type : 'Document' }}
                </td>
                <td>{{ number_format($purchase->amount, 0, ',', ' ') }} CFA</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        TOTAL PAYÉ : {{ number_format($purchase->amount, 0, ',', ' ') }} CFA
    </div>

    <div class="footer">
        MIO Ressources &bull; Université Iba Der Thiam, Thiès, Sénégal &bull; {{ date('Y') }}<br>
        Ceci est une preuve de transaction électronique. Aucun cachet n'est requis.
    </div>
</body>
</html>
