<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'if0_40266491_visionarios');
define('DB_USER', 'if0_40266491'); 
define('DB_PASS', 'judP7NX3UiG');      

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
];

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    die("Falha na conexão com o banco: " . $e->getMessage());
}

$conn = $pdo;
?>