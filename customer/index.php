<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('customer')) {
    redirect('login.php');
}

$pageTitle = "My Dashboard";
$hideNav = true;
$useMap = true; // Use Leaflet
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700;">Discover Services</h1>
                <p style="color: var(--text-muted);">Find the best rated pros near you.</p>
            </div>
            <div style="display: flex; gap: 1rem; flex: 1; max-width: 400px; margin-left: 2rem;">
                <div style="position: relative; width: 100%;">
                    <i data-lucide="search"
                        style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); width: 18px;"></i>
                    <input type="text" class="form-control" style="padding-left: 3rem;"
                        placeholder="Search for plumbers, electricians...">
                </div>
            </div>
        </header>

        <!-- Discovery Map -->
        <div class="card" style="padding: 0; overflow: hidden; height: 450px; margin-bottom: 2rem;">
            <div id="map" style="height: 100%; width: 100%;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Recommended for You</h2>
            <a href="#" style="color: var(--primary); font-weight: 600; font-size: 0.875rem;">See All</a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            <?php
            // Fetch services from DB
            $stmt = $pdo->prepare("
                SELECT s.*, u.username as provider_name 
                FROM services s
                JOIN users u ON s.provider_id = u.id
                WHERE s.status = 'active'
                ORDER BY s.created_at DESC
                LIMIT 6
            ");
            $stmt->execute();
            $services = $stmt->fetchAll();

            foreach ($services as $s):
                ?>
                <div class="card" style="padding: 0; overflow: hidden;">
                    <img src="<?php echo BASE_URL; ?>assets/img/services/<?php echo $s['image']; ?>"
                        onerror="this.src='https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=400&auto=format'"
                        style="width: 100%; height: 160px; object-fit: cover;">
                    <div style="padding: 1.25rem;">
                        <div
                            style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h3 style="font-size: 1.125rem; font-weight: 600;"><?php echo $s['name']; ?></h3>
                            <div style="display: flex; align-items: center; gap: 0.25rem; color: var(--warning);">
                                <i data-lucide="star" style="width: 14px; fill: var(--warning);"></i>
                                <span style="font-size: 0.875rem; font-weight: 600; color: var(--text-main);">4.9</span>
                            </div>
                        </div>
                        <p
                            style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i data-lucide="map-pin" style="width: 14px;"></i>
                            <?php echo $s['latitude'] . ', ' . $s['longitude']; ?>
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span
                                style="font-size: 1.125rem; font-weight: 700; color: var(--primary);"><?php echo formatCurrency($s['price']); ?><small
                                    style="font-size: 0.75rem; color: var(--text-muted); font-weight: 400;">/hr</small></span>
                            <a href="<?php echo BASE_URL; ?>service-details.php?id=<?php echo $s['id']; ?>"
                                class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;">View Profile</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
            if (empty($services)): ?>
                <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-muted);" class="card">
                    <p>No services found near you. Be the first to list one!</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
    // Initialize Map
    window.addEventListener('load', () => {
        const map = L.map('map').setView([40.7128, -74.0060], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        <?php if (!empty($services)): ?>
            const markers = <?php echo json_encode($services); ?>;
            markers.forEach(s => {
                const m = L.marker([s.latitude, s.longitude]).addTo(map)
                    .bindPopup(`
                    <div style="min-width: 150px;">
                        <img src="<?php echo BASE_URL; ?>assets/img/services/${s.image}" onerror="this.src='https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=200&auto=format'" style="width: 100%; border-radius: 4px; margin-bottom: 8px;">
                        <b style="font-size: 14px;">${s.name}</b><br>
                        <span style="color: var(--primary); font-weight: 700;">K${s.price}/hr</span><br>
                        <a href='<?php echo BASE_URL; ?>service-details.php?id=${s.id}' style="color: var(--primary); font-size: 12px; font-weight: 600;">View Details</a>
                    </div>
                `);
            });
        <?php endif; ?>
    });
</script>

<?php require_once '../partials/footer.php'; ?>