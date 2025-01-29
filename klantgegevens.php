<?php

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

class KlantManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->pdo;
    }

    // Haal alle klanten op
    public function getAllKlanten() {
        $stmt = $this->db->query("SELECT * FROM klant ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Voeg klant toe
    public function addKlant($naam, $adres, $plaats, $telefoon, $email) {
        $stmt = $this->db->prepare("INSERT INTO klant (naam, adres, plaats, telefoon, email) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$naam, $adres, $plaats, $telefoon, $email]);
    }

    // Verwijder klant
    public function deleteKlant($id) {
        $stmt = $this->db->prepare("DELETE FROM klant WHERE id = ?");
        return $stmt->execute([$id]);
    }
}

// Initialiseer database en KlantManager
$database = new Database();
$klantManager = new KlantManager($database);

// Verwerk het toevoegen van een klant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $plaats = $_POST['plaats'];
    $telefoon = $_POST['telefoon'];
    $email = $_POST['email'];
    $klantManager->addKlant($naam, $adres, $plaats, $telefoon, $email);
    header("Location: klantgegevens.php");  // Herlaad de pagina
    exit;
}

// Verwijder klant
if (isset($_GET['delete'])) {
    $klantManager->deleteKlant((int) $_GET['delete']);
    header("Location: klantgegevens.php");  // Herlaad de pagina
    exit;
}

// Haal klanten op
$klanten = $klantManager->getAllKlanten();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Klantgegevens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color:rgb(156, 176, 197);
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
            padding: 10px;
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

        /* Modal styling */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5); 
            overflow: auto; 
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- Sidebar Menu -->
<div class="sidebar">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-bars"></i> Dashboard</a></li>
        <li><a href="index.php"><i class="fas fa-bars"></i> Hoofdpagina</a></li>
        <li><a href="users.php"><i class="fas fa-bars"></i> Persoongegevens</a></li>
        <li><a href="klantgegevens.php"><i class="fas fa-bars"></i> Klantgegevens</a></li>
        <li><a href="voorraadbeheer.php"><i class="fas fa-bars"></i> Voorraadbeheer</a></li>
        <li><a href="opbrengst_verkopen.php"><i class="fas fa-bars"></i> Opbrengst Verkopen</a></li>
        <li><a href="rit_planning.php"><i class="fas fa-bars"></i> Rit Planning</a></li>
    </ul>
</div>

<div class="content">
    <h2>Klantgegevens</h2>

    <!-- Knop om de modal te tonen -->
    <button onclick="openModal()">Voeg klant toe</button>

    <!-- De Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Nieuwe klant toevoegen</h3>
            <form action="klantgegevens.php" method="POST">
                <label for="naam">Naam:</label>
                <input type="text" name="naam" id="naam" required>
                
                <label for="adres">Adres:</label>
                <input type="text" name="adres" id="adres" required>
                
                <label for="plaats">Plaats:</label>
                <input type="text" name="plaats" id="plaats" required>
                
                <label for="telefoon">Telefoonnummer:</label>
                <input type="text" name="telefoon" id="telefoon" required>
                
                <label for="email">E-mail:</label>
                <input type="email" name="email" id="email" required>
                
                <button type="submit" name="add">Voeg klant toe</button>
            </form>
        </div>
    </div>

    <!-- Tabel om bestaande klanten te tonen -->
    <table>
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Plaats</th>
            <th>Telefoon</th>
            <th>E-mail</th>
            <th>Acties</th>
        </tr>
        <?php foreach ($klanten as $klant): ?>
        <tr>
            <td><?= htmlspecialchars($klant['id']) ?></td>
            <td><?= htmlspecialchars($klant['naam']) ?></td>
            <td><?= htmlspecialchars($klant['adres']) ?></td>
            <td><?= htmlspecialchars($klant['plaats']) ?></td>
            <td><?= htmlspecialchars($klant['telefoon']) ?></td>
            <td><?= htmlspecialchars($klant['email']) ?></td>
            <td>
                <a href="klantgegevens.php?delete=<?= $klant['id'] ?>" onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">
                    <button style="background-color: red; color: white;">❌ Verwijder</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<script>
// Open de modal
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

// Sluit de modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Sluit de modal als je buiten de modal klikt
window.onclick = function(event) {
    if (event.target == document.getElementById("myModal")) {
        closeModal();
    }
}
</script>

</body>
</html>
