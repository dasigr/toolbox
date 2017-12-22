<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 25/10/2017
 * Time: 6:07 PM
 */

$ch = curl_init('http://www.toolbox.local/api/events');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$events = json_decode($response, 1);
print_r($events);
