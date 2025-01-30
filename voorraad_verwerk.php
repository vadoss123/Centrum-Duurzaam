<?php
session_start(); // Start de sessie
require 'db.php'; // Laad de databaseverbinding

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Haal de POST-gegevens op
    $artikel_id = $_POST['artikel_id'];
    $aantal = $_POST['aantal'];
    $soort = $_POST['soort'];
    $type = $_POST['type'];
    $merk = $_POST['merk'];
    $locatie = $_POST['locatie_magazijn'];
    $reparatie = $_POST['reparatie'];
    $verkoop_gereed = $_POST['verkoop_gereed'];
    $prijs = $_POST['prijs'];

    // ✅ Validatie: controleer of velden leeg zijn
    if (empty($artikel_id) || empty($aantal) || empty($soort) || empty($type) || empty($merk) || empty($locatie) || empty($prijs)) {
        die("Fout: Alle velden zijn verplicht.");
    }

    // SQL-query om artikel toe te voegen aan de voorraad
    $stmt = $conn->prepare("INSERT INTO voorraad (artikel_id, aantal, soort, type, merk, locatie_magazijn, reparatie, verkoop_gereed, prijs, datum_ingvoerd) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    try {
        // Voer de query uit met de gegevens van het formulier
        $stmt->execute([$artikel_id, $aantal, $soort, $type, $merk, $locatie, $reparatie, $verkoop_gereed, $prijs]);
        
        // Redirect na succesvol toevoegen
        header("Location: voorraad.php?success=Artikel toegevoegd!");
        exit;
    } catch (PDOException $e) {
        // Foutmelding als er iets misgaat
        die("Fout bij het opslaan: " . $e->getMessage());
    }
}



$voorraad = new VoorraadVerwerk($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $artikel_id = $_POST['artikel_id'];
    $aantal = $_POST['aantal'];
    $soort = $_POST['soort'];
    $type = $_POST['type'];
    $merk = $_POST['merk'];
    $locatie = $_POST['locatie_magazijn'];
    $reparatie = $_POST['reparatie'];
    $verkoop_gereed = $_POST['verkoop_gereed'];
    $prijs = $_POST['prijs'];

    // ✅ Validatie (controle op lege velden)
    if (empty($artikel_id) || empty($aantal) || empty($soort) || empty($type) || empty($merk) || empty($locatie) || empty($prijs)) {
        die("Fout: Alle velden zijn verplicht.");
    }

    // ✅ Toevoegen aan de database
    if ($voorraad->addArtikel($artikel_id, $aantal, $soort, $type, $merk, $locatie, $reparatie, $verkoop_gereed, $prijs)) {
        header("Location: voorraad.php?success=Artikel toegevoegd!");
        exit;
    } else {
        die("Fout bij het opslaan.");
    }
}
?>
