<?php
require_once __DIR__ . '/../server/dbh.inc.php';

session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Quote History</title>
    <link rel="stylesheet" type="text/css" href="FuelQuoteHistory.css">
</head>
<body>
    <img src="loginlogo.jpg" alt="Logo" class="logo"> 
   
    <h1 >Fuel Quote History</h1>
    <div class="nav-container">
        <nav>
            <ul>
                <li><a href="loginhome.php">Homepage</a></li>
                <li><a href="FuelQuoteForm.php">Quote</a></li>
                <li><a href="ClientProfileManagement.php">Manage Profile</a></li>
                <li><a href="homepage.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <table>
        <thead>
            <tr>
              
                <th>Client Name</th>
                <th>Client Address</th>
                <th>Gallons Requested</th>
                <th>Delivery Date</th>
                <th>Suggested Price</th>
                <th>Total Amount Due</th>
            </tr>
        </thead>
        <tbody>
        <?php
            try {
                // require_once "/../includes/dbh.inc.php"; // File with database connection
                
                // Prepare SQL statement to fetch fuel quote history based on user ID
                $stmt = $pdo->prepare("SELECT clientinfo.Name, fueldata.address, fueldata.gallons, fueldata.deliverydate, fueldata.suggestedprice, fueldata.totalprice FROM fueldata INNER JOIN clientinfo ON fueldata.fuel_id = clientinfo.id INNER JOIN users ON fueldata.fuel_id = users.user_id WHERE users.username = :username");
                $stmt->bindParam(":username", $user);
                $stmt->execute();
                
                // Fetch and display the fuel quote history rows
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    
                    echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['gallons']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['deliverydate']) . "</td>";
                    echo "<td>$" . number_format($row['suggestedprice'], 2) . "</td>";
                    echo "<td>$" . number_format($row['totalprice'], 2) . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                // Handle database error
                echo "Error: " . $e->getMessage();
            }
        ?>   
            
        </tbody>
    </table>
    
</body>
</html>
