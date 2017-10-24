<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 4:19 PM
 */

$concerts = array(
  array(
    'title' => 'The Magic Flute',
    'time' => 1329636600
  ),
  array(
    'title' => 'Vivaldi Four Seasons',
    'time' => 1329291000
  ),
  array(
    'title' => "Mozart's Requiem",
    'time' => 1330196400
  )
);

$encoded_data = json_encode($concerts);
echo $encoded_data;
echo "\n";

$decoded_data = json_decode($encoded_data, TRUE);
print_r($decoded_data);
echo "\n";
