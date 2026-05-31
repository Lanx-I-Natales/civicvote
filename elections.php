<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/auth.php';

$stmt = $pdo->query("
    SELECT * FROM elections 
    WHERE status != 'draft' 
    ORDER BY 
        CASE status 
            WHEN 'open' THEN 1 
            WHEN 'closed' THEN 2 
        END,
        created_at DESC
");
$elections = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Elections</title>
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
					<?php if (isAdmin()): ?>
						<li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Dashboard</a></li>
					<?php endif; ?>
					<?php if (isLoggedIn()): ?>
						<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
					<?php else: ?>
						<li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
						<li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
					<?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- Elections Section -->
        <section class="py-5">
            <div class="container">

                <!-- Title + Search -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0">Elections</h2>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search elections..." style="width: 250px;">
                        <select id="filterStatus" class="form-select" style="width: 150px;">
                            <option value="all">All</option>
                            <option value="open">Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                </div>

				<?php foreach ($elections as $election): ?>
					<div class="election-card card mb-3 p-3" data-status="<?= $election['status'] ?>">
						<div class="d-flex justify-content-between align-items-center">
							<div style="flex: 1;">
								<h5 class="fw-bold mb-1"><?= htmlspecialchars($election['title']) ?></h5>
								<small class="text-muted"><?= date('M d, Y', strtotime($election['start_date'])) ?> — <?= date('M d, Y', strtotime($election['end_date'])) ?></small>
							</div>
							<div style="flex: 1;" class="text-center">
								<?php if ($election['status'] === 'open'): ?>
									<span class="badge" style="background-color: #D4EDDA; color: #155724;">● Open</span>
								<?php else: ?>
									<span class="badge" style="background-color: #F8D7DA; color: #721C24;">● Closed</span>
								<?php endif; ?>
								<p class="mb-0 fw-bold">
									<?php
									$cstmt = $pdo->prepare("SELECT COUNT(*) FROM candidates WHERE election_id = ?");
									$cstmt->execute([$election['id']]);
									echo $cstmt->fetchColumn() . ' Candidates';
									?>
								</p>
							</div>
							<div style="flex: 1;" class="text-end">
								<?php if ($election['status'] === 'open'): ?>
									<a href="vote.php?id=<?= $election['id'] ?>" class="btn" style="background-color: #1F4E79; color: white;">Vote Now</a>
								<?php else: ?>
									<a href="results.php?id=<?= $election['id'] ?>" class="btn btn-secondary">View Results</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>

				<?php if (empty($elections)): ?>
					<div class="text-center py-5">
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
</body>
</html>