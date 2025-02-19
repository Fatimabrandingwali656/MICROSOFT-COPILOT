<?php
$host = "localhost";
$dbname = "dbefxglrfvlsnj";
$username = "u2nn5be4jtjwn";
$password = "8wuu1kjnftng";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
