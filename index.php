<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Home</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="elections.php">Elections</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Your Community, Your Voice</h1>
            <p class="lead mt-3">Vote online from anywhere, anytime.</p>
            <div class="mt-4">
                <a href="register.php" class="btn btn-primary btn-lg me-2">Register</a>
                <a href="login.php" class="btn btn-outline-secondary btn-lg">Login</a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section py-5">
        <div class="container">
            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <div class="card text-center shadow-sm p-3">
                        <h3 class="fw-bold">Active Elections</h3>
                        <p class="text-muted mb-0">3 Ongoing</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center shadow-sm p-3">
                        <h3 class="fw-bold">Registered Voters</h3>
                        <p class="text-muted mb-0">1,248</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/main.js"></script>
</body>
</html>