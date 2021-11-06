<?php

    // Call the database in the project

    // How to connect to database in php

    $dsn = "mysql:host=localhost;dbname=blog";
    $user = "root";
    $password = "";
    $option = array (

        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",

    );

    try {
        $connect = new PDO($dsn, $user, $password, $option);
            // Set the PDO error mode to exception
        $connect -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // echo "Connected successfully in database" . "<br>";   // Know the success of the database. 
    } 

    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

?>