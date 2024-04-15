<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Login</title>
    <link rel="stylesheet" href="loginstyle.css" />
    <style>
        /* Style the eye icons within the password fields */
        .toggle-password {
          cursor: pointer;
          position: absolute;
          right: 10px;
          top: 10px;
        }
    </style>
</head>
<body>
    <img src="loginlogo.jpg" alt="Logo" class="logo">
    
    <div class="center">
      <h1>Client Login</h1>
      <?php
        // Check if error message is set in session
        if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
            echo '<p style="color: red;">' . $_SESSION['error'] . '</p>';
            // Clear the error message after displaying it
            unset($_SESSION['error']);
        }
      ?>
      <form action="../server/userlogin.inc.php" method="post" >
        <div class="txt_field">
          <input type="text" name="username" required />
          <span></span>
          <label>Client Username</label>
        </div>
        <div class="txt_field">
          <input type="password" id="password" name="password" required />
          <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
          <label>Client Password</label>
        </div>

        <input type="submit" value="Login" />
        <div class="signup_link">New Client? <a href="registration.php">Register here</a></div>
      </form>
    </div>

    <script>
      function togglePassword(fieldId) {
        var passwordField = document.getElementById(fieldId);
        if (passwordField.type === 'password') {
          passwordField.type = 'text';
        } else {
          passwordField.type = 'password';
        }
      }
      
    </script>
</body>
</html>
