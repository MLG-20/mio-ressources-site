<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO('mysql:host=' . $host, $user, $pass);
    $pdo->exec('CREATE DATABASE IF NOT EXISTS mio_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "✓ Base de données mio_test créée avec succès.\n";
} catch (PDOException $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
