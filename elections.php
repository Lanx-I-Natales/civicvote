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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

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

            <!-- Election Cards -->
            <div id="electionList">

                <div class="election-card card mb-3 p-3" data-status="open">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">Mayor Election 2026</h5>
                            <p class="text-muted mb-0">3 Candidates</p>
                            <small class="text-muted">Jan 01, 2026 — Mar 31, 2026</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="vote.php" class="btn btn-sm" style="background-color: #1F4E79; color: white;">Vote</a>
                            <span class="badge bg-success">Open</span>
                        </div>
                    </div>
                </div>

                <div class="election-card card mb-3 p-3" data-status="closed">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">Park Renovation Poll</h5>
                            <p class="text-muted mb-0">2 Options</p>
                            <small class="text-muted">Dec 01, 2025 — Dec 31, 2025</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="results.php" class="btn btn-sm btn-secondary">Results</a>
                            <span class="badge bg-danger">Closed</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>