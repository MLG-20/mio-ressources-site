<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Modifier - {{ $lesson->titre }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300" x-data="{
    type: '{{ old('type', $lesson->type ?? 'payant') }}',
    disponibilites: @json($lesson->disponibilites ?? []),
    ajouterCreneau() {
        this.disponibilites.push({ jour: 'Lundi', horaires: '' });
    },
    supprimerCreneau(index) {
        this.disponibilites.splice(index, 1);
    }
}">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <!-- NAVBAR -->
    <nav class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <a href="{{ route('teacher.private-lessons.index') }}">
            <div class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="hidden sm:inline font-black uppercase tracking-tighter text-xs md:text-base">Modifier le Cours</span>
            </div>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            <a href="{{ route('teacher.private-lessons.index') }}" class="bg-slate-100 dark:bg-white/10 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-slate-200 dark:hover:bg-white/20 transition">
                ← Retour
            </a>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <div class="bg-white rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
            <h1 class="text-2xl md:text-3xl font-black text-slate-800 uppercase mb-8">✏️ Modifier le Cours</h1>

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl" role="alert">
                    <p class="font-bold">⚠ Erreur</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teacher.private-lessons.update', $lesson->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- TITRE -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Titre du cours *</label>
                    <input type="text" name="titre" value="{{ old('titre', $lesson->titre) }}" required
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none"
                        placeholder="Ex: Cours de Mathématiques - Algèbre avancée">
                </div>

                <!-- DESCRIPTION -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Description *</label>
                    <textarea name="description" rows="5" required
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 text-sm md:text-base focus:border-blue-500 focus:outline-none"
                        placeholder="Décrivez le contenu du cours, les prérequis, les objectifs...">{{ old('description', $lesson->description) }}</textarea>
                </div>

                <!-- TYPE DE COURS -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-lg md:rounded-2xl p-4 md:p-6">
                    <label class="block text-xs md:text-sm font-bold text-slate-700 mb-4 uppercase">Type de cours *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Cours Payant -->
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="payant" x-model="type"
                                class="hidden peer" {{ old('type', $lesson->type ?? 'payant') === 'payant' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-slate-200 rounded-lg peer-checked:border-blue-600 peer-checked:bg-blue-100 transition-all">
                                <p class="font-bold text-sm md:text-base text-slate-800">💰 Cours Payant</p>
                                <p class="text-xs md:text-sm text-slate-600 mt-1">Les étudiants payent pour accéder</p>
                            </div>
                        </label>

                        <!-- Tutoriel Gratuit -->
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="tutoriel" x-model="type"
                                class="hidden peer" {{ old('type', $lesson->type) === 'tutoriel' ? 'checked' : '' }}>
                            <div class="p-4 border-2 border-slate-200 rounded-lg peer-checked:border-green-600 peer-checked:bg-green-100 transition-all">
                                <p class="font-bold text-sm md:text-base text-slate-800">🎓 Tutoriel Gratuit</p>
                                <p class="text-xs md:text-sm text-slate-600 mt-1">Gratuit pour tous les étudiants</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- GRILLE : PRIX & DURÉE & PLACES -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- PRIX (Masqué si tutoriel) -->
                    <div x-show="type === 'payant'" x-transition>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Prix (FCFA) *</label>
                        <input type="number" name="prix" value="{{ old('prix', $lesson->prix ?? 0) }}" min="0" step="100"
                            x-show="type === 'payant'" required
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none"
                            placeholder="5000">
                    </div>

                    <!-- Prix Tutoriel (Caché mais présent) -->
                    <input type="hidden" name="prix" value="0" x-show="type === 'tutoriel'">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Durée (minutes) *</label>
                        <input type="number" name="duree_minutes" value="{{ old('duree_minutes', $lesson->duree_minutes) }}" min="1" required
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none"
                            placeholder="60">
                        <p class="text-xs text-slate-500 mt-1">Durée en minutes (ex: 30, 45, 60, 90, 120)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Places max *</label>
                        <input type="number" name="places_max" value="{{ old('places_max', $lesson->places_max) }}" min="1" required
                            class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none"
                            placeholder="1">
                        <p class="text-xs text-slate-500 mt-1">Nombre d'étudiants max par session</p>
                    </div>
                </div>

                <!-- MATIÈRE -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Matière (optionnel)</label>
                    <select name="matiere_id"
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none">
                        <option value="">-- Aucune matière --</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ old('matiere_id', $lesson->matiere_id) == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- NIVEAU D'ÉTUDE -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Niveau d'étude visé *</label>
                    <select name="student_level" required
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none">
                        <option value="">-- Sélectionner un niveau --</option>
                        <option value="L1" {{ old('student_level', $lesson->student_level) == 'L1' ? 'selected' : '' }}>L1 - Première Année</option>
                        <option value="L2" {{ old('student_level', $lesson->student_level) == 'L2' ? 'selected' : '' }}>L2 - Deuxième Année</option>
                        <option value="L3" {{ old('student_level', $lesson->student_level) == 'L3' ? 'selected' : '' }}>L3 - Troisième Année</option>
                    </select>
                    <p class="text-xs text-slate-500 mt-1">📚 Ce cours sera proposé à tous les étudiants du niveau sélectionné</p>
                </div>

                <!-- DISPONIBILITÉS -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Disponibilités *</label>
                    <p class="text-xs text-slate-500 mb-4">Indiquez vos créneaux horaires disponibles (ex: "14h-16h, 18h-20h")</p>

                    <template x-for="(dispo, index) in disponibilites" :key="index">
                        <div class="flex gap-2 mb-3">
                            <select :name="'disponibilites[' + index + '][jour]'" x-model="dispo.jour" required
                                class="bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-blue-500 focus:outline-none">
                                <option value="Lundi">Lundi</option>
                                <option value="Mardi">Mardi</option>
                                <option value="Mercredi">Mercredi</option>
                                <option value="Jeudi">Jeudi</option>
                                <option value="Vendredi">Vendredi</option>
                                <option value="Samedi">Samedi</option>
                                <option value="Dimanche">Dimanche</option>
                            </select>

                            <input type="text" :name="'disponibilites[' + index + '][horaires]'" x-model="dispo.horaires" required
                                class="flex-1 bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-sm focus:border-blue-500 focus:outline-none"
                                placeholder="Ex: 14h-16h, 18h-20h">

                            <button type="button" @click="supprimerCreneau(index)" x-show="disponibilites.length > 1"
                                class="bg-red-500 text-white px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-red-600 transition">
                                🗑️
                            </button>
                        </div>
                    </template>

                    <button type="button" @click="ajouterCreneau"
                        class="bg-blue-100 text-blue-700 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-blue-200 transition">
                        ➕ Ajouter un créneau
                    </button>
                </div>

                <!-- DATE/HEURE DE DÉBUT DU COURS -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase">Date et heure du cours *</label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', $lesson->start_date?->format('Y-m-d\\TH:i') ?? '') }}" required
                        min="{{ now()->format('Y-m-d\\TH:i') }}"
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 font-bold text-sm md:text-base focus:border-blue-500 focus:outline-none">
                    <p class="text-xs text-slate-500 mt-2">📅 Fixez le jour et l'heure exacte où le cours aura lieu</p>
                </div>

                <!-- BOUTONS -->
                <div class="flex flex-col md:flex-row gap-4 pt-6">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black shadow-xl uppercase text-sm hover:bg-blue-700 transition">
                        ✓ Enregistrer les modifications
                    </button>
                    <a href="{{ route('teacher.private-lessons.index') }}" class="flex-1 bg-slate-200 text-slate-700 py-4 rounded-2xl font-black shadow-xl uppercase text-sm hover:bg-slate-300 transition text-center">
                        ✗ Annuler
                    </a>
                </div>
            </form>
        </div>

    </main>

    @include('layouts.footer')

<script>
    (function () {
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-toggle-icon');
        if (!btn || !icon) return;
        const syncIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
        };
        syncIcon();
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncIcon();
        });
    })();
</script>
</body>
</html>
