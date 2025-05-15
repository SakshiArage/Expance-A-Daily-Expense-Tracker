<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'Sakshi@123');  // MySQL root password
define('DB_NAME', 'expense_db');

try {
    // First connect without database
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Now connect to the database
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    
    // Create tables if they don't exist
    $sql = file_get_contents(__DIR__ . "/../database.sql");
    $conn->exec($sql);
    
    // Verify tables exist
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        throw new PDOException("No tables were created in the database.");
    }
    
} catch(PDOException $e) {
    echo "<b>Connection Error Details:</b><br>";
    echo "Error message: " . $e->getMessage() . "<br>";
    echo "Error code: " . $e->getCode() . "<br>";
    echo "<br>Debug information:<br>";
    echo "1. Host: " . DB_HOST . "<br>";
    echo "2. Database: " . DB_NAME . "<br>";
    echo "3. Username: " . DB_USER . "<br>";
    die();
}
?> 