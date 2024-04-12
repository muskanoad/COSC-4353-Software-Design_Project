<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Client Registration</title>
    <link rel="stylesheet" href="registrationstyle.css" />
    <style>
        /* Style the eye icons within the password fields */
        .toggle-password {
          cursor: pointer;
          position: absolute;
          right: 10px;
          top: 10px;
        }
        /* Style for error message */
        .error-message {
          color: red;
          font-size: 14px;
          margin-bottom: 10px;
        }
    </style>
</head>
<body>
   
    <img src="registrationlogo.jpg" alt="Logo" class="logo">
    <div class="center">
      <h1>Client Registration</h1>
      <?php
      session_start();
      if(isset($_SESSION['error'])) {
          echo "<p style='color:red'>".$_SESSION['error']."</p>";
          unset($_SESSION['error']);
      }
      ?>
      <form action="../includes/userregister.inc.php" method="post" >
        <div class="txt_field">
          <input type="text" name="username" required />
          <span></span>
          <label>Create Username</label>
        </div>
        <div class="txt_field">
          <input type="password" id="password" name="password" required oninput="validatePasswords()" />
          <span class="toggle-password" onclick="togglePassword()">👁️</span>
          <label>Create Password</label>
        </div>
        <div class="txt_field">
          <input type="password" id="confirm_password" name="confirm_password" required oninput="validatePasswords()" />
          <span class="toggle-password" onclick="togglePassword()">👁️</span>
          <label>Confirm Password</label>
        </div>
        <div id="error-message" class="error-message" style="display: none;">Passwords do not match.</div>
        <input type="submit" value="Register" />
      </form>
    </div>


    <script>
      function togglePassword() {
        var password = document.getElementById('password');
        var confirmPassword = document.getElementById('confirm_password');
        var togglePasswords = document.getElementsByClassName('toggle-password');
        if (password.type === 'password') {
          password.type = 'text';
          confirmPassword.type = 'text';
          for(var i=0; i < togglePasswords.length; i++) {
            togglePasswords[i].textContent = '❌'; // Change to hide icon
          }
        } else {
          password.type = 'password';
          confirmPassword.type = 'password';
          for(var i=0; i < togglePasswords.length; i++) {
            togglePasswords[i].textContent = '👁️'; // Change to show icon
          }
        }
      }

      function validatePasswords() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirm_password').value;
        if (password !== confirmPassword) {
          document.getElementById('error-message').style.display = 'block';
        } else {
          document.getElementById('error-message').style.display = 'none';
        }
      }

      // Add event listeners to password fields
      document.getElementById('password').addEventListener('input', validatePasswords);
      document.getElementById('confirm_password').addEventListener('input', validatePasswords);
    </script>
</body>
</html>
