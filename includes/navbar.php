<?php
$base = isset($base) ? $base : '';
$current = isset($current) ? $current : '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark-blue sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= $base ?>index.php">
            <span class="brand-civic">Civic</span><span class="brand-vote">Vote</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'home' ? 'active' : '' ?>" href="<?= $base ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current === 'elections' ? 'active' : '' ?>" href="<?= $base ?>elections.php">Elections</a>
                </li>
                <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current === 'dashboard' ? 'active' : '' ?>" href="<?= $base ?>admin/dashboard.php">Dashboard</a>
                    </li>
                <?php endif; ?>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $base ?>logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current === 'login' ? 'active' : '' ?>" href="<?= $base ?>login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current === 'register' ? 'active' : '' ?>" href="<?= $base ?>register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>