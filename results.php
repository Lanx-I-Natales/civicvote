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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">CivicVote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Results Section -->
    <section class="py-5">
        <div class="container">

            <h2 class="fw-bold">Mayor Election 2026 — Results</h2>
            <p class="text-muted">Total Votes: 847 | Turnout: 72%</p>

            <!-- Stat Cards -->
            <div class="row g-3 mb-5">
                <div class="col-md-4">
                    <div class="card text-center p-3" style="background-color: #F0F4F8;">
                        <h3 class="fw-bold">847</h3>
                        <p class="text-muted mb-0">Total Votes</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3" style="background-color: #F0F4F8;">
                        <h3 class="fw-bold">72%</h3>
                        <p class="text-muted mb-0">Turnout</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center p-3" style="background-color: #F0F4F8;">
                        <h3 class="fw-bold">3</h3>
                        <p class="text-muted mb-0">Candidates</p>
                    </div>
                </div>
            </div>

            <!-- Result Bars -->
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-bold">Bilal Khan</span>
                    <span>52%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" style="width: 52%; background-color: #1F4E79;"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-bold">Sara Riaz</span>
                    <span>31%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" style="width: 31%; background-color: #1F4E79;"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-bold">Ali Hassan</span>
                    <span>17%</span>
                </div>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" style="width: 17%; background-color: #1F4E79;"></div>
                </div>
            </div>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>