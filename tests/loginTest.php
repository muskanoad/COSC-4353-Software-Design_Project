<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/login.php';

class loginTest extends PHPUnit\Framework\TestCase {
    
    public function testSuccessfulLogin() {
        $_POST['username'] = 'Max';
        $_POST['password'] = '123';
        ob_start();
        include 'login.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('Location: loginhome.php', $output);
    }

    public function testUnsuccessfulLogin() {
        $_POST['username'] = 'InvalidUser';
        $_POST['password'] = 'InvalidPassword';
        ob_start();
        include 'login.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('Invalid username or password.', $output);
    }
    public function testMissingUsernameOrPassword() {
        $_POST['password'] = 'Password';
        ob_start();
        include 'login.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('Username and password are required.', $output);
    }
}

