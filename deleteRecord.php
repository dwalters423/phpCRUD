<?php

//Class: <CMIS485 6380 Web Database Development (2152) >
//Student Name: <David Walters>
//Instructor: <Dr. Alla Webb>
//Assignment #: Project 1
//Description: <RRE Database and PHP interactivity. >
//Due Date :<1/25/2015>
//I pledge that I have completed the programming assignment independently.
//I have not copied the code from a student or any source.
//I have not given my code to any student.
//Sign here: <David Walters>
//Additional Comments: This script uses odbc connectivity to delete a record.


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


    