<?php
session_start();

class Database {
    private $host = 'localhost';
    private $dbname = 'duurzaam';
    private $user = 'root';
    private $pass = '';
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Databaseverbinding mislukt: " . $e->getMessage());
        }
    }
}

class UserManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->pdo;
    }

    public function login($gebruikersnaam, $wachtwoord) {
        // Haal gebruiker op uit de database met de gebruikersnaam
        $stmt = $this->db->prepare("SELECT * FROM gebruiker WHERE gebruikersnaam = ?");
        $stmt->execute([$gebruikersnaam]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
            return $user; // Return gebruiker als wachtwoord correct is
        }

        return false; // Return false als gebruiker niet gevonden of wachtwoord verkeerd is
    }
}

$database = new Database();
$userManager = new UserManager($database);

$error_message = '';

// Verwerk het login formulier
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controleer of de velden bestaan in de POST-gegevens
    $gebruikersnaam = isset($_POST['gebruikersnaam']) ? $_POST['gebruikersnaam'] : '';
    $wachtwoord = isset($_POST['wachtwoord']) ? $_POST['wachtwoord'] : '';

    if ($gebruikersnaam && $wachtwoord) {
        $user = $userManager->login($gebruikersnaam, $wachtwoord);

        if ($user) {
            // Start een sessie en bewaar gebruikersgegevens
            $_SESSION['user'] = $user;

            // Redirect naar de juiste pagina op basis van de rol
            if (strpos($user['rollen'], 'chauffeur') !== false) {
                header("Location: rit_planning_chauffeur.php"); // Chauffeur wordt doorgestuurd naar rit planning
            } else {
                header("Location: index.php"); // Andere gebruikers worden doorgestuurd naar dashboard
            }
            exit;
        } else {
            $error_message = "Ongeldige gebruikersnaam of wachtwoord.";
        }
    } else {
        $error_message = "Vul alstublieft zowel de gebruikersnaam als het wachtwoord in.";
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            border: 1px solid #ddd;
            padding: 30px;
            border-radius: 5px;
            background-color: #f9f9f9;
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #16A085;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .login-btn:hover {
            background-color: #1ABC9C;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Inloggen</h2>

    <!-- Foutmelding weergeven als er een fout is -->
    <?php if ($error_message): ?>
        <p class="error-message"><?= $error_message ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="text" name="gebruikersnaam" class="input-field" placeholder="Gebruikersnaam" required>
        <input type="password" name="wachtwoord" class="input-field" placeholder="Wachtwoord" required>
        <button type="submit" class="login-btn">Inloggen</button>
    </form>
</div>

</body>
</html>
