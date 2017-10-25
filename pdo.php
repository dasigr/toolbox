<?php
/**
 * Created by PhpStorm.
 * User: das
 * Date: 24/10/2017
 * Time: 3:32 PM
 */

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
