<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean($_POST['username']);
    $email = clean($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = clean($_POST['role']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "User with this email or username already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $role])) {
                setFlash('register', 'Registration successful! Please login.', 'success');
                redirect('login.php');
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$pageTitle = "Register";
$hideNav = true;
require_once 'partials/header.php';
?>

<div class="auth-wrapper">
    <div class="card auth-card">
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="<?php echo BASE_URL; ?>"
                style="display: inline-flex; align-items: center; gap: 0.5rem; font-size: 1.75rem; font-weight: 800; color: var(--primary); margin-bottom: 1rem;">
                <i data-lucide="map-pin"></i>
                MYCITY
            </a>
            <h2 style="font-size: 1.5rem;">Create an Account</h2>
            <p style="color: var(--text-muted);">Join the community and start booking services</p>
        </div>

        <?php if ($error): ?>
            <div
                style="background-color: var(--danger); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Choose a username">
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label class="form-label">Account Type</label>
                <select name="role" class="form-control" required>
                    <option value="customer">I'm a Customer (I want to book services)</option>
                    <option value="provider">I'm a Service Provider (I want to list services)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Sign Up
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; color: var(--text-muted);">
            Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600;">Log In</a>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>