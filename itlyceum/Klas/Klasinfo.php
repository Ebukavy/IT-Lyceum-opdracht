<?php 
include '../db.php';
require_once('../header.php'); 
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klasinformatie</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Klassen Informatie</h2>
        
        <div class="row">
            <?php

            
            // Query om de klassen en bijbehorende leerlingen op te halen
            $query = "SELECT k.ID, k.Klascode, s.Naam 
                      FROM klas k
                      LEFT JOIN studenten s ON k.ID = s.Klas
                      ORDER BY k.Klascode, s.Naam";
            
            $stmt = $myDb->exec($query);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Groepeer resultaten per klas
            $klassen = [];
            foreach ($result as $row) {
                $klasID = $row['ID'];
                $klasNaam = $row['Klascode'];
                $studentNaam = $row['Naam'];
                
                if (!isset($klassen[$klasID])) {
                    $klassen[$klasID] = [
                        'Klascode' => $klasNaam,
                        'Studenten' => []
                    ];
                }
                
                // Voeg de naam van de student toe aan de betreffende klas
                if ($studentNaam) {
                    $klassen[$klasID]['Studenten'][] = $studentNaam;
                }
            }
            
            // Loop door elke klas en toon de informatie in een Bootstrap card
            foreach ($klassen as $klas) {
                echo '<div class="col-md-4">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">Klas: ' . $klas['Klascode'] . '</h5>';
                echo '<ul class="list-unstyled">';
                foreach ($klas['Studenten'] as $student) {
                    echo '<li>' . $student . '</li>';
                }
                echo '</ul>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
