<?php
declare(strict_types=1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    //Validations
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
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
            // Verify user password
            if (password_verify($password, $user['pwd'])) {
                // Password is correct, set user session and redirect
                $_SESSION['user'] = $user['username'];
                header("Location: ../Assignment4/loginhome.php");
                exit();
            } else {
                // Password is incorrect, stay in the login page but output a error message onto front end
                $_SESSION['error'] = 'Invalid password.';
                header("Location: ../Assignment4/login.php");
                exit();
            }
        } else {
            // User not found, redirect back to login page with error
            $_SESSION['error'] = 'Invalid username or password.';
            header("Location: ../Assignment4/login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../Assignment4/homepage.php");
    exit();
}

