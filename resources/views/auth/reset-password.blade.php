<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Vendosni Fjalëkalimin e Ri</h2>
        <p class="text-sm text-gray-600 mb-6">Ju lutem shkruani fjalëkalimin tuaj të ri më poshtë për të rifituar aksesin në llogari.</p>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Adresa</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $request->email) }}" 
                    required 
                    readonly
                    class="w-full px-4 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-500 cursor-not-allowed outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fjalëkalimi i Ri</label>
                <input 
                    type="password" 
                    name="password" 
                    required 
                    autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('password') border-red-500 @else border-gray-300 @enderror"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmo Fjalëkalimin</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                    placeholder="••••••••"
                >
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition-colors shadow-md">
                Rifillo Fjalëkalimin
            </button>
        </form>
    </div>
</div>