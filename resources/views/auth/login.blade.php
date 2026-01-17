<x-layouts.auth title="Sign In Page">
  <x-slot:heading>
      Sign In
  </x-slot:heading>

  @if ($errors->any() || session('error'))
      <div class="bg-red-50 text-red-700 border border-red-200 rounded-lg p-3 mb-4 text-sm">
          {{ session('error') ?? $errors->first() }}
      </div>
  @endif

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
      </div>

      <div>
          <div class="flex items-center justify-between mb-1">
              <label class="block text-sm font-medium text-gray-700">Password</label>
              <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:text-indigo-500">Forgot password?</a>
          </div>
          <input 
              type="password" 
              name="password"
              required
              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all @error('password') border-red-500 @else border-gray-300 @enderror"
              placeholder="••••••••"
          />
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

  <x-slot:footer>
      Don't have an account? 
      <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">Create one</a>
  </x-slot:footer>

</x-layouts.auth>
