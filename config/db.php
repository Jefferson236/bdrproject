<?php
// config/db.php
$host = 'localhost';
$dbname = 'restaurante_db';
$username = 'root';
$password = '123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Bro, hubo un error al conectar a la base de datos: " . $e->getMessage());
}
?>