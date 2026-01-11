<form action= "{{ route('register')}}" method="POST">
  @csrf

  <div class="form-group">
      <label for="name">Full Name</label>
      <input 
          type="text" 
          name="name" 
          id="name" 
          required 
          value="{{ old('name') }}"
      >
  </div>

  <div class="form-group">
      <label for="email">Email Address</label>
      <input 
          type="email" 
          name="email" 
          id="email" 
          required 
          value="{{ old('email') }}"
      >
  </div>

  <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
  </div>

  <div class="form-group">
      <label for="password_confirmation">Confirm Password</label>
      <input type="password" name="password_confirmation" id="password_confirmation" required>
  </div>

  <div class="form-group">
      <label>
          <input 
              type="checkbox" 
              name="is_admin" 
              value="1" 
              disabled
              {{ $isAdmin ? 'checked' : '' }}
          >
          Register as Administrator
      </label>
  </div>

  <button type="submit">Create Account</button>
</form>