<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';
redirectIfNotLoggedIn();
if (isAdmin()) {
    header("Location: elections.php");
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($error)) {
    $candidate_id = $_POST['candidate'];

    $stmt = $pdo->prepare("INSERT INTO votes (election_id, candidate_id, user_id) VALUES (?, ?, ?)");
    $stmt->execute([$election_id, $candidate_id, $_SESSION['user_id']]);

    $success = "Your vote has been cast successfully!";
}
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Vote Section -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">
                        <h2 class="fw-bold"><?= htmlspecialchars($election['title']) ?></h2>
						<p class="text-muted">Select one candidate</p>

						<?php if (isset($error)): ?>
							<div class="alert alert-danger"><?= $error ?></div>
						<?php endif; ?>

						<?php if (isset($success)): ?>
							<div class="alert alert-success"><?= $success ?></div>
						<?php endif; ?>

						<form action="vote.php?id=<?= $election_id ?>" method="POST" novalidate>
							<?php foreach ($candidates as $candidate): ?>
								<label class="vote-card card mb-3 p-3 w-100" for="c<?= $candidate['id'] ?>">
									<div class="d-flex align-items-center gap-3">
										<input type="radio" name="candidate" id="c<?= $candidate['id'] ?>" value="<?= $candidate['id'] ?>" required>
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