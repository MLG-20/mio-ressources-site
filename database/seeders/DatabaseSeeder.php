<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PrivateLesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Super admin (lit les credentials depuis .env)
        $this->call(AdminSeeder::class);

        // Appeler le seeder académique
        $this->call(AcademicSeeder::class);

        // Créer des utilisateurs de test
        $testStudent = User::factory()->create([
            'name' => 'Etudiant Test',
            'email' => 'etudiant@test.com',
            'user_type' => 'student',
            'student_level' => 'L1',
            'role' => 'etudiant',
        ]);

        $testTeacher = User::factory()->create([
            'name' => 'Professeur Test',
            'email' => 'prof@test.com',
            'user_type' => 'teacher',
            'role' => 'professeur',
            'specialty' => 'Mathématiques',
        ]);

        // Créer des cours particuliers de test
        PrivateLesson::create([
            'titre' => 'Révision Algèbre Linéaire L1',
            'description' => 'Cours de révision complet sur l\'algèbre linéaire. Matrices, vecteurs, espaces vectoriels et applications linéaires. Exercices pratiques inclus.',
            'prix' => 5000,
            'duree_minutes' => 60,
            'teacher_id' => $testTeacher->id,
            'matiere_id' => 13, // MIO 1131 - Mathématiques Générales
            'disponibilites' => json_encode([
                ['jour' => 'Lundi', 'horaires' => '14h-16h, 18h-20h'],
                ['jour' => 'Mercredi', 'horaires' => '15h-17h'],
                ['jour' => 'Vendredi', 'horaires' => '14h-16h'],
            ]),
            'places_max' => 3,
            'statut' => 'actif',
        ]);

        PrivateLesson::create([
            'titre' => 'Introduction à la Programmation Python',
            'description' => 'Apprenez les bases de Python : variables, boucles, fonctions, et POO. Parfait pour débuter en programmation.',
            'prix' => 4000,
            'duree_minutes' => 90,
            'teacher_id' => $testTeacher->id,
            'matiere_id' => 14, // MIO 1132 - Algorithmique
            'disponibilites' => json_encode([
                ['jour' => 'Mardi', 'horaires' => '17h-19h'],
                ['jour' => 'Jeudi', 'horaires' => '14h-16h, 18h-20h'],
                ['jour' => 'Samedi', 'horaires' => '10h-12h, 14h-16h'],
            ]),
            'places_max' => 2,
            'statut' => 'actif',
        ]);

        PrivateLesson::create([
            'titre' => 'Statistiques & Probabilités - Préparation Examen',
            'description' => 'Préparation intensive aux examens de statistiques. Variables aléatoires, lois de probabilité, tests d\'hypothèses.',
            'prix' => 6000,
            'duree_minutes' => 120,
            'teacher_id' => $testTeacher->id,
            'matiere_id' => 16, // MIO 1232 - Calcul de Probabilités
            'disponibilites' => json_encode([
                ['jour' => 'Lundi', 'horaires' => '19h-21h'],
                ['jour' => 'Mercredi', 'horaires' => '18h-20h'],
                ['jour' => 'Dimanche', 'horaires' => '15h-17h'],
            ]),
            'places_max' => 1,
            'statut' => 'actif',
        ]);

        PrivateLesson::create([
            'titre' => 'Comptabilité Financière - Niveau L1',
            'description' => 'Maîtrisez les fondamentaux : bilan, compte de résultats, écritures comptables et journal.',
            'prix' => 5500,
            'duree_minutes' => 60,
            'teacher_id' => $testTeacher->id,
            'matiere_id' => null,
            'disponibilites' => json_encode([
                ['jour' => 'Mardi', 'horaires' => '14h-16h'],
                ['jour' => 'Jeudi', 'horaires' => '15h-17h'],
            ]),
            'places_max' => 4,
            'statut' => 'actif',
        ]);

        PrivateLesson::create([
            'titre' => 'Droit Commercial pour Débutants',
            'description' => 'Découvrez les bases du droit commercial : contrats, obligations, responsabilité civile.',
            'prix' => 3500,
            'duree_minutes' => 30,
            'teacher_id' => $testTeacher->id,
            'matiere_id' => null,
            'disponibilites' => json_encode([
                ['jour' => 'Mercredi', 'horaires' => '13h-14h30'],
                ['jour' => 'Samedi', 'horaires' => '11h-12h30'],
            ]),
            'places_max' => 5,
            'statut' => 'actif',
        ]);
    }
}
