<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 4:45 PM
 */

// enable allow_url_fopen in your php.ini file

$fp = fopen('http://www.example.com');

$result = file_get_contents('http://www.example.com');
print_r(json_decode($result));

$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
      "Cookie: foo=bar\r\n"
  )
);

$context = stream_context_create($opts);

/* Sends an http request to www.example.com
   with additional headers shown above */
$fp = fopen('http://www.example.com', 'r', false, $context);
fpassthru($fp);
fclose($fp);
