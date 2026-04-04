<?php

$host = '127.0.0.1';
$user = 'root';
$pass = ''; // isi password MySQL kamu
$dbname = 'restaurant_db';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database '$dbname' created successfully!\n";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}