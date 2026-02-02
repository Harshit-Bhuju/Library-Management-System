    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Simple Theme Toggle Logic
        const toggleTheme = () => {
            const html = document.querySelector('html');
            const current = html.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        };

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.querySelector('html').setAttribute('data-theme', savedTheme);
    </script>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
