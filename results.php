<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (!isset($_GET['id'])) {
    header("Location: elections.php");
    exit();
}

$election_id = $_GET['id'];

// Fetch election

$stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ?");
$stmt->execute([$election_id]);
$election = $stmt->fetch();

if (!$election) {
    header("Location: elections.php");
    exit();
}

// Fetch total votes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE election_id = ?");
$stmt->execute([$election_id]);
$total_votes = $stmt->fetchColumn();

// Fetch total voters
$total_voters = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'voter'")->fetchColumn();
$turnout = $total_voters > 0 ? round(($total_votes / $total_voters) * 100) : 0;

// Fetch candidates with vote counts
$stmt = $pdo->prepare("
    SELECT c.id, c.name, COUNT(v.id) as vote_count
    FROM candidates c
    LEFT JOIN votes v ON c.id = v.candidate_id
    WHERE c.election_id = ?
    GROUP BY c.id, c.name
    ORDER BY vote_count DESC
");
$stmt->execute([$election_id]);
$candidates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <?php $base = ''; $current = 'elections'; include 'includes/navbar.php'; ?>

    <main>
        <!-- Results Section -->
        <section class="py-5">
            <div class="container">

                <h2 class="fw-bold"><?= htmlspecialchars($election['title']) ?> — Results</h2>
				<p class="text-muted">Total Votes: <?= $total_votes ?> | Turnout: <?= $turnout ?>%</p>

				<?php if ($election['status'] === 'open'): ?>
					<div class="alert alert-info">Live results — updates every 30 seconds</div>
				<?php endif; ?>

				<!-- Stat Cards -->
				<div class="row g-3 mb-5">
					<div class="col-md-4">
						<div class="card text-center p-3" style="background-color: #F0F4F8;">
							<h3 class="fw-bold"><?= $total_votes ?></h3>
							<p class="text-muted mb-0">Total Votes</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card text-center p-3" style="background-color: #F0F4F8;">
							<h3 class="fw-bold"><?= $turnout ?>%</h3>
							<p class="text-muted mb-0">Turnout</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card text-center p-3" style="background-color: #F0F4F8;">
							<h3 class="fw-bold"><?= count($candidates) ?></h3>
							<p class="text-muted mb-0">Candidates</p>
						</div>
					</div>
				</div>

				<!-- Result Bars -->
				<?php foreach ($candidates as $candidate): ?>
					<?php $percentage = $total_votes > 0 ? round(($candidate['vote_count'] / $total_votes) * 100) : 0; ?>
					<div class="mb-4">
						<div class="d-flex justify-content-between mb-1">
							<span class="fw-bold"><?= htmlspecialchars($candidate['name']) ?></span>
							<span><?= $percentage ?>% (<?= $candidate['vote_count'] ?> votes)</span>
						</div>
						<div class="progress" style="height: 20px;">
							<div class="progress-bar" style="width: <?= $percentage ?>%; background-color: #1F4E79;"></div>
						</div>
					</div>
				<?php endforeach; ?>

            </div>
        </section>
		
		<?php if ($election['status'] === 'open'): ?>
		<script>
			setTimeout(function() {
				location.reload();
			}, 30000);
		</script>
		<?php endif; ?>
		
    </main>
    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>