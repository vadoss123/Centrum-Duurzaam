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

class RitManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->pdo;
    }

    // Haal alle ritten op
    public function getAllRitten() {
        $stmt = $this->db->query("SELECT p.id, p.ophalen_of_bezorgen, p.afspraak_op, k.naam, a.naam as artikel 
                                  FROM planning p 
                                  JOIN klant k ON p.klant_id = k.id 
                                  JOIN artikel a ON p.artikel_id = a.id
                                  ORDER BY p.afspraak_op ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haal alle klanten op voor de dropdown
    public function getAllKlanten() {
        $stmt = $this->db->query("SELECT id, naam FROM klant");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haal alle artikelen op voor de dropdown
    public function getAllArtikelen() {
        $stmt = $this->db->query("SELECT id, naam FROM artikel");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Voeg rit toe
    public function addRit($artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op) {
        if (empty($artikel_id)) {
            throw new Exception("Artikel moet geselecteerd worden.");
        }

        // Voeg rit toe met klant_id en artikel_id
        $stmt = $this->db->prepare("INSERT INTO planning (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op]);
    }
}

// Initialiseer database en RitManager
$database = new Database();
$ritManager = new RitManager($database);

// Haal klanten en artikelen op voor de dropdowns
$klanten = $ritManager->getAllKlanten();
$artikelen = $ritManager->getAllArtikelen();

// Verwerk het toevoegen van een rit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $artikel_id = isset($_POST['artikel_id']) ? $_POST['artikel_id'] : null; // Controleer of artikel_id aanwezig is
    $klant_id = $_POST['klant_id']; // Klant wordt nu geselecteerd uit de dropdown
    $kenteken = $_POST['kenteken'];
    $ophalen_of_bezorgen = $_POST['ophalen_of_bezorgen'];
    $afspraak_op = $_POST['afspraak_op'];
    
    try {
        if ($ritManager->addRit($artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op)) {
            header("Location: rit_planning.php");  // Herlaad de pagina
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Haal ritten op
$ritten = $ritManager->getAllRitten();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Rit Planning</title>
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
    <h2>Rit Planning</h2>

    <!-- Foutmelding weergeven als het artikel niet bestaat -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= $error_message ?></p>
    <?php endif; ?>

    <!-- Knop om de modal te tonen -->
    <button onclick="openModal()">Plan rit</button>

    <!-- De Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Nieuwe rit plannen</h3>
            <form action="rit_planning.php" method="POST">
                <label for="artikel_id">Artikel:</label>
                <select name="artikel_id" id="artikel_id" required>
                    <?php foreach ($artikelen as $artikel): ?>
                        <option value="<?= htmlspecialchars($artikel['id']) ?>"><?= htmlspecialchars($artikel['naam']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="klant_id">Klant:</label>
                <select name="klant_id" id="klant_id" required>
                    <?php foreach ($klanten as $klant): ?>
                        <option value="<?= htmlspecialchars($klant['id']) ?>"><?= htmlspecialchars($klant['naam']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="kenteken">Kenteken:</label>
                <input type="text" name="kenteken" id="kenteken" required>

                <label for="ophalen_of_bezorgen">Soort rit:</label>
                <select name="ophalen_of_bezorgen" id="ophalen_of_bezorgen" required>
                    <option value="ophalen">Ophalen</option>
                    <option value="bezorgen">Bezorgen</option>
                </select>

                <label for="afspraak_op">Afspraakdatum:</label>
                <input type="datetime-local" name="afspraak_op" id="afspraak_op" required>

                <button type="submit" name="add">Plan rit</button>
            </form>
        </div>
    </div>

    <!-- Tabel om bestaande ritten te tonen -->
    <table>
        <tr>
            <th>ID</th>
            <th>Artikel</th>
            <th>Klant</th>
            <th>Kenteken</th>
            <th>Rit type</th>
            <th>Afspraak</th>
        </tr>
        <?php foreach ($ritten as $rit): ?>
        <tr>
            <td><?= htmlspecialchars($rit['id']) ?></td>
            <td><?= htmlspecialchars($rit['artikel']) ?></td>
            <td><?= htmlspecialchars($rit['naam']) ?></td>
            <td><?= htmlspecialchars($rit['kenteken']) ?></td>
            <td><?= htmlspecialchars($rit['ophalen_of_bezorgen']) ?></td>
            <td><?= htmlspecialchars($rit['afspraak_op']) ?></td>
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
