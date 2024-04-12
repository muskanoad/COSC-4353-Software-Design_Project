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
        // Retrieve user information from the database
        $user = get_user($pdo, $username);

        if ($user) {
            // Verify user
            $_SESSION['user'] = $user['username'];
            header("Location: ../Assignment4/loginhome.php");
         
        } else {
            // User not found, redirect back to login page with error
            $_SESSION['error'] = 'Invalid username or password.';
            header("Location: ../Assignment4/login.php?error=2");
            exit();
        }
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../Assignment4/homepage.php");
    exit();
}