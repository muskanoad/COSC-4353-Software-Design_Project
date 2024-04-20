<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/FuelQuoteForm.php';

class FuelQuoteFormTest extends PHPUnit\Framework\TestCase {

    public function testGetQuote()
    {
        $pdoMock = $this->createMock(PDO::class);
        $fuelQuoteForm = new FuelQuoteForm($pdoMock);

        $_POST['gallons_requested'] = '100';
        $_POST['delivery_date'] = '2024-04-15';
        $validOutput = $fuelQuoteForm->getQuote();
        $this->assertStringContainsString('Cost estimate', $validOutput);

        $_POST['gallons_requested'] = 'abc';
        $_POST['delivery_date'] = '2024-04-15';
        $invalidOutput = $fuelQuoteForm->getQuote();
        $this->assertStringContainsString('Please enter a numerical value for Gallons Requested.', $invalidOutput);
    }

    public function testCheckUserLocation()
    {
        global $pdo;
        $pdo = $this->createMock(PDO::class);
        $inState1 = PriceModel::checkUserLocation('testUser1');
        $this->assertTrue($inState1);
        $inState2 = PriceModel::checkUserLocation('testUser2');
        $this->assertFalse($inState2);
    }
    
    public function testGenerateHtmlOutput(){
        $gallons_requested = 100;
        $price_per_gallon = 1.47;
        $delivery_date = '2024-03-15';
        $htmlOutput = generateHtmlOutput($gallons_requested, $price_per_gallon, $delivery_date);

        $expectedHtmlOutput = '<h2>Cost estimate:</h2>' .
            '<p>Gallons Requested: 100</p>' .
            '<p>Price per Gallon: $1.47</p>' .
            '<p>Total Cost: $147.00</p>' .
            '<p>Delivery Date: 2024-03-15</p>';
        $this->assertEquals($expectedHtmlOutput, $htmlOutput);
    }

    public function testInvalidFormSubmission() {
        $_POST['gallons_requested'] = 'abc'; 
        $_POST['delivery_date'] = '2024-03-22';
        include 'FuelQuoteForm.php';
        $this->assertEquals(0, $price_per_gallon);
        $this->assertEquals(0, $gallons_requested * $price_per_gallon);
    }

    public function testZeroGallonsRequested() {
        $_POST['gallons_requested'] = 0;
        $_POST['delivery_date'] = '2024-03-20';
        include 'FuelQuoteForm.php';
        $this->assertEquals(0, $price_per_gallon);
        $this->assertEquals(0, $gallons_requested * $price_per_gallon);
    }
    
}