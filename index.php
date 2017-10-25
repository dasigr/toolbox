<?php

function __autoload($classname) {
  include str_replace('\\', '/', $classname) . '.php';
}

function handleMissedException($e) {
  echo "Sorry, something is wrong. Please try again, or contact us if the problem persists";
  error_log("Unhandled Exception: " . $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine());
}

set_exception_handler('handleMissedException');

// initialize the request object and store the requested URL
$request = new Request();
$request->url_elements = array();
if (isset($_SERVER['PATH_INFO'])) {
  $request->url_elements = explode('/', $_SERVER['PATH_INFO']);
}

// figure out the verb and grab the incoming data
$request->verb = $_SERVER['REQUEST_METHOD'];
switch ($request->verb) {
  case 'GET':
    $request->parameters = $_GET;
    break;
  case 'POST':
  case 'PUT':
    $request->parameters = json_decode(file_get_contents('php://input'), 1);
    break;
  case 'DELETE':
  default:
    // we won't set any parameters in these cases
    $request->parameters = array();
}
