<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 4:20 PM
 */

$simplexml = new SimpleXMLElement('<?xml version="1.0"?><concerts />');

$concert1 = $simplexml->addChild('concert');
$concert1->addChild("title", "The Magic Flute");
$concert1->addChild("time", 1329636600);

$concert2 = $simplexml->addChild('concert');
$concert2->addChild("title", "Vivaldi Four Seasons");
$concert2->addChild("time", 1329291000);

$concert3 = $simplexml->addChild('concert');
$concert3->addChild("title", "Mozart's Requiem");
$concert3->addChild("time", 1330196400);

$xml = $simplexml->asXML();
echo $xml;
echo "\n";

$concert_list = simplexml_load_string($xml);
print_r($concert_list);
echo "\n";
