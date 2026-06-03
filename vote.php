<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
redirectIfNotLoggedIn();

if (isAdmin()) {
    header("Location: elections.php");
    $_SESSION['error'] = "Admins are not allowed to vote.";
    exit();
}

// Get election ID from URL
if (!isset($_GET['id'])) {
    header("Location: elections.php");
    exit();
}

$election_id = $_GET['id'];

// Fetch election
$stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ? AND status = 'open'");
$stmt->execute([$election_id]);
$election = $stmt->fetch();

if (!$election) {
    header("Location: elections.php");
    exit();
}

// Check if user already voted
$stmt = $pdo->prepare("SELECT id FROM votes WHERE election_id = ? AND user_id = ?");
$stmt->execute([$election_id, $_SESSION['user_id']]);
if ($stmt->rowCount() > 0) {
    $error = "You have already voted in this election!";
}

// Fetch candidates
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id = ?");
$stmt->execute([$election_id]);
$candidates = $stmt->fetchAll();

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['error'])) {
    $candidate_id = $_POST['candidate'];

    // Check double vote
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE election_id = ? AND user_id = ?");
    $stmt->execute([$election_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "You have already voted in this election!";
    } else {
        $stmt = $pdo->prepare("INSERT INTO votes (election_id, candidate_id, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$election_id, $candidate_id, $_SESSION['user_id']]);
        $_SESSION['success'] = "Your vote has been cast successfully!";
    }
    
    header("Location: vote.php?id=" . $election_id);
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
    <title>CivicVote - Cast Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <?php $base = ''; $current = 'elections'; include 'includes/navbar.php'; ?>

    <main>
        <!-- Vote Section -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <h2 class="fw-bold"><?= htmlspecialchars($election['title']) ?></h2>
						<p class="text-muted">Select one candidate</p>

							<?php if ($error): ?>
								<div class="alert alert-danger"><?= $error ?></div>
							<?php endif; ?>
							<?php if ($success): ?>
								<div class="alert alert-success"><?= $success ?></div>
							<?php endif; ?>

						<form action="vote.php?id=<?= $election_id ?>" method="POST" novalidate>
							<?php foreach ($candidates as $candidate): ?>
								<label class="vote-card card mb-3 p-3 w-100" for="c<?= $candidate['id'] ?>">
									<div class="d-flex align-items-center gap-3">
										<input type="radio" name="candidate" id="c<?= $candidate['id'] ?>" value="<?= $candidate['id'] ?>" required>
										<?php if ($candidate['photo']): ?>
											<img src="<?= $candidate['photo'] ?>" alt="<?= htmlspecialchars($candidate['name']) ?>" width="50" height="50" style="border-radius: 50%; object-fit: cover;">
										<?php else: ?>
											<div style="width: 50px; height: 50px; border-radius: 50%; background-color: #F0F4F8; display: flex; align-items: center; justify-content: center; font-size: 20px;">👤</div>
										<?php endif; ?>
										<div>
											<span class="fw-bold"><?= htmlspecialchars($candidate['name']) ?></span>
										</div>
									</div>
								</label>
							<?php endforeach; ?>

							<?php if (!isset($error)): ?>
								<div class="d-flex gap-2 mt-4">
									<button type="submit" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Submit Vote</button>
									<a href="elections.php" class="btn btn-lg btn-secondary">Cancel</a>
								</div>
							<?php endif; ?>
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