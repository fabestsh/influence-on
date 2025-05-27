<?php
// Connect to MySQL without selecting a database
try {
    $pdo = new PDO(
        "mysql:host=localhost",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS influence_on CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created successfully or already exists.\n";

} catch (PDOException $e) {
    die("Database creation failed: " . $e->getMessage() . "\n");
}
?> 