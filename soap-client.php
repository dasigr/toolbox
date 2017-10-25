<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 24/10/2017
 * Time: 2:19 PM
 */

$options = array(
  'uri' => 'http://www.toolbox.local/',
  'location' => 'http://www.toolbox.local/soap-service.php',
  'trace' => 1,
);

try {
  $client = new SoapClient(NULL, $options);
  $output = $client->getDisplayName('Joe', 'Bloggs');

  // Using WSDL file
  ini_set('soap.wsdl_cache_enabled', 0);
  $client = new SoapClient('http://localhost/wsdl');

  // List available methods in WSDL
  $functions = $client->__getFunctions();
  var_dump($functions);
} catch (Exception $e) {
  echo "Exception Error: " . $e->getMessage();
}
