<?php 
    $id = $_GET["id"];
    $filter = ["id" => strval($id)];
    $options = ["projection" => ['_id' => 0]];

    header('Content-Type: application/json');

    $mng = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mng->executeQuery("nobel.laureates", $query);

    foreach ($rows as $row) {
        echo json_encode($row, JSON_PRETTY_PRINT);
    }  
?>