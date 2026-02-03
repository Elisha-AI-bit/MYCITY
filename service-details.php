<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("
    SELECT s.*, u.username as provider_name, c.name as category_name 
    FROM services s
    JOIN users u ON s.provider_id = u.id
    JOIN categories c ON s.category_id = c.id
    WHERE s.id = ?
");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    die("Service not found.");
}

$pageTitle = $service['name'];
$useMap = true;
require_once 'partials/header.php';
?>

<div class="container" style="padding: 3rem 0;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem;">
        <!-- Left Column: Details -->
        <div>
            <img src="<?php echo BASE_URL; ?>assets/img/services/<?php echo $service['image']; ?>"
                onerror="this.src='https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=800&auto=format'"
                style="width: 100%; height: 400px; object-fit: cover; border-radius: var(--radius); margin-bottom: 2rem;">

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div>
                    <span
                        style="padding: 0.25rem 0.75rem; background: var(--primary); color: white; border-radius: 99px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                        <?php echo $service['category_name']; ?>
                    </span>
                    <h1 style="font-size: 2.5rem; font-weight: 800; margin-top: 0.5rem;">
                        <?php echo $service['name']; ?>
                    </h1>
                    <p
                        style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); margin-top: 0.5rem;">
                        <i data-lucide="user" style="width: 18px;"></i> Provided by <span
                            style="font-weight: 600; color: var(--text-main);">
                            <?php echo $service['provider_name']; ?>
                        </span>
                    </p>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">
                        <?php echo formatCurrency($service['price']); ?>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.875rem;">Starting price</p>
                </div>
            </div>

            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Service Description</h3>
                <p style="color: var(--text-muted); line-height: 1.8;">
                    <?php echo nl2br($service['description']); ?>
                </p>
            </div>

            <div class="card">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem;">Location</h3>
                <div id="map" style="height: 300px; border-radius: var(--radius);"></div>
            </div>
        </div>

        <!-- Right Column: Booking Sidebar -->
        <aside>
            <div class="card glass" style="position: sticky; top: 120px; box-shadow: var(--shadow-lg);">
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem;">Book this Service</h3>

                <?php if (isLoggedIn() && hasRole('customer')): ?>
                    <form action="book_process.php" method="POST">
                        <input type="hidden" name="service_id" value="<?php echo $id; ?>">

                        <div class="form-group">
                            <label class="form-label">Select Date</label>
                            <input type="date" name="booking_date" class="form-control" required
                                min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select Time</label>
                            <input type="time" name="booking_time" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes for Provider</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Any specific requirements?"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            Confirm Booking
                        </button>

                        <p style="text-align: center; color: var(--text-muted); font-size: 0.75rem; margin-top: 1rem;">
                            <i data-lucide="shield-check" style="width: 12px; vertical-align: middle;"></i> Secure booking &
                            verified provider
                        </p>
                    </form>
                <?php elseif (isLoggedIn()): ?>
                    <p style="color: var(--danger); text-align: center; font-weight: 500;">
                        Only customers can book services.
                    </p>
                <?php else: ?>
                    <div style="text-align: center; padding: 1rem;">
                        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Please login to book this service.</p>
                        <a href="login.php" class="btn btn-primary" style="width: 100%;">Login to Book</a>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<script>
    window.addEventListener('load', () => {
        const lat = <?php echo $service['latitude']; ?>;
        const lng = <?php echo $service['longitude']; ?>;

        const map = L.map('map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup("<?php echo $service['name']; ?>").openPopup();
    });
</script>

<?php require_once 'partials/footer.php'; ?>