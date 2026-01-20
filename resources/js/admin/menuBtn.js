document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-menu');

    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    requestAnimationFrame(() => {
        sidebar.classList.add('animate');
    });

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');

            const currentState = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', currentState);
        });
    }
});
