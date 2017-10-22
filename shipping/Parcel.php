<?php

/**
 * Created by PhpStorm.
 * User: das
 * Date: 22/10/2017
 * Time: 11:27 AM
 */

namespace shipping;

class Parcel {
  protected $weight;
  protected $destinationCountry;
  protected $address;

  public function getWeight() {
    return $this->weight;
  }

  public function setWeight($weight) {
    $this->weight = $weight;
    return $this;
  }

  public function getDestinationCountry() {
    return $this->destinationCountry;
  }

  public function setDestinationCountry($country) {
    $this->destinationCountry = $country;
    return $this;
  }

  public function getAddress() {
    return $this->address;
  }

  public function setAddress($address) {
    $this->address = $address;
    return $this;
  }
}
