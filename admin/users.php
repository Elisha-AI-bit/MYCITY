<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!isLoggedIn() || !hasRole('admin')) {
    redirect('login.php');
}

// Handle User Status
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $action = $_GET['action'];
    $status = ($action === 'suspend') ? 'suspended' : 'active';

    $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'admin'");
    if ($stmt->execute([$status, $user_id])) {
        setFlash('user', "User status updated to $status.", 'success');
    }
    redirect('admin/users.php');
}

// Fetch users
$stmt = $pdo->query("SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC");
$users = $stmt->fetchAll();

$pageTitle = "Manage Users";
$hideNav = true;
require_once '../partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once '../partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700;">Manage Users</h1>
            <p style="color: var(--text-muted);">Manage customers and service providers.</p>
        </header>

        <?php $flash = getFlash('user');
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
                            <th style="padding: 1rem;">User</th>
                            <th style="padding: 1rem;">Email</th>
                            <th style="padding: 1rem;">Role</th>
                            <th style="padding: 1rem;">Status</th>
                            <th style="padding: 1rem;">Joined At</th>
                            <th style="padding: 1rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo $u['username']; ?>"
                                        style="width: 32px; height: 32px; border-radius: 50%;">
                                    <?php echo $u['username']; ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php echo $u['email']; ?>
                                </td>
                                <td style="padding: 1rem;"><span style="text-transform: capitalize;">
                                        <?php echo $u['role']; ?>
                                    </span></td>
                                <td style="padding: 1rem;">
                                    <span
                                        style="padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                                    background: <?php echo $u['status'] === 'active' ? 'rgba(34, 197, 94, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>;
                                    color: <?php echo $u['status'] === 'active' ? 'var(--accent)' : 'var(--danger)'; ?>;">
                                        <?php echo ucfirst($u['status']); ?>
                                    </span>
                                </td>
                                <td style="padding: 1rem; color: var(--text-muted); font-size: 0.875rem;">
                                    <?php echo date('M d, Y', strtotime($u['created_at'])); ?>
                                </td>
                                <td style="padding: 1rem;">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <a href="?action=suspend&id=<?php echo $u['id']; ?>" class="btn glass"
                                            style="padding: 0.4rem 0.75rem; font-size: 0.75rem; color: var(--danger);">Suspend</a>
                                    <?php else: ?>
                                        <a href="?action=activate&id=<?php echo $u['id']; ?>" class="btn btn-primary"
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