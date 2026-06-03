<footer class="py-5 mt-5" style="background-color: #1F4E79; color: white;">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-md-4">
                <h5 class="fw-bold mb-1">
                    <span style="color: white;">Civic</span><span style="color: #90CAF9;">Vote</span>
                </h5>
                <small style="color: #AED6F1;">Community Online Voting System</small>
                <p class="mt-2" style="color: #AED6F1; font-size: 13px;">Making community voting simple, secure and accessible.</p>
            </div>

            <!-- Quick Links -->
            <div class="col-md-4 text-md-center">
                <h6 class="fw-bold mb-3" style="color: #AED6F1; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1"><a href="<?= $base ?>index.php" style="color: white; text-decoration: none; font-size: 13px;">Home</a></li>
                    <li class="mb-1"><a href="<?= $base ?>elections.php" style="color: white; text-decoration: none; font-size: 13px;">Elections</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="mb-1"><a href="<?= $base ?>admin/dashboard.php" style="color: white; text-decoration: none; font-size: 13px;">Dashboard</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="mb-1"><a href="<?= $base ?>register.php" style="color: white; text-decoration: none; font-size: 13px;">Register</a></li>
                        <li class="mb-1"><a href="<?= $base ?>login.php" style="color: white; text-decoration: none; font-size: 13px;">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Info -->
            <div class="col-md-4">
				<h6 class="fw-bold mb-3" style="color: #AED6F1; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">About</h6>
				<p style="color: #AED6F1; font-size: 13px;">CivicVote is a secure community voting platform built with PHP, MySQL and Bootstrap 5.</p>
			</div>
        </div>

        <!-- Divider -->
        <hr style="border-color: rgba(255,255,255,0.2); margin: 20px 0;">

        <!-- Bottom -->
        <div class="text-center">
            <small style="color: #AED6F1;">© 2026 CivicVote. All rights reserved.</small>
        </div>
    </div>
</footer>