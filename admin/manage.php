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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue">
        <div class="container">
            <a class="navbar-brand fw-bold" href="../index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Manage Elections Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">

                    <h2 class="fw-bold mb-4">Add New Election</h2>

                    <form action="manage.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Election Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter election title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Enter election description" required></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date & Time</label>
                                <input type="datetime-local" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date & Time</label>
                                <input type="datetime-local" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Candidates</label>
                            <div id="candidateList">
                                <div class="mb-2">
                                    <input type="text" name="candidates[]" class="form-control" placeholder="Candidate 1" required>
                                </div>
                                <div class="mb-2">
                                    <input type="text" name="candidates[]" class="form-control" placeholder="Candidate 2" required>
                                </div>
                            </div>
                            <button type="button" id="addCandidate" class="btn btn-secondary btn-sm mt-2">+ Add Candidate</button>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" name="action" value="publish" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Publish Election</button>
                            <button type="submit" name="action" value="draft" class="btn btn-lg btn-secondary">Save as Draft</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>