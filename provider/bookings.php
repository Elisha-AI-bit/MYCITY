<?php 
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('provider')) {
    redirect('login.php');
}

$provider_id = $_SESSION['user_id'];

// Handle Actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = ($action === 'accept') ? 'accepted' : (($action === 'reject') ? 'rejected' : 'completed');

    $stmt = $pdo->prepare("UPDATE bookings b JOIN services s ON b.service_id = s.id SET b.status = ? WHERE b.id = ? AND s.provider_id = ?");
    if ($stmt->execute([$status, $booking_id, $provider_id])) {
        setFlash('booking', "Booking marked as $status.", 'success');
    }
    redirect('provider/bookings.php');
}

// Fetch bookings
$stmt = $pdo->prepare("
    SELECT b.*, s.name as service_name, u.username as customer_name 
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN users u ON b.customer_id = u.id
    WHERE s.provider_id = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$provider_id]);
$bookings = $stmt->fetchAll();

$pageTitle = "Manage Bookings";
$hideNav = true;
require_once '../partials/header.php'; 
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>
    
    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700;">Manage Bookings</h1>
            <p style="color: var(--text-muted);">Accept or reject incoming service requests.</p>
        </header>

        <?php $flash = getFlash('booking'); if ($flash): ?>
            <div style="background-color: var(--primary); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 1rem;">Customer</th>
                            <th style="padding: 1rem;">Service</th>
                            <th style="padding: 1rem;">Date & Time</th>
                            <th style="padding: 1rem;">Status</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $b): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem;"><?php echo $b['customer_name']; ?></td>
                            <td style="padding: 1rem;"><?php echo $b['service_name']; ?></td>
                            <td style="padding: 1rem;"><?php echo $b['booking_date'] . ' ' . $b['booking_time']; ?></td>
                            <td style="padding: 1rem;">
                                <span style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                                    background: <?php echo $b['status'] === 'accepted' ? 'rgba(34, 197, 94, 0.1)' : ($b['status'] === 'pending' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(239, 68, 68, 0.1)'); ?>;
                                    color: <?php echo $b['status'] === 'accepted' ? 'var(--accent)' : ($b['status'] === 'pending' ? 'var(--warning)' : 'var(--danger)'); ?>;">
                                    <?php echo ucfirst($b['status']); ?>
                                </span>
                            </td>
                            <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                                <?php if ($b['status'] === 'pending'): ?>
                                    <a href="?action=accept&id=<?php echo $b['id']; ?>" class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.75rem;">Accept</a>
                                    <a href="?action=reject&id=<?php echo $b['id']; ?>" class="btn glass" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; color: var(--danger);">Reject</a>
                                <?php elseif ($b['status'] === 'accepted'): ?>
                                    <a href="?action=complete&id=<?php echo $b['id']; ?>" class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; background: var(--accent);">Mark Done</a>
                                <?php else: ?>
                                    <span style="color: var(--text-muted); font-size: 0.75rem;">No actions</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="5" style="padding: 2rem; text-align: center; color: var(--text-muted);">No bookings found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once '../partials/footer.php'; ?>
