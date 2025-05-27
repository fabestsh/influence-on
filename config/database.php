<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'influence_on');
define('DB_USER', 'root');
define('DB_PASS', '');

// Error reporting for database operations
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/database.log');
?> 