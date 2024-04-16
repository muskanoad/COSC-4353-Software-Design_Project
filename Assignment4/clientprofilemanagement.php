<?php
require_once __DIR__ . '/../server/dbh.inc.php';

session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];

    // Fetch client information from the database if it exists
    try {
        $stmt = $pdo->prepare("SELECT * FROM clientinfo WHERE id = (SELECT user_id FROM users WHERE username = :username)");
        $stmt->bindParam(":username", $user);
        $stmt->execute();
        $clientInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
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
    <?php
        if(isset($_SESSION['error'])) {
            echo "<p style='color:red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }
    ?>
    <form action="../server/profilemanage.inc.php" method="post">
        <div>
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" maxlength="50" required value="<?php echo isset($clientInfo['Name']) ? $clientInfo['Name'] : ''; ?>">
        </div>
        <div>
            <label for="address_1">Address 1:</label>
            <input type="text" id="address_1" name="address_1" maxlength="100" required value="<?php echo isset($clientInfo['address_1']) ? $clientInfo['address_1'] : ''; ?>">
        </div>
        <div>
            <label for="address_2">Address 2:</label>
            <input type="text" id="address_2" name="address_2" maxlength="100" value="<?php echo isset($clientInfo['address_2']) ? $clientInfo['address_2'] : ''; ?>">
        </div>
        <div>
            <label for="city">City:</label>
            <input type="text" id="city" name="city" maxlength="100" required value="<?php echo isset($clientInfo['city']) ? $clientInfo['city'] : ''; ?>">
        </div>
        <div>
            <label for="state">State:</label>
            <select id="state" name="state" required>
                <option value="">Select State</option>
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
            </select>
        </div>
        <div>
            <label for="zipcode">Zipcode:</label>
            <input type="text" id="zipcode" name="zipcode" minlength="5" maxlength="9" required value="<?php echo isset($clientInfo['zip']) ? $clientInfo['zip'] : ''; ?>">       
        </div>
        <input type="submit" value="Submit" id="submitBtn" /> 
        <button type="button" onclick="editClientInfo()" style="width: 100%; height: 40px; border: 2px solid white; background: #733b3b; border-radius: 20px; font-size: 16px; color: #fff; font-weight: 700; cursor: pointer; outline: none;">Edit</button>
        <button type="submit" style="display: none; width: 100%; height: 40px; border: 2px solid #333; background: #733b3b; border-radius: 20px; font-size: 16px; color: #fff; font-weight: 700; cursor: pointer; outline: none;" id="updateBtn" name="update">Update</button>

    </form>
    </div>
    <script>
    function editClientInfo() {
        // Enable all form fields for editing
        document.getElementById("full_name").disabled = false;
        document.getElementById("address_1").disabled = false;
        document.getElementById("address_2").disabled = false;
        document.getElementById("city").disabled = false;
        document.getElementById("state").disabled = false;
        document.getElementById("zipcode").disabled = false;

        // Hide submit button, show update button
        document.getElementById("submitBtn").style.display = "none";
        document.getElementById("updateBtn").style.display = "inline";
    }
</script>
</body>
</html>
