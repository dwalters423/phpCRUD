<?php

//Class: <CMIS485 6380 Web Database Development (2152) >
//Student Name: <David Walters>
//Instructor: <Dr. Alla Webb>
//Assignment #: Project 1
//Description: <RRE Database and PHP interactivity. >
//Due Date :<1/18/2015>
//I pledge that I have completed the programming assignment independently.
//I have not copied the code from a student or any source.
//I have not given my code to any student.
//Sign here: <David Walters>
//Additional Comments: This script connects to the database. The source from this file
//includes scripts and functionalities learned from www.startutorial.com/articles/view/php-crud-tutorial-part-1
//The advantage of using a separate database connection object is to provide a layer of abstraction
//so that the user cannot directly interface with the database. This also allows portablility,
//so that if the developer wants to update their database, they only need to update their
//data access object.
class DatabaseConnection {
    private static $dbName = 'CMIS008F';
    private static $dbConnection = null;
    
  //establishes a connection  
    public static function connect() {
        
      //ensures no previous connection has been established  
        if (self::$dbConnection == null){
            
           //trys to establish a connection
           self::$dbConnection = odbc_connect('SDEVDemo', '','');
           
           //tests the connection.
           if (self::$dbConnection){
               echo "<font color='green'>SECURE</font>";
               return self::$dbConnection;
           }
           if (!self::$dbConnection) {
               echo "<font color='red'>FAILED</font>";
               exit;
           }
        } //end if statement
        
    } //end of connect()
    
  //disconnects from the database by setting the database connection to null.
    public static function disconnect() {
        
        self::$dbConnection = null;
    }
    
} //end of class

?>