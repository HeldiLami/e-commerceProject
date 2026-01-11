<form action= "{{ route('login')}}" method="POST">
  @csrf

  <div class="form-group">
      <label for="email">Email Address</label>
      <input 
        type="email" 
        name="email" 
        id="email" 
        required 
        autofocus
        value="{{ old('email') }}"
      >
  </div>

  <div class="form-group">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" required>
  </div>

  <button type="submit">Sign In</button>
</form>