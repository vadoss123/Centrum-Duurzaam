<?php
session_start();
require 'db.php'; // Databaseverbinding

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit();
}

// Haal de rol van de ingelogde gebruiker op
$stmt = $conn->prepare("SELECT rollen FROM gebruiker WHERE id = ?");
$stmt->execute([$_SESSION['gebruiker_id']]);
$gebruiker = $stmt->fetch();
$rol = $gebruiker['rollen'];

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Bedrijf Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="voorraad.php">Voorraadbeheer</a></li>
                    <li class="nav-item"><a class="nav-link" href="klanten.php">Klantbeheer</a></li>
                    <li class="nav-item"><a class="nav-link" href="ritplanning.php">Ritplanning</a></li>
                    <li class="nav-item"><a class="nav-link" href="rapportage.php">Rapportage</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h1>Welkom, <?php echo htmlspecialchars($rol); ?>!</h1>
        <p>Gebruik het menu om de gewenste functionaliteit te openen.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
