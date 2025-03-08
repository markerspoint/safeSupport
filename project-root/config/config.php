<?php
define('DB_SERVER', 'localhost');   // Database host
define('DB_USERNAME', 'root');      // Database username
define('DB_PASSWORD', '');          // Database password
define('DB_DATABASE', 'safesupport'); // Database name

// Function to create and return a PDO database connection
function getDb() {
    try {
        $db = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_DATABASE, DB_USERNAME, DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
