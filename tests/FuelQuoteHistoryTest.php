<?php
require_once __DIR__ . '/../vendor/autoload.php';

class FuelQuoteHistoryTest extends PHPUnit\Framework\TestCase {
    public function testFuelQuoteHistoryPage()
    {
        
        ob_start();

        $sampleData = [
            ['Name' => 'John Doe', 'address' => '123 Main St', 'gallons' => 100, 'deliverydate' => '2024-04-10', 'suggestedprice' => 2.50, 'totalprice' => 250.00],
            ['Name' => 'Jane Smith', 'address' => '456 Elm St', 'gallons' => 150, 'deliverydate' => '2024-04-11', 'suggestedprice' => 2.75, 'totalprice' => 412.50],
        ];

        $pdoMock = $this->getPdoMock($sampleData);
        $GLOBALS['pdo'] = $pdoMock;

        require_once __DIR__ . '/../Assignment4/FuelQuoteHistory.php';

        $output = ob_get_clean();

        $this->assertStringContainsString('<th>Client Name</th>', $output);
        $this->assertStringContainsString('<th>Client Address</th>', $output);
        $this->assertStringContainsString('<th>Gallons Requested</th>', $output);
        $this->assertStringContainsString('<th>Delivery Date</th>', $output);
        $this->assertStringContainsString('<th>Suggested Price</th>', $output);
        $this->assertStringContainsString('<th>Total Amount Due</th>', $output);

        foreach ($sampleData as $data) {
            foreach ($data as $value) {
                $this->assertStringContainsString((string) $value, $output);
            }
        }
    }
}
