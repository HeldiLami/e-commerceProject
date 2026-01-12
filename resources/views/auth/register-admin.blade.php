<x-layouts.auth title="Create Admin Account">
    
  <x-slot:heading>
      Create Account
  </x-slot:heading>

  <form action="{{ route('register') }}" method="POST" class="space-y-4">
      @csrf 
      
      {{-- Hidden input ensures all registrations from this form are Admins --}}
      <input type="hidden" name="is_admin" value="1">

      <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
          <input 
              type="text" 
              name="name"
              value="{{ old('name') }}"
              required
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
              required
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
              required
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
              required
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
              placeholder="••••••••"
          />
      </div>

      <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
          Register
      </button>
  </form>

  <x-slot:footer>
      Already have an account? 
      <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign In</a>
  </x-slot:footer>

</x-layouts.auth>