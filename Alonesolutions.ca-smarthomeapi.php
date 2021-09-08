<?php
    
    //connect to database through an external script
    include("dbconnect.php");
    
    //check if the sensor data is available in the URL of the GET request
    if (array_key_exists("boardid", $_GET) && array_key_exists("description", $_GET) && array_key_exists("value", $_GET)) {
    
      //INSERT the data into the database with a table structure of |boardid|description|value|  lastupdated   |
      //                                                            |-------|-----------|-----|----------------|
      //                                                            |example|temperature| 24.6|2021-09-08 11:46|
      $query = "INSERT INTO `smarthome_sensor_data` (boardid, description, value, lastupdated) VALUES('" . mysqli_real_escape_string($link, $_GET["boardid"]). "', '" . mysqli_real_escape_string($link, $_GET["description"]) . "', '" . mysqli_real_escape_string($link, $_GET["value"]) . "', NOW())";
        $result = mysqli_query($link, $query);

    if ($result) {
        print_r($_GET);
    } else {
        echo "failed";
    }
    
    //Output any database errors if there are any
    echo "<br> SQL ERROR: <br>" . mysqli_error($link);

    }


?>
