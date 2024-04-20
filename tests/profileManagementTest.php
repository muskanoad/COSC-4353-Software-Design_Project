<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/clientprofilemanagement.php';

class profileManagementTest extends PHPUnit\Framework\TestCase {

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
    
    public function testInjectionAttack() {
        $_POST["full_name"] = "Tim Well";
        $_POST["address_1"] = "123 Main St";
        $_POST["city"] = "Anytown";
        $_POST["state"] = "New York";
        $_POST["zipcode"] = "12345";
        
        ob_start();
        include 'clientprofilemanagement.php';
        $output = ob_get_clean();
        $this->assertStringContainsString('<button type="submit" name="update-profile"', $output, "Update button not found: $output");
    }
}
