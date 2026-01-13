document.addEventListener('DOMContentLoaded', function () {
    // 1. Find the toggle button and the sidebar
    // Make sure your HTML has class="menu-toggle-icon" and class="sidebar"
    const toggleBtn = document.querySelector('.menu-toggle-icon');
    const sidebar = document.querySelector('.sidebar');

    // 2. Add the click event listener
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            // This adds/removes the .collapsed class we added to your CSS
            sidebar.classList.toggle('collapsed');
        });
    }
});