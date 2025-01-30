<?php
session_start();
require 'db.php';

// Verkrijg de artikelen
$stmt = $conn->query("SELECT * FROM artikel");
$artikelen = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraadbeheer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
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
        <h2>Voorraad Artikel Overzicht</h2>
        <!-- Toegevoegde knop die naar voorraad_invoer.php verwijst -->
        <a href="voorraad_invoer.php" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Voeg Artikel In
        </a>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Artikel Naam</th>
                        <th>Soort</th>
                        <th>Type</th>
                        <th>Merk</th>
                        <th>Verkoop Gereed</th>
                        <th>Prijs</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artikelen as $artikel): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($artikel['naam']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['soort']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['type']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['merk']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['verkoop_gereed']); ?></td>
                            <td>€<?php echo htmlspecialchars($artikel['artikel_prijs']); ?></td>
                            <td>
                                <a href="bewerk_voorraad.php?edit=<?php echo $artikel['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Bewerk
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
