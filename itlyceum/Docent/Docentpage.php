<?php
include '../db.php';
require_once('../header.php'); 
session_start();

// Controleer of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header("Location: /login.php");
    exit();
}

// Haal de rol van de huidige gebruiker op uit de database
$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT role_id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Controleer of de rol van de gebruiker overeenkomt met 'Docent' (role_id = 3)
if ($user['role_id'] != 3) {
    die("Geen toegang - U bent geen docent.");
}

// Als hieronder worden de toegestane acties voor een Docent uitgevoerd

// Voorbeeld: bekijk eigen gegevens
$stmt = $db->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docent - Mijn Gegevens</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Mijn Gegevens - Docent</h2>
        <p><strong>Gebruikersnaam:</strong> <?php echo $userInfo['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $userInfo['email']; ?></p>
        <a href="/logout.php" class="btn btn-danger">Uitloggen</a>
    </div>
</body>
</html>
