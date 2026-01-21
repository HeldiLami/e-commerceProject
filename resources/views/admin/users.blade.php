<x-layouts.admin-layout>
    <x-slot:title>Admin • Users</x-slot>

    @vite(['resources/css/admin/users.css'])

    <section class="panel">
        <div class="panel__head">
            <h1 class="title">Users</h1>
        </div>

        <div class="panel__searchRow">
            <label class="searchbar">
                <span class="searchbar__hint">Search</span>
                <input id="userSearchInput" type="text" placeholder="Kërko: emër / email..." />
                <button id="userSearchBtn" type="button">Search</button>
            </label>
        </div>

        <div class="tableWrap" aria-label="Users table">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="right" style="text-align: center;">Created</th>
                        <th class="right" style="text-align: right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        @php
                            $role = $user->is_admin ? 'Admin' : ($user->role ?? 'User');

                            $status = $user->status ?? 'Active';

                            $pillClass = match (strtolower($status)) {
                                'active' => 'pill--ok',
                                'pending' => 'pill--warn',
                                'blocked' => 'pill--bad',
                                default => 'pill--ok',
                            };
                        @endphp

                        <tr class="js-user-row"
                            role="button"
                            tabindex="0"
                            data-name="{{ strtolower($user->name ?? '') }}"
                            data-email="{{ strtolower($user->email ?? '') }}"
                            onclick='window.location="{{ route("admin.users.edit", $user) }}"'
                            onkeydown="if(event.key==='Enter'){ window.location='{{ route('admin.users.edit', $user) }}' }"
                            style="cursor:pointer;">
                            <td>    
                                <div class="userCell">
                                    <div class="avatar" aria-hidden="true"></div>
                                    <div class="nameEmail">
                                        <div class="name">{{ $user->name ?? 'No name' }}</div>
                                        <div class="email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="pill pill--role">{{ $role }}</span>
                            </td>

                            <td>
                                <span class="pill {{ $pillClass }}">{{ $status }}</span>
                            </td>

                            <td class="right" style="text-align: center;">
                                {{ optional($user->created_at)->format('Y-m-d') }}
                            </td>

                            {{-- Actions: stopPropagation që mos hapet edit kur klikojmë koshin --}}
                            <td class="right" onclick="event.stopPropagation()" style="text-align:right;">
                                @if (!$user->is_admin)
                                    <form method="POST"
                                          action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('Je i sigurt që do ta fshish këtë user?');"
                                          style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="iconBtn" aria-label="Delete user" title="Delete">
                                            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M9 3h6l1 2h4v2H4V5h4l1-2zm1 6h2v10h-2V9zm4 0h2v10h-2V9zM7 9h2v10H7V9zm-1 14h12a2 2 0 0 0 2-2V9H4v12a2 2 0 0 0 2 2z"/>
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <span style="opacity:.6;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Nuk ka users në databazë.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <script>
        const input = document.getElementById('userSearchInput');
        const btn = document.getElementById('userSearchBtn');

        function filterUsers() {
            const q = (input.value || '').trim().toLowerCase();
            document.querySelectorAll('.js-user-row').forEach(row => {
                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const ok = !q || name.includes(q) || email.includes(q);
                row.style.display = ok ? '' : 'none';
            });
        }

        btn?.addEventListener('click', filterUsers);
        input?.addEventListener('input', filterUsers);
    </script>
</x-layouts.admin-layout>
