<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicVote - Cast Vote</title>
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

    <!-- Vote Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <h2 class="fw-bold">Mayor Election 2026</h2>
                    <p class="text-muted">Select one candidate</p>

                    <form action="vote.php" method="POST">

                       <label class="vote-card card mb-3 p-3 w-100" for="c1">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="candidate" id="c1" value="1" required>
                                <div>
                                    <span class="fw-bold">Sara Riaz</span><br>
                                    <small class="text-muted">Community Party</small>
                                </div>
                            </div>
                        </label>

                        <label class="vote-card card mb-3 p-3 w-100" for="c2">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="candidate" id="c2" value="2">
                                <div>
                                    <span class="fw-bold">Ali Hassan</span><br>
                                    <small class="text-muted">Independent</small>
                                </div>
                            </div>
                        </label>

                        <label class="vote-card card mb-3 p-3 w-100" for="c3">
                            <div class="d-flex align-items-center gap-3">
                                <input type="radio" name="candidate" id="c3" value="3">
                                <div>
                                    <span class="fw-bold">Bilal Khan</span><br>
                                    <small class="text-muted">Reform Party</small>
                                </div>
                            </div>
                        </label>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-lg" style="background-color: #1F4E79; color: white;">Submit Vote</button>
                            <a href="elections.php" class="btn btn-lg btn-secondary">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>