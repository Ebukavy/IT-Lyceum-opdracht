<?php
include '../db.php';
require_once('../header.php'); 
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['student_id']) && !isset($_SESSION['id'])) {
    header("Location: /itlyceum/login/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom op de Homepage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom CSS styles if needed */
        .jumbotron {
            background-color: #343a40;
            color: #ffffff;
            padding: 3rem;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4">Welkom terug, <?php echo $_SESSION['name']; ?>!</h1>
            <p class="lead">Hier is wat informatie die nuttig kan zijn voor jou.</p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Rooster</h5>
                        <p class="card-text">Bekijk je rooster en je lesplanning.</p>
                        <a href="rooster.php" class="btn btn-primary">Bekijk Rooster</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Klasinformatie</h5>
                        <p class="card-text">Informatie over je klas en medestudenten.</p>
                        <a href="klassen.php" class="btn btn-primary">Bekijk Klasinformatie</a>
                    </div>
                </div>
            </div>
        </div>
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