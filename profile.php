<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch User Data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email']);
    $username = clean($_POST['username']);

    // Simple Update
    $stmt = $pdo->prepare("UPDATE users SET email = ?, username = ? WHERE id = ?");
    if ($stmt->execute([$email, $username, $user_id])) {
        $_SESSION['user_name'] = $username;
        $success = "Profile updated successfully!";
        // Refresh data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    } else {
        $error = "Failed to update profile.";
    }
}

$pageTitle = "My Profile";
$hideNav = true;
require_once 'partials/header.php';
?>

<div class="dashboard-layout">
    <?php require_once 'partials/sidebar.php'; ?>

    <main class="main-content">
        <header style="margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; font-weight: 700;">Account Settings</h1>
            <p style="color: var(--text-muted);">Manage your personal information and preferences.</p>
        </header>

        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
            <div class="card" style="text-align: center;">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo $user['username']; ?>"
                    style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 1.5rem; border: 4px solid var(--border-color);">
                <h3 style="font-size: 1.25rem; font-weight: 700;">
                    <?php echo $user['username']; ?>
                </h3>
                <p style="color: var(--text-muted); text-transform: capitalize; margin-bottom: 2rem;">
                    <?php echo $user['role']; ?>
                </p>
                <button class="btn glass" style="width: 100%;">Change Avatar</button>
            </div>

            <div class="card">
                <?php if ($success): ?>
                    <div
                        style="background-color: var(--accent); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div
                        style="background-color: var(--danger); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Registration Date</label>
                        <input type="text" class="form-control"
                            value="<?php echo date('F d, Y', strtotime($user['created_at'])); ?>" disabled>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once 'partials/footer.php'; ?>