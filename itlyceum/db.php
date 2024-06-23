<?php
class DB {
    private $dbh;
    protected $stmt;

    public function __construct($db, $host = "localhost", $port = "3307", $user = "root", $pass = "") {
        try {
            $this->dbh = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function prepare($sql) {
        return $this->dbh->prepare($sql);
    }

    public function exec($sql, $placeholders = null) {
        try {
            $stmt = $this->prepare($sql);
            $stmt->execute($placeholders);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}


$myDb = new DB('itlyceum', 'localhost', 3307);
?>
