<?php

namespace shipping;

class Courier {
  public $name;
  public $home_country;
  
  public function __construct($name) {
    $this->name = $name;
    return true;
  }
  
  public static function getCouriersByCountry($country) {
    // get a list of couriers with their home_country = $country
    
    // create a Courier object for each result
    
    // return an array of the results
    return $courier_list;
  }
  
  public function ship(Parcel $parcel) {
    // sends the parcel to its destination
    return true;
  }
  
  public function calculateShipping($parcel) {
    // look up the rate for the destination, we'll invent one
    $rate = 1.78;
    
    // calculate the cost
    $cost = $rate * $parcel->weight;
    return $cost;
  }
}
