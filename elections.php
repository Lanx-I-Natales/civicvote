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
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
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

                <!-- Election Cards -->
                <div id="electionList">

                    <div class="election-card card mb-3 p-3" data-status="open">
                        <div class="d-flex justify-content-between align-items-center">
                            <div style="flex: 1;">
                                <h5 class="fw-bold mb-1">Mayor Election 2026</h5>
                                <span class="badge mb-1" style="background-color: #D4EDDA; color: #155724;">● Open</span>
                            </div>
                            <div style="flex: 1;" class="text-center">
                                <p class="mb-0 fw-bold">3 Candidates</p>
                                <small class="text-muted">May 01 — July 31, 2026</small>
                            </div>
                            <div style="flex: 1;" class="text-end">
                                <a href="vote.php" class="btn" style="background-color: #1F4E79; color: white;">Vote Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="election-card card mb-3 p-3" data-status="closed">
                        <div class="d-flex justify-content-between align-items-center">
                            <div style="flex: 1;">
                                <h5 class="fw-bold mb-1">Mayor Election 2025</h5>
                                <span class="badge mb-1" style="background-color: #F8D7DA; color: #721C24;">● Closed</span>
                            </div>
                            <div style="flex: 1;" class="text-center">
                                <p class="mb-0 fw-bold">3 Candidates</p>
                                <small class="text-muted">Jan 01 — Mar 31, 2025</small>
                            </div>
                            <div style="flex: 1;" class="text-end">
                                <a href="results.php" class="btn btn-secondary">View Results</a>
                            </div>
                        </div>
                    </div>

                    <div id="emptyState" style="display:none;" class="text-center py-5">
                        <p class="text-muted">No matching elections found.</p>
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