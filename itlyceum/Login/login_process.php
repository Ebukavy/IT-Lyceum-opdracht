<?php
include '../db.php';
require_once('../header.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    
    $stmt = $myDb->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role_id']; 
        $_SESSION['name'] = $user['username']; 

        header("Location: /itlyceum/home/Homepage.php");
    } else {
        $stmt = $myDb->prepare("SELECT * FROM studenten WHERE Email = ?");
        $stmt->execute([$email]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student && password_verify($password, $student['Password'])) {
            $_SESSION['student_id'] = $student['ID'];
            $_SESSION['name'] = $student['Naam'];
            header("Location: /itlyceum/home/Homepage.php");
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }
} else {
    die("Invalid request.");
}
?>
