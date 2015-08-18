<?php
    //establish database connection
    include 'databaseConnection.php';
    $dbConnection = DatabaseConnection::connect();
    
    //get the ID number from the GET method lined from the Delete button, and build
    //SQL statement, then execute statement.
    $id = filter_input(INPUT_GET, 'id');
    $sql = 'DELETE FROM customers WHERE cust_ID = '.$id.';';
    $res = odbc_exec($dbConnection,$sql);
    
    //redirect to main page.
    header ("Location: project1.php");


    
