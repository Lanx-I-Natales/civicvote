<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Fetch stats
$total_elections = $pdo->query("SELECT COUNT(*) FROM elections WHERE status = 'open'")->fetchColumn();
$total_voters = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'voter'")->fetchColumn();
$total_votes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();

// Fetch latest elections
$stmt = $pdo->query("
    SELECT * FROM elections 
    WHERE status != 'draft' 
    ORDER BY 
        CASE status 
            WHEN 'open' THEN 1 
            WHEN 'closed' THEN 2 
        END,
        created_at DESC
    LIMIT 2
");
$latest_elections = $stmt->fetchAll();

// Fetch elections with candidate photos for hero
$hero_stmt = $pdo->query("
    SELECT e.id, e.title, c.name as candidate_name, c.photo 
    FROM elections e
    JOIN candidates c ON c.election_id = e.id
    WHERE e.status != 'draft' AND c.photo IS NOT NULL
    ORDER BY e.id, c.id
");
$hero_data = $hero_stmt->fetchAll();

// Group by election
$hero_elections = [];
foreach ($hero_data as $row) {
    $hero_elections[$row['id']]['title'] = $row['title'];
    $hero_elections[$row['id']]['candidates'][] = [
        'name' => $row['candidate_name'],
        'photo' => $row['photo']
    ];
}
$hero_elections = array_values($hero_elections);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <?php $base = ''; $current = 'home'; include 'includes/navbar.php'; ?>

    <main>
        <!-- Hero Section -->
		<section class="hero-section text-center" id="heroSection">
			
			<!-- Gradient fallback shown by default -->
			<div id="heroGradient" class="hero-overlay"></div>
			
			<!-- Candidate backgrounds -->
			<?php if (!empty($hero_elections)): ?>
				<?php foreach ($hero_elections as $eindex => $helection): ?>
					<?php $candidates = $helection['candidates']; ?>
					<?php if (count($candidates) >= 2): ?>
						<div class="hero-candidates" data-index="<?= $eindex ?>" style="display: none;">
							<?php 
							$sliced = array_slice($candidates, 0, 3);
							$last = end($sliced);
							?>
							<?php foreach ($sliced as $candidate): ?>
								<div class="hero-left" style="background-image: url('<?= $candidate['photo'] ?>');">
									<div class="hero-name"><?= htmlspecialchars($candidate['name']) ?></div>
								</div>
								<?php if ($candidate !== $last): ?>
									<div class="hero-bolt">⚡</div>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>

			<!-- Content on top -->
			<div class="hero-content container">
				<h1 class="display-3 fw-bold" style="color: white; text-shadow: 2px 2px 8px rgba(0,0,0,0.8);">Your Community, Your Voice</h1>
				<p class="lead mt-3 mb-4" style="color: #f0f0f0; text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">Participate in local elections and polls — simple, secure, and accessible.</p>
				<div class="mt-4">
					<?php if (isLoggedIn()): ?>
						<h4 class="fw-bold" style="color: white; text-shadow: 1px 1px 4px rgba(0,0,0,0.8);">Welcome back, <?= htmlspecialchars($_SESSION['name']) ?>! 👋</h4>
						<a href="elections.php" class="btn btn-lg mt-3" style="background-color: white; color: #1F4E79;">View Elections</a>
					<?php else: ?>
						<a href="register.php" class="btn btn-lg me-2" style="background-color: white; color: #1F4E79;">Get Started</a>
						<a href="login.php" class="btn btn-lg" style="background-color: transparent; color: white; border: 2px solid white;">Login</a>
					<?php endif; ?>
				</div>
			</div>
		</section>

        <!-- Stats Section -->
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center g-4">
                    <div class="col-md-3">
                        <div class="card p-3 text-center" style="border-top: 4px solid #1F4E79;">
                            <h2 class="fw-bold mb-1"><?= $total_elections ?></h2>
                            <p class="text-muted mb-0" style="font-size: 13px;">Active Elections</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center" style="border-top: 4px solid #198754;">
                            <h2 class="fw-bold mb-1"><?= $total_voters ?></h2>
                            <p class="text-muted mb-0" style="font-size: 13px;">Registered Voters</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-3 text-center" style="border-top: 4px solid #FFC107;">
                            <h2 class="fw-bold mb-1"><?= $total_votes ?></h2>
                            <p class="text-muted mb-0" style="font-size: 13px;">Total Votes Cast</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it Works Section -->
        <section class="py-5" style="background-color: #F0F4F8;">
            <div class="container">
                <h4 class="fw-bold text-center mb-4">How It Works</h4>
                <div class="row g-4 text-center">
                    <div class="col-md-4">
                        <div class="card p-4">
                            <div style="font-size: 2rem; margin-bottom: 10px;">📝</div>
                            <h6 class="fw-bold">1. Register</h6>
                            <p class="text-muted mb-0" style="font-size: 13px;">Create your account with your CNIC and verify your identity.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4">
                            <div style="font-size: 2rem; margin-bottom: 10px;">🗳️</div>
                            <h6 class="fw-bold">2. Vote</h6>
                            <p class="text-muted mb-0" style="font-size: 13px;">Browse open elections and cast your vote securely online.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-4">
                            <div style="font-size: 2rem; margin-bottom: 10px;">📊</div>
                            <h6 class="fw-bold">3. Results</h6>
                            <p class="text-muted mb-0" style="font-size: 13px;">View live results and see your community's decision.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Latest Elections Section -->
        <section class="py-5">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Latest Elections</h4>
                    <a href="elections.php" class="btn btn-sm" style="background-color: #1F4E79; color: white;">View All →</a>
                </div>
                <div class="row g-3">
                    <?php foreach ($latest_elections as $election): ?>
						<div class="col-md-6">
							<div class="card p-3">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<?php if ($election['status'] === 'open'): ?>
											<span class="badge mb-1" style="background-color: #D4EDDA; color: #155724;">● Open</span>
										<?php else: ?>
											<span class="badge mb-1" style="background-color: #F8D7DA; color: #721C24;">● Closed</span>
										<?php endif; ?>
										<h6 class="fw-bold mb-1"><?= htmlspecialchars($election['title']) ?></h6>
										<small class="text-muted"><?= date('M d, Y', strtotime($election['start_date'])) ?> — <?= date('M d, Y', strtotime($election['end_date'])) ?></small>
									</div>
									<?php if ($election['status'] === 'open'): ?>
										<a href="vote.php?id=<?= $election['id'] ?>" class="btn btn-sm" style="background-color: #1F4E79; color: white;">Vote Now</a>
									<?php else: ?>
										<a href="results.php?id=<?= $election['id'] ?>" class="btn btn-sm btn-secondary">Results</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>

					<?php if (empty($latest_elections)): ?>
						<div class="col-12 text-center">
							<p class="text-muted">No elections available at the moment.</p>
						</div>
					<?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
	
	<?php if (!empty($hero_elections)): ?>
		<script>
			const heroCandidates = document.querySelectorAll('.hero-candidates');
			const heroGradient = document.getElementById('heroGradient');
			let current = 0;

			function showHero(index) {
				heroGradient.style.display = 'none';
				heroCandidates.forEach((el, i) => {
					el.style.display = 'none';
					el.style.opacity = '0';
				});
				heroCandidates[index].style.display = 'flex';
				setTimeout(() => {
					heroCandidates[index].style.transition = 'opacity 0.8s ease';
					heroCandidates[index].style.opacity = '1';
				}, 50);
			}

			if (heroCandidates.length > 0) {
				showHero(0);
				setInterval(() => {
					current = (current + 1) % heroCandidates.length;
					showHero(current);
				}, 5000);
			}
		</script>
	<?php endif; ?>
</body>
</html>