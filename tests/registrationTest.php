<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust the path as needed
require_once __DIR__ . '/../Assignment4/registration.php';

class registrationTest extends PHPUnit\Framework\TestCase {
    
    public function testValidRegistration() {
        $_POST['username'] = 'newuser';
        $_POST['password'] = 'password123';
        $_POST['confirm_password'] = 'password123';
        session_start();
        include 'registration.php';
        $this->assertArrayHasKey('registered_users', $_SESSION);
    }

 // Test case for empty fields
    public function testEmptyFields() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['username'] = '';
        $_POST['password'] = '';
        $_POST['confirm_password'] = '';

        ob_start();
        include 'registration.php';
        $output = ob_get_clean();
        session_start();
        $this->assertArrayNotHasKey('registered_users', $_SESSION);
        $this->assertArrayHasKey('error', $_SESSION);
    }

    // Test case for mismatched passwords
    public function testMismatchedPasswords() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['username'] = 'newuser';
        $_POST['password'] = 'password123';
        $_POST['confirm_password'] = 'password456';

        ob_start();
        include 'registration.php';
        $output = ob_get_clean();
        session_start();
        $this->assertArrayNotHasKey('registered_users', $_SESSION);
        $this->assertArrayHasKey('error', $_SESSION);
    }
}

