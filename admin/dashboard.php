<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $election_id = $_POST['election_id'];
    $action = $_POST['action'];

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM elections WHERE id = ?");
        $stmt->execute([$election_id]);
    } elseif ($action === 'publish') {
        $stmt = $pdo->prepare("UPDATE elections SET status = 'open' WHERE id = ?");
        $stmt->execute([$election_id]);
    } elseif ($action === 'close') {
        $close_reason = trim($_POST['close_reason']);
        $stmt = $pdo->prepare("UPDATE elections SET status = 'closed', close_reason = ? WHERE id = ?");
        $stmt->execute([$close_reason, $election_id]);
    }

    header("Location: dashboard.php");
    exit();
}

// Fetch stats
$total_elections = $pdo->query("SELECT COUNT(*) FROM elections")->fetchColumn();
$total_voters = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'voter'")->fetchColumn();
$total_votes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();

// Fetch all elections
$elections = $pdo->query("
    SELECT * FROM elections 
    ORDER BY 
        CASE status 
            WHEN 'draft' THEN 1 
            WHEN 'open' THEN 2 
            WHEN 'closed' THEN 3 
        END,
        created_at DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <section class="py-4">
            <div class="container">

                <h2 class="fw-bold mb-4">Admin Dashboard</h2>
                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card p-3" style="border-left: 5px solid #1F4E79;">
                            <div>
                                <p class="text-muted mb-1" style="font-size: 13px;">Total Elections</p>
                                <h3 class="fw-bold mb-0"><?= $total_elections ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3" style="border-left: 5px solid #198754;">
                            <div>
                                <p class="text-muted mb-1" style="font-size: 13px;">Registered Voters</p>
                                <h3 class="fw-bold mb-0"><?= $total_voters ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3" style="border-left: 5px solid #FFC107;">
                            <div>
                                <p class="text-muted mb-1" style="font-size: 13px;">Total Votes</p>
                                <h3 class="fw-bold mb-0"><?= $total_votes ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add New Election Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Elections</h5>
                    <a href="manage.php" class="btn" style="background-color: #1F4E79; color: white;">+ Add New Election</a>
                </div>

                <!-- Elections Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background-color: #1F4E79; color: white;">
                            <tr>
                                <th class="py-3 ps-3">Election Name</th>
                                <th class="py-3">Status</th>
                                <th class="py-3">Candidates</th>
                                <th class="py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($elections as $election): ?>
								<tr>
									<td class="ps-3 fw-bold"><?= htmlspecialchars($election['title']) ?></td>
									<td>
										<?php if ($election['status'] === 'draft'): ?>
											<span class="badge" style="background-color: #FFF3CD; color: #856404;">● Draft</span>
										<?php elseif ($election['status'] === 'open'): ?>
											<span class="badge" style="background-color: #D4EDDA; color: #155724;">● Open</span>
										<?php else: ?>
											<span class="badge" style="background-color: #F8D7DA; color: #721C24;">● Closed</span>
										<?php endif; ?>
									</td>
									<td>
										<?php
										$cstmt = $pdo->prepare("SELECT COUNT(*) FROM candidates WHERE election_id = ?");
										$cstmt->execute([$election['id']]);
										echo $cstmt->fetchColumn();
										?>
									</td>
									<td class="d-flex gap-2">
										<?php if ($election['status'] === 'draft'): ?>
											<a href="manage.php?id=<?= $election['id'] ?>" class="btn btn-sm" style="background-color: #1F4E79; color: white;">Edit</a>
											<form method="POST" action="dashboard.php" style="display:inline;">
												<input type="hidden" name="election_id" value="<?= $election['id'] ?>">
												<button name="action" value="delete" class="btn btn-sm btn-danger">Delete</button>
												<button name="action" value="publish" class="btn btn-sm btn-success">Publish</button>
											</form>
										<?php elseif ($election['status'] === 'open'): ?>
											<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal<?= $election['id'] ?>">Close</button>
											<div class="modal fade" id="closeModal<?= $election['id'] ?>" tabindex="-1">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title">Close Election</h5>
															<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
														</div>
														<div class="modal-body">
															<form method="POST" action="dashboard.php">
																<input type="hidden" name="election_id" value="<?= $election['id'] ?>">
																<label class="form-label">Reason for closing</label>
																<textarea name="close_reason" class="form-control" rows="3" placeholder="Enter reason..." required></textarea>
																<div class="modal-footer px-0 pb-0">
																	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
																	<button name="action" value="close" class="btn btn-danger">Confirm Close</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
										<?php else: ?>
											<a href="../results.php?id=<?= $election['id'] ?>" class="btn btn-sm btn-secondary">View Results</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>