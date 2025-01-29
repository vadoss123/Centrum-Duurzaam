<?php
class Card {
    public $title;
    public $description;
    public $buttonText;

    public function __construct($title, $description, $buttonText) {
        $this->title = $title;
        $this->description = $description;
        $this->buttonText = $buttonText;
    }

    public function render() {
        echo "
        <div class='col-md-5 mb-4 d-flex justify-content-center'>
            <div class='card text-center p-4 shadow-sm rounded-4' style='width: 100%; max-width: 500px;'>
                <h4 class='fw-bold'>{$this->title}</h4>
                <p class='text-muted'>{$this->description}</p>
                <a href='#' class='btn btn-primary rounded-pill'>{$this->buttonText}</a>
            </div>
        </div>
        ";
    }
}

// Cards aanmaken
$cards = [
    new Card("Kledingstukken", "Beschrijving", "Ga naar kledingstukken"),
    new Card("Ritten", "Beschrijving", "Ga naar ritten"),
    new Card("Klanten", "Beschrijving", "Ga naar klanten"),
    new Card("Voorraad beheer", "Beschrijving", "Ga naar voorraad beheer"),
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
    </style>
</head>
<body class="bg-light">

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Kringloop Centrum</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Ritten</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Voorraadbeheer</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Admin</a></li>
                </ul>
                <a href="#" class="btn btn-outline-light">Aanmelden</a>
            </div>
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
