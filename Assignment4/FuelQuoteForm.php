<?php
require_once __DIR__ . '/../server/dbh.inc.php';

session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}
include 'PriceModel.php';

// Create an instance of the PriceModel class
$priceModel = new PriceModel(); // just a class for now

$gallons_requested = "";
$same_address = true; // Set default value for same_address
$delivery_date = "";
$price_per_gallon = 1.47; // Initialize default price per gallon random value I put


function generateHtmlOutput($gallons_requested, $same_address, $price_per_gallon, $delivery_date) {
    $htmlOutput = '';

    // Append each HTML element to the output variable
    $htmlOutput .= '<h2>Cost estimate:</h2>';
    $htmlOutput .= '<p>Gallons Requested: ' . floatval($gallons_requested). '</p>';
    $htmlOutput .= '<p>In-State? ' . ($same_address ? 'Yes' : 'No') . '</p>';
    $htmlOutput .= '<p>Price per Gallon: $' . number_format($price_per_gallon, 2) . '</p>';
    $htmlOutput .= '<p>Total Cost: $' . number_format(floatval($gallons_requested) * $price_per_gallon, 2) . '</p>';
    $htmlOutput .= '<p>Delivery Date: ' . htmlspecialchars($delivery_date) . '</p>';

    return $htmlOutput;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Quote Form</title>
    <link rel="stylesheet" href="FuelQuoteFormStyle.css">
</head>

<body>
    <img src="fuelquoteformlogo.jpg" alt="Logo" class="logo">
    <div class="nav-container">
        <nav>
            <ul>
                <li><a href="loginhome.php">Homepage</a></li>
                <li><a href="FuelQuoteHistory.php">Quote History</a></li>
                <li><a href="ClientProfileManagement.php">Manage Profile</a></li>
                <li><a href="homepage.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <h1>Fuel Quote Form</h1>
        <form id="quoteForm" method="post" action="../includes/quoteform.inc.php" >
            <div>
                <label>Gallons Requested:</label>
                <input type="text" name="gallons_requested" id="gallons_requested" value="<?php echo htmlspecialchars($gallons_requested); ?>" required>
            </div>
            <div>
            <?php
            try {
                // require_once "../includes/dbh.inc.php"; // file with database connection

                // Prepare SQL statement to fetch the address based on the username
                $stmt = $pdo->prepare("SELECT address_1, city, state, zip FROM clientinfo INNER JOIN users ON clientinfo.id = users.user_id WHERE users.username = :username");
                $stmt->bindParam(":username", $_SESSION['user']);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                // Check if address is found in the database
                if ($row) {
                    // Print the address
                    echo '<p>' . htmlspecialchars($row['address_1']) . '<br>' . 
                        htmlspecialchars($row['city']) . ', ' . 
                        htmlspecialchars($row['state']) . ' ' . 
                        htmlspecialchars($row['zip']) . '</p>';
                } else {
                    // Handle case when address is not found
                    echo '<p>Address not found</p>';
                }
            } catch (PDOException $e) {
                // Handle database error
                echo "Error: " . $e->getMessage();
            }
            ?>
                <input type="checkbox" name="same_address" id="same_address" <?php if ($same_address) echo 'checked'; ?>>
            </div>
            <div>
                <label>Delivery Date:</label>
                <input type="date" name="delivery_date" id="delivery_date" value="<?php echo htmlspecialchars($delivery_date); ?>" required>
            </div>
            <input type="hidden" name="suggested_price" id="suggested_price">
            <input type="hidden" name="total_price" id="total_price">
            <button type="button" onclick="getQuote()">Get Quote</button>
            <button type="submit">Submit</button>
        </form>
        <div id="cost_estimate">
            <?php
            // Output user-entered data
            $htmlOutput = generateHtmlOutput($gallons_requested, $same_address, $price_per_gallon, $delivery_date);
            echo $htmlOutput; // Output the HTML
            ?>
        </div>
    </div>

    <script>
        function getQuote() {
        // Fetch user-entered data
        var gallons_requested = parseFloat(document.getElementById("gallons_requested").value);
        var same_address = document.getElementById("same_address").checked;
        var delivery_date = document.getElementById("delivery_date").value;

        var suggestedPrice = calculateSuggestedPrice(gallons_requested, same_address);

        var totalPrice = calculateTotalPrice(gallons_requested, suggestedPrice);

        // Update HTML output with user-entered and calculated data
        var htmlOutput = '<h2>Cost estimate:</h2>';
        htmlOutput += '<p>Gallons Requested: ' + gallons_requested + '</p>';
        htmlOutput += '<p>In-State? ' + (same_address ? 'Yes' : 'No') + '</p>';
        htmlOutput += '<p>Price per Gallon: $' + suggestedPrice.toFixed(2) + '</p>';
        htmlOutput += '<p>Total Cost: $' + totalPrice.toFixed(2) + '</p>';
        htmlOutput += '<p>Delivery Date: ' + delivery_date + '</p>';

        document.getElementById("cost_estimate").innerHTML = htmlOutput;

        document.getElementById("suggested_price").value = suggestedPrice;
        document.getElementById("total_price").value = totalPrice;
    }


        function calculateSuggestedPrice() {
            var same_address = document.getElementById("same_address").checked;
            var price_per_gallon = <?php echo json_encode($price_per_gallon); ?>;
            // Your calculation logic here
            if(same_address){
                return price_per_gallon;
            }
        }

        function calculateTotalPrice() {
            var gallons_requested = document.getElementById("gallons_requested").value;
            var price_per_gallon = <?php echo json_encode($price_per_gallon); ?>;

            return gallons_requested * price_per_gallon;
        }
    </script>
</body>
</html>

