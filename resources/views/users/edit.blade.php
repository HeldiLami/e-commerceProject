<x-layouts.user-layout title="Edit Profile">
    <x-slot name="css">
        @vite(['resources/css/user-page.css'])
    </x-slot>

    <div class="profile-card">
        <h1 class="profile-title">Update Your Information</h1>

        @if ($errors->any())
            <div class="alert-list">
                <ul class="alert">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="userEditForm" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="info-item">
                <label class="info-label">Profile Photo</label>
                <div class="photo-preview landscape">
                    <img
                        id="photoPreviewImg"
                        src="{{ $user->photo_url }}" 
                        alt="Profile photo preview"
                    >
                </div>
                <input
                    type="file"
                    name="photo"
                    id="photo"
                    accept="image/*"
                    class="profile-input @error('photo') input-error @enderror"
                >
                <span id="photo-error" class="error-message">
                    @error('photo')
                        {{ $message }}
                    @enderror
                </span>
            </div>

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
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       class="profile-input"
                       placeholder="••••••••">
            </div>

            <div class="actions-row">
                <button type="submit" class="btn-save">Save Changes</button>
                <a href="{{ route('users.show', $user) }}" class="btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

    <x-slot name="scripts">
        @vite(['resources/js/edit-profile.js'])
    </x-slot>
</x-layouts.user-layout>
