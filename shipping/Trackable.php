<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 22/10/2017
 * Time: 2:00 PM
 */

namespace shipping;


interface Trackable {
  public function getTrackInfo($parcelId);
}
