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
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }

        .content {
            padding: 40px 20px;
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
            background-color: #007bff;
            color: white;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .header {
            margin-bottom: 20px;
            text-align: center;
        }

        .header h2 {
            font-size: 28px;
            color: #333;
            font-weight: bold;
        }

        .back-btn {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
        }

        .back-btn a {
            font-size: 16px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn a:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="content">
    <!-- Mooie knop die naar index.php verwijst -->
    <div class="back-btn">
        <a href="index.php"><i class="fas fa-arrow-left"></i> Terug naar Dashboard</a>
    </div>

    <div class="container">
        <div class="header">
            <h2>Voorraad Artikel Overzicht</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Artikel Naam</th>
                        <th>Merk</th>
                        <th>Verkoop Gereed</th>
                        <th>Prijs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artikelen as $artikel): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($artikel['naam']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['merk']); ?></td>
                            <td><?php echo htmlspecialchars($artikel['verkoop_gereed']); ?></td>
                            <td>€<?php echo htmlspecialchars($artikel['artikel_prijs']); ?></td>
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
