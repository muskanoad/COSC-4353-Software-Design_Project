<?php
require_once __DIR__ . '/../server/dbh.inc.php';

session_start();
$user = null;
$userData = null;

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    // Assuming $conn is your database connection from dbh.inc.php
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Profile Management</title>
    <link rel="stylesheet" href="ClientProfileManagement.css" />
  </head>
  
<body>
    <img src="logo.png" alt="Logo" class="logo">
    <div class="nav-container">
        <nav>
            <ul>
                <li><a href="loginhome.php">Homepage</a></li>
                <li><a href="FuelQuoteForm.php">Quote</a></li>
                <li><a href="FuelQuoteHistory.php">Quote History</a></li>
                <li><a href="homepage.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    <div class="center">
    <h1>Client Profile Management</h1>
    <form action="../server/profilemanage.inc.php" method="post">
        <div>
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" maxlength="50" value="<?php echo $userData['full_name'] ?? ''; ?>" required>
        </div>
        <div>
            <label for="address_1">Address 1:</label>
            <input type="text" id="address_1" name="address_1" maxlength="100" value="<?php echo $userData['address_1'] ?? ''; ?>" required>
        </div>
        <div>
            <label for="address_2">Address 2:</label>
            <input type="text" id="address_2" name="address_2" maxlength="100" value="<?php echo $userData['address_2'] ?? ''; ?>">
        </div>
        <div>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" maxlength="100" value="<?php echo $userData['city'] ?? ''; ?>" required>
        </div>
        <div>
            <label for="state">State:</label>
            <select id="state" name="state" required>
                <option value="">Select State</option>
                <?php
                $states = [
                    "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona",
                    "AR" => "Arkansas", "CA" => "California", "CO" => "Colorado",
                    "CT" => "Connecticut", "DE" => "Delaware", "FL" => "Florida",
                    "GA" => "Georgia", "HI" => "Hawaii", "ID" => "Idaho",
                    "IL" => "Illinois", "IN" => "Indiana", "IA" => "Iowa",
                    "KS" => "Kansas", "KY" => "Kentucky", "LA" => "Louisiana",
                    "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts",
                    "MI" => "Michigan", "MN" => "Minnesota", "MS" => "Mississippi",
                    "MO" => "Missouri", "MT" => "Montana", "NE" => "Nebraska",
                    "NV" => "Nevada", "NH" => "New Hampshire", "NJ" => "New Jersey",
                    "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina",
                    "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma",
                    "OR" => "Oregon", "PA" => "Pennsylvania", "RI" => "Rhode Island",
                    "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee",
                    "TX" => "Texas", "UT" => "Utah", "VT" => "Vermont",
                    "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia",
                    "WI" => "Wisconsin", "WY" => "Wyoming"
                ];
                foreach ($states as $abbr => $name) {
                    echo '<option value="' . $abbr . '"' . ($userData['state'] === $abbr ? ' selected' : '') . '>' . $name . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <label for="zipcode">Zipcode:</label>
            <input type="text" id="zipcode" name="zipcode" minlength="5" maxlength="9" value="<?php echo $userData['zipcode'] ?? ''; ?>" required>
        </div>
        <input type="submit" value="<?php echo $userData ? 'Submit Updated Form' : 'Submit New Form'; ?>" />
    </form>
    </div>
</body>
</html>
