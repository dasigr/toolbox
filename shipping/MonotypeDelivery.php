<?php

namespace shipping;

class MonotypeDelivery extends Courier implements Trackable {
  public function getTrackInfo($parcelId) {
    // look up some information
    return(array("status" => "in transit"));
  }

  public function ship(Parcel $parcel) {
    // put in box
    // send and get parcel ID (we'll just pretend)
    $parcelId = 42;
    return $parcelId;
  }
}
