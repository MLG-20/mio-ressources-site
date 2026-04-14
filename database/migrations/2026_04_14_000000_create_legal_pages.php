<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pages')->insert([
            [
                'titre' => 'Conditions Générales d\'Utilisation',
                'slug' => 'cgu',
                'contenu' => $this->getCGUContent(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Mentions Légales',
                'slug' => 'mentions-legales',
                'contenu' => $this->getMentionsLegalesContent(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'titre' => 'Politique de Confidentialité',
                'slug' => 'politique-confidentialite',
                'contenu' => $this->getPolitiqueConfidentialiteContent(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pages')->whereIn('slug', ['cgu', 'mentions-legales', 'politique-confidentialite'])->delete();
    }

    private function getCGUContent(): string
    {
        return <<<'HTML'
<h2>1. Présentation</h2>
<p>MIO Ressources est une plateforme éducative collaborative destinée aux étudiants de l'Université Iba Der Thiam de Thiès, Sénégal. Elle permet l'accès à des ressources académiques, des cours particuliers, un forum d'entraide et une bibliothèque numérique.</p>
<p><strong>Éditeur :</strong><br/>Mamadou Lamine Gueye<br/>Email : mlamine.gueye1@univ-thies.sn<br/>Adresse : Thiès, Sénégal</p>

<h2>2. Acceptation des CGU</h2>
<p>L'utilisation de la plateforme MIO Ressources implique l'acceptation pleine et entière des présentes Conditions Générales d'Utilisation. Si vous n'acceptez pas ces conditions, vous ne pouvez pas utiliser la plateforme.</p>

<h2>3. Accès à la plateforme</h2>
<h3>3.1 Inscription</h3>
<p>L'accès aux fonctionnalités complètes nécessite la création d'un compte avec :</p>
<ul>
<li>Une adresse email valide</li>
<li>Un mot de passe sécurisé</li>
<li>Votre niveau d'étude</li>
</ul>

<h3>3.2 Période d'essai</h3>
<p>Tout nouveau compte bénéficie d'une <strong>période d'essai gratuite</strong> permettant d'accéder aux ressources premium.</p>

<h3>3.3 Abonnement</h3>
<p>Après la période d'essai, l'accès aux ressources premium nécessite un abonnement mensuel de <strong>500 FCFA/mois</strong>, payable via PayTech (Wave, Orange Money, carte bancaire).</p>

<h2>4. Responsabilités des utilisateurs</h2>
<p>En utilisant MIO Ressources, vous vous engagez à :</p>
<ul>
<li>Fournir des informations exactes lors de l'inscription</li>
<li>Ne pas partager vos identifiants de connexion</li>
<li>Respecter les droits d'auteur des ressources publiées</li>
<li>Adopter un comportement respectueux sur le forum</li>
<li>Ne pas télécharger ou distribuer les ressources à des fins commerciales</li>
<li>Ne pas tenter de contourner le système d'abonnement</li>
</ul>

<h2>5. Contenu de la plateforme</h2>
<h3>5.1 Ressources académiques</h3>
<p>Les cours, TD, examens et documents publiés sur MIO Ressources sont fournis à titre éducatif. La plateforme ne garantit pas leur exactitude ou leur complétude.</p>

<h3>5.2 Contenu utilisateur</h3>
<p>Les utilisateurs peuvent publier du contenu (forum, avis, publications). Vous êtes seul responsable du contenu que vous publiez. Tout contenu inapproprié, diffamatoire ou illégal sera supprimé.</p>

<h3>5.3 Cours particuliers</h3>
<p>Les cours particuliers sont proposés par des enseignants inscrits sur la plateforme. MIO Ressources agit en tant qu'intermédiaire et ne peut être tenu responsable de la qualité des cours dispensés.</p>

<h2>6. Paiements et remboursements</h2>
<h3>6.1 Tarification</h3>
<ul>
<li>Abonnement mensuel : <strong>500 FCFA/mois</strong></li>
<li>Ressources premium à l'unité : prix affiché sur chaque ressource</li>
</ul>

<h3>6.2 Moyens de paiement</h3>
<p>Les paiements sont traités par <strong>PayTech</strong> (Wave, Orange Money, carte bancaire).</p>

<h3>6.3 Politique de remboursement</h3>
<p>Les paiements effectués sont <strong>non remboursables</strong>, sauf en cas de dysfonctionnement technique avéré de la plateforme.</p>

<h2>7. Propriété intellectuelle</h2>
<h3>7.1 Contenu de la plateforme</h3>
<p>Le code, le design et les contenus originaux de MIO Ressources sont la propriété de Mamadou Lamine Gueye. Toute reproduction sans autorisation est interdite.</p>

<h3>7.2 Contenu publié par les utilisateurs</h3>
<p>En publiant du contenu sur MIO Ressources, vous accordez à la plateforme une licence non exclusive d'utilisation à des fins éducatives.</p>

<h2>8. Suspension et résiliation</h2>
<p>MIO Ressources se réserve le droit de suspendre ou supprimer un compte en cas de :</p>
<ul>
<li>Non-respect des présentes CGU</li>
<li>Comportement inapproprié sur le forum</li>
<li>Tentative de fraude ou de contournement du système de paiement</li>
<li>Utilisation abusive de la plateforme</li>
</ul>

<h2>9. Limitation de responsabilité</h2>
<p>MIO Ressources ne peut être tenu responsable de :</p>
<ul>
<li>L'interruption temporaire du service pour maintenance</li>
<li>La perte de données due à un cas de force majeure</li>
<li>L'utilisation frauduleuse d'un compte par un tiers</li>
<li>L'exactitude des ressources académiques publiées</li>
</ul>

<h2>10. Modifications des CGU</h2>
<p>MIO Ressources se réserve le droit de modifier ces CGU à tout moment. Les utilisateurs seront informés par email au moins <strong>15 jours</strong> avant l'entrée en vigueur des modifications.</p>

<h2>11. Droit applicable</h2>
<p>Les présentes CGU sont soumises au droit sénégalais. Tout litige sera soumis aux tribunaux compétents de Thiès, Sénégal.</p>

<h2>12. Contact</h2>
<p>Pour toute question relative aux CGU :<br/>
📧 <strong>mlamine.gueye1@univ-thies.sn</strong><br/>
📍 <strong>Thiès, Sénégal</strong><br/>
🌐 <strong>https://mio-ressources.me</strong></p>
HTML;
    }

    private function getMentionsLegalesContent(): string
    {
        return <<<'HTML'
<h2>1. Éditeur de la plateforme</h2>
<p><strong>Nom :</strong> Mamadou Lamine Gueye<br/>
<strong>Qualité :</strong> Étudiant en Licence 3 MIO<br/>
<strong>Établissement :</strong> Université Iba Der Thiam de Thiès<br/>
<strong>Email :</strong> mlamine.gueye1@univ-thies.sn<br/>
<strong>Adresse :</strong> Thiès, Sénégal</p>

<h2>2. Hébergement</h2>
<p><strong>Hébergeur :</strong> Contabo GmbH<br/>
<strong>Adresse :</strong> Aschauer Straße 32a, 81549 Munich, Allemagne<br/>
<strong>Site web :</strong> https://contabo.com<br/>
<strong>Serveur :</strong> VPS Cloud, Union Européenne</p>

<h2>3. Directeur de la publication</h2>
<p>Mamadou Lamine Gueye<br/>
mlamine.gueye1@univ-thies.sn</p>

<h2>4. Propriété intellectuelle</h2>
<p>Le contenu de MIO Ressources (code source, design, logo, textes originaux) est protégé par les lois relatives à la propriété intellectuelle. Toute reproduction, distribution ou utilisation sans autorisation écrite préalable est strictement interdite.</p>

<h2>5. Partenaires techniques</h2>
<table style="width: 100%; border-collapse: collapse;">
<thead>
<tr>
<th style="border: 1px solid #ddd; padding: 8px;">Service</th>
<th style="border: 1px solid #ddd; padding: 8px;">Rôle</th>
<th style="border: 1px solid #ddd; padding: 8px;">Site</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">PayTech</td>
<td style="border: 1px solid #ddd; padding: 8px;">Traitement des paiements</td>
<td style="border: 1px solid #ddd; padding: 8px;">paytech.sn</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Sentry</td>
<td style="border: 1px solid #ddd; padding: 8px;">Monitoring des erreurs</td>
<td style="border: 1px solid #ddd; padding: 8px;">sentry.io</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Google Analytics</td>
<td style="border: 1px solid #ddd; padding: 8px;">Statistiques de visite</td>
<td style="border: 1px solid #ddd; padding: 8px;">analytics.google.com</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Let's Encrypt</td>
<td style="border: 1px solid #ddd; padding: 8px;">Certificat SSL</td>
<td style="border: 1px solid #ddd; padding: 8px;">letsencrypt.org</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Namecheap</td>
<td style="border: 1px solid #ddd; padding: 8px;">Nom de domaine</td>
<td style="border: 1px solid #ddd; padding: 8px;">namecheap.com</td>
</tr>
</tbody>
</table>

<h2>6. Contact</h2>
<p>📧 <strong>mlamine.gueye1@univ-thies.sn</strong><br/>
📍 <strong>Thiès, Sénégal</strong><br/>
🌐 <strong>https://mio-ressources.me</strong></p>
HTML;
    }

    private function getPolitiqueConfidentialiteContent(): string
    {
        return <<<'HTML'
<h2>1. Responsable du traitement</h2>
<p>La plateforme MIO Ressources est éditée et gérée par :</p>
<p><strong>Mamadou Lamine Gueye</strong><br/>
Étudiant en Licence 3 MIO — Université Iba Der Thiam de Thiès<br/>
Email : mlamine.gueye1@univ-thies.sn<br/>
Adresse : Thiès, Sénégal</p>

<h2>2. Données collectées</h2>
<h3>2.1 Données d'inscription</h3>
<ul>
<li>Nom complet</li>
<li>Adresse email</li>
<li>Mot de passe (chiffré avec bcrypt)</li>
<li>Niveau d'étude (L1, L2, L3)</li>
<li>Type d'utilisateur (étudiant, enseignant)</li>
</ul>

<h3>2.2 Données de navigation</h3>
<ul>
<li>Adresse IP</li>
<li>Pages visitées</li>
<li>Date et heure de connexion</li>
<li>Historique des téléchargements</li>
</ul>

<h3>2.3 Données de paiement</h3>
<ul>
<li>Historique des transactions (montant, date)</li>
<li>Statut de l'abonnement</li>
<li>Référence de paiement PayTech</li>
</ul>

<p><strong>⚠️ Aucune donnée bancaire</strong> (numéro de carte, code CVV) n'est stockée sur nos serveurs. Les paiements sont traités exclusivement par notre partenaire <strong>PayTech</strong>.</p>

<h2>3. Finalité du traitement</h2>
<p>Vos données sont collectées pour les finalités suivantes :</p>
<table style="width: 100%; border-collapse: collapse;">
<thead>
<tr>
<th style="border: 1px solid #ddd; padding: 8px;">Finalité</th>
<th style="border: 1px solid #ddd; padding: 8px;">Base légale</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Création et gestion de votre compte</td>
<td style="border: 1px solid #ddd; padding: 8px;">Exécution du contrat</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Accès aux ressources académiques</td>
<td style="border: 1px solid #ddd; padding: 8px;">Exécution du contrat</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Gestion des abonnements et paiements</td>
<td style="border: 1px solid #ddd; padding: 8px;">Exécution du contrat</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Envoi d'emails de confirmation</td>
<td style="border: 1px solid #ddd; padding: 8px;">Exécution du contrat</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Statistiques d'utilisation anonymisées</td>
<td style="border: 1px solid #ddd; padding: 8px;">Intérêt légitime</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Sécurité et prévention des fraudes</td>
<td style="border: 1px solid #ddd; padding: 8px;">Obligation légale</td>
</tr>
</tbody>
</table>

<h2>4. Durée de conservation</h2>
<table style="width: 100%; border-collapse: collapse;">
<thead>
<tr>
<th style="border: 1px solid #ddd; padding: 8px;">Donnée</th>
<th style="border: 1px solid #ddd; padding: 8px;">Durée de conservation</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Données de compte</td>
<td style="border: 1px solid #ddd; padding: 8px;">Jusqu'à suppression du compte + 1 an</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Historique de paiement</td>
<td style="border: 1px solid #ddd; padding: 8px;">5 ans (obligation comptable)</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Logs de connexion</td>
<td style="border: 1px solid #ddd; padding: 8px;">12 mois</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Données de navigation</td>
<td style="border: 1px solid #ddd; padding: 8px;">13 mois</td>
</tr>
</tbody>
</table>

<h2>5. Partage des données</h2>
<p>Vos données personnelles ne sont <strong>jamais vendues</strong> à des tiers. Elles peuvent être partagées uniquement avec :</p>
<ul>
<li><strong>PayTech</strong> : pour le traitement des paiements (montant, référence)</li>
<li><strong>Sentry</strong> : pour la détection d'erreurs techniques (données techniques uniquement)</li>
<li><strong>Google Analytics</strong> : pour les statistiques de visite (données anonymisées)</li>
</ul>

<h2>6. Sécurité</h2>
<p>Nous mettons en œuvre les mesures suivantes pour protéger vos données :</p>
<ul>
<li>Connexion sécurisée HTTPS (SSL/TLS)</li>
<li>Mots de passe chiffrés (bcrypt)</li>
<li>Serveur VPS sécurisé (Contabo, Union Européenne)</li>
<li>Monitoring des erreurs en temps réel (Sentry)</li>
<li>Sauvegardes régulières de la base de données</li>
</ul>

<h2>7. Vos droits</h2>
<p>Conformément aux principes de protection des données personnelles, vous disposez des droits suivants :</p>
<ul>
<li><strong>Droit d'accès</strong> : obtenir une copie de vos données</li>
<li><strong>Droit de rectification</strong> : corriger vos données inexactes</li>
<li><strong>Droit à l'effacement</strong> : demander la suppression de votre compte</li>
<li><strong>Droit d'opposition</strong> : vous opposer au traitement de vos données</li>
<li><strong>Droit à la portabilité</strong> : recevoir vos données dans un format lisible</li>
</ul>

<p>Pour exercer ces droits, contactez-nous à : <strong>mlamine.gueye1@univ-thies.sn</strong></p>
<p>Nous répondrons à votre demande dans un délai de <strong>30 jours</strong>.</p>

<h2>8. Cookies</h2>
<p>MIO Ressources utilise les cookies suivants :</p>
<table style="width: 100%; border-collapse: collapse;">
<thead>
<tr>
<th style="border: 1px solid #ddd; padding: 8px;">Cookie</th>
<th style="border: 1px solid #ddd; padding: 8px;">Type</th>
<th style="border: 1px solid #ddd; padding: 8px;">Durée</th>
<th style="border: 1px solid #ddd; padding: 8px;">Finalité</th>
</tr>
</thead>
<tbody>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Session Laravel</td>
<td style="border: 1px solid #ddd; padding: 8px;">Nécessaire</td>
<td style="border: 1px solid #ddd; padding: 8px;">Session</td>
<td style="border: 1px solid #ddd; padding: 8px;">Authentification</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">XSRF-TOKEN</td>
<td style="border: 1px solid #ddd; padding: 8px;">Nécessaire</td>
<td style="border: 1px solid #ddd; padding: 8px;">Session</td>
<td style="border: 1px solid #ddd; padding: 8px;">Sécurité</td>
</tr>
<tr>
<td style="border: 1px solid #ddd; padding: 8px;">Google Analytics (_ga)</td>
<td style="border: 1px solid #ddd; padding: 8px;">Analytique</td>
<td style="border: 1px solid #ddd; padding: 8px;">2 ans</td>
<td style="border: 1px solid #ddd; padding: 8px;">Statistiques</td>
</tr>
</tbody>
</table>

<h2>9. Modifications</h2>
<p>Cette politique peut être mise à jour. En cas de modification substantielle, les utilisateurs seront notifiés par email au moins <strong>15 jours</strong> avant l'entrée en vigueur.</p>

<h2>10. Contact</h2>
<p>Pour toute question relative à cette politique :<br/>
📧 <strong>mlamine.gueye1@univ-thies.sn</strong><br/>
📍 <strong>Thiès, Sénégal</strong><br/>
🌐 <strong>https://mio-ressources.me</strong></p>
HTML;
    }
};
HTML;
