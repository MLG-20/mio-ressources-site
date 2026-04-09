<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ScheduledMeetingController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\TeacherSpaceController;
use App\Http\Controllers\UserSpaceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PrivateLessonController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;



Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $db = 'ok';
    } catch (\Exception $e) {
        $db = 'error';
    }

    try {
        Cache::put('health', 'ok', 10);
        $cache = Cache::get('health') === 'ok' ? 'ok' : 'error';
    } catch (\Exception $e) {
        $cache = 'error';
    }

    $status = ($db === 'ok' && $cache === 'ok') ? 'healthy' : 'unhealthy';

    return response()->json([
        'status' => $status,
        'checks' => [
            'database' => $db,
            'cache' => $cache,
        ]
    ]);
});

/*
|--------------------------------------------------------------------------
| HEALTH CHECK (monitoring)
|--------------------------------------------------------------------------
*/

Route::get('/health', function () {
    $checks = [];

    // Base de données
    try {
        DB::connection()->getPdo();
        $checks['database'] = 'ok';
    } catch (\Exception $e) {
        $checks['database'] = 'error';
    }

    // Cache Redis
    try {
        Cache::put('health_check', true, 10);
        $checks['cache'] = Cache::get('health_check') ? 'ok' : 'error';
    } catch (\Exception $e) {
        $checks['cache'] = 'error';
    }

    $status = in_array('error', $checks) ? 500 : 200;

    return response()->json([
        'status' => $status === 200 ? 'healthy' : 'unhealthy',
        'checks' => $checks,
    ], $status);
})->middleware('throttle:30,1')->name('health');

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/page/{slug}', [HomeController::class, 'showPage'])->name('page.show');
Route::get('/semestre/{id}', [HomeController::class, 'showSemestre'])->name('semestre.show');
Route::get('/matiere/{id}', [HomeController::class, 'showMatiere'])->name('matiere.show');
Route::get('/bibliotheque', [HomeController::class, 'library'])->name('library.index');
Route::post('/avis', [HomeController::class, 'storeReview'])->middleware('throttle:10,1')->name('avis.store');


// --- PAIEMENT ---
Route::get('/payer/{id}/{type}', [PaymentController::class, 'initiatePayment'])->middleware('throttle:10,1')->name('payment.pay');
Route::get('/paiement/succes/{id}/{type}', [PaymentController::class, 'success'])->name('payment.success');
    // C'EST CETTE ROUTE QUI MANQUAIT POUR LE LIVRE
Route::get('/achat-livre/{id}', [HomeController::class, 'checkoutBook'])->name('book.checkout');

/*
|--------------------------------------------------------------------------
| ROUTES PAIEMENT & WEBHOOKS
|--------------------------------------------------------------------------
*/

// IPN (Webhook) - Doit être public
//Route::post('/paiement/ipn', [PaymentController::class, 'handleIPN'])->name('payment.ipn');
Route::post('/api/paytech/callback', [PaymentController::class, 'handleIPN'])->name('payment.ipn');

Route::get('/telechargement-invite/{token}/{type}/{id}', [HomeController::class, 'guestDownload'])
    ->name('guest.download')
    ->middleware('signed');

Route::get('/merci', function() { return view('payments.thankyou'); })->name('payment.thankyou');

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES (Membres connectés)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Route dashboard (requise par Laravel Breeze après login/register/confirm-password)
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    // Espace Personnel
    Route::get('/mon-espace', [UserSpaceController::class, 'index'])->name('user.dashboard');
    Route::post('/mon-espace/update', [UserSpaceController::class, 'updateProfile'])->name('user.profile.update');
    Route::delete('/user/delete', [UserSpaceController::class, 'deleteAccount'])->name('user.account.delete');

    // Révision instantanée (Jitsi)
    Route::get('/revision-instantanee', [MeetingController::class, 'quick'])->name('meeting.quick');

    // Cours vidéo planifiés (Phase 2)
    Route::resource('scheduled-meetings', ScheduledMeetingController::class);
    Route::post('/scheduled-meetings/{scheduled_meeting}/close', [ScheduledMeetingController::class, 'close'])->name('scheduled-meetings.close');

    // Cours pour étudiants (Phase 2)
    Route::get('/mes-cours', [StudentCourseController::class, 'index'])->name('student.courses');
    Route::post('/cours/{meeting}/inscription', [StudentCourseController::class, 'enroll'])->name('student.courses.enroll');
    Route::delete('/cours/{meeting}/desinscription', [StudentCourseController::class, 'unenroll'])->name('student.courses.unenroll');

    // Mémoires d'étudiants
    Route::post('/mon-espace/publier-memoire', [UserSpaceController::class, 'publishMemoir'])->name('user.memoire.store');
    Route::delete('/mon-espace/memoire/{id}', [UserSpaceController::class, 'destroyMemoir'])->name('user.memoire.destroy');

    // Historique
    Route::get('/view/{id}', [HomeController::class, 'viewRessource'])->name('ressource.view');
    Route::get('/download/{id}', [HomeController::class, 'downloadRessource'])->name('ressource.download');
    Route::delete('/historique/supprimer/{id}', [UserSpaceController::class, 'destroyHistory'])->name('user.history.destroy');
    Route::delete('/historique/vider', [UserSpaceController::class, 'clearHistory'])->name('user.history.clear');

    // Facture
    Route::get('/facture/{id}', [InvoiceController::class, 'download'])->name('invoice.download');

    // Avis sur documents
    Route::post('/rate/{id}/{type}', [HomeController::class, 'storeResourceRating'])->middleware('throttle:10,1')->name('resource.rate');



    // --- ESPACE PROFESSEUR ---
    // SÉCURITÉ : accès réservé uniquement aux comptes ayant role = 'professeur'
    Route::prefix('espace-enseignant')->middleware('role:professeur')->group(function () {
        Route::get('/', [TeacherSpaceController::class, 'index'])->name('teacher.dashboard');
        Route::post('/profil/update', [TeacherSpaceController::class, 'updateProfile'])->name('teacher.profile.update');
        Route::post('/publier', [TeacherSpaceController::class, 'store'])->name('teacher.publication.store');
        Route::delete('/supprimer/{id}', [TeacherSpaceController::class, 'destroy'])->name('teacher.publication.destroy');
        Route::delete('/compte/supprimer', [TeacherSpaceController::class, 'deleteAccount'])->name('teacher.account.delete');
    });

    // --- GROUPES DE TRAVAIL ---
    Route::prefix('groupes')->name('groups.')->group(function () {
        Route::get('/', [\App\Http\Controllers\WorkGroupController::class, 'index'])->name('index');
        Route::post('/create', [\App\Http\Controllers\WorkGroupController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\WorkGroupController::class, 'show'])->name('show');
        Route::post('/{id}/invite', [\App\Http\Controllers\WorkGroupController::class, 'invite'])->name('invite');
        Route::delete('/{id}/members/{userId}', [\App\Http\Controllers\WorkGroupController::class, 'removeMember'])->name('remove-member');
        Route::delete('/{id}/leave', [\App\Http\Controllers\WorkGroupController::class, 'leave'])->name('leave');
        Route::delete('/{id}', [\App\Http\Controllers\WorkGroupController::class, 'destroy'])->name('destroy');
    });

    // --- COURS PARTICULIERS ---
    // Marketplace pour étudiants
    Route::prefix('cours-particuliers')->name('private-lessons.')->group(function () {
        Route::get('/', [PrivateLessonController::class, 'browse'])->name('browse');
        Route::get('/room/{enrollmentId}', [PrivateLessonController::class, 'joinRoom'])->name('room');
        Route::delete('/enrollment/{enrollmentId}', [PrivateLessonController::class, 'cancelEnrollment'])->name('enrollment.cancel');
        Route::get('/{id}', [PrivateLessonController::class, 'show'])->name('show');
        Route::post('/{id}/acceder', [PrivateLessonController::class, 'access'])->name('access');

        // Paiement Paytech
        Route::post('/payment/callback', [PrivateLessonController::class, 'paymentCallback'])->name('payment.callback');
        Route::get('/payment/success', [PrivateLessonController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/payment/cancel', [PrivateLessonController::class, 'paymentCancel'])->name('payment.cancel');
    });

    // Gestion professeur
    Route::prefix('enseignant/cours-particuliers')->middleware('role:professeur')->name('teacher.private-lessons.')->group(function () {
        Route::get('/', [PrivateLessonController::class, 'teacherIndex'])->name('index');
        Route::get('/creer', [PrivateLessonController::class, 'create'])->name('create');
        Route::post('/creer', [PrivateLessonController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [PrivateLessonController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PrivateLessonController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [PrivateLessonController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{id}', [PrivateLessonController::class, 'destroy'])->name('destroy');
    });

    // --- FORUM ---
    Route::middleware(['auth'])->group(function () {

    // --- 1. ROUTES D'AFFICHAGE (ForumController) ---
    // Celles-ci servent à naviguer dans le forum quand on clique sur "Voir"
    Route::prefix('forum')->group(function () {
        Route::get('/', [ForumController::class, 'index'])->name('forum.index');
        Route::get('/categorie/{id}', [ForumController::class, 'showCategory'])->name('forum.category');
        Route::get('/sujet/{id}', [ForumController::class, 'showSujet'])->name('forum.sujet');

        // Répondre à un sujet existant (depuis la page show.blade.php)
        Route::post('/sujet/{id}/repondre', [ForumController::class, 'storeMessage'])->middleware('throttle:20,1')->name('forum.sujet.reply');
    });

    // --- 2. ROUTES D'ACTION DASHBOARD (UserSpaceController) ---
    // Celles-ci font fonctionner votre formulaire sur le bureau

    // C'est LA route utilisée par votre formulaire "Lancer une discussion"
    Route::post('/forum/message/store', [UserSpaceController::class, 'storeMessage'])->middleware('throttle:20,1')->name('forum.message.store');

    // Pour supprimer une discussion (Titre + Messages)
    Route::delete('/forum/sujet-perso/{id}', [UserSpaceController::class, 'destroySujet'])->name('user.sujet.destroy');

    // Pour supprimer un message unique
    Route::delete('/forum/message/{id}', [UserSpaceController::class, 'destroyMessage'])->name('forum.message.destroy');

    // Pour modifier un message
    Route::put('/forum/message/{id}', [UserSpaceController::class, 'updateMessage'])->middleware('throttle:20,1')->name('forum.message.update');

});


});

require __DIR__.'/auth.php';
