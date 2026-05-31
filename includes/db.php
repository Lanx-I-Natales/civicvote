<?php
$host = 'localhost';
$dbname = 'civicvote';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$pdo->query("UPDATE elections SET status = 'closed' WHERE status = 'open' AND end_date < NOW()");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Face++ API
define('FACEPP_API_KEY', 'czWiqlq_XFEY-sib2PzG7EJOJJFWkUFK');
define('FACEPP_API_SECRET', '9WYMA8YXvVdqtzY1rjFNcg_QKkP5xI3_');
?>
