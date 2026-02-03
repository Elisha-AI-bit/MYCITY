<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('login.php');
}

// Handle Service Status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $service_id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = ($action === 'deactivate') ? 'inactive' : 'active';

    $stmt = $pdo->prepare("UPDATE services SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $service_id])) {
        setFlash('service', "Service status updated to $status.", 'success');
    }
    redirect('admin/services.php');
}

// Fetch services
$stmt = $pdo->query("
    SELECT s.*, u.username as provider_name, c.name as category_name 
    FROM services s
    JOIN users u ON s.provider_id = u.id
    JOIN categories c ON s.category_id = c.id
    ORDER BY s.created_at DESC
");
$services = $stmt->fetchAll();

$pageTitle = "Manage Services";
$hideNav = true;
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700;">Manage Services</h1>
            <p style="color: var(--text-muted);">Overview of all service listings on the platform.</p>
        </header>

        <?php $flash = getFlash('service');
        if ($flash): ?>
            <div
                style="background-color: var(--primary); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 1rem;">Service</th>
                            <th style="padding: 1rem;">Provider</th>
                            <th style="padding: 1rem;">Category</th>
                            <th style="padding: 1rem;">Price</th>
                            <th style="padding: 1rem;">Status</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $s): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="<?php echo BASE_URL; ?>assets/img/services/<?php echo $s['image']; ?>"
                                        onerror="this.src='https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=400&auto=format'"
                                        style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                    <?php echo $s['name']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo $s['provider_name']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo $s['category_name']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo formatCurrency($s['price']); ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <span
                                        style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                                    background: <?php echo $s['status'] === 'active' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>;
                                    color: <?php echo $s['status'] === 'active' ? 'var(--accent)' : 'var(--danger)'; ?>;">
                                        <?php echo ucfirst($s['status']); ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($s['status'] === 'active'): ?>
                                        <a href="?action=deactivate&id=<?php echo $s['id']; ?>" class="btn glass"
                                            style="padding: 0.4rem 0.75rem; font-size: 0.75rem; color: var(--danger);">Deactivate</a>
                                    <?php else: ?>
                                        <a href="?action=activate&id=<?php echo $s['id']; ?>" class="btn btn-primary"
                                            style="padding: 0.4rem 0.75rem; font-size: 0.75rem;">Activate</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once '../partials/footer.php'; ?>