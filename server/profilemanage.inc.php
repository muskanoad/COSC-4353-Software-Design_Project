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
   

    try {
        require_once "dbh.inc.php";

       // correcly inputs the user information into the database
        $query = "INSERT INTO clientinfo (Name, address_1, address_2, city, state, zip, id)
          SELECT :Name, :address_1, :address_2, :city, :state, :zip, users.user_id
          FROM users
          WHERE users.username = :username";

        
        header("Location: ../Assignment4/loginhome.php");
        exit();
    } catch (PDOException $e) {
        die("Query failed: ". $e->getMessage());
    }
} else {
    header("Location: ../Assignment4/login.php");
    exit();
}