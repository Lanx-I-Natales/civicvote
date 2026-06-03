<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate token
        $token = bin2hex(random_bytes(32));
		
        // Save token to database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = DATE_ADD(NOW(), INTERVAL 30 MINUTE) WHERE email = ?");
		$stmt->execute([$token, $email]);

        // Send email
        require_once 'includes/PHPMailer/PHPMailer.php';
        require_once 'includes/PHPMailer/SMTP.php';
        require_once 'includes/PHPMailer/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = '6c7c06bf912a3c';
            $mail->Password = '3cb2d4671ebd0f';
            $mail->Port = 2525;

            $mail->setFrom('noreply@civicvote.com', 'CivicVote');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'CivicVote - Password Reset';
            $mail->Body = "
				<h2>Password Reset Request</h2>
				<p>Click the link below to reset your password. This link expires in 30 minutes.</p>
				<a href='http://localhost/civicvote/reset_password.php?token=$token'>Reset Password</a>
				<p>If you did not request this, ignore this email.</p>
			";
            $mail->send();
        } catch (Exception $e) {
            error_log("Email failed: " . $mail->ErrorInfo);
        }
    }

    // Always show success to prevent email enumeration
    $_SESSION['success'] = "If that email exists, a reset link has been sent.";
    header("Location: forgot_password.php");
    exit();
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
    <title>CivicVote - Forgot Password</title>
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
                        <h2 class="fw-bold mb-4 text-center">Forgot Password</h2>

                        <?php if (isset($error)): ?>
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

                        <form action="forgot_password.php" method="POST" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Send Reset Link</button>
                            </div>
                            <p class="text-center mt-3"><a href="login.php">Back to Login</a></p>
                        </form>
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