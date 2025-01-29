<?php
$host = 'localhost'; // Database host
$dbname = 'duurzaam'; // Database naam
$username = 'root'; // Database gebruiker
$password = ''; // Database wachtwoord (indien nodig aanpassen)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database verbinding mislukt: " . $e->getMessage());
}
?>