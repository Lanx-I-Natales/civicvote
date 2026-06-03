<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';


// Check token
if (!isset($_GET['token']) || empty($_GET['token'])) {
    $_SESSION['error'] = "Invalid reset link. Please request a new one.";
    header("Location: forgot_password.php");
    exit();
}

$token = $_GET['token'];

// Verify token
$stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
$stmt->execute([$token]);
$user = $stmt->fetch();

if (!$user) {
    $_SESSION['error'] = "Invalid or expired reset link. Please request a new one.";
    header("Location: forgot_password.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: reset_password.php?token=" . $token);
        exit();
    } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
        $_SESSION['error'] = "Password must be at least 8 characters, one uppercase, one number and one special character.";
        header("Location: reset_password.php?token=" . $token);
        exit();
    } elseif (password_verify($password, $user['password'])) {
        $_SESSION['error'] = "New password cannot be the same as your previous password.";
        header("Location: reset_password.php?token=" . $token);
        exit();
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
        $stmt->execute([$hashed, $user['id']]);

        $_SESSION['success'] = "Password changed successfully! You can now login.";
		header("Location: reset_password.php");
		exit();
    }
}
// Read and clear session messages immediately
$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <?php $base = ''; $current = 'login'; include 'includes/navbar.php'; ?>

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <h2 class="fw-bold mb-4 text-center">Reset Password</h2>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
						<?php if ($success): ?>
							<div class="alert alert-success">
								<?= $success ?>
								<br><small>Redirecting to login in <span id="countdown">3</span> seconds...</small>
							</div>
							<script>
								window.onload = function() {
									let seconds = 3;
									const countdown = document.getElementById('countdown');
									const interval = setInterval(() => {
										seconds--;
										countdown.textContent = seconds;
										if (seconds <= 0) {
											clearInterval(interval);
											window.location.href = 'login.php';
										}
									}, 1000);
								};
							</script>
						<?php endif; ?>
					<?php if (!$success): ?>
                        <form action="reset_password.php?token=<?= htmlspecialchars($token) ?>" method="POST" novalidate>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                                <div id="passwordChecklist" class="mt-2" style="font-size: 13px; display: none;">
                                    <div id="check-length" class="text-danger">✕ At least 8 characters</div>
                                    <div id="check-upper" class="text-danger">✕ One uppercase letter</div>
                                    <div id="check-lower" class="text-danger">✕ One lowercase letter</div>
                                    <div id="check-number" class="text-danger">✕ One number</div>
                                    <div id="check-special" class="text-danger">✕ One special character</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter new password" required>
                                <div id="passwordMatch" class="form-text" style="display:none;"></div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Reset Password</button>
                            </div>

                            <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>

                        </form>
					<?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>