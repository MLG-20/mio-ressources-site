<?php

namespace App\Http\Controllers;

use App\Models\PrivateLesson;
use App\Models\PrivateLessonEnrollment;
use App\Models\Matiere;
use App\Notifications\TeacherStartedPrivateLessonNotification;
use App\Notifications\PrivateLessonReminderNotification;
use App\Notifications\PrivateLessonCancelledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PrivateLessonController extends Controller
{
    /**
     * PAGE MARKETPLACE POUR ÉTUDIANTS
     * Afficher tous les cours disponibles
     */
    public function browse(Request $request)
    {
        $query = PrivateLesson::with(['teacher', 'matiere'])
            ->active();

        // Recherche textuelle
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                // Recherche dans le titre
                $q->where('titre', 'LIKE', "%{$searchTerm}%")
                  // Recherche dans la description
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  // Recherche par nom de matière
                  ->orWhereHas('matiere', function($q) use ($searchTerm) {
                      $q->where('nom', 'LIKE', "%{$searchTerm}%");
                  })
                  // Recherche par nom de professeur
                  ->orWhereHas('teacher', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%");
                  });

                // Recherche par type (gratuit/payant)
                if (stripos($searchTerm, 'gratuit') !== false || stripos($searchTerm, 'tutoriel') !== false) {
                    $q->orWhere('type', 'tutoriel');
                }
                if (stripos($searchTerm, 'payant') !== false) {
                    $q->orWhere('type', 'payant');
                }

                // Recherche par durée
                if (stripos($searchTerm, '1 heure') !== false || stripos($searchTerm, '60') !== false) {
                    $q->orWhere('duree_minutes', 60);
                }
                if (stripos($searchTerm, '30') !== false) {
                    $q->orWhere('duree_minutes', 30);
                }
                if (stripos($searchTerm, '90') !== false || stripos($searchTerm, '1h30') !== false) {
                    $q->orWhere('duree_minutes', 90);
                }
                if (stripos($searchTerm, '2 heure') !== false || stripos($searchTerm, '120') !== false) {
                    $q->orWhere('duree_minutes', 120);
                }
            });
        }

        // Anciens filtres (gardés pour compatibilité si besoin)
        if ($request->filled('matiere_id')) {
            $query->where('matiere_id', $request->matiere_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('prix_min') || $request->filled('prix_max')) {
            $query->where('type', '!=', 'tutoriel');

            if ($request->filled('prix_min')) {
                $query->where('prix', '>=', $request->prix_min);
            }

            if ($request->filled('prix_max')) {
                $query->where('prix', '<=', $request->prix_max);
            }
        }

        if ($request->filled('duree')) {
            $query->where('duree_minutes', $request->duree);
        }

        $lessons = $query->paginate(12);
        $matieres = Matiere::all();

        return view('private-lessons.browse', compact('lessons', 'matieres'));
    }

    /**
     * Afficher les détails d'un cours
     */
    public function show($id)
    {
        $lesson = PrivateLesson::with(['teacher', 'matiere', 'enrollments'])->findOrFail($id);

        // Calculer les places restantes
        $placesReservees = $lesson->enrollments()->where('payment_status', 'paid')->count();
        $placesRestantes = $lesson->places_max - $placesReservees;

        return view('private-lessons.show', compact('lesson', 'placesRestantes'));
    }

    /**
     * Accéder à un cours (créer l'inscription et gérer le paiement si nécessaire)
     */
    public function access(Request $request, $id)
    {
        $lesson = PrivateLesson::with('enrollments')->findOrFail($id);

        // Vérifier que la date de début est définie
        if (!$lesson->start_date) {
            return back()->withErrors(['error' => 'Le cours n\'a pas de date de début définie. Contactez le professeur.']);
        }

        $existingEnrollment = $lesson->enrollments()
            ->where('student_id', Auth::id())
            ->where('payment_status', 'paid')
            ->first();

        if ($existingEnrollment) {
            return back()->with('info', 'Vous êtes déjà inscrit à ce cours !');
        }

        // Créer l'inscription
        $enrollment = PrivateLessonEnrollment::create([
            'private_lesson_id' => $lesson->id,
            'student_id' => Auth::id(),
            'scheduled_at' => $lesson->start_date,
            'payment_status' => $lesson->type === 'tutoriel' ? 'paid' : 'pending',
            'amount_paid' => $lesson->prix,
            'session_status' => 'scheduled',
        ]);

        // Si c'est un tutoriel gratuit, redirection directe vers le dashboard
        if ($lesson->type === 'tutoriel') {
            return redirect()->to(route('user.dashboard', [], false) . '#courses')
                ->with('success', '✅ Inscription confirmée ! Le cours "' . $lesson->titre . '" est maintenant visible dans votre espace.');
        }

        // Sinon, rediriger vers Paytech pour paiement
        return $this->initiatePayment($enrollment, $lesson);
    }

    /**
     * Initier le paiement Paytech
     */
    private function initiatePayment($enrollment, $lesson)
    {
        $response = Http::asForm()->post('https://paytech.sn/api/payment/request-payment', [
            'item_name' => $lesson->titre,
            'item_price' => $lesson->prix,
            'currency' => 'XOF',
            'ref_command' => 'PL-' . $enrollment->id,
            'command_name' => "Cours particulier: {$lesson->titre}",
            'env' => config('services.paytech.env'),
            'ipn_url' => route('private-lessons.payment.callback'),
            'success_url' => route('private-lessons.payment.success'),
            'cancel_url' => route('private-lessons.payment.cancel'),
            'custom_field' => json_encode([
                'enrollment_id' => $enrollment->id,
            ]),
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return redirect($data['redirect_url']);
        }

        return back()->withErrors(['error' => 'Erreur lors de l\'initialisation du paiement.']);
    }

    /**
     * Callback Paytech (IPN)
     */
    public function paymentCallback(Request $request)
    {
        // Vérifier la signature
        $receivedSignature = $request->header('Paytech-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, config('services.paytech.secret'));

        if ($receivedSignature !== $expectedSignature) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $data = $request->all();

        if ($data['type_event'] === 'sale_complete') {
            $customField = json_decode($data['custom_field'], true);
            $enrollment = PrivateLessonEnrollment::find($customField['enrollment_id']);

            if ($enrollment) {
                $enrollment->update([
                    'payment_status' => 'paid',
                    'payment_reference' => $data['ref_command'],
                ]);

                // TODO: Envoyer email de confirmation
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Page de succès après paiement
     */
    public function paymentSuccess(Request $request)
    {
        return view('private-lessons.payment-success');
    }

    /**
     * Page d'annulation de paiement
     */
    public function paymentCancel()
    {
        return view('private-lessons.payment-cancel');
    }

    /**
     * =============================
     * SECTION PROFESSEUR
     * =============================
     */

    /**
     * Liste des cours du professeur
     */
    public function teacherIndex()
    {
        $lessons = PrivateLesson::where('teacher_id', Auth::id())
            ->with(['matiere', 'enrollments'])
            ->withCount(['enrollments as enrollments_paid_count' => function($query) {
                $query->where('payment_status', 'paid');
            }])
            ->get();

        // Calculer les revenus totaux
        $totalRevenue = PrivateLessonEnrollment::whereHas('privateLesson', function($query) {
            $query->where('teacher_id', Auth::id());
        })->where('payment_status', 'paid')->sum('amount_paid');

        return view('teacher.private-lessons.index', compact('lessons', 'totalRevenue'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $matieres = Matiere::all();
        return view('teacher.private-lessons.create', compact('matieres'));
    }

    /**
     * Enregistrer un nouveau cours
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'duree_minutes' => 'required|integer|in:30,60,90,120',
            'matiere_id' => 'nullable|exists:matieres,id',
            'student_level' => 'required|in:L1,L2,L3',
            'places_max' => 'required|integer|min:1',
            'disponibilites' => 'required|array',
            'type' => 'required|in:payant,tutoriel',
            'start_date' => 'required|date|after:now',
        ]);

        $lesson = PrivateLesson::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->type === 'tutoriel' ? 0 : $request->prix,
            'duree_minutes' => $request->duree_minutes,
            'teacher_id' => Auth::id(),
            'matiere_id' => $request->matiere_id,
            'student_level' => $request->student_level,
            'disponibilites' => $request->disponibilites,
            'places_max' => $request->places_max,
            'type' => $request->type,
            'statut' => 'actif',
            'start_date' => $request->start_date,
        ]);

        // Envoyer un email de confirmation au professeur
        Auth::user()->notify(new PrivateLessonReminderNotification($lesson));

        return redirect()->route('teacher.private-lessons.index')
            ->with('success', 'Cours créé avec succès ! Un email de rappel vous a été envoyé.');
    }

    /**
     * Formulaire d'édition
     */
    public function edit($id)
    {
        $lesson = PrivateLesson::where('teacher_id', Auth::id())->findOrFail($id);
        $matieres = Matiere::all();
        return view('teacher.private-lessons.edit', compact('lesson', 'matieres'));
    }

    /**
     * Mettre à jour un cours
     */
    public function update(Request $request, $id)
    {
        $lesson = PrivateLesson::where('teacher_id', Auth::id())->findOrFail($id);

        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'duree_minutes' => 'required|integer|in:30,60,90,120',
            'matiere_id' => 'nullable|exists:matieres,id',
            'student_level' => 'required|in:L1,L2,L3',
            'places_max' => 'required|integer|min:1',
            'disponibilites' => 'required|array',
            'type' => 'required|in:payant,tutoriel',
            'start_date' => 'required|date|after:now',
        ]);

        $lesson->update([
            'titre' => $request->titre,
            'description' => $request->description,
            'prix' => $request->type === 'tutoriel' ? 0 : $request->prix,
            'duree_minutes' => $request->duree_minutes,
            'matiere_id' => $request->matiere_id,
            'student_level' => $request->student_level,
            'disponibilites' => $request->disponibilites,
            'places_max' => $request->places_max,
            'type' => $request->type,
            'start_date' => $request->start_date,
        ]);
        return redirect()->route('teacher.private-lessons.index')
            ->with('success', 'Cours mis à jour avec succès !');
    }

    /**
     * Changer le statut d'un cours
     */
    public function toggleStatus($id)
    {
        $lesson = PrivateLesson::where('teacher_id', Auth::id())->findOrFail($id);

        $newStatus = $lesson->statut === 'actif' ? 'inactif' : 'actif';
        $lesson->update(['statut' => $newStatus]);

        return back()->with('success', 'Statut mis à jour !');
    }

    /**
     * Supprimer un cours
     */
    public function destroy($id)
    {
        $lesson = PrivateLesson::with(['enrollments.student', 'teacher'])->where('teacher_id', Auth::id())->findOrFail($id);

        // Récupérer tous les étudiants inscrits (payés ou non)
        $enrolledStudents = $lesson->enrollments()->where('payment_status', 'paid')->get();

        // Notifier tous les étudiants inscrits de l'annulation
        foreach ($enrolledStudents as $enrollment) {
            $enrollment->student->notify(new PrivateLessonCancelledNotification($lesson, $lesson->teacher->name));
        }

        // Supprimer toutes les inscriptions d'abord
        $lesson->enrollments()->delete();

        // Supprimer le cours
        $lesson->delete();

        $studentsNotified = $enrolledStudents->count();
        $message = 'Cours supprimé avec succès !';
        if ($studentsNotified > 0) {
            $message .= ' ' . $studentsNotified . ' étudiant(s) ont été notifiés par email.';
        }

        return redirect()->route('teacher.private-lessons.index')
            ->with('success', $message);
    }

    /**
     * Accéder à la salle Jitsi d'une session
     */
    public function joinRoom($enrollmentId)
    {
        $enrollment = PrivateLessonEnrollment::with(['privateLesson', 'student'])
            ->findOrFail($enrollmentId);

        // Vérifier que l'utilisateur est soit le prof soit l'étudiant
        $isTeacher = $enrollment->privateLesson->teacher_id === Auth::id();
        $isStudent = $enrollment->student_id === Auth::id();

        if (!$isTeacher && !$isStudent) {
            abort(403, 'Accès non autorisé.');
        }

        // Vérifier que le paiement est effectué
        if ($enrollment->payment_status !== 'paid') {
            return back()->withErrors(['error' => 'Le paiement n\'a pas été effectué.']);
        }

        // Marquer la session comme active si elle vient de commencer
        if ($enrollment->session_status === 'scheduled' && $isTeacher) {
            $enrollment->update([
                'session_status' => 'active',
                'started_at' => now(),
            ]);

            // 🔔 ENVOYER UNE NOTIFICATION À L'ÉTUDIANT
            $enrollment->student->notify(new TeacherStartedPrivateLessonNotification($enrollment));
        }

        return view('private-lessons.room', compact('enrollment'));
    }

    /**
     * Annuler l'inscription à un cours
     */
    public function cancelEnrollment($enrollmentId)
    {
        $enrollment = PrivateLessonEnrollment::findOrFail($enrollmentId);

        // Vérifier que l'étudiant est bien le propriétaire de cette inscription
        if ($enrollment->student_id !== Auth::id()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        // Vérifier que le cours n'a pas encore commencé
        if ($enrollment->session_status === 'active' || $enrollment->session_status === 'completed') {
            return response()->json(['error' => 'Impossible d\'annuler un cours qui a commencé ou qui est terminé'], 422);
        }

        // Supprimer l'inscription
        $enrollment->delete();

        return redirect()->back()->with('success', '✅ Vous avez annulé votre réservation au cours.');
    }
}
