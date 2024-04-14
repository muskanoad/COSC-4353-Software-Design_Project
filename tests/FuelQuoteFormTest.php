<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/FuelQuoteForm.php';
require_once __DIR__ . '/../Assignment4/PriceModel.php';

class FuelQuoteFormTest extends PHPUnit\Framework\TestCase {
    // Test case to simulate form submission with in-state address
    public function testInStateFormSubmission() {
        $_POST['gallons_requested'] = 100;
        $_POST['same_address'] = true;
        $_POST['delivery_date'] = '2024-03-20';
        include 'PriceModel.php';
        include 'FuelQuoteForm.php';
        $price = new PriceModel();
        $price_per_gallon = $price->getPricePerGallonInState();        
        $this->assertSame(1.47, $price_per_gallon);
        $this->assertEquals(147.00, $_POST['gallons_requested'] * $price_per_gallon);
    }

    // Test case to simulate form submission with out-of-state address
    public function testOutOfStateFormSubmission() {
        $_POST['gallons_requested'] = 150;
        $_POST['same_address'] = false;
        $_POST['delivery_date'] = '2024-03-22';
        include 'PriceModel.php';
        include 'FuelQuoteForm.php';
        $price = new PriceModel();
        $price_per_gallon = $price->getPricePerGallonOutOfState(); 
        $this->assertEquals(2.67, $price_per_gallon);
        $this->assertEquals(400.50, $_POST['gallons_requested'] * $price_per_gallon);
    }

    // Test case to verify HTML output
    public function testGenerateHtmlOutput()
    {
        // Define test data
        $gallons_requested = 100;
        $same_address = true;
        $price_per_gallon = 1.47;
        $delivery_date = '2024-03-15';

        // Generate HTML output using the function
        $htmlOutput = generateHtmlOutput($gallons_requested, $same_address, $price_per_gallon, $delivery_date);

        // Define expected HTML output
        $expectedHtmlOutput = '<h2>Cost estimate:</h2>' .
            '<p>Gallons Requested: 100</p>' .
            '<p>In-State? Yes</p>' .
            '<p>Price per Gallon: $1.47</p>' .
            '<p>Total Cost: $147.00</p>' .
            '<p>Delivery Date: 2024-03-15</p>';

        // Assert that the generated HTML output matches the expected HTML output
        $this->assertEquals($expectedHtmlOutput, $htmlOutput);
    }

    // Test case to simulate form submission with invalid data
    public function testInvalidFormSubmission() {
        $_POST['gallons_requested'] = 'abc'; // Non-numeric value
        $_POST['same_address'] = false;
        $_POST['delivery_date'] = '2024-03-22';
        include 'FuelQuoteForm.php';
        $this->assertEquals(0, $price_per_gallon);
        $this->assertEquals(0, $gallons_requested * $price_per_gallon);
    }

    // Test case to simulate form submission with zero gallons requested
    public function testZeroGallonsRequested() {
        $_POST['gallons_requested'] = 0;
        $_POST['same_address'] = true;
        $_POST['delivery_date'] = '2024-03-20';
        include 'FuelQuoteForm.php';
        $this->assertEquals(0, $price_per_gallon);
        $this->assertEquals(0, $gallons_requested * $price_per_gallon);
    }
    
}