<x-layouts.user-layout title="Edit Profile">
  <div class="profile-card">
      <h1 class="profile-title">Update Your Information</h1>

      <form action="{{ route('users.update', $user) }}" method="POST">
          @csrf
          @method('PATCH')

          <div class="info-item">
              <label for="name" class="info-label">Full Name</label>
              <input type="text" 
                     name="name" 
                     id="name" 
                     value="{{ old('name', $user->name) }}" 
                     class="profile-input @error('name') input-error @enderror"
                     required>
              @error('name')
                  <span class="error-message">{{ $message }}</span>
              @enderror
          </div>

          <div class="info-item">
              <label for="email" class="info-label">Email Address</label>
              <input type="email" 
                     name="email" 
                     id="email" 
                     value="{{ old('email', $user->email) }}" 
                     class="profile-input @error('email') input-error @enderror"
                     required>
              @error('email')
                  <span class="error-message">{{ $message }}</span>
              @enderror
          </div>
          <div class="info-item">
            <label for="password" class="info-label">New Password (Leave blank to keep current)</label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="profile-input @error('password') input-error @enderror"
                   placeholder="••••••••">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="info-item">
          <label for="password_confirmation" class="info-label">Confirm New Password</label>
          <input
            type="password" 
            name="password_confirmation" 
            id="password_confirmation" 
            class="profile-input"
            placeholder="••••••••"
          >
        </div>
          <div style="margin-top: 25px; display: flex; gap: 10px;">
              <button type="submit" class="btn-save">
                  Save Changes
              </button>
              <a href="{{ route('users.show', $user) }}" class="btn-cancel">
                  Cancel
              </a>
          </div>
      </form>
  </div>
</x-layouts.user-layout>