<?php
include '../db.php';
require_once('../header.php'); 
session_start();

// Redirect to login if not logged in or if the user is not a Docent
if (!isset($_SESSION['id']) || $_SESSION['role'] != 3) {
    header("Location: /itlyceum/login/login.php");
    exit();
}

// Fetch docent's information from the database
$id = $_SESSION['id'];
$stmt = $myDb->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$docent = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for updating information
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_info'])) {
    $username = $_POST['username'];
    $Email = $_POST['Email'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $docent['password']; // Update password only if a new password is provided

    $updateStmt = $myDb->prepare("UPDATE users SET username = ?, Email = ?, password = ? WHERE id = ?");
    $updateStmt->execute([$username, $Email, $password, $id]);

    // Refresh docent information after update
    $stmt->execute([$id]);
    $docent = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Informatie succesvol bijgewerkt.";
}

// Handle form submission for adding new klas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_klas'])) {
    $klascode = $_POST['klascode'];

    $addKlasStmt = $myDb->prepare("INSERT INTO klas (Klascode) VALUES (?)");
    $addKlasStmt->execute([$klascode]);

    echo "Nieuwe klas succesvol toegevoegd.";
}

// Handle form submission for updating klas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_klas'])) {
    $klasID = $_POST['klas_id'];
    $klascode = $_POST['klascode'];

    $updateKlasStmt = $myDb->prepare("UPDATE klas SET Klascode = ? WHERE ID = ?");
    $updateKlasStmt->execute([$klascode, $klasID]);

    echo "Klas succesvol bijgewerkt.";
}

// Handle form submission for deleting klas
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_klas'])) {
    $klasID = $_POST['klas_id'];

    $deleteKlasStmt = $myDb->prepare("DELETE FROM klas WHERE ID = ?");
    $deleteKlasStmt->execute([$klasID]);

    echo "Klas succesvol verwijderd.";
}

// Handle form submission for adding new student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_student'])) {
    $studentName = $_POST['student_name'];
    $studentEmail = $_POST['student_email'];
    $klasID = $_POST['klas_id'];
    $password = password_hash($_POST['student_password'], PASSWORD_BCRYPT);

    $addStudentStmt = $myDb->prepare("INSERT INTO studenten (Naam, Email, Klas, Password) VALUES (?, ?, ?, ?)");
    $addStudentStmt->execute([$studentName, $studentEmail, $klasID, $password]);

    echo "Nieuwe student succesvol toegevoegd.";
}

// Handle form submission for updating student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_student'])) {
    $studentID = $_POST['student_id'];
    $studentName = $_POST['student_name'];
    $studentEmail = $_POST['student_email'];
    $klasID = $_POST['klas_id'];
    $password = $_POST['student_password'] ? password_hash($_POST['student_password'], PASSWORD_BCRYPT) : null;

    if ($password) {
        $updateStudentStmt = $myDb->prepare("UPDATE studenten SET Naam = ?, Email = ?, Klas = ?, Password = ? WHERE ID = ?");
        $updateStudentStmt->execute([$studentName, $studentEmail, $klasID, $password, $studentID]);
    } else {
        $updateStudentStmt = $myDb->prepare("UPDATE studenten SET Naam = ?, Email = ?, Klas = ? WHERE ID = ?");
        $updateStudentStmt->execute([$studentName, $studentEmail, $klasID, $studentID]);
    }

    echo "Student succesvol bijgewerkt.";
}

// Handle form submission for deleting student
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_student'])) {
    $studentID = $_POST['student_id'];

    $deleteStudentStmt = $myDb->prepare("DELETE FROM studenten WHERE ID = ?");
    $deleteStudentStmt->execute([$studentID]);

    echo "Student succesvol verwijderd.";
}

// Fetch existing klassen from the database
$klassenStmt = $myDb->prepare("SELECT * FROM klas");
$klassenStmt->execute();
$klassen = $klassenStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch existing students from the database
$studentsStmt = $myDb->prepare("SELECT * FROM studenten");
$studentsStmt->execute();
$students = $studentsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docent Informatie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-4">Welkom, <?php echo htmlspecialchars($docent['username']); ?>!</h1>
            <p class="lead">Hier is jouw persoonlijke informatie:</p>
            <hr class="my-4">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($docent['id']); ?></p>
            <p><strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($docent['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($docent['Email']); ?></p>
            <p><strong>Rol:</strong> Docent</p>
            <!-- Add more information as needed -->

            <h3 class="mt-4">Update jouw informatie</h3>
            <form action="Docentpage.php" method="POST">
                <input type="hidden" name="update_info" value="1">
                <div class="form-group">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($docent['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" class="form-control" id="Email" name="Email" value="<?php echo htmlspecialchars($docent['Email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Nieuw wachtwoord (laat leeg om niet te wijzigen)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Bijwerken</button>
            </form>

            <h3 class="mt-4">Voeg een nieuwe klas toe</h3>
            <form action="Docentpage.php" method="POST">
                <input type="hidden" name="add_klas" value="1">
                <div class="form-group">
                    <label for="klascode">Klascode</label>
                    <input type="text" class="form-control" id="klascode" name="klascode" required>
                </div>
                <button type="submit" class="btn btn-primary">Voeg Klas Toe</button>
            </form>

            <h3 class="mt-4">Bestaande klassen</h3>
            <?php foreach ($klassen as $klas): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Klas ID: <?php echo htmlspecialchars($klas['ID']); ?></h5>
                        <p class="card-text">Klascode: <?php echo htmlspecialchars($klas['Klascode']); ?></p>
                        <form action="Docentpage.php" method="POST" class="d-inline">
                            <input type="hidden" name="klas_id" value="<?php echo $klas['ID']; ?>">
                            <input type="hidden" name="edit_klas" value="1">
                            <div class="form-group">
                                <label for="klascode">Nieuwe Klascode</label>
                                <input type="text" class="form-control" id="klascode" name="klascode" value="<?php echo htmlspecialchars($klas['Klascode']); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Bijwerken</button>
                        </form>
                        <form action="Docentpage.php" method="POST" class="d-inline">
                            <input type="hidden" name="klas_id" value="<?php echo $klas['ID']; ?>">
                            <input type="hidden" name="delete_klas" value="1">
                            <button type="submit" class="btn btn-danger">Verwijderen</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3 class="mt-4">Bestaande studenten</h3>
        <?php foreach ($students as $student): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Student ID: <?php echo htmlspecialchars($student['ID']); ?></h5>
                    <p class="card-text">Naam: <?php echo htmlspecialchars($student['Naam']); ?></p>
                    <p class="card-text">Email: <?php echo htmlspecialchars($student['Email']); ?></p>
                    <p class="card-text">Klas ID: <?php echo htmlspecialchars($student['Klas']); ?></p>
                    <form action="Docentpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="student_id" value="<?php echo $student['ID']; ?>">
                        <input type="hidden" name="edit_student" value="1">
                        <div class="form-group">
                            <label for="student_name">Nieuwe Naam</label>
                            <input type="text" class="form-control" id="student_name" name="student_name" value="<?php echo htmlspecialchars($student['Naam']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="student_email">Nieuwe Email</label>
                            <input type="email" class="form-control" id="student_email" name="student_email" value="<?php echo htmlspecialchars($student['Email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="klas_id">Nieuwe Klas ID</label>
                            <input type="text" class="form-control" id="klas_id" name="klas_id" value="<?php echo htmlspecialchars($student['Klas']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="student_password">Nieuw wachtwoord (laat leeg om niet te wijzigen)</label>
                            <input type="password" class="form-control" id="student_password" name="student_password">
                        </div>
                        <button type="submit" class="btn btn-primary">Bijwerken</button>
                    </form>
                    <form action="Docentpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="student_id" value="<?php echo $student['ID']; ?>">
                        <input type="hidden" name="delete_student" value="1">
                        <button type="submit" class="btn btn-danger">Verwijderen</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <h3 class="mt-4">Voeg een nieuwe student toe</h3>
        <form action="Docentpage.php" method="POST">
            <input type="hidden" name="add_student" value="1">
            <div class="form-group">
                <label for="student_name">Naam</label>
                <input type="text" class="form-control" id="student_name" name="student_name" required>
            </div>
            <div class="form-group">
                <label for="student_email">Email</label>
                <input type="email" class="form-control" id="student_email" name="student_email" required>
            </div>
            <div class="form-group">
                <label for="klas_id">Klas ID</label>
                <input type="text" class="form-control" id="klas_id" name="klas_id" required>
            </div>
            <div class="form-group">
                <label for="student_password">Wachtwoord</label>
                <input type="password" class="form-control" id="student_password" name="student_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Voeg Student Toe</button>
        </form>
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
