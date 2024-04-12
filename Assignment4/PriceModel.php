<?php
class PriceModel {
    private $price_per_gallon_inState = 1.47;
    private $price_per_gallon_outOfState = 2.67;

    // Setter methods
    public function setPricePerGallonInState($price) {
        $this->price_per_gallon_inState = $price;
    }

    public function setPricePerGallonOutOfState($price) {
        $this->price_per_gallon_outOfState = $price;
    }

    // Getter methods
    public function getPricePerGallonInState() {
        return $this->price_per_gallon_inState;
    }

    public function getPricePerGallonOutOfState() {
        return $this->price_per_gallon_outOfState;
    }
}

