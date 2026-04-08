<?php

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'mio_test';

try {
    $pdo = new PDO('mysql:host=' . $host, $user, $pass);

    // Drop et recréer la base de données
    $pdo->exec('DROP DATABASE IF EXISTS mio_test');
    echo "✓ Base de données mio_test supprimée\n";

    $pdo->exec('CREATE DATABASE mio_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    echo "✓ Base de données mio_test recréée\n";

} catch (PDOException $e) {
    echo "✗ Erreur: " . $e->getMessage() . "\n";
    exit(1);
}
