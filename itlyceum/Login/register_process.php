<?php
include '../db.php';
require_once('../header.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash het wachtwoord voor veiligheid

    // Selecteer willekeurig een klas
    $stmt = $myDb->exec("SELECT ID, Klascode FROM klas ORDER BY RAND() LIMIT 1");
    $klas = $stmt->fetch(PDO::FETCH_ASSOC);
    $klasID = $klas['ID'];
    $klasNaam = $klas['Klascode'];

    // Controleer of de student al bestaat
    $checkStudent = $myDb->exec("SELECT ID FROM studenten WHERE Email = ?", [$email]);
    if ($checkStudent->rowCount() > 0) {
        die("Een student met dit emailadres bestaat al.");
    }

    // Voeg de student toe aan de database
    $sql = "INSERT INTO studenten (Naam, Email, Password, Klas) VALUES (?, ?, ?, ?)";
    $myDb->exec($sql, [$name, $email, $password, $klasID]);

    echo "Registratie succesvol! Je bent toegewezen aan klas: " . $klasNaam . " (ID: " . $klasID . ")";
} else {
    die("Ongeldig verzoek.");
}
?>