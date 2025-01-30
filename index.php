<?php
class Card {
    public $title;
    public $description;
    public $buttonText;
    public $link;

    public function __construct($title, $description, $buttonText, $link) {
        $this->title = $title;
        $this->description = $description;
        $this->buttonText = $buttonText;
        $this->link = $link;
    }

    public function render() {
        echo "
        <div class='col-md-5 mb-4 d-flex justify-content-center'>
            <div class='card text-center p-4 shadow-sm rounded-4' style='width: 100%; max-width: 500px;'>
                <h4 class='fw-bold'>{$this->title}</h4>
                <p class='text-muted'>{$this->description}</p>
                <a href='{$this->link}' class='btn btn-primary rounded-pill'>{$this->buttonText}</a>
            </div>
        </div>
        ";
    }
}

// Cards aanmaken met links
$cards = [
    new Card("Kledingstukken", "Beschrijving", "Ga naar kledingstukken", "kledingstukken.php"),
    new Card("Ritten", "Beschrijving", "Ga naar ritten", "rit_planning.php"),
    new Card("Klanten", "Beschrijving", "Ga naar klanten", "klantgegevens.php"),
    new Card("Voorraad beheer", "Beschrijving", "Ga naar voorraad beheer", "voorraad.php"),
];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #007bff; /* Blauwe kleur */
        }
        .navbar a {
            color: white !important;
            font-weight: bold;
        }
        .btn-outline-light {
            border-width: 1px;
        }
        .navbar-nav {
            gap: 15px;
        }
        .navbar-brand {
            margin-right: 30px;
        }
        /* Maak de tekst van de dropdown zwart */
        .navbar-nav .dropdown-menu .dropdown-item {
            color: black !important;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Kringloop Centrum</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="rit_planning.php">Ritten</a></li>
                    <li class="nav-item"><a class="nav-link" href="voorraad.php">Voorraadbeheer</a></li>

                    <!-- Admin Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="rit_planning.php">Rit</a></li>
                            <li><a class="dropdown-item" href="voorraad.php">Voorraad</a></li>
                            <li><a class="dropdown-item" href="klantgegevens.php">Klanten</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
            <a href="login.php" class="btn btn-outline-light">Aanmelden</a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <?php foreach ($cards as $card) { $card->render(); } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
