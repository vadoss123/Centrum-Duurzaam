<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
    $rollen = $_POST['rollen'];

    $stmt = $conn->prepare("INSERT INTO gebruiker (gebruikersnaam, wachtwoord, rollen, is_geverifieerd) VALUES (?, ?, ?, 0)");
    
    try {
        $stmt->execute([$gebruikersnaam, $wachtwoord, $rollen]);
        header('Location: login.php');
        exit();
    } catch (PDOException $e) {
        $error = "Gebruikersnaam bestaat al.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Registreren</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Gebruikersnaam</label>
                <input type="text" name="gebruikersnaam" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Wachtwoord</label>
                <input type="password" name="wachtwoord" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Rol</label><select name="rollen" class="form-control">
                    <option value="directie">Directie</option>
                    <option value="magazijnmedewerker">Magazijnmedewerker</option>
                    <option value="winkelpersoneel">Winkelpersoneel</option>
                    <option value="chauffeur">Chauffeur</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registreren</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
