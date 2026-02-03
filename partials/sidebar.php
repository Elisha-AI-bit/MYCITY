<?php
$role = $_SESSION['user_role'] ?? 'customer';
?>
<aside class="sidebar">
    <div style="margin-bottom: 2.5rem;">
        <a href="<?php echo BASE_URL; ?>"
            style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
            <i data-lucide="map-pin"></i>
            MYCITY
        </a>
    </div>

    <nav style="flex: 1;">
        <ul style="display: flex; flex-direction: column; gap: 0.5rem;">
            <!-- Common Links -->
            <li>
                <a href="<?php echo BASE_URL . $role; ?>/index.php"
                    class="<?php echo str_contains($_SERVER['PHP_SELF'], 'index.php') ? 'active-link' : ''; ?>"
                    style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                    <i data-lucide="layout-dashboard" style="width: 20px;"></i>
                    Dashboard
                </a>
            </li>

            <?php if ($role === 'admin'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>admin/users.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="users" style="width: 20px;"></i>
                        Manage Users
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>admin/services.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="briefcase" style="width: 20px;"></i>
                        Manage Services
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>admin/analytics.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="bar-chart-3" style="width: 20px;"></i>
                        Reports
                    </a>
                </li>

            <?php elseif ($role === 'provider'): ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>provider/my-services.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="list" style="width: 20px;"></i>
                        My Services
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>provider/bookings.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="calendar" style="width: 20px;"></i>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>provider/earnings.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="dollar-sign" style="width: 20px;"></i>
                        Earnings
                    </a>
                </li>

            <?php else: ?>
                <li>
                    <a href="<?php echo BASE_URL; ?>customer/find-services.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="search" style="width: 20px;"></i>
                        Find Services
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL; ?>customer/my-bookings.php"
                        style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                        <i data-lucide="clock" style="width: 20px;"></i>
                        My Bookings
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="<?php echo BASE_URL; ?>profile.php"
                    style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500;">
                    <i data-lucide="user" style="width: 20px;"></i>
                    Profile
                </a>
            </li>
        </ul>
    </nav>

    <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
        <a href="<?php echo BASE_URL; ?>logout.php"
            style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: var(--radius); font-weight: 500; color: var(--danger);">
            <i data-lucide="log-out" style="width: 20px;"></i>
            Logout
        </a>
    </div>
</aside>

<style>
    .active-link {
        background-color: var(--primary);
        color: white !important;
    }

    .sidebar a:hover:not(.active-link) {
        background-color: var(--border-color);
    }
</style>