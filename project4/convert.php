<?php 

    // read JSON data
    $file_content = file_get_contents("/home/cs143/data/nobel-laureates.json");
    $data = json_decode($file_content, true);

    $laureateFile = "laureates.import";

    foreach ($data["laureates"] as $laureate) {
        $laureateString = json_encode($laureate) . "\n";
        // print $laureateString;
        file_put_contents($laureateFile, $laureateString, FILE_APPEND);
    }

    file_put_contents($laureateFile, PHP_EOL , FILE_APPEND);

?>