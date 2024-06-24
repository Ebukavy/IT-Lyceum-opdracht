<?php
include '../db.php';
require_once('../header.php'); 
session_start();

// Redirect to login if not logged in or if the user is not a Manager
if (!isset($_SESSION['id']) || $_SESSION['role'] != 1) {
    header("Location: /itlyceum/login/login.php");
    exit();
}

// Fetch manager's information from the database
$id = $_SESSION['id'];
$stmt = $myDb->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$manager = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for adding new docent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_docent'])) {
    $username = $_POST['username'];
    $Email = $_POST['Email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $addDocentStmt = $myDb->prepare("INSERT INTO users (username, Email, password, role_id) VALUES (?, ?, ?, 3)");
    $addDocentStmt->execute([$username, $Email, $password]);

    echo "Nieuwe docent succesvol toegevoegd.";
}

// Handle form submission for updating docent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_docent'])) {
    $docentID = $_POST['docent_id'];
    $username = $_POST['username'];
    $Email = $_POST['Email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

    if ($password) {
        $updateDocentStmt = $myDb->prepare("UPDATE users SET username = ?, Email = ?, password = ? WHERE id = ?");
        $updateDocentStmt->execute([$username, $Email, $password, $docentID]);
    } else {
        $updateDocentStmt = $myDb->prepare("UPDATE users SET username = ?, Email = ? WHERE id = ?");
        $updateDocentStmt->execute([$username, $Email, $docentID]);
    }

    echo "Docent informatie succesvol bijgewerkt.";
}

// Handle form submission for deleting docent
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_docent'])) {
    $docentID = $_POST['docent_id'];

    $deleteDocentStmt = $myDb->prepare("DELETE FROM users WHERE id = ?");
    $deleteDocentStmt->execute([$docentID]);

    echo "Docent succesvol verwijderd.";
}

// Fetch existing docents from the database
$docentsStmt = $myDb->prepare("SELECT * FROM users WHERE role_id = 3");
$docentsStmt->execute();
$docents = $docentsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-4">Welkom, <?php echo htmlspecialchars($manager['username']); ?>!</h1>
            <p class="lead">Hier kunt u docenten beheren:</p>
            <hr class="my-4">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($manager['id']); ?></p>
            <p><strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($manager['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($manager['Email']); ?></p>
            <p><strong>Rol:</strong> Manager</p>
        </div>

        <h3 class="mt-4">Voeg een nieuwe docent toe</h3>
        <form action="Managerpage.php" method="POST">
            <input type="hidden" name="add_docent" value="1">
            <div class="form-group">
                <label for="username">Gebruikersnaam</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="Email">Email</label>
                <input type="Email" class="form-control" id="Email" name="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Voeg Docent Toe</button>
        </form>

        <h3 class="mt-4">Bestaande docenten</h3>
        <?php foreach ($docents as $docent): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Docent ID: <?php echo htmlspecialchars($docent['id']); ?></h5>
                    <p class="card-text">Gebruikersnaam: <?php echo htmlspecialchars($docent['username']); ?></p>
                    <p class="card-text">Email: <?php echo htmlspecialchars($docent['Email']); ?></p>
                    <form action="Managerpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="docent_id" value="<?php echo $docent['id']; ?>">
                        <input type="hidden" name="edit_docent" value="1">
                        <div class="form-group">
                            <label for="username">Nieuwe Gebruikersnaam</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($docent['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="Email">Nieuwe Email</label>
                            <input type="Email" class="form-control" id="Email" name="Email" value="<?php echo htmlspecialchars($docent['Email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Nieuw wachtwoord (laat leeg om niet te wijzigen)</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary">Bijwerken</button>
                    </form>
                    <form action="Managerpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="docent_id" value="<?php echo $docent['id']; ?>">
                        <input type="hidden" name="delete_docent" value="1">
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
