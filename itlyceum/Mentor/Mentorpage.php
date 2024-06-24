<?php
include '../db.php';
require_once('../header.php');
session_start();

// Redirect to login if not logged in or if the user is not a mentor
if (!isset($_SESSION['id']) || $_SESSION['role'] != 4) { // Assuming role 4 is for mentors
    header("Location: /itlyceum/login/login.php");
    exit();
}

// Fetch mentor's information from the database
$id = $_SESSION['id'];
$stmt = $myDb->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$mentor = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for adding a new conversation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_conversation'])) {
    $studentId = $_POST['student_id'];
    $conversation = $_POST['conversation'];
    $date = $_POST['date'];

    $stmt = $myDb->prepare("INSERT INTO conversations (mentor_id, student_id, conversation, date) VALUES (?, ?, ?, ?)");
    $stmt->execute([$id, $studentId, $conversation, $date]);

    echo "Gesprek succesvol toegevoegd.";
}

// Handle form submission for updating an existing conversation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_conversation'])) {
    $conversationId = $_POST['conversation_id'];
    $studentId = $_POST['student_id'];
    $conversation = $_POST['conversation'];
    $date = $_POST['date'];

    $stmt = $myDb->prepare("UPDATE conversations SET student_id = ?, conversation = ?, date = ? WHERE id = ?");
    $stmt->execute([$studentId, $conversation, $date, $conversationId]);

    echo "Gesprek succesvol bijgewerkt.";
}

// Handle form submission for deleting a conversation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_conversation'])) {
    $conversationId = $_POST['conversation_id'];

    $stmt = $myDb->prepare("DELETE FROM conversations WHERE id = ?");
    $stmt->execute([$conversationId]);

    echo "Gesprek succesvol verwijderd.";
}

// Fetch existing conversations from the database
$stmt = $myDb->prepare("SELECT * FROM conversations WHERE mentor_id = ? ORDER BY date DESC");
$stmt->execute([$id]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="jumbotron mt-4">
            <h1 class="display-4">Welkom, <?php echo htmlspecialchars($mentor['username']); ?>!</h1>
            <p class="lead">Hier kunt u uw gesprekken beheren:</p>
            <hr class="my-4">
            <p><strong>ID:</strong> <?php echo htmlspecialchars($mentor['id']); ?></p>
            <p><strong>Gebruikersnaam:</strong> <?php echo htmlspecialchars($mentor['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($mentor['Email']); ?></p>
            <p><strong>Rol:</strong> Mentor</p>
        </div>

        <h3 class="mt-4">Voeg een nieuw gesprek toe</h3>
        <form action="Mentorpage.php" method="POST">
            <input type="hidden" name="add_conversation" value="1">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" required>
            </div>
            <div class="form-group">
                <label for="conversation">Gesprek</label>
                <textarea class="form-control" id="conversation" name="conversation" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="date">Datum</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary">Voeg Gesprek Toe</button>
        </form>

        <h3 class="mt-4">Bestaande gesprekken</h3>
        <?php foreach ($conversations as $entry): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Gesprek ID: <?php echo htmlspecialchars($entry['id']); ?></h5>
                    <p class="card-text">Student ID: <?php echo htmlspecialchars($entry['student_id']); ?></p>
                    <p class="card-text">Gesprek: <?php echo htmlspecialchars($entry['conversation']); ?></p>
                    <p class="card-text">Datum: <?php echo htmlspecialchars($entry['date']); ?></p>
                    
                    <!-- Edit Form -->
                    <form action="Mentorpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="conversation_id" value="<?php echo $entry['id']; ?>">
                        <input type="hidden" name="edit_conversation" value="1">
                        <div class="form-group">
                            <label for="student_id">Student ID</label>
                            <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($entry['student_id']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="conversation">Gesprek</label>
                            <textarea class="form-control" id="conversation" name="conversation" rows="3" required><?php echo htmlspecialchars($entry['conversation']); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="date">Datum</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?php echo htmlspecialchars($entry['date']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Bijwerken</button>
                    </form>

                    <!-- Delete Form -->
                    <form action="Mentorpage.php" method="POST" class="d-inline">
                        <input type="hidden" name="conversation_id" value="<?php echo $entry['id']; ?>">
                        <input type="hidden" name="delete_conversation" value="1">
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
