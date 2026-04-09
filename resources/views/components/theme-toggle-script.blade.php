<script>
    (function () {
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-toggle-icon');
        if (!btn || !icon) return;

        const syncIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
        };

        syncIcon();

        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncIcon();
        });
    })();
</script>
