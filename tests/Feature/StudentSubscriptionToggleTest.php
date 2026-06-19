<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureStudentSubscriptionActive;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class StudentSubscriptionToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Route de test isolant le middleware d'abonnement.
        Route::middleware(EnsureStudentSubscriptionActive::class)
            ->get('/__test_sub', fn () => 'OK');
    }

    private function expiredStudent(): User
    {
        return User::factory()->create([
            'user_type'               => 'student',
            'role'                    => 'etudiant',
            'trial_ends_at'           => now()->subDay(),
            'subscription_paid_until' => null,
        ]);
    }

    public function test_expired_student_keeps_access_when_subscription_not_required(): void
    {
        // Interrupteur OFF = valeur par défaut posée par la migration de seed.
        $this->actingAs($this->expiredStudent())
            ->get('/__test_sub')
            ->assertOk()
            ->assertSee('OK');
    }

    public function test_expired_student_is_blocked_when_subscription_required(): void
    {
        Setting::where('key', 'student_subscription_required')->update(['is_enabled' => true]);

        $this->actingAs($this->expiredStudent())
            ->get('/__test_sub')
            ->assertRedirect(route('student.subscription.paywall'));
    }

    public function test_student_in_trial_keeps_access_even_when_required(): void
    {
        Setting::where('key', 'student_subscription_required')->update(['is_enabled' => true]);

        $student = User::factory()->create([
            'user_type'               => 'student',
            'role'                    => 'etudiant',
            'trial_ends_at'           => now()->addMonth(),
            'subscription_paid_until' => null,
        ]);

        $this->actingAs($student)->get('/__test_sub')->assertOk();
    }
}
