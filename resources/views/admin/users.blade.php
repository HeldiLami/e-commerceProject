<!doctype html>
<html lang="sq">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin • Users</title>

@vite(['resources/css/pages/admin/users.css'])

</head>

<body>
  <main class="page">
    <section class="panel">
      <div class="panel__head">
        <h1 class="title">Users</h1>
        <p class="subtitle">Kërko një user dhe do shfaqet vetëm ai (frontend filter).</p>
      </div>

      <div class="panel__searchRow">
        <label class="searchbar">
          <span class="searchbar__hint">Search</span>
          <input id="userSearchInput" type="text" placeholder="Kërko: Neim, Elis, Jon / email..." />
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
              <th class="right">Created</th>
            </tr>
          </thead>

          <tbody>
            <!-- 3 usera statik -->
            <tr>
              <td>
                <div class="userCell">
                  <div class="avatar" aria-hidden="true"></div>
                  <div class="nameEmail">
                    <div class="name">Neim Sinaj</div>
                    <div class="email">neim.sinaj@example.com</div>
                  </div>
                </div>
              </td>
              <td><span class="pill pill--role">Admin</span></td>
              <td><span class="pill pill--ok">Active</span></td>
              <td class="right">2026-01-02</td>
            </tr>

            <tr>
              <td>
                <div class="userCell">
                  <div class="avatar" aria-hidden="true"></div>
                  <div class="nameEmail">
                    <div class="name">Elis Bobin</div>
                    <div class="email">elis.bobin@example.com</div>
                  </div>
                </div>
              </td>
              <td><span class="pill pill--role">Manager</span></td>
              <td><span class="pill pill--warn">Pending</span></td>
              <td class="right">2025-12-18</td>
            </tr>

            <tr>
              <td>
                <div class="userCell">
                  <div class="avatar" aria-hidden="true"></div>
                  <div class="nameEmail">
                    <div class="name">Jon Doe</div>
                    <div class="email">jon.doe@example.com</div>
                  </div>
                </div>
              </td>
              <td><span class="pill pill--role">User</span></td>
              <td><span class="pill pill--bad">Blocked</span></td>
              <td class="right">2025-10-09</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

<script src="{{ asset('js/admin/users.js') }}" defer></script>


</body>
</html>
