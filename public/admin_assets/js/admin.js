document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menuBtn');
    const backdrop = document.getElementById('sidebarBackdrop');

    const closeSidebar = () => {
        sidebar?.classList.remove('show');
        backdrop?.classList.remove('show');
    };

    menuBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('show');
        backdrop?.classList.toggle('show');
    });

    backdrop?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.settings-toggle').forEach((button) => {
        button.addEventListener('click', () => button.closest('.settings-menu')?.classList.toggle('open'));
    });

    const search = document.getElementById('globalAdminSearch');
    if (search) {
        search.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter') return;
            const url = new URL(window.location.href);
            if (search.value.trim()) url.searchParams.set('search', search.value.trim());
            else url.searchParams.delete('search');
            window.location.href = url.toString();
        });
    }

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!confirm(form.dataset.confirm || 'Are you sure?')) event.preventDefault();
        });
    });
});
