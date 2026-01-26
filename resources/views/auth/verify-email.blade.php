<x-guest-layout>
    
    <div class="mb-8 text-center">
        <div class="w-20 h-20 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-envelope-open-text text-4xl"></i>
        </div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase mb-3">Vérifiez vos emails</h1>
        
        <p class="text-slate-600 font-medium text-sm leading-relaxed mb-4">
            Merci pour votre inscription ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ?
        </p>
        <p class="text-slate-500 text-xs italic">
            Si vous n'avez pas reçu l'email, nous pouvons vous en envoyer un autre.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-center text-sm animate-pulse">
            Un nouveau lien de vérification a été envoyé à l'adresse email fournie lors de l'inscription.
        </div>
    @endif

    <div class="mt-8 space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl shadow-lg text-lg font-black text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                Renvoyer l'email de vérification
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf

            <button type="submit" class="text-sm font-bold text-slate-400 hover:text-slate-700 uppercase tracking-widest transition underline decoration-2 underline-offset-4">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>