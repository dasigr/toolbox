<?php

/**
 * @file
 * Xml request to intershipper server.
 */

/**
 * Function to create request to the intershipper server.
 *
 * @param object $order
 *   The order object.
 *
 * @return \SimpleXMLElement
 *   A SimpleXMLElement resource from the Intershipper API.
 */
function intershipper_create_rate_request($order) {
  $url = 'www.intershipper.com';
  $uri = '/Interface/Intershipper/XML/v2.0/HTTP.jsp?';
  $api_vars = intershipper_decrypt_vars(TRUE);
  $username = $api_vars['intershipper_username'];
  $password = $api_vars['intershipper_password'];
  $post = array();
  // Get shipping object.
  $shipping = commerce_customer_profile_load($order->commerce_customer_shipping['und'][0]['profile_id']);

  $UC = $shipping->commerce_customer_address['und'][0]['country'];
  $UZ = $shipping->commerce_customer_address['und'][0]['postal_code'];
  $weight = 0;
  foreach($order->commerce_line_items['und'] as $val) {
    $line_item = commerce_line_item_load($val['line_item_id']);
    // print_r($line_item->commerce_product);die();
    if (isset($line_item->commerce_product)) {
      $product = commerce_product_load($line_item->commerce_product['und'][0]['product_id']);
      if (isset($product->field_weight['und'][0]['value'])) {
        $weight += trim($product->field_weight['und'][0]['value']);
      }
      else {
        $weight += 0.1;
      }
    }
  }
  $classes = variable_get('intershipper_classes');

  // Creating Request Array.
  $post[] = $uri . 'Username=' . $username;
  $post[] = "Password=" . $password;
  $post[] = "Version=2.0.0.0";
  $post[] = "ShipmentID=1";
  $post[] = "QueryID=1";

  $jcnt = 1;
  foreach ($classes as $key => $v) {
    $post[] = "ClassCode" . $jcnt . "=" . $key;
    $jcnt++;
  }
  $post[] = "TotalClasses=" . ($jcnt - 1);
  $post[] = "DeliveryType=COM";
  $post[] = "ShipMethod=SCD";
  $post[] = "OriginationPostal=" . variable_get('intershipper_postal');
  $post[] = "OriginationCountry=" . variable_get('intershipper_country');
  $post[] = "DestinationPostal=" . $UZ;
  $post[] = "DestinationCountry=" . $UC;
  $post[] = "Currency=USD";
  $post[] = "TotalPackages=1";
  $post[] = "BoxID1=box1";
  $post[] = "Weight1=" . number_format($weight, 2);
  $post[] = "WeightUnit1=LB";
  $post[] = "Length1=" . variable_get('intershipper_length');
  $post[] = "Width1=" . variable_get('intershipper_width');
  $post[] = "Height1=" . variable_get('intershipper_height');
  $post[] = "DimensionalUnit1=IN";
  $post[] = "Packaging1=BOX";
  $post[] = "Contents1=OTR";
  $post[] = "Cod1=0";
  $post[] = "Insurance1=0";

  $jcnt = 1;
  $carriers =variable_get('intershipper_carrier');
  foreach ($carriers as $key => $v) {
    $post[] = "CarrierCode" . $jcnt . "=" . $key;
    $jcnt++;
  }
  $post[] = "TotalCarriers=" . ($jcnt - 1);
  $query = implode('&', $post);

  //Send the socket request with the uri/url
  $fp = fsockopen ($url, 80, $errno, $errstr, 60);
  if (!$fp) {
    echo "Error: $errstr ($errno)<br>\n";
  }
  else {
    $depth = array();
    fputs ($fp, "GET $query HTTP/1.0\r\nHost: $url\r\n\r\n");

    // Define the XML parsing routines/functions to call.
    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "intershipper_start_element", "intershipper_end_element");
    xml_set_character_data_handler($xml_parser, "intershipper_character_data");
    
    $newdata = '';
    while ($data = fread($fp, 4096)) {
      $newdata .= $data;
      // Strip unneeded data.
      $newdata = preg_replace('/\r\n\r\n/', "", $newdata);
      $newdata = preg_replace('/^HTTP.*\r\n/', "", $newdata);
      $newdata = preg_replace('/^Date.*\r\n/', "", $newdata);
      $newdata = preg_replace('/^Server.*\r\n/', "", $newdata);
      $newdata = preg_replace('/Con.*/', "", $newdata);
      $newdata = preg_replace('/Set.*/', "", $newdata);
      $newdata = preg_replace('/\r/', "", $newdata);
      $newdata = preg_replace('/\n/', "", $newdata);
      $newdata = preg_replace('/\r\n/', "", $newdata);
      $newdata = preg_replace('/Date.*<\?/s', "<?", $newdata);
    }
    
    // Parse xml.
    $xml = simplexml_load_string($newdata);
    return $xml;
  }
}

/**
 * Funtion to parse the XML data.
 * @param $Parser
 * @param $Line
 */
function intershipper_character_data($Parser, $Line) {
  global $state;
  $states = join (' ',$state);
  if ($states == "SHIPPMENT ERROR") {
    $error = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE BOXID") {
    global $boxID;
    $boxID = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE CARRIER NAME") {
    global $quote;
    $quote{carrier_name} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE CARRIER CODE") {
    global $quote;
    $quote{carrier_code} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE CLASS NAME") {
    global $quote;
    $quote{class_name} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE CLASS CODE") {
    global $quote;
    $quote{class_code} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE SERVICE NAME") {
    global $quote;
    $quote{service_name} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE SERVICE CODE") {
    global $quote;
    $quote{service_code} = $Line;
  }
  elseif ($states == "SHIPMENT PACKAGE QUOTE RATE AMOUNT") {
    global $quote;
    $quote['amount'] = $Line;
  }
}
/**
 * Function handles the end elements.
 * @param $Parser
 * @param $Elem
 */
function intershipper_end_element($Parser, $Elem) {
  global $state;
  $states = join (' ',$state);
  if ($states == "SHIPMENT PACKAGE QUOTE") {
    global $quote;
    global $boxID;
    global $quotes;
    unset ($quote['id']);
    unset ($quote['package_id']);
    // the $key is a combo of the carrier_code and service_code
    // this is the logical way to key each quote returned
    $key = $quote['carrier_code'] . ' ' . $quote['service_code'];
    $quotes[$boxID][$key] = $quote;
  }
  array_pop($state);
}

/**
 * Function to handle the start elements for the XML data.
 *
 * @param $Parser
 * @param $Elem
 * @param $Attr
 */
function intershipper_start_element(&$Parser, &$Elem, $Attr) {
  global $state;
  array_push ($state, $Elem);
  $states = join (' ',$state);
  //check what state we are in
  if ($states == "SHIPMENT PACKAGE") {
    global $package_id;
    $package_id = $Attr['ID'];
  }
  //check what state we are in
  elseif ($states == "SHIPMENT PACKAGE QUOTE") {
    global $package_id;
    global $quote;
    $quote = array ( package_id => $package_id, id => $Attr['ID']);
  }
}