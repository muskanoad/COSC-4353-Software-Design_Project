<?php
declare(strict_types= 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    try {
        require_once "dbh.inc.php";

        function get_user(object $pdo, string $username){
            $query = "SELECT * FROM users WHERE username = :username;";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result; 
        }
        
        
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../Assignment4/homepage.php");
    exit();
}