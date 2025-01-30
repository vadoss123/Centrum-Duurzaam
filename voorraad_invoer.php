<?php
session_start();
require 'db.php';

// Variabelen om fouten te tonen
$error = '';
$success = '';

// Invoeren van nieuw artikel
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verkrijg de formulierwaarden
    $naam = $_POST['naam'];
    $categorie_id = $_POST['categorie_id'];
    $soort = $_POST['soort'];
    $type = $_POST['type'];
    $merk = $_POST['merk'];
    $verkoop_gereed = isset($_POST['verkoop_gereed']) ? 1 : 0;
    $artikel_prijs = $_POST['artikel_prijs'];
    $datum_ingevuld = date('Y-m-d H:i:s'); // Huidige datum en tijd

    // Bereid de SQL-query voor invoeren
    $query = "INSERT INTO artikel (naam, categorie_id, soort, type, merk, verkoop_gereed, artikel_prijs, datum_ingevuld) 
              VALUES (:naam, :categorie_id, :soort, :type, :merk, :verkoop_gereed, :artikel_prijs, :datum_ingevuld)";
    
    $stmt = $conn->prepare($query);

    // Bind de waarden aan de SQL-query
    $stmt->bindParam(':naam', $naam);
    $stmt->bindParam(':categorie_id', $categorie_id);
    $stmt->bindParam(':soort', $soort);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':merk', $merk);
    $stmt->bindParam(':verkoop_gereed', $verkoop_gereed);
    $stmt->bindParam(':artikel_prijs', $artikel_prijs);
    $stmt->bindParam(':datum_ingevuld', $datum_ingevuld);

    try {
        // Voer de query uit
        $stmt->execute();
        $success = "Artikel succesvol toegevoegd!";
    } catch (PDOException $e) {
        $error = "Fout bij invoeren artikel: " . $e->getMessage();
    }
}

// Ophalen van categorieën voor de dropdown
$query = "SELECT * FROM categorie";
$stmt = $conn->prepare($query);
$stmt->execute();
$categorieën = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoer van Artikelen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .alert {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Invoer van Artikelen</h1>

        <!-- Terug Button -->
        <a href="voorraad.php" class="btn btn-secondary mb-4">Terug naar Voorraad</a>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Formulier voor het invoeren van artikelen -->
        <div class="card p-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="naam" class="form-label">Artikelnaam</label>
                    <input type="text" class="form-control" id="naam" name="naam" required>
                </div>

                <div class="mb-3">
                    <label for="categorie_id" class="form-label">Categorie</label>
                    <select class="form-select" id="categorie_id" name="categorie_id" required>
                        <?php foreach ($categorieën as $categorie): ?>
                            <option value="<?php echo $categorie['id']; ?>"><?php echo $categorie['categorie']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="soort" class="form-label">Soort</label>
                    <input type="text" class="form-control" id="soort" name="soort" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" class="form-control" id="type" name="type" required>
                </div>

                <div class="mb-3">
                    <label for="merk" class="form-label">Merk</label>
                    <input type="text" class="form-control" id="merk" name="merk" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="verkoop_gereed" name="verkoop_gereed">
                    <label class="form-check-label" for="verkoop_gereed">Verkoop gereed?</label>
                </div>

                <div class="mb-3">
                    <label for="artikel_prijs" class="form-label">Prijs (exclusief BTW)</label>
                    <input type="number" class="form-control" id="artikel_prijs" name="artikel_prijs" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-primary">Voeg Artikel Toe</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
