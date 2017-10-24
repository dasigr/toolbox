<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 4:39 PM
 */

$request = new HttpRequest('https://httpbin.org/get');
$request->send();

$result = $request->getResponseBody();
print_r(json_decode($result));
