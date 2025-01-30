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

class UserManager {
    private $db;

    public function __construct(Database $database) {
        $this->db = $database->pdo;
    }

    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM gebruiker ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUser($id, $gebruikersnaam, $rollen) {
        $stmt = $this->db->prepare("UPDATE gebruiker SET gebruikersnaam = ?, rollen = ? WHERE id = ?");
        return $stmt->execute([$gebruikersnaam, $rollen, $id]);
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM gebruiker WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAvailableRoles() {
        return ['Admin', 'magazijnmederwerker', 'winkelpersoneel', 'chaffeur'];
    }
}

// Initialiseer database en UserManager
$database = new Database();
$userManager = new UserManager($database);

// Verwijder gebruiker
if (isset($_GET['delete'])) {
    $userManager->deleteUser((int) $_GET['delete']);
    header("Location: users.php");
    exit;
}

// Bewerk gebruiker
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $userManager->updateUser((int) $_POST['id'], $_POST['gebruikersnaam'], $_POST['rollen']);
    header("Location: users.php");
    exit;
}

// Haal gebruikers op
$gebruikers = $userManager->getAllUsers();
$rollen = $userManager->getAvailableRoles();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <title>Gebruikersbeheer</title>
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
    color: black; /* Verander tekstkleur naar zwart */
    display: block;
    font-size: 16px;
    transition: 0.3s;
    padding: 10px;
}

.sidebar a:hover {
    background-color: #16A085;
    padding-left: 10px;
    color: white; /* Optioneel: verander tekstkleur bij hover naar wit */
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

    </style>
</head>
<body>

<!-- Sidebar Menu -->
<div class="sidebar">
    <ul>
        <li><a href="index.php"><i class="fas fa-bars"></i> Dashboard</a></li>
        <li><a href="index.php"><i class="fas fa-bars"></i> Hoofdpagina</a></li>
        <li><a href="users.php"><i class="fas fa-bars"></i> Persoongegevens</a></li>
        <li><a href="klantgegevens.php"><i class="fas fa-bars"></i> Klantgegevens</a></li>
        <li><a href="voorraad.php"><i class="fas fa-bars"></i> Voorraadbeheer</a></li>
        <li><a href="opbrengst_verkopen.php"><i class="fas fa-bars"></i> Opbrengst Verkopen</a></li>
        <li><a href="rit_planning.php"><i class="fas fa-bars"></i> Rit Planning</a></li>
    </ul>
</div>


<div class="content">
    <h2>persoongegevens</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Gebruikersnaam</th>
            <th>Rollen</th>
            <th>Acties</th>
        </tr>
        <?php foreach ($gebruikers as $gebruiker): ?>
        <tr>
            <td><?= htmlspecialchars($gebruiker['id']) ?></td>
            <td>
                <form action="users.php" method="post" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $gebruiker['id'] ?>">
                    <input type="text" name="gebruikersnaam" value="<?= htmlspecialchars($gebruiker['gebruikersnaam']) ?>" required>
            </td>
            <td>
                    <select name="rollen">
                        <?php foreach ($rollen as $rol): ?>
                            <option value="<?= $rol ?>" <?= ($gebruiker['rollen'] == $rol) ? 'selected' : '' ?>>
                                <?= $rol ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
            </td>
            <td>
                    <button type="submit" name="update">✏️ Bewerk</button>
                </form>
                <a href="users.php?delete=<?= $gebruiker['id'] ?>" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                    <button style="background-color: red; color: white;">❌ Verwijder</button>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>

