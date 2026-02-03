<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('login.php');
}

// Fetch dynamic counts
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_services = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
$bookings_today = $pdo->query("SELECT COUNT(*) FROM bookings WHERE DATE(created_at) = CURDATE()")->fetchColumn();
$total_revenue = $pdo->query("SELECT SUM(price) FROM bookings b JOIN services s ON b.service_id = s.id WHERE b.status = 'completed'")->fetchColumn() ?: 0;

$pageTitle = "Admin Dashboard";
$hideNav = true; // Use Dashboard layout instead
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700;">Admin Overview</h1>
                <p style="color: var(--text-muted);">Welcome back, Admin. Here's what's happening.</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button class="btn glass"><i data-lucide="bell"></i></button>
                <div
                    style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 1rem; background: var(--bg-white); border-radius: var(--radius); border: 1px solid var(--border-color);">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Admin" alt="Admin"
                        style="width: 32px; height: 32px; border-radius: 50%;">
                    <span style="font-weight: 600;">System Admin</span>
                </div>
            </div>
        </header>

        <!-- Stats Grid -->
        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="card" style="display: flex; align-items: center; gap: 1rem;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(13, 148, 136, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="users"></i>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted);">Total Users</h3>
                    <p style="font-size: 1.5rem; font-weight: 700;"><?php echo number_format($total_users); ?></p>
                </div>
            </div>
            <div class="card" style="display: flex; align-items: center; gap: 1rem;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(59, 130, 246, 0.1); color: var(--secondary); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="briefcase"></i>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted);">Active Services</h3>
                    <p style="font-size: 1.5rem; font-weight: 700;"><?php echo number_format($total_services); ?></p>
                </div>
            </div>
            <div class="card" style="display: flex; align-items: center; gap: 1rem;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(34, 197, 94, 0.1); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="check-circle"></i>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted);">Bookings Today</h3>
                    <p style="font-size: 1.5rem; font-weight: 700;"><?php echo $bookings_today; ?></p>
                </div>
            </div>
            <div class="card" style="display: flex; align-items: center; gap: 1rem;">
                <div
                    style="width: 48px; height: 48px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: var(--warning); display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="dollar-sign"></i>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--text-muted);">Revenue</h3>
                    <p style="font-size: 1.5rem; font-weight: 700;"><?php echo formatCurrency($total_revenue); ?></p>
                </div>
            </div>
        </div>

        <!-- Charts and Trends -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600;">Booking Trends</h3>
                    <select class="form-control" style="width: auto; padding: 0.4rem 0.75rem;">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                    </select>
                </div>
                <div
                    style="height: 300px; display: flex; align-items: center; justify-content: center; background: var(--bg-light); border-radius: var(--radius); color: var(--text-muted);">
                    <div style="text-align: center;">
                        <i data-lucide="bar-chart"
                            style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Real-time analytics visualization</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem;">Top Categories</h3>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <?php
                    $top_cats = $pdo->query("SELECT c.name, COUNT(s.id) as count FROM categories c LEFT JOIN services s ON c.id = s.category_id GROUP BY c.id ORDER BY count DESC LIMIT 4")->fetchAll();
                    foreach ($top_cats as $tc):
                        ?>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--primary);"></div>
                                <span><?php echo $tc['name']; ?></span>
                            </div>
                            <span style="font-weight: 600;"><?php echo $tc['count']; ?> services</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../partials/footer.php'; ?>