<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 23/10/2017
 * Time: 2:31 PM
 */

function __autoload($classname) {
  include str_replace('\\', '/', $classname) . '.php';
}

function handleMissedException($e) {
  echo "Sorry, something is wrong. Please try again, or contact us if the problem persists";
  error_log("Unhandled Exception: " . $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine());
}

set_exception_handler('handleMissedException');
