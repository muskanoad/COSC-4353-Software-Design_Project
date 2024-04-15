<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get session user
    $user = $_SESSION['user'];

    // Get form data
    $gallons_requested = $_POST['gallons_requested'];
    $delivery_date = $_POST['delivery_date'];
    $suggested_price = $_POST['suggested_price'];
    $total_price = $_POST['total_price'];
    
    // Validate required fields
    if (empty($gallons_requested) || empty($delivery_date) || empty($suggested_price) || empty($total_price)) {
        $_SESSION['error'] = 'All fields are required.';
        header("Location: ../Assignment4/fuelquoteform.php?error=1");
        exit();
    }
        // Validate field types
    if (!is_numeric($gallons_requested) || !is_numeric($suggested_price) || !is_numeric($total_price)) {
        $_SESSION['error'] = 'Numeric values are required for Gallons Requested, Suggested Price, and Total Price.';
        header("Location: ../Assignment4/fuelquoteform.php?error=1");
        exit();
    }
    try {
        require_once "dbh.inc.php";

        // Prepare SQL statement
        $query = "INSERT INTO fueldata (gallons, address, deliverydate, suggestedprice, totalprice, fuel_id) 
                  SELECT :gallons, CONCAT(clientinfo.address_1, ', ', clientinfo.city, ', ', clientinfo.state, ' ', clientinfo.zip), 
                         :deliverydate, :suggestedprice, :totalprice, users.user_id
                  FROM users
                  INNER JOIN clientinfo ON users.user_id = clientinfo.id
                  WHERE users.username = :username";

        // Bind parameters and execute query
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":gallons", $gallons_requested);
        $stmt->bindParam(":deliverydate", $delivery_date);
        $stmt->bindParam(":suggestedprice", $suggested_price);
        $stmt->bindParam(":totalprice", $total_price);
        $stmt->bindParam(":username", $user);

        if ($stmt->execute()) {
            // Data inserted successfully
            header("Location: ../Assignment4/FuelQuoteHistory.php");
            exit();
        } else {
            // Error inserting data
            $_SESSION['error'] = 'Error inserting fuel data.';
            header("Location: ../Assignment4/fuelquoteform.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        // Database error
        die("Query failed: " . $e->getMessage());
    }
} else {
    // Redirect to homepage if form is not submitted via POST method
    header("Location: ../Assignment4/homepage.php");
    exit();
}
