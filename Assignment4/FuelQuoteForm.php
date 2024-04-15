<?php
require_once __DIR__ . '/../includes/dbh.inc.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

// Define the PriceModel functionalities directly within FuelQuoteForm.php
class PriceModel
{
    private const CURRENT_PRICE = 1.50; // Current price per gallon

    public static function calculateSuggestedPrice($user, $gallonsRequested)
    {
        $inState = self::checkUserLocation($user);
        $hasHistory = self::checkRateHistory($user);
        
        $gallonsRequestedFactor = ($gallonsRequested) ? 0.02 : 0.03;
        $locationFactor = $inState ? 0.02 : 0.04;
        $rateHistoryFactor = $hasHistory ? 0.01 : 0;

        $margin = self::CURRENT_PRICE * ($locationFactor - $rateHistoryFactor + $gallonsRequestedFactor + 0.10);
        $suggestedPrice = self::CURRENT_PRICE + $margin;

        return $suggestedPrice;
    }

    public static function checkUserLocation($user)
    {
        global $pdo; // Use the database connection from the global scope

        try {
            $stmt = $pdo->prepare("SELECT state FROM clientinfo INNER JOIN users ON clientinfo.id = users.user_id WHERE users.username = :username");
            $stmt->bindParam(":username", $user);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return ($row && $row['state'] === 'TX');
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }

    public static function checkRateHistory($user)
    {
        global $pdo; // Use the database connection from the global scope

        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM fueldata INNER JOIN users ON fueldata.fuel_id = users.user_id WHERE users.username = :username");
            $stmt->bindParam(":username", $user);
            $stmt->execute();
            $rowCount = $stmt->fetchColumn();

            return ($rowCount > 0);
        } catch (PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}

$gallons_requested = "";
$delivery_date = "";
$price_per_gallon = 0;
function generateHtmlOutput($gallons_requested, $price_per_gallon, $delivery_date) {
    $htmlOutput = '';

    // Append each HTML element to the output variable
    $htmlOutput .= '<h2>Cost estimate:</h2>';
    $htmlOutput .= '<p>Gallons Requested: ' . floatval($gallons_requested). '</p>';
    $htmlOutput .= '<p>Price per Gallon: $' . number_format($price_per_gallon, 3) . '</p>';
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
        <?php
        if(isset($_SESSION['error'])) {
            echo "<p style='color:red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
        ?>
        <form id="quoteForm" method="post" action="../includes/quoteform.inc.php" >
            <div>
                <label>Gallons Requested:</label>
                <input type="text" name="gallons_requested" id="gallons_requested" value="<?php echo htmlspecialchars($gallons_requested); ?>" required>
            </div>
            <div>
            <?php
            try {
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
            $htmlOutput = generateHtmlOutput($gallons_requested, $price_per_gallon, $delivery_date);
            echo $htmlOutput; // Output the HTML
            ?>
        </div>
    </div>

    <script>
    function getQuote() {
        // Fetch user-entered data
        var gallons_requested = parseFloat(document.getElementById("gallons_requested").value);
        var delivery_date = document.getElementById("delivery_date").value;
        // Calculate suggested price using PriceModel method
        var over1000 = gallons_requested > 1000;

        var suggestedPrice = <?php echo PriceModel::calculateSuggestedPrice($user, ' + over1000 + '); ?>;

        // Calculate total price using suggested price
        var totalPrice = gallons_requested * suggestedPrice;

        // Update HTML output with user-entered and calculated data
        var htmlOutput = '<h2>Cost estimate:</h2>';
        htmlOutput += '<p>Gallons Requested: ' + gallons_requested + '</p>';
        htmlOutput += '<p>Price per Gallon: $' + suggestedPrice.toFixed(3) + '</p>';
        htmlOutput += '<p>Total Cost: $' + totalPrice.toFixed(2) + '</p>';
        htmlOutput += '<p>Delivery Date: ' + delivery_date + '</p>';

        document.getElementById("cost_estimate").innerHTML = htmlOutput;

        document.getElementById("suggested_price").value = suggestedPrice;
        document.getElementById("total_price").value = totalPrice;
    }
</script>

</body>
</html>
