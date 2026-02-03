<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('provider')) {
    redirect('login.php');
}

$pageTitle = "Provider Dashboard";
$hideNav = true;
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.75rem; font-weight: 700;">Provider Dashboard</h1>
                <p style="color: var(--text-muted);">Manage your services and bookings.</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="new-service.php" class="btn btn-primary"><i data-lucide="plus"></i> Add Service</a>
            </div>
        </header>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="card">
                <h3 style="font-size: 0.875rem; color: var(--text-muted);">Total Earnings</h3>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.5rem;">$1,450.00</p>
                <div
                    style="font-size: 0.75rem; color: var(--accent); margin-top: 0.5rem; display: flex; align-items: center; gap: 0.25rem;">
                    <i data-lucide="trending-up" style="width: 12px;"></i> +12% from last month
                </div>
            </div>
            <div class="card">
                <h3 style="font-size: 0.875rem; color: var(--text-muted);">Pending Bookings</h3>
                <p style="font-size: 1.5rem; font-weight: 700; margin-top: 0.5rem;">5</p>
            </div>
            <div class="card">
                <h3 style="font-size: 0.875rem; color: var(--text-muted);">Avg. Rating</h3>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <p style="font-size: 1.5rem; font-weight: 700;">4.8</p>
                    <div style="color: var(--warning); display: flex;">
                        <i data-lucide="star" style="width: 16px; fill: var(--warning);"></i>
                        <i data-lucide="star" style="width: 16px; fill: var(--warning);"></i>
                        <i data-lucide="star" style="width: 16px; fill: var(--warning);"></i>
                        <i data-lucide="star" style="width: 16px; fill: var(--warning);"></i>
                        <i data-lucide="star" style="width: 16px; fill: var(--warning);"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1.5rem;">Recent Booking Requests</h3>
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
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=John"
                                    style="width: 32px; height: 32px; border-radius: 50%;">
                                John Doe
                            </td>
                            <td style="padding: 1rem;">Leak Repair</td>
                            <td style="padding: 1rem;">Oct 24, 10:00 AM</td>
                            <td style="padding: 1rem;"><span
                                    style="padding: 0.25rem 0.75rem; background: rgba(245, 158, 11, 0.1); color: var(--warning); border-radius: 99px; font-size: 0.75rem; font-weight: 600;">Pending</span>
                            </td>
                            <td style="padding: 1rem; display: flex; gap: 0.5rem;">
                                <button class="btn btn-primary"
                                    style="padding: 0.4rem 0.75rem; font-size: 0.75rem;">Accept</button>
                                <button class="btn glass"
                                    style="padding: 0.4rem 0.75rem; font-size: 0.75rem; color: var(--danger);">Decline</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once '../partials/footer.php'; ?>