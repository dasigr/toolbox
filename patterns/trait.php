<?php

// Define the Singleton Trait

trait Singleton {
  // A static variable to hold our single instance
  private static $_instance = null;

  // A method to get our singleton instance
  public static function getInstance()
  {
    // Dynamically use the current class name
    $class = __CLASS__;

    if (!(self::$_instance instanceof __CLASS__)) {
      self::$_instance = new $class();
    }

    return self::$_instance;
  }
}

class DBWriteConnection extends PDO {
  // Use the Singleton Trait
  use Singleton;

  private function __construct()
  {
    parent::__construct($dsn, $username, $passwd, $options);
  }
}

class DBReadConnection extends PDO {
  // Use the Singleton Trait
  use Singleton;

  private function __construct()
  {
    parent::__construct($dsn, $username, $passwd, $options);
  }
}
