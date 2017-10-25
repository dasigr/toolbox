<?php

/**
 * Created by PhpStorm.
 * User: das
 * Date: 24/10/2017
 * Time: 2:12 PM
 */
class ServiceFunctions {
  public function getDisplayName($first_name, $last_name) {
    $name = '';
    $name .= strtoupper(substr($first_name, 0, 1));
    $name .= ' ' . ucfirst($last_name);
    return $name;
  }

  public function countWords($paragraph) {
    $words = preg_split('/[. ,!?;]+/', $paragraph);
    return count($words);
  }
}
