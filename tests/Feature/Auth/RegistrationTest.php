<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type' => 'student',
            'student_level' => 'L1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/mon-espace');
    }

    public function test_student_must_choose_a_level(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sans Niveau',
            'email' => 'sansniveau@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'user_type' => 'student',
            // student_level volontairement absent
        ]);

        $response->assertSessionHasErrors('student_level');
        $this->assertGuest();
    }
}
