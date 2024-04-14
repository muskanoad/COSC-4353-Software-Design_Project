<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/clientprofilemanagement.php';

class profileManagementTest extends PHPUnit\Framework\TestCase {
    // Test case for empty form submission
    public function testEmptyFormSubmission() {
        $_POST["full_name"] = "";
        $_POST["address_1"] = "";
        $_POST["city"] = "";
        $_POST["state"] = "";
        $_POST["zipcode"] = "";
        
        ob_start();
        include 'clientprofilemanagement.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString('required', $output, "Empty form submission failed: $output");
    }
    
    // Test case for injection attack
    public function testInjectionAttack() {
        $_POST["full_name"] = "<script>alert('Injected');</script>";
        $_POST["address_1"] = "123 Main St";
        $_POST["city"] = "Anytown";
        $_POST["state"] = "New York";
        $_POST["zipcode"] = "12345";
        
        ob_start();
        include 'clientprofilemanagement.php';
        $output = ob_get_clean();
        
        $this->assertStringNotContainsString('<script>alert', $output, "Injection attack test failed: $output");
    }
}
