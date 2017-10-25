<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 24/10/2017
 * Time: 2:16 PM
 */

include 'ServiceFunctions.php';

$options = array('uri' => 'http://www.toolbox.local/');
$server = new SoapServer(NULL, $options);
$server->setClass('ServiceFunctions');
$server->handle();
