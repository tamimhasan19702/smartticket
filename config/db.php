<?php 


try {
    // Database connection parameters
    $dsn = 'mysql:host=localhost;dbname=support_tickets;charset=utf8';
    $username = 'root';  // MySQL username (default: root)
    $password = '';      // MySQL password (default: empty for root on localhost)
    
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password);
    
    // Set PDO attributes for better error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection errors
    die('Connection failed: ' . $e->getMessage());
}