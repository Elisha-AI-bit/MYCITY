<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('customer')) {
    redirect('login.php');
}

$customer_id = $_SESSION['user_id'];

// Fetch bookings
$stmt = $pdo->prepare("
    SELECT b.*, s.name as service_name, u.username as provider_name 
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN users u ON s.provider_id = u.id
    WHERE b.customer_id = ?
    ORDER BY b.created_at DESC
");
$stmt->execute([$customer_id]);
$bookings = $stmt->fetchAll();

$pageTitle = "My Bookings";
$hideNav = true;
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700;">My Bookings</h1>
            <p style="color: var(--text-muted);">Track your service requests and their status.</p>
        </header>

        <?php $flash = getFlash('booking');
        if ($flash): ?>
            <div
                style="background-color: var(--primary); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                <?php echo $flash['message']; ?>
            </div>
        <?php endif; ?>

        <div style="display: grid; gap: 1rem;">
            <?php foreach ($bookings as $b): ?>
                <div class="card"
                    style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 600;">
                            <?php echo $b['service_name']; ?>
                        </h3>
                        <p style="color: var(--text-muted); font-size: 0.875rem;">Provider: <span
                                style="font-weight: 600; color: var(--text-main);">
                                <?php echo $b['provider_name']; ?>
                            </span></p>
                        <div style="display: flex; gap: 1rem; margin-top: 0.5rem; font-size: 0.875rem;">
                            <span style="display: flex; align-items: center; gap: 0.25rem;"><i data-lucide="calendar"
                                    style="width: 14px;"></i>
                                <?php echo $b['booking_date']; ?>
                            </span>
                            <span style="display: flex; align-items: center; gap: 0.25rem;"><i data-lucide="clock"
                                    style="width: 14px;"></i>
                                <?php echo $b['booking_time']; ?>
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="margin-bottom: 0.5rem;">
                            <span
                                style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                            background: <?php echo $b['status'] === 'accepted' ? 'rgba(34, 197, 94, 0.1)' : ($b['status'] === 'pending' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(239, 68, 68, 0.1)'); ?>;
                            color: <?php echo $b['status'] === 'accepted' ? 'var(--accent)' : ($b['status'] === 'pending' ? 'var(--warning)' : 'var(--danger)'); ?>;">
                                <?php echo ucfirst($b['status']); ?>
                            </span>
                        </div>
                        <?php if ($b['status'] === 'completed'): ?>
                            <button class="btn btn-primary" style="padding: 0.4rem 0.75rem; font-size: 0.75rem;">Rate
                                Service</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($bookings)): ?>
                <div class="card" style="text-align: center; padding: 3rem;">
                    <i data-lucide="calendar"
                        style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                    <p>You haven't booked any services yet.</p>
                    <a href="index.php" class="btn btn-primary" style="margin-top: 1rem;">Find Services</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once '../partials/footer.php'; ?>