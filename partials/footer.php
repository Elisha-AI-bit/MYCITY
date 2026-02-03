<footer
    style="margin-top: 4rem; padding: 3rem 0; border-top: 1px solid var(--border-color); background-color: var(--bg-white);">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
            <div>
                <div
                    style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.25rem; font-weight: 700; color: var(--primary); margin-bottom: 1rem;">
                    <i data-lucide="map-pin"></i>
                    MYCITY
                </div>
                <p style="color: var(--text-muted);">Finding local services made easy, fast, and reliable.</p>
            </div>
            <div>
                <h4 style="margin-bottom: 1rem;">Quick Links</h4>
                <ul style="color: var(--text-muted); display: flex; flex-direction: column; gap: 0.5rem;">
                    <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li><a href="<?php echo BASE_URL; ?>services.php">Find Services</a></li>
                    <li><a href="<?php echo BASE_URL; ?>register.php?role=provider">Become a Provider</a></li>
                </ul>
            </div>
            <div>
                <h4 style="margin-bottom: 1rem;">Support</h4>
                <ul style="color: var(--text-muted); display: flex; flex-direction: column; gap: 0.5rem;">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div style="margin-top: 3rem; text-align: center; color: var(--text-muted); font-size: 0.875rem;">
            &copy;
            <?php echo date('Y'); ?> MYCITY. All rights reserved.
        </div>
    </div>
</footer>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Theme Toggle Logic
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    // Check for saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    body.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    themeToggle?.addEventListener('click', () => {
        const currentTheme = body.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        if (theme === 'dark') {
            icon.setAttribute('data-lucide', 'sun');
        } else {
            icon.setAttribute('data-lucide', 'moon');
        }
        lucide.createIcons();
    }
</script>
</body>

</html>