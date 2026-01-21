<x-layouts.user-layout title="My Profile">
    <x-slot:css>
        @vite(['resources/css/user-page.css'])
    </x-slot:css>    
    <div class="profile-card">
        <h1 class="profile-title">Profile Information</h1>
        
        <div class="profile-body">
  
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
  
            <div class="photo-section">
                <label class="info-label" style="text-align: center">Profile Photo</label>
                <div class="photo-preview square">
                    <img
                        id="photoPreviewImg"
                        src="{{ $user->photo_url }}"
                        alt="Profile photo preview"
                    >
                </div>
            </div>
  
        </div>
    </div>
  </x-layouts.user-layout>