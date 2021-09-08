<?php
/*

Daniel Martinez wrote this script as a test but didn't get to test it out
thourougly for errors and bugs, so further testing and development using
this code would have to account for that. 

The intention of this script is to check if the record from the same board and referring to the same
data is already in the database, and then to overwrite it if it does.


*/
    include("dbconnect.php");
    
    //check if sensor data is encoded in the GET request URL
    if (array_key_exists("boardid", $_GET) && array_key_exists("description", $_GET) && array_key_exists("value", $_GET)) {
        
        //create a query that checks if an entry from the same board and same description already exists in the database
        $query = "SELECT * FROM `smarthome_sensor_data` WHERE `boardid`='" . mysqli_real_escape_string($link, $_GET["boardid"]) . "' AND `description` ='" . mysqli_real_escape_string($link, $_GET["description"]) . "'";
        //$result = mysqli_query($link, $query);
        
        //run the query
        if ($result = mysqli_query($link, $query)) {

            $rowcount=mysqli_num_rows($result);
            
            //if there is an entry with that data, update it with the below query
            if ($rowcount > 0) {
                $query = "UPDATE `smarthome_sensor_data` SET `value`='" . mysqli_real_escape_string($link, $_GET["value"]) . "' WHERE `boardid` = '" . mysqli_real_escape_string($link, $_GET["boardid"]) . "' AND `description` = '" . mysqli_real_escape_string($link, $_GET["description"]) . "'";
                $result = mysqli_query($link, $query);

                if ($result) {
                    print_r($_GET);
                } else {
                    echo "failed";
                }

            } else {
              
                //if there is no previous entry from that board with that description, update the entry with the newly received data
                $query = "INSERT INTO `smarthome_sensor_data` (boardid, description, value, lastupdated) VALUES('" . mysqli_real_escape_string($link, $_GET["boardid"]). "', '" . mysqli_real_escape_string($link, $_GET["description"]) . "', '" . mysqli_real_escape_string($link, $_GET["value"]) . "', NOW())";

                $result = mysqli_query($link, $query);

                if ($result) {
                    print_r($_GET);
                } else {
                    echo "failed";
                }
            }


        } else {
            echo "failed";
        }

        echo "<br> SQL ERROR: <br>" . mysqli_error($link);

    }


?>
