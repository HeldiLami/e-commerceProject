<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>
    
    <form action="{{ route('login') }}" method="POST" class="space-y-4">
      @csrf 

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
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
        <div class="flex items-center justify-between mb-1">
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <a href="#" class="text-xs text-indigo-600 hover:text-indigo-500">Forgot password?</a>
        </div>
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

      <div class="flex items-center">
        <label class="flex items-center cursor-pointer">
          <input 
            type="checkbox" 
            name="remember" 
            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
          />
          <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label>
      </div>

      <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
        Sign In
      </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
      Don't have an account? 
      <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Create one</a>
    </div>
  </div>
</div>