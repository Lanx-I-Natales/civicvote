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
                    <li class="nav-item"><a class="nav-link" href="../logout.php">Logout</a></li>
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
                                <h3 class="fw-bold mb-0">3</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3" style="border-left: 5px solid #198754;">
                            <div>
                                <p class="text-muted mb-1" style="font-size: 13px;">Registered Voters</p>
                                <h3 class="fw-bold mb-0">1,248</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3" style="border-left: 5px solid #FFC107;">
                            <div>
                                <p class="text-muted mb-1" style="font-size: 13px;">Total Votes</p>
                                <h3 class="fw-bold mb-0">847</h3>
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
                            <tr>
                                <td class="ps-3 fw-bold">Budget Priority Vote</td>
                                <td><span class="badge" style="background-color: #FFF3CD; color: #856404;">● Draft</span></td>
                                <td>4</td>
                                <td class="d-flex gap-2">
                                    <a href="manage.php" class="btn btn-sm" style="background-color: #1F4E79; color: white;">Edit</a>
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                    <button class="btn btn-sm btn-success">Publish</button>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold">Mayor Election 2026</td>
                                <td><span class="badge" style="background-color: #D4EDDA; color: #155724;">● Open</span></td>
                                <td>3</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#closeModal">Close</button>
                                    <div class="modal fade" id="closeModal" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Close Election</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label class="form-label">Reason for closing</label>
                                                    <textarea class="form-control" rows="3" placeholder="Enter reason..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-danger">Confirm Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-3 fw-bold">Mayor Election 2025</td>
                                <td><span class="badge" style="background-color: #F8D7DA; color: #721C24;">● Closed</span></td>
                                <td>3</td>
                                <td>
                                    <a href="../results.php" class="btn btn-sm btn-secondary">View Results</a>
                                </td>
                            </tr>
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