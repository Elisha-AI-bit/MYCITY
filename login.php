<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        if ($user['status'] === 'suspended') {
            $error = "Your account has been suspended. Please contact support.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                redirect('admin/index.php');
            } elseif ($user['role'] === 'provider') {
                redirect('provider/index.php');
            } else {
                redirect('customer/index.php');
            }
        }
    } else {
        $error = "Invalid email or password.";
    }
}

$pageTitle = "Login";
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
            <h2 style="font-size: 1.5rem;">Welcome Back</h2>
            <p style="color: var(--text-muted);">Enter your credentials to access your account</p>
        </div>

        <?php
        $regFlash = getFlash('register');
        if ($regFlash): ?>
            <div
                style="background-color: var(--accent); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <?php echo $regFlash['message']; ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div
                style="background-color: var(--danger); color: white; padding: 0.75rem; border-radius: var(--radius); margin-bottom: 1.5rem; font-size: 0.875rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@example.com">
            </div>
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label class="form-label">Password</label>
                    <a href="#" style="font-size: 0.875rem; color: var(--primary);">Forgot?</a>
                </div>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="form-group" style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="remember">
                <label for="remember" style="font-size: 0.875rem; cursor: pointer;">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                Log In
            </button>
        </form>

        <div style="text-align: center; margin-top: 2rem; color: var(--text-muted);">
            Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600;">Sign Up</a>
        </div>
    </div>
</div>

<?php require_once 'partials/footer.php'; ?>