<?php
require 'db.php';


$klassen = [
    ['Klascode' => 'KlasA'],
    ['Klascode' => 'KlasB'],
    ['Klascode' => 'KlasC']
];


foreach ($klassen as $klas) {
    $sql = "INSERT INTO klas (Klascode) VALUES (?)";
    try {
        $myDb->exec($sql, [$klas['Klascode']]);
        echo "Klas " . $klas['Klascode'] . " succesvol aangemaakt.<br>";
    } catch (Exception $e) {
        echo "Fout bij het aanmaken van " . $klas['Klascode'] . ": " . $e->getMessage() . "<br>";
    }
}
?>
