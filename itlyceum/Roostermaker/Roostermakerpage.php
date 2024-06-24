<?php
include '../db.php';
require_once('../header.php');
session_start();

// Redirect to login if not logged in or if the user is not a Roostermaker
if (!isset($_SESSION['id']) || $_SESSION['role'] != 2) {
    header("Location: /itlyceum/login/login.php");
    exit();
}

// Fetch roostermaker's information from the database
$id = $_SESSION['id'];
$stmt = $myDb->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$roostermaker = $stmt->fetch(PDO::FETCH_ASSOC);

// Function to generate a schedule for the upcoming week
function generateSchedule($myDb) {
    $startDate = new DateTime();
    $startDate->modify('next Monday');
    for ($i = 0; $i < 5; $i++) { // For each day from Monday to Friday
        $date = $startDate->format('Y-m-d');
        $vak = 'Vak ' . ($i + 1); // Example vak name
        $tijd = '09:00 - 10:00'; // Example time slot
        $Klascode = 'Klascode ' . ($i + 1); // Example Klascode name

        $stmt = $myDb->prepare("INSERT INTO rooster (Klascode, Vak, Datum, Tijd) VALUES (?, ?, ?, ?)");
        $stmt->execute([$Klascode, $vak, $date, $tijd]);

        $startDate->modify('+1 day');
    }
    echo "Rooster voor de komende week succesvol gegenereerd.";
}

// Handle form submission for generating a new schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_schedule'])) {
    generateSchedule($myDb);
}

// Handle form submission for updating a schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_schedule'])) {
    $roosterID = $_POST['rooster_id'];
    $Klascode = $_POST['Klascode'];
    $vak = $_POST['vak'];
    $datum = $_POST['datum'];
    $tijd = $_POST['tijd'];

    $updateStmt = $myDb->prepare("UPDATE rooster SET Klascode = ?, Vak = ?, Datum = ?, Tijd = ? WHERE ID = ?");
    $updateStmt->execute([$Klascode, $vak, $datum, $tijd, $roosterID]);

    echo "Rooster succesvol bijgewerkt.";
}

// Handle form submission for deleting a schedule
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_schedule'])) {
    $roosterID = $_POST['rooster_id'];

    $deleteStmt = $myDb->prepare("DELETE FROM rooster WHERE ID = ?");
    $deleteStmt->execute([$roosterID]);

    echo "Rooster succesvol verwijderd.";
}

// Fetch existing schedules from the database
$roosterStmt = $myDb->prepare("SELECT * FROM rooster ORDER BY Datum, Tijd");
$roosterStmt->execute();
$rooster = $roosterStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roostermaker Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-4">Welkom, <?php echo htmlspecialchars($roostermaker['username']); ?>!</h1>
            <p class="lead">Hier kunt u uw informatie beheren en roosters maken:</p>
            <hr class="my-4">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($roostermaker['id']); ?></p>
            <p><strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($roostermaker['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($roostermaker['Email']); ?></p>
            <p><strong>Rol:</strong> Roostermaker</p>
        </div>

        <h3 class="mt-4">Genereer een rooster voor de komende week</h3>
        <form action="Roostermakerpage.php" method="POST">
            <input type="hidden" name="generate_schedule" value="1">
            <button type="submit" class="btn btn-primary">Genereer Rooster</button>
        </form>

        <h3 class="mt-4">Bestaande roosters</h3>
        <?php foreach ($rooster as $entry): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Rooster ID: <?php echo htmlspecialchars($entry['ID']); ?></h5>
                    <p class="card-text">Klascode: <?php echo htmlspecialchars($entry['Klascode']); ?></p>
                    <p class="card-text">Vak: <?php echo htmlspecialchars($entry['Vak']); ?></p>
                    <p class="card-text">Datum: <?php echo htmlspecialchars($entry['Datum']); ?></p>
                    <p class="card-text">Tijd: <?php echo htmlspecialchars($entry['Tijd']); ?></p>
                    <form action="Roostermakerpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="rooster_id" value="<?php echo $entry['ID']; ?>">
                        <input type="hidden" name="edit_schedule" value="1">
                        <div class="form-group">
                            <label for="Klascode">Nieuwe Klascode</label>
                            <input type="text" class="form-control" id="Klascode" name="Klascode" value="<?php echo htmlspecialchars($entry['Klascode']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="vak">Nieuwe Vak</label>
                            <input type="text" class="form-control" id="vak" name="vak" value="<?php echo htmlspecialchars($entry['Vak']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="datum">Nieuwe Datum</label>
                            <input type="date" class="form-control" id="datum" name="datum" value="<?php echo htmlspecialchars($entry['Datum']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tijd">Nieuwe Tijd</label>
                            <input type="time" class="form-control" id="tijd" name="tijd" value="<?php echo htmlspecialchars($entry['Tijd']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bijwerken</button>
                    </form>
                    <form action="Roostermakerpage.php" method="POST" class="d-inline ml-2">
                        <input type="hidden" name="rooster_id" value="<?php echo $entry['ID']; ?>">
                        <input type="hidden" name="delete_schedule" value="1">
                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-center text-white py-3">
        <p>&copy; 2024 My School. Alle rechten voorbehouden.</p>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
