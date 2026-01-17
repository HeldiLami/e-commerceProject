<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Create Account</h2>
    
    <form action="{{ route('register') }}" method="POST" class="space-y-4 js-register-form">
      @csrf <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
        <input 
          type="text" 
          name="name"
          value="{{ old('name') }}"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('name') border-red-500 @else border-gray-300 @enderror"
          placeholder="John Doe"
        />
        @error('name')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input 
          type="email" 
          name="email"
          value="{{ old('email') }}"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('email') border-red-500 @else border-gray-300 @enderror"
          placeholder="your@email.com"
        />
        @error('email')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          type="password" 
          name="password"
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('password') border-red-500 @else border-gray-300 @enderror"
          placeholder="••••••••"
        />
        @error('password')
          <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
        <input 
          type="password" 
          name="password_confirmation"
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
          placeholder="••••••••"
        />
      </div>

      <input type="hidden" name="is_admin" value="0">

      <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors js-register-submit">
        Register
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      Already have an account? 
      <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign In</a>
    </div>
  </div>
</div>

<x-slot:scripts>
  @vite('resources/js/admin/registerForm.js')
</x-slot:scripts>
