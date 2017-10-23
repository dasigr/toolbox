<?php

function __autoload($classname) {
  include str_replace('\\', '/', $classname) . '.php';
}

function handleMissedException($e) {
  echo "Sorry, something is wrong. Please try again, or contact us if the problem persists";
  error_log("Unhandled Exception: " . $e->getMessage() . " in file " . $e->getFile() . " on line " . $e->getLine());
}

set_exception_handler('handleMissedException');

use shipping\Courier;
use shipping\PigeonPost;
use shipping\MonotypeDelivery;
use shipping\Parcel;
use shipping\Trackable;
use shipping\HeavyParcelException;

//$courier = new PigeonPost('Local Avian Delivery Ltd');
$courier = new Courier('Delivery Service Inc', 'United States');
//var_dump($courier);
//$data = serialize($courier);
//echo $data;
//echo unserialize($data);

//if ($courier instanceof Courier) {
//  echo $courier->name . " is a Courier\n";
//}
//
//if ($courier instanceof PigeonPost) {
//  echo $courier->name . " is a PigeonPost\n";
//}
//
//if ($courier instanceof MonotypeDelivery) {
//  echo $courier->name . " is a MonotypeDelivery\n";
//}
//
//if ($courier instanceof Parcel) {
//  echo $courier->name . " is a Parcel\n";
//}
//
//if ($courier instanceof Trackable) {
//  echo $courier->name . " is Trackable\n";
//}

//$box1 = new Parcel();
//$box1->destinationCountry = 'Denmark';
//
//$box2 = $box1;
//$box2->destinationCountry = 'Brazil';
//
//echo 'Parcels need to ship to: ' . $box1->destinationCountry . ' and ' . $box2->destinationCountry . "\n";
//
//if ($box1 == $box2) echo "equivalent\n";
//if ($box1 === $box2) echo "exact same object!\n";

//$parcel = new Parcel();
//$parcel->setAddress('123 Four St.');
//$parcel->setWeight(rand(1, 7));

//$shipping_cost = $courier->calculateShipping($myparcel);
//echo 'Shipping cost is: ' . $shipping_cost . "\n";

//try {
//  $courier->ship($parcel);
//  echo "parcel shipped";
//}
//catch (HeavyParcelException $e) {
//  echo "Parcel weight error: " . $e->getMessage() . "\n";
//  // redirect them to choose another courier
//}
//catch (Exception $e) {
//  echo "Something went wrong. " . $e->getMessage() . "\n";
//  // exit so we don't try to proceed any further
//  exit;
//}

try {
  $dsn = 'mysql:host=localhost;dbname=basic_oop';
  $db_username = 'root';
  $db_password = 'root';
  $db_conn = new PDO($dsn, $db_username, $db_password);
}
catch (PDOException $e) {
  echo "Could not connect to database\n";
}

//// query for one recipe
//$sql = 'SELECT name, description, chef
//        FROM recipes
//        WHERE id = :recipe_id';
//
//// prepare statement
//$stmt = $db_conn->prepare($sql);
//
//// perform query
//$stmt->execute(array("recipe_id" => 1));
//
//$recipe = $stmt->fetch();
//var_dump($recipe);

//// display results
//while ($row = $stmt->fetch()) {
//  echo $row['name'] . " by " . $row['chef'] . "\n";
//}

// SELECT
$sql = 'SELECT name, chef
        FROM recipes
        WHERE id = :recipe_id';

// UPDATE
$sql = 'UPDATE recipes SET category_id = :id WHERE category_id IS NULL';

// DELETE
$sql = 'DELETE FROM categories WHERE name = :name';

// query for one recipe
$sql = 'SELECT recipes.name, recipes.description, categories.name AS category
        FROM recipes
        INNER JOIN categories ON categories.id = recipes.category_id
        WHERE recipes.chef = :chef
        AND categories.name = :category_name';

try {
  $stmt = $db_conn->prepare($sql);

  if ($stmt) {
    // bind the chef value, we only want Lorna's recipes
    $stmt->bindValue(':chef', 'Lorna');
    $stmt->bindParam(':category_name', $category);

    // pudding
    $category = 'Pudding';
    $result = $stmt->execute();

    if ($result) {
      $starters = $stmt->fetchAll(PDO::FETCH_ASSOC);
      print_r($starters);
    } else {
      $error = $stmt->errorInfo();
      echo "Query failed with message: " . $error[2] . "\n";
    }
  }
} catch (PDOException $e) {
  echo "A database problem has occurred: " . $e->getMessage() . "\n";
}
