<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="homepage.css">
</head>
</html>

<body>
    <img src="logo.png" alt="Logo" class="logo"> 
    <header>
        <h1>Welcome to Fuel Quote Master</h1>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="registration.php">Register</a></li>
                <!-- <li><a href="FuelQuoteForm.php">Quote</a></li>
                <li><a href="ClientProfileManagement.php">New Client</a></li> -->
            </ul>
        </nav>
    </header>
    <?php
    session_start();
    
        echo "<p><strong>\"Your premier destination for instant fuel price comparisons. <br>
        Say goodbye to uncertainty and hello to transparency with our user-friendly platform. <br>
        Start saving time and money today with Fuel Quote Master.\"</strong></p>";
    
    ?>
</body>
</html>

