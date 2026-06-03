<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

$election = null;
$candidates = [];

// Fetch election if editing
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $election = $stmt->fetch();

    $cstmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id = ?");
    $cstmt->execute([$_GET['id']]);
    $candidates = $cstmt->fetchAll();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $action = $_POST['action'];
    $status = $action === 'publish' ? 'open' : 'draft';

    if (isset($_POST['election_id'])) {
        $stmt = $pdo->prepare("UPDATE elections SET title=?, description=?, start_date=?, end_date=?, status=? WHERE id=?");
        $stmt->execute([$title, $description, $start_date, $end_date, $status, $_POST['election_id']]);

        $stmt = $pdo->prepare("DELETE FROM candidates WHERE election_id = ?");
        $stmt->execute([$_POST['election_id']]);

        foreach ($_POST['candidates'] as $index => $candidate) {
            $candidate = trim($candidate);
            if (!empty($candidate)) {
                $photo_path = null;
                if (!empty($_FILES['candidate_photos']['name'][$index])) {
                    $photo = uniqid() . '_' . basename($_FILES['candidate_photos']['name'][$index]);
                    $photo_path = 'assets/uploads/candidates/' . $photo;
                    move_uploaded_file($_FILES['candidate_photos']['tmp_name'][$index], '../' . $photo_path);
                }
                $stmt = $pdo->prepare("INSERT INTO candidates (election_id, name, photo) VALUES (?, ?, ?)");
                $stmt->execute([$_POST['election_id'], $candidate, $photo_path]);
            }
        }
        $_SESSION['success'] = "Election updated successfully!";
        header("Location: manage.php?id=" . $_POST['election_id']);
        exit();
    } else {
        $stmt = $pdo->prepare("INSERT INTO elections (title, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $start_date, $end_date, $status]);
        $election_id = $pdo->lastInsertId();

        foreach ($_POST['candidates'] as $index => $candidate) {
            $candidate = trim($candidate);
            if (!empty($candidate)) {
                $photo_path = null;
                if (!empty($_FILES['candidate_photos']['name'][$index])) {
                    $photo = uniqid() . '_' . basename($_FILES['candidate_photos']['name'][$index]);
                    $photo_path = 'assets/uploads/candidates/' . $photo;
                    move_uploaded_file($_FILES['candidate_photos']['tmp_name'][$index], '../' . $photo_path);
                }
                $stmt = $pdo->prepare("INSERT INTO candidates (election_id, name, photo) VALUES (?, ?, ?)");
                $stmt->execute([$election_id, $candidate, $photo_path]);
            }
        }
        $_SESSION['success'] = $status === 'open' ? "Election published successfully!" : "Election saved as draft!";
        header("Location: dashboard.php");
        exit();
    }
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
$success = isset($_SESSION['success']) ? $_SESSION['success'] : null;
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Manage Elections</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

    <?php $base = '../'; $current = 'dashboard'; include '../includes/navbar.php'; ?>

    <main>
        <section class="py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7">

                        <h2 class="fw-bold mb-4"><?= $election ? 'Edit Election' : 'Add New Election' ?></h2>

                        <form action="manage.php" method="POST" enctype="multipart/form-data" novalidate>
                            <?php if ($election): ?>
                                <input type="hidden" name="election_id" value="<?= $election['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Election Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter election title" value="<?= $election ? htmlspecialchars($election['title']) : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Enter election description" required><?= $election ? htmlspecialchars($election['description']) : '' ?></textarea>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Start Date & Time</label>
                                    <input type="datetime-local" name="start_date" class="form-control" value="<?= $election ? date('Y-m-d\TH:i', strtotime($election['start_date'])) : '' ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Date & Time</label>
                                    <input type="datetime-local" name="end_date" class="form-control" value="<?= $election ? date('Y-m-d\TH:i', strtotime($election['end_date'])) : '' ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Candidates</label>
                                <div id="candidateList">
                                    <?php if (!empty($candidates)): ?>
                                        <?php foreach ($candidates as $index => $candidate): ?>
                                            <div class="mb-3 p-3 border rounded candidate-item">
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Candidate Name</label>
                                                        <input type="text" name="candidates[]" class="form-control" value="<?= htmlspecialchars($candidate['name']) ?>" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Photo</label>
                                                        <input type="file" name="candidate_photos[]" class="form-control" accept="image/*">
                                                        <?php if ($candidate['photo']): ?>
                                                            <small class="text-muted mt-1 d-block">Current: <img src="../<?= $candidate['photo'] ?>" height="30" class="rounded"></small>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-candidate w-100">Remove</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="mb-3 p-3 border rounded candidate-item">
                                            <div class="row g-2">
                                                <div class="col-md-8">
                                                    <label class="form-label">Candidate Name</label>
                                                    <input type="text" name="candidates[]" class="form-control" placeholder="Candidate 1" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Photo</label>
                                                    <input type="file" name="candidate_photos[]" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 p-3 border rounded candidate-item">
                                            <div class="row g-2">
                                                <div class="col-md-8">
                                                    <label class="form-label">Candidate Name</label>
                                                    <input type="text" name="candidates[]" class="form-control" placeholder="Candidate 2" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Photo</label>
                                                    <input type="file" name="candidate_photos[]" class="form-control" accept="image/*" required>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button type="button" id="addCandidate" class="btn btn-secondary btn-sm mt-2">+ Add Candidate</button>
                            </div>

                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <div class="d-flex gap-2 mt-4">
                                <button type="submit" name="action" value="publish" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Publish Election</button>
                                <button type="submit" name="action" value="draft" class="btn btn-lg btn-secondary">Save as Draft</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>