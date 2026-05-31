<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

function verifyFaces($cnic_path, $profile_path) {
    $url = 'https://api-us.faceplusplus.com/facepp/v3/compare';
    
    $post_data = [
        'api_key' => FACEPP_API_KEY,
        'api_secret' => FACEPP_API_SECRET,
        'image_file1' => new CURLFile(realpath($cnic_path)),
        'image_file2' => new CURLFile(realpath($profile_path)),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['confidence'])) {
        return $result['confidence'];
    }
    return 0;
}

function sendVerificationEmail($email, $name) {
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
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'CivicVote - Registration Successful';
        $mail->Body = "
            <h2>Welcome to CivicVote, $name!</h2>
            <p>Your identity has been successfully verified.</p>
            <p>You can now login and participate in community elections.</p>
            <br>
            <p>Thank you for joining CivicVote.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email failed: " . $mail->ErrorInfo);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $cnic = trim($_POST['cnic']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle file uploads
    $cnic_pic = uniqid() . '_' . basename($_FILES['cnic_pic']['name']);
    $profile_pic = uniqid() . '_' . basename($_FILES['profile_pic']['name']);

    $cnic_path = 'assets/uploads/cnic/' . $cnic_pic;
    $profile_path = 'assets/uploads/selfies/' . $profile_pic;

    move_uploaded_file($_FILES['cnic_pic']['tmp_name'], $cnic_path);
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_path);

    // Check if email or CNIC already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR cnic = ?");
    $stmt->execute([$email, $cnic]);

    if ($stmt->rowCount() > 0) {
        $error = "Email or CNIC already registered.";
    } else {
		// Verify faces
		$face_score = verifyFaces($cnic_path, $profile_path);
		$is_verified = $face_score >= 60 ? 1 : 0;

		// Insert user
		$stmt = $pdo->prepare("INSERT INTO users (name, email, cnic, password, cnic_pic, profile_pic, is_verified, face_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute([$name, $email, $cnic, $password, $cnic_path, $profile_path, $is_verified, $face_score]);

		if ($is_verified) {
			sendVerificationEmail($email, $name);
			$success = "Registration successful! Your identity has been verified.";
		} else {
			$error = "Registration failed. Face verification score too low (" . round($face_score, 2) . "%). Please upload clearer pictures.";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link active" href="register.php">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Register Form -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <h2 class="fw-bold mb-4 text-center">Create an Account</h2>
                        <form action="register.php" method="POST" enctype="multipart/form-data" novalidate>
                        
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CNIC Number</label>
                                <input type="text" name="cnic" id="cnic" class="form-control" placeholder="Enter 13 digit CNIC" maxlength="15" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CNIC Front Picture</label>
                                <input type="file" name="cnic_pic" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_pic" class="form-control" accept="image/*" required>
                            </div>
							
							<?php if (isset($error)): ?>
								<div class="alert alert-danger"><?= $error ?></div>
							<?php endif; ?>
							<?php if (isset($success)): ?>
								<div class="alert alert-success"><?= $success ?></div>
							<?php endif; ?>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" style="background-color: #1F4E79; border-color: #1F4E79;">Register</button>
                            </div>

                            <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>

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