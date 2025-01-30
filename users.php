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
    <title>Gebruikersbeheer</title>
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
    <h2>Persoongegevens</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gebruikersnaam</th>
                    <th>Rollen</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
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
                        <button type="submit" name="update" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Bewerk
                        </button>
                        <a href="users.php?delete=<?= $gebruiker['id'] ?>" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Verwijder
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
