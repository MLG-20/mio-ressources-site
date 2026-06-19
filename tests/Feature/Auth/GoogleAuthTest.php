<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Mocke le retour de Google avec les infos fournies.
     */
    private function mockGoogleUser(string $id, string $email, string $name = 'Awa Diop', string $avatar = 'https://example.com/a.png'): void
    {
        $abstractUser = Mockery::mock(\Laravel\Socialite\Contracts\User::class);
        $abstractUser->shouldReceive('getId')->andReturn($id);
        $abstractUser->shouldReceive('getName')->andReturn($name);
        $abstractUser->shouldReceive('getEmail')->andReturn($email);
        $abstractUser->shouldReceive('getAvatar')->andReturn($avatar);

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_redirect_route_sends_user_to_google(): void
    {
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $this->get(route('google.redirect'))
            ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    public function test_callback_with_new_user_redirects_to_choose_type_without_creating_account(): void
    {
        $this->mockGoogleUser('g-new', 'nouveau@gmail.com');

        $response = $this->get(route('google.callback'));

        $response->assertRedirect(route('google.choose-type'));
        $response->assertSessionHas('google_oauth');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'nouveau@gmail.com']);
    }

    public function test_choose_type_store_creates_student_with_trial(): void
    {
        $response = $this->withSession(['google_oauth' => [
            'google_id' => 'g-stu',
            'name'      => 'Awa Diop',
            'email'     => 'awa@gmail.com',
            'avatar'    => 'https://example.com/a.png',
        ]])->post(route('google.store'), [
            'user_type'     => 'student',
            'student_level' => 'L2',
        ]);

        $response->assertRedirect(route('user.dashboard', absolute: false));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email'         => 'awa@gmail.com',
            'google_id'     => 'g-stu',
            'role'          => 'etudiant',
            'user_type'     => 'student',
            'student_level' => 'L2',
        ]);

        $user = User::where('email', 'awa@gmail.com')->first();
        $this->assertNull($user->password);            // pas de mot de passe (compte Google)
        $this->assertNotNull($user->trial_ends_at);    // essai 3 mois
        $this->assertNotNull($user->email_verified_at); // e-mail vérifié par Google
    }

    public function test_choose_type_store_requires_level_for_student(): void
    {
        $response = $this->withSession(['google_oauth' => [
            'google_id' => 'g-nolevel',
            'name'      => 'Sans Niveau',
            'email'     => 'nolevel@gmail.com',
            'avatar'    => null,
        ]])->post(route('google.store'), [
            'user_type' => 'student',
            // student_level absent
        ]);

        $response->assertSessionHasErrors('student_level');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'nolevel@gmail.com']);
    }

    public function test_choose_type_store_creates_teacher_without_trial(): void
    {
        $response = $this->withSession(['google_oauth' => [
            'google_id' => 'g-prof',
            'name'      => 'Pr. Sow',
            'email'     => 'sow@gmail.com',
            'avatar'    => null,
        ]])->post(route('google.store'), [
            'user_type' => 'teacher',
        ]);

        $response->assertRedirect('/espace-enseignant');
        $this->assertAuthenticated();

        $user = User::where('email', 'sow@gmail.com')->first();
        $this->assertSame('professeur', $user->role);
        $this->assertSame('teacher', $user->user_type);
        $this->assertNull($user->trial_ends_at);
    }

    public function test_callback_links_existing_email_account_and_logs_in(): void
    {
        $user = User::factory()->create([
            'email'     => 'awa@gmail.com',
            'google_id' => null,
            'role'      => 'etudiant',
            'user_type' => 'student',
        ]);

        $this->mockGoogleUser('g-link', 'awa@gmail.com');

        $response = $this->get(route('google.callback'));

        $response->assertRedirect(route('user.dashboard', absolute: false));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('g-link', $user->fresh()->google_id);
    }

    public function test_callback_logs_in_existing_google_account(): void
    {
        $user = User::factory()->create([
            'google_id' => 'g-known',
            'role'      => 'professeur',
            'user_type' => 'teacher',
        ]);

        $this->mockGoogleUser('g-known', $user->email);

        $response = $this->get(route('google.callback'));

        $response->assertRedirect('/espace-enseignant');
        $this->assertAuthenticatedAs($user);
    }
}
