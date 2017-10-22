<?php

namespace shipping;

class Courier implements \Countable {
  /**
   * @var string
   *   The courier's name.
   */
  protected $name;

  /**
   * @var string
   *   The Courier's home country.
   */
  protected $home_country;

  /**
   * @var array
   *   The courier's data.
   */
  protected $data = array();

  protected $count = 0;
  protected $logfile;

  /**
   * Courier constructor.
   *
   * @param $name
   *   The name of the courier.
   */
  public function __construct($name, $country) {
    $this->name = $name;
    $this->home_country = $country;
    $this->logfile = $this->getLogFile();

    $this->data['name'] = $name;

    return TRUE;
  }

  /**
   * Courier getter method.
   *
   * @param $property
   * @return mixed
   */
  public function __get($property) {
    return $this->data[$property];
  }

  /**
   * Courier setter method.
   *
   * @param $property
   * @param $value
   * @return bool
   */
  public function __set($property, $value) {
    $this->data[$property] = $value;
    return TRUE;
  }

  public function __call($name, $arguments) {
    if ($name == 'sendParcel') {
      // legacy system requirement, pass to newer send() method
      return $this->send($arguments[0]);
    }
    else {
      error_log('Failed call to ' . $name . ' in Courier class');
      return FALSE;
    }
  }

  public function __toString() {
    return $this->name . " (" . $this->getHomeCountry() . ")\n";
  }

  public function __sleep() {
    // only store the "safe" properties
    return array("name", "home_country");
  }

  public function __wakeup() {
    // properties are restored, now add the logfile
    $this->logfile = $this->getLogFile();
  }

  public function getName() {
    return $this->name;
  }

  public function setName($value) {
    $this->name = $value;
    return TRUE;
  }

  public function getHomeCountry() {
    return $this->home_country;
  }

  public function setHomeCountry($value) {
    $this->home_country = $value;
    return TRUE;
  }
  
  public static function getCouriersByCountry($country) {
    // get a list of couriers with their home_country = $country
    $courier_list = array();
    
    // create a Courier object for each result
    $courier_list[] = new Courier('Worldwide Courier');
    
    // return an array of the results
    return $courier_list;
  }
  
  public function ship(Parcel $parcel) {
    // check we have an address
    if (empty($parcel->getAddress())) {
      throw new \Exception('Address not specified');
    }

    // check the weight
    if ($parcel->getWeight() > 5) {
      throw new HeavyParcelException('Parcel extends courier limit');
    }

    // count the number of parcels been shipped
    $this->count++;

    // sends the parcel to its destination
    return TRUE;
  }
  
  public function calculateShipping(Parcel $parcel) {
    // look up the rate for the destination, we'll invent one
    $rate = $this->getShippingRateForCountry($parcel->getDestinationCountry());
    
    // calculate the cost
    $cost = $rate * $parcel->getWeight();
    return $cost;
  }

  /**
   * Count elements of an object
   * @link http://php.net/manual/en/countable.count.php
   * @return int The custom count as an integer.
   * </p>
   * <p>
   * The return value is cast to an integer.
   * @since 5.1.0
   */
  public function count() {
    return $this->count;
  }

  private function getLogFile() {
    // error log location would be a config file
    return fopen('/tmp/error_log.txt', 'a');
  }

  private function getShippingRateForCountry($country) {
    // some excellent rate calculating code goes here
    // for the example, we'll just think of a number
    return 1.2;
  }
}
