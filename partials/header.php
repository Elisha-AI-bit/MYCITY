<?php require_once '../includes/config.php'; ?>
<?php require_once '../includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME; ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">

    <!-- Leaflet JS (Optional - included only if needed) -->
    <?php if (isset($useMap)): ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>
</head>

<body data-theme="light">
    <?php if (!isset($hideNav)): ?>
        <nav class="glass"
            style="position: sticky; top: 0; z-index: 100; border-bottom: 1px solid var(--border-color); padding: 1rem 0;">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
                <a href="<?php echo BASE_URL; ?>"
                    style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                    <i data-lucide="map-pin"></i>
                    MYCITY
                </a>

                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <a href="<?php echo BASE_URL; ?>services.php" style="font-weight: 500;">Explore</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo BASE_URL; ?>dashboard.php" class="btn btn-primary">Dashboard</a>
                    <?php else: ?>
                        <a href="<?php echo BASE_URL; ?>login.php" style="font-weight: 500;">Login</a>
                        <a href="<?php echo BASE_URL; ?>register.php" class="btn btn-primary">Join MYCITY</a>
                    <?php endif; ?>
                    <button id="themeToggle" class="btn" style="padding: 0.5rem; background: var(--border-color);"><i
                            data-lucide="moon"></i></button>
                </div>
            </div>
        </nav>
    <?php endif; ?>