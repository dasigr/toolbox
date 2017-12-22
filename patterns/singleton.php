<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 08/11/2017
 * Time: 4:43 PM
 */

// The Database class represents our global DB connection
class Database extends PDO {

  // A static variable to hold our single instance
  private static $_instance = null;

  // Make the constructor private to ensure singleton
  private function __construct()
  {
    // Call the PDO constructor
    parent::__construct($dsn, $username, $passwd, $options);
  }

  // A method to get our singleton instance
  public static function getInstance()
  {
    if (!(self::$_instance instanceof Database)) {
      self::$_instance = new Database();
    }

    return self::$_instance;
  }
}

// Get a single instance of the database.
$db = Database::getInstance();
