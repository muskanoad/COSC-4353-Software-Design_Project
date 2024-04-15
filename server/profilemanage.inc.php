<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $full_name = $_POST["full_name"];
    $address_1 = $_POST["address_1"];
    $address_2 = $_POST["address_2"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $zip = $_POST["zipcode"];
    $user = $_SESSION['user'];
    
    //Validations
    if (empty($full_name) || empty($address_1) || empty($city) || empty($state) || empty($zip)) {
        die("All fields are required.");
    }

    if (strlen($full_name) > 50) {
        die("Full Name must be less than 50 characters.");
    }

    if (strlen($address_1) > 100) {
        die("Address 1 must be less than 100 characters.");
    }

    if (strlen($city) > 100) {
        die("City must be less than 100 characters.");
    }

    if (strlen($zip) < 5 || strlen($zip) > 9) {
        die("Zipcode must be between 5 and 9 characters.");
    }

    try {
        require_once "dbh.inc.php";

       // correcly inputs the user information into the database
        $query = "INSERT INTO clientinfo (Name, address_1, address_2, city, state, zip, id)
          SELECT :Name, :address_1, :address_2, :city, :state, :zip, users.user_id
          FROM users
          WHERE users.username = :username";
        // storing the user entered data into the database
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":Name",$full_name);
        $stmt->bindParam(":address_1",$address_1);
        $stmt->bindParam(":address_2",$address_2);
        $stmt->bindParam(":city",$city);
        $stmt->bindParam(":state",$state);
        $stmt->bindParam(":zip",$zip);
        $stmt->bindParam(":username", $user);
        $stmt->execute();

        $pdo = null;
        $stmt = null;
        
        header("Location: ../Assignment4/loginhome.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../Assignment4/login.php");
    exit();
}
