<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Homepage</title>
    <link rel="stylesheet" href="loginhome.css">
</head>
</html>

<body>
    <img src="loginhomelogo.jpg" alt="Logo" class="logo"> 
    <header>
        <h1>Fuel Quote Master</h1>
        <nav>
            <ul>
                <li><a href="FuelQuoteForm.php">Quote</a></li>
                <li><a href="ClientProfileManagement.php">Manage Profile</a></li>
                <li><a href="FuelQuoteHistory.php">Client History</a></li>
                <li><a href="homepage.php">Logout</a></li>

            </ul>
        </nav>
    </header>
    <?php
    session_start();
    if (isset($_SESSION['user'])) {
        echo "<p>Welcome back, " . htmlspecialchars($_SESSION['user']) . "!</p>";
    } else {
        header('Location: homepage.php');
        exit();
    }
    ?>
    <div class="about-us-container">
        <img src="Aboutusscreenshoot.png" alt="Fuel Quote Form" class="about-us-screenshot">
        <div class="about-us-text">
            <p>Fuel Quote Master is your reliable partner in supplying the energy for your travel. We are more than just a fuel quote provider. Established by seasoned professionals from the energy industry, we take great satisfaction in offering clear, affordable fuel quotations together with unmatched customer support. Our state-of-the-art platform guarantees that, whether for personal use or extensive operations, you receive the most economical fuel pricing suited to your particular needs. Since we recognize how crucial dependability and efficiency are to your schedule, we promise on-time delivery to keep you going. Fuel Quote Master is dedicated to providing the best possible service and attention to detail to fuel your day, your career, and your life.</p>
        </div>
    </div>

</body>

</html>

