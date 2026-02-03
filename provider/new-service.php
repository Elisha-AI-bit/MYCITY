<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('provider')) {
    redirect('login.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $category_id = intval($_POST['category_id']);
    $description = clean($_POST['description']);
    $price = floatval($_POST['price']);
    $lat = floatval($_POST['latitude']);
    $lng = floatval($_POST['longitude']);
    $provider_id = $_SESSION['user_id'];

    // Handle Image Upload (Simplified)
    $image_name = 'default_service.jpg';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = 'service_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], '../assets/img/services/' . $image_name);
    }

    $stmt = $pdo->prepare("INSERT INTO services (provider_id, category_id, name, description, price, image, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$provider_id, $category_id, $name, $description, $price, $image_name, $lat, $lng])) {
        setFlash('service', 'Service listed successfully!', 'success');
        redirect('provider/index.php');
    } else {
        $error = "Failed to add service. Please try again.";
    }
}

// Fetch categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

$pageTitle = "Add New Service";
$hideNav = true;
$useMap = true; // For choosing location
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <a href="index.php"
                style="color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                <i data-lucide="arrow-left" style="width: 16px;"></i> Back to Dashboard
            </a>
            <h1 style="font-size: 1.75rem; font-weight: 700;">Add New Service</h1>
            <p style="color: var(--text-muted);">Fill in the details to list your service on MYCITY.</p>
        </header>

        <div class="card" style="max-width: 800px;">
            <?php if ($error): ?>
                <div
                    style="background-color: var(--danger); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Service Title</label>
                        <input type="text" name="name" class="form-control" required
                            placeholder="e.g. Master Plumbing & Leak Fix">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo $cat['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4" required
                        placeholder="Describe what you offer..."></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Price (per hour/job)</label>
                        <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Service Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Service Location (Click on map to set)</label>
                    <div id="map" style="height: 300px; border-radius: var(--radius); margin-bottom: 1rem;"></div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <input type="hidden" name="latitude" id="lat" value="40.7128">
                        <input type="hidden" name="longitude" id="lng" value="-74.0060">
                    </div>
                    <p id="location-status" style="font-size: 0.875rem; color: var(--text-muted);">Current: 40.7128,
                        -74.0060</p>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                    <button type="reset" class="btn glass">Reset</button>
                    <button type="submit" class="btn btn-primary">List Service</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    window.addEventListener('load', () => {
        const defaultLat = 40.7128;
        const defaultLng = -74.0060;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        function updateLocation(lat, lng) {
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lng').value = lng.toFixed(6);
            document.getElementById('location-status').innerText = `Current: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        }

        map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            marker.setLatLng([lat, lng]);
            updateLocation(lat, lng);
        });

        marker.on('dragend', () => {
            const { lat, lng } = marker.getLatLng();
            updateLocation(lat, lng);
        });
    });
</script>

<?php require_once '../partials/footer.php'; ?>