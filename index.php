<?php

function __autoload($classname) {
  include str_replace('\\', '/', $classname) . '.php';
}

use shipping\Courier;
use shipping\PigeonPost;

$courier = new PigeonPost('Local Avian Delivery Ltd');

if ($courier instanceof Courier) {
  echo $courier->name . " is a Courier\n";
}

if ($courier instanceof PigeonPost) {
  echo $courier->name . " is a PigeonPost\n";
}
