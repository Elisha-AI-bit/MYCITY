<?php
/**
 * MYCITY - Configuration File
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'bslimted_mycity');
define('DB_USER', 'bslimted_mubita');
define('DB_PASS', 'Rabs@#12345'); // Default WAMP password is empty

// Site Configuration
define('SITE_NAME', 'MYCITY');
define('BASE_URL', 'http://localhost/MYCITY/');

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
