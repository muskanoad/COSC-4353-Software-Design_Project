<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Assignment4/PriceModel.php';

class PriceModelTest extends PHPUnit\Framework\TestCase {
    
    public function testGetPricePerGallonInState() {

        $priceModel = new PriceModel();
        $this->assertSame(1.47, $priceModel->getPricePerGallonInState());
    }

    public function testSetPricePerGallonInState() {
        $priceModel = new PriceModel();

        $priceModel->setPricePerGallonInState(1.60);
        $this->assertSame(1.60, $priceModel->getPricePerGallonInState());
    }

    public function testGetPricePerGallonOutOfState() {

        $priceModel = new PriceModel();
        $this->assertSame(2.67, $priceModel->getPricePerGallonOutOfState());
    }

    public function testSetPricePerGallonOutOfState() {
        $priceModel = new PriceModel();

        $priceModel->setPricePerGallonOutOfState(2.80);
        $this->assertSame(2.80, $priceModel->getPricePerGallonOutOfState());
    }
}
