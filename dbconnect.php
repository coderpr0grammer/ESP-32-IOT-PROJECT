<?php
    
    //link to the database connection
    $link = mysqli_connect("localhost", "admin_aloneuser", "Danico2003", "admin_alonewp");
    
    //if there is an error connecting to the database, then stop the script
    if ( mysqli_connect_error()) {

        die ("There was an error connecting to the database");

    }

?>
