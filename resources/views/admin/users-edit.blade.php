<x-layouts.admin-layout>
    <x-slot:title>Admin • Edit User</x-slot>

    @vite([
        'resources/css/admin/users-edit.css',
        'resources/js/admin/users-edit.js'
    ])

    <section class="panel">
        <div class="usersEditWrap">
            <div class="panel__head">
                <h1 class="title">Edit User</h1>
                <p class="subtitle">Admin mund të ndryshojë të dhënat + ta bëjë userin admin (true/false).</p>
            </div>

            @if (session('success'))
                <div class="alert alert--success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert--error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert--error">
                    <ul class="alert__list">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card__titleRow">
                    <h2 class="card__title">Update User Information</h2>
                    <a class="linkBack" href="{{ route('admin.users') }}">← Back to users</a>
                </div>

                <form id="adminUserEditForm" action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="grid">
                        {{-- PHOTO + PREVIEW (zë 2 kolona) --}}
                        <div class="field field--full">
                            <label for="photo">Photo URL (optional)</label>
                            <input
                                type="text"
                                name="photo"
                                id="photo"
                                value="{{ old('photo', $user->photo) }}"
                                class="@error('photo') is-error @enderror"
                                placeholder="https://... ose /images/users/user1.png (ose bosh për default)"
                            >
                            @error('photo') <div class="field__error">{{ $message }}</div> @enderror
                            <div class="hint">Nëse është bosh, shfaqet default icon.</div>

                            <div class="photoPreview">
                                <div id="photoPlaceholder" class="photoPreview__placeholder">Vendos URL/path sipër</div>

                                <img
                                    id="photoPreviewImg"
                                    class="photoPreview__img"
                                    alt="Preview"
                                    data-default-src="{{ asset('images/icons/default-user-icon.png') }}"
                                    src="{{ $user->photo_url }}"
                                >
                            </div>
                        </div>

                        {{-- NAME --}}
                        <div class="field">
                            <label for="name">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $user->name) }}"
                                class="@error('name') is-error @enderror"
                                required
                            >
                            @error('name') <div class="field__error">{{ $message }}</div> @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="field">
                            <label for="email">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email', $user->email) }}"
                                class="@error('email') is-error @enderror"
                                required
                            >
                            @error('email') <div class="field__error">{{ $message }}</div> @enderror
                        </div>

                        {{-- ADMIN --}}
                        <div class="field">
                            <label for="is_admin">Admin</label>
                            <select name="is_admin" id="is_admin" class="@error('is_admin') is-error @enderror">
                                <option value="0" {{ (string)old('is_admin', (int)$user->is_admin) === '0' ? 'selected' : '' }}>false</option>
                                <option value="1" {{ (string)old('is_admin', (int)$user->is_admin) === '1' ? 'selected' : '' }}>true</option>
                            </select>
                            @error('is_admin') <div class="field__error">{{ $message }}</div> @enderror

                            {{-- këto janë vetëm për JS --}}
                            <input type="hidden" id="originalIsAdmin" value="{{ (int)$user->is_admin }}">
                            <input type="hidden" id="editingUserId" value="{{ $user->id }}">
                            <input type="hidden" id="authUserId" value="{{ auth()->id() }}">
                        </div>

                        {{-- PASSWORD --}}
                        <div class="field">
                            <label for="password">New Password (optional)</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="@error('password') is-error @enderror"
                                placeholder="••••••••"
                            >
                            @error('password') <div class="field__error">{{ $message }}</div> @enderror
                            <div class="hint">Leave blank to keep current password.</div>
                        </div>

                        {{-- CONFIRM --}}
                        <div class="field">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn--primary">Save Changes</button>
                        <a href="{{ route('admin.users') }}" class="btn btn--ghost">Cancel</a>
                    </div>
                </form>

                @if (!$user->is_admin)
                    <form method="POST"
                          action="{{ route('admin.users.destroy', $user) }}"
                          class="dangerZone"
                          onsubmit="return confirm('Je i sigurt që do ta fshish këtë user?');">
                        @csrf
                        @method('DELETE')

                        <div class="dangerZone__left">
                            <div class="dangerZone__title">Kujdes!</div>
                            <div class="dangerZone__text">Fshin userin (nuk mund të kthehet mbrapsht).</div>
                        </div>

                        <button type="submit" class="btn btn--danger">Delete User</button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</x-layouts.admin-layout>
