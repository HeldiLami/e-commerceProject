document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-menu');

    // 1. Kontrollo nëse ka një gjendje të ruajtur në memorie
    const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';

    // 2. Apliko gjendjen e ruajtur menjëherë
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
    }

    // 3. Event listener për klikimin e butonit
    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            // Shto ose hiq klasën 'collapsed'
            sidebar.classList.toggle('collapsed');

            // Ruaj gjendjen e re në localStorage
            const currentState = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', currentState);
        });
    }
});