<?php declare(strict_types=1); ?>
<script>
function themeApp() {
    return {
        dark: false,
        init() {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const resolvedTheme = storedTheme ?? (prefersDark ? 'dark' : 'light');

            this.dark = resolvedTheme === 'dark';
            localStorage.setItem('theme', resolvedTheme);
            document.documentElement.classList.toggle('dark', this.dark);
        },
        toggleTheme() {
            this.dark = !this.dark;
            const nextTheme = this.dark ? 'dark' : 'light';

            localStorage.setItem('theme', nextTheme);
            document.documentElement.classList.toggle('dark', this.dark);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const target = document.getElementById('lottie-mail');
    if (target && window.lottie) {
        window.lottie.loadAnimation({
            container: target,
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: 'https://assets6.lottiefiles.com/packages/lf20_u25cckyh.json'
        });
    }
});
</script>
</body>
</html>
