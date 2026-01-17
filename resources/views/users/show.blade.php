<x-layouts.user-layout title="My Profile">
  <div class="profile-card">
      <h1 class="profile-title">Profile Information</h1>

      <div class="profile-info-group">
          <div class="info-item">
              <label class="info-label">Full Name</label>
              <p class="info-value">{{ $user->name }}</p>
          </div>

          <div class="info-item">
              <label class="info-label">Email Address</label>
              <p class="info-value">{{ $user->email }}</p>
          </div>

          <div class="info-item">
              <label class="info-label">Account Created</label>
              <p class="info-value">{{ $user->created_at->format('M d, Y') }}</p>
          </div>
      </div>
  </div>
</x-layouts.user-layout>