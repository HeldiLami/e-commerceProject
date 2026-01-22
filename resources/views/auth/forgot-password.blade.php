<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Forgot password?</h2>
        <p class="text-sm text-gray-600 mb-6">Insert your email to get the password reset link in your inbox.</p>

        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm border border-green-200">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('email') border-red-500 @else border-gray-300 @enderror"
                    placeholder="your@email.com">
                @error('email')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 rounded-lg transition-colors shadow-md">
                Send Reset Link
            </button>
            
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-indigo-600">Return to Login</a>
            </div>
        </form>
    </div>
</div>