<?php
include '../db.php';
require_once('../header.php');
session_start();

// Redirect to login if not logged in or if the user is not a student
if (!isset($_SESSION['student_id'])) {
    header("Location: /itlyceum/login/login.php");
    exit();
}

// Fetch student's class code from the session data
$studentId = $_SESSION['student_id'];

// Assume the user's class code is stored in the users table
$stmt = $myDb->prepare("SELECT Klascode FROM klas WHERE id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
$klascode = $student['Klascode'];

// Fetch the student's schedule from the database
$stmt = $myDb->prepare("SELECT * FROM rooster WHERE Klascode = ?");
$stmt->execute([$klascode]);
$schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Rooster</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .jumbotron {
            background-color: #343a40;
            color: #ffffff;
            padding: 3rem;
            margin-top: 20px;
        }
        .table thead th {
            background-color: #343a40;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-4">Mijn Rooster</h1>
            <p class="lead">Hier kunt u uw rooster bekijken:</p>
        </div>

        <?php if (count($schedule) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Klascode</th>
                        <th scope="col">Vak</th>
                        <th scope="col">Datum</th>
                        <th scope="col">Tijd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schedule as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['Klascode']); ?></td>
                            <td><?php echo htmlspecialchars($entry['Vak']); ?></td>
                            <td><?php echo htmlspecialchars($entry['Datum']); ?></td>
                            <td><?php echo htmlspecialchars($entry['Tijd']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                Er zijn geen roostergegevens beschikbaar.
            </div>
        <?php endif; ?>
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
