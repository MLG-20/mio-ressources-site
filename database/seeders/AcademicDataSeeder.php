<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\Matiere;
use App\Models\Page;
use App\Models\Semestre;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Seeder sûr pour la PRODUCTION.
 * Utilise firstOrCreate → ne supprime RIEN, n'écrase RIEN.
 * Peut être relancé autant de fois sans risque.
 */
class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. SEMESTRES ---
        $semestres = [
            ['id' => 1, 'nom' => 'Semestre 1', 'niveau' => 'L1'],
            ['id' => 2, 'nom' => 'Semestre 2', 'niveau' => 'L1'],
            ['id' => 3, 'nom' => 'Semestre 3', 'niveau' => 'L2'],
            ['id' => 4, 'nom' => 'Semestre 4', 'niveau' => 'L2'],
            ['id' => 5, 'nom' => 'Semestre 5', 'niveau' => 'L3'],
            ['id' => 6, 'nom' => 'Semestre 6', 'niveau' => 'L3'],
        ];

        foreach ($semestres as $s) {
            Semestre::firstOrCreate(['id' => $s['id']], $s);
        }

        $this->command->info('✓ Semestres créés.');

        // --- 2. MATIÈRES ---
        $matieres = [
            // S1
            ['code' => 'MIO 1111', 'nom' => 'Statistiques pour l\'Economie et la Gestion', 'semestre_id' => 1],
            ['code' => 'MIO 1112', 'nom' => 'Comptabilité Financière 1', 'semestre_id' => 1],
            ['code' => 'MIO 1121', 'nom' => 'Economie Générale', 'semestre_id' => 1],
            ['code' => 'MIO 1122', 'nom' => 'Introduction à l\'Etude du Droit', 'semestre_id' => 1],
            ['code' => 'MIO 1123', 'nom' => 'Anglais', 'semestre_id' => 1],
            ['code' => 'MIO 1131', 'nom' => 'Mathématiques Générales', 'semestre_id' => 1],
            ['code' => 'MIO 1132', 'nom' => 'Algorithmique', 'semestre_id' => 1],
            ['code' => 'MIO 1141', 'nom' => 'Recherche Documentaire', 'semestre_id' => 1],
            ['code' => 'MIO1142', 'nom' => 'Projet Personnel Professionnel', 'semestre_id' => 1],
            ['code' => 'MIO1143', 'nom' => 'Sport, Culture et Pédagogie', 'semestre_id' => 1],
            // S2
            ['code' => 'MIO 1211', 'nom' => 'Economie de l\'Entreprise', 'semestre_id' => 2],
            ['code' => 'MIO 1212', 'nom' => 'Comptabilité Financière 2', 'semestre_id' => 2],
            ['code' => 'MIO 1221', 'nom' => 'Systèmes d\'information de Gestion', 'semestre_id' => 2],
            ['code' => 'MIO 1222', 'nom' => 'Techniques de Communication', 'semestre_id' => 2],
            ['code' => 'MIO 1231', 'nom' => 'Mathématiques Discrètes', 'semestre_id' => 2],
            ['code' => 'MIO 1232', 'nom' => 'Calcul de Probabilités', 'semestre_id' => 2],
            ['code' => 'MIO 1241', 'nom' => 'Algorithmique et Programmation', 'semestre_id' => 2],
            ['code' => 'MIO 1242', 'nom' => 'Applications Informatiques', 'semestre_id' => 2],
            ['code' => 'MIO1251', 'nom' => 'Visite d\'Entreprise/Stage', 'semestre_id' => 2],
            // S3
            ['code' => 'MIO 2311', 'nom' => 'Méthodes d\'Optimisation pour la Gestion', 'semestre_id' => 3],
            ['code' => 'MIO 2312', 'nom' => 'Comptabilité de Gestion', 'semestre_id' => 3],
            ['code' => 'MIO 2321', 'nom' => 'Management Stratégique et Opérationnel', 'semestre_id' => 3],
            ['code' => 'MIO 2322', 'nom' => 'Anglais', 'semestre_id' => 3],
            ['code' => 'MIO 2323', 'nom' => 'Introduction au Marketing', 'semestre_id' => 3],
            ['code' => 'MIO 2331', 'nom' => 'Analyse et Conception et Systèmes d\'information', 'semestre_id' => 3],
            ['code' => 'MIO 2332', 'nom' => 'Langages pour le Développement Web', 'semestre_id' => 3],
            // S4
            ['code' => 'MIO 2411', 'nom' => 'Langages pour le Développement Web Avancé', 'semestre_id' => 4],
            ['code' => 'MIO 2412', 'nom' => 'Techniques de Communication', 'semestre_id' => 4],
            ['code' => 'MIO 2413', 'nom' => 'Droit des Obligations et Commercial', 'semestre_id' => 4],
            ['code' => 'MIO 2421', 'nom' => 'Estimation de Tests et Statistiques', 'semestre_id' => 4],
            ['code' => 'MIO2422', 'nom' => 'Analyse de Données', 'semestre_id' => 4],
            ['code' => 'MIO 2431', 'nom' => 'Analyse et Conception de Systèmes Orientés Objet', 'semestre_id' => 4],
            ['code' => 'MIO 2432', 'nom' => 'Base de Données', 'semestre_id' => 4],
            ['code' => 'MIO2441', 'nom' => 'Visite d\'Entreprise / Stage', 'semestre_id' => 4],
            // S5
            ['code' => 'MIO3511', 'nom' => 'Analyse Financière', 'semestre_id' => 5],
            ['code' => 'MIO3512', 'nom' => 'Gestion des Ressources Humaines', 'semestre_id' => 5],
            ['code' => 'MIO3513', 'nom' => 'Création d\'Entreprises', 'semestre_id' => 5],
            ['code' => 'MIO 3521', 'nom' => 'Fiscalité', 'semestre_id' => 5],
            ['code' => 'MIO 3522', 'nom' => 'Gestion Budgétaire', 'semestre_id' => 5],
            ['code' => 'MIO 3523', 'nom' => 'Comptabilité des Sociétés', 'semestre_id' => 5],
            ['code' => 'MIO3533', 'nom' => 'Langage Orienté Objet', 'semestre_id' => 5],
            ['code' => 'MIO3531', 'nom' => 'Outils pour le Développement et l\'Administration Web', 'semestre_id' => 5],
            ['code' => 'MIO3532', 'nom' => 'Réseaux et Protocoles', 'semestre_id' => 5],
            // S6
            ['code' => 'MIO3613', 'nom' => 'Methodologie de Rédaction de PFC', 'semestre_id' => 6],
            ['code' => 'MIO3611', 'nom' => 'Management de Projets', 'semestre_id' => 6],
            ['code' => 'MIO3612', 'nom' => 'Econométrie', 'semestre_id' => 6],
            ['code' => 'MIO3621', 'nom' => 'Projet de Fin de Cycle/ Rapport', 'semestre_id' => 6],
        ];

        foreach ($matieres as $m) {
            Matiere::firstOrCreate(['code' => $m['code']], $m);
        }

        $this->command->info('✓ ' . count($matieres) . ' matières créées.');

        // --- 3. CATÉGORIES FORUM ---
        $categories = [
            ['nom' => 'Discussions Générales', 'description' => 'Pour parler de tout et de rien.'],
            ['nom' => 'Licence 1',              'description' => 'Entraide pour la L1.'],
            ['nom' => 'Licence 2',              'description' => 'Entraide pour la L2.'],
            ['nom' => 'Licence 3',              'description' => 'Entraide pour la L3.'],
            ['nom' => 'Annonces des Professeurs', 'description' => 'Informations officielles.'],
        ];

        foreach ($categories as $cat) {
            ForumCategory::firstOrCreate(['nom' => $cat['nom']], $cat);
        }

        $this->command->info('✓ Catégories forum créées.');

        // --- 4. PAGE À PROPOS ---
        Page::firstOrCreate(
            ['slug' => 'a-propos'],
            ['titre' => 'À Propos de MIO-Ressources', 'contenu' => '<p>Bienvenue sur la plateforme MIO Ressources.</p>']
        );

        $this->command->info('✓ Page à propos créée.');

        // --- 5. PARAMÈTRES ---
        $settings = [
            ['key' => 'contact_email',  'label' => 'Email de contact',          'value' => 'contact@mio.sn'],
            ['key' => 'contact_phone',  'label' => 'Téléphone',                  'value' => '+221 77 000 00 00'],
            ['key' => 'social_facebook','label' => 'Lien Facebook',              'value' => ''],
            ['key' => 'social_linkedin','label' => 'Lien LinkedIn',              'value' => ''],
            ['key' => 'social_twitter', 'label' => 'Lien X (Twitter)',           'value' => ''],
            ['key' => 'univ_address',   'label' => 'Adresse de l\'Université',   'value' => 'Thiès, Sénégal'],
            ['key' => 'univ_map',       'label' => 'Carte Google Maps (Iframe)', 'value' => ''],
        ];

        foreach ($settings as $s) {
            Setting::firstOrCreate(['key' => $s['key']], $s);
        }

        $this->command->info('✓ Paramètres créés.');
        $this->command->info('');
        $this->command->info('Seeder terminé avec succès !');
    }
}
