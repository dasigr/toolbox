<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 4:28 PM
 */

$ch = curl_init('http://www.toolbox.local/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$response = curl_exec($ch);

// Check if any error occurred
if (!curl_errno($ch)) {
  $info = curl_getinfo($ch);
  print_r(json_decode($response));
  echo 'Took ', $info['total_time'], ' seconds to send a request to ', $info['url'], "\n";
  print_r($info);
}

// Close handle
curl_close($ch);
