<?php
session_start();
require 'db.php';

// Zoekfunctionaliteit
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
}

// SQL-query voor het ophalen van voorraadgegevens, met een JOIN op de artikel tabel om de naam te krijgen
$query = "
    SELECT voorraad.id, voorraad.artikel_id, voorraad.status_id, voorraad.aantal, artikel.naam 
    FROM voorraad 
    JOIN artikel ON voorraad.artikel_id = artikel.id
    WHERE voorraad.artikel_id LIKE :search OR artikel.naam LIKE :search
";
$stmt = $conn->prepare($query);

// Bind de zoekparameter (we gebruiken % om de zoekterm in het midden van de kolom te vinden)
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->execute();

// Haal alle voorraadgegevens op
$artikelen = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraad Beheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: rgb(156, 176, 197);
            padding-top: 20px;
            position: fixed;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            padding: 15px;
        }

        .sidebar a {
            text-decoration: none;
            color: black;
            display: block;
            font-size: 16px;
            transition: 0.3s;
            padding: 10px;
        }

        .sidebar a:hover {
            background-color: #16A085;
            padding-left: 10px;
            color: white;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        button {
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
        }

        select, input {
            padding: 5px;
        }

        .form-control {
            width: 100%;
            max-width: 400px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <ul>
        <li><a href="index.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="index.php"><i class="fas fa-bars"></i> Hoofdpagina</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i> Persoongegevens</a></li>
        <li><a href="klantgegevens.php"><i class="fas fa-address-book"></i> Klantgegevens</a></li>
        <li><a href="voorraad.php"><i class="fas fa-boxes"></i> Voorraadbeheer</a></li>
        <li><a href="opbrengst_verkopen.php"><i class="fas fa-chart-line"></i> Opbrengst Verkopen</a></li>
        <li><a href="rit_planning.php"><i class="fas fa-route"></i> Rit Planning</a></li>
    </ul>
</div>

<div class="content">
    <div class="container mt-5">
        <h1>Voorraad Beheer</h1>

        <!-- Zoekformulier -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="search" class="form-label">Zoek Artikel</label>
                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Zoek op artikelnummer of omschrijving">
            </div>
            <button type="submit" class="btn btn-primary">Zoek</button>
        </form>

        <!-- Voorraad Overzicht -->
        <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Artikel ID</th>
                        <th>Artikel Naam</th>
                        <th>Aantal</th>
                        <th>Status ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Zorg ervoor dat de variabele $artikelen gevuld is met data uit de database
                    if ($artikelen) {
                        foreach ($artikelen as $item) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($item['artikel_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['naam']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['aantal']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['status_id']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Geen resultaten gevonden</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>