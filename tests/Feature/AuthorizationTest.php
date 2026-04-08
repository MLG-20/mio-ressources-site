<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create(['role' => 'professeur', 'is_blocked' => false]);
        $this->student = User::factory()->create(['role' => 'etudiant', 'is_blocked' => false]);
    }

    /**
     * Test: Utilisateur non authentifié est redirigé vers login
     */
    public function test_unauthenticated_user_redirect_to_login(): void
    {
        $this->get(route('user.dashboard'))
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Étudiant peut accéder à son espace
     */
    public function test_student_can_access_dashboard(): void
    {
        $this->actingAs($this->student)
            ->get(route('user.dashboard'))
            ->assertStatus(200);
    }

    /**
     * Test: Professeur peut accéder à son espace
     */
    public function test_teacher_can_access_teacher_dashboard(): void
    {
        $this->actingAs($this->teacher)
            ->get(route('teacher.dashboard'))
            ->assertStatus(200);
    }

    /**
     * Test: Étudiant ne peut pas accéder à l'espace professeur
     */
    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $this->actingAs($this->student)
            ->get(route('teacher.dashboard'))
            ->assertStatus(403);
    }

    /**
     * Test: Professeur peut accéder au dashboard des cours particuliers
     */
    public function test_teacher_can_access_private_lessons_dashboard(): void
    {
        $this->actingAs($this->teacher)
            ->get(route('teacher.private-lessons.index'))
            ->assertStatus(200);
    }

    /**
     * Test: Étudiant ne peut pas accéder au dashboard prof des cours
     */
    public function test_student_cannot_access_teacher_private_lessons(): void
    {
        $this->actingAs($this->student)
            ->get(route('teacher.private-lessons.index'))
            ->assertStatus(403);
    }

    /**
     * Test: Étudiant peut consulter les cours particuliers disponibles
     */
    public function test_student_can_browse_available_private_lessons(): void
    {
        $this->actingAs($this->student)
            ->get(route('private-lessons.browse'))
            ->assertStatus(200);
    }

    /**
     * Test: Page d'accueil est accessible
     */
    public function test_homepage_is_accessible(): void
    {
        $this->get(route('home'))
            ->assertStatus(200);
    }

    /**
     * Test: Bibliothèque est accessible
     */
    public function test_library_is_accessible(): void
    {
        $this->get(route('library.index'))
            ->assertStatus(200);
    }

    /**
     * Test: Forum est accessible pour utilisator connecté
     */
    public function test_authenticated_user_can_access_forum(): void
    {
        $this->actingAs($this->student)
            ->get(route('forum.index'))
            ->assertStatus(200);
    }

    /**
     * Test: Forum non-accessible pour utilisateurs non authentifiés
     */
    public function test_unauthenticated_user_cannot_access_forum(): void
    {
        $this->get(route('forum.index'))
            ->assertRedirect(route('login'));
    }

    /**
     * Test: Professeur peut accéder à son espace
     */
    public function test_profeser_can_access_teacher_space(): void
    {
        $this->actingAs($this->teacher)
            ->get(route('teacher.dashboard'))
            ->assertStatus(200);
    }
}

