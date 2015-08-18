<!DOCTYPE html>
<?php
   //establish database connection
   include 'databaseConnection.php';
   $dbConnection = DatabaseConnection::connect();
    //this is a primitive form of session management to see if the user
    //has posted to the form yet or not.
    if (!empty($_POST)){

        //gathers input from the user  
        $name_first = filter_input(INPUT_POST, 'Name_first');
        $name_last = filter_input(INPUT_POST, 'Name_last');
        $name_MI = filter_input(INPUT_POST, 'Name_MI');
        $Referrer = filter_input(INPUT_POST, 'Referrer');
        $Employer = filter_input(INPUT_POST, 'Employer');
        $Date_of_birth = (filter_input(INPUT_POST, 'Date_of_birth'));
        $Address_street = filter_input(INPUT_POST, 'Address_street');
        $Address_city = filter_input (INPUT_POST, 'Address_city');
        $Address_state = filter_input (INPUT_POST, 'Address_state');
        $Address_zip = filter_input (INPUT_POST, 'Address_zip');
        $Cell_phone = filter_input (INPUT_POST, 'Cell_phone');
        $Home_phone = filter_input (INPUT_POST, 'Home_phone');
        $agent_ID = filter_input (INPUT_POST, 'agent');

        $valid = true;

        //below contains the validation rules.
        //First Name and Last name must not be empty.
        //Middile initial can be empty but if filled must only be 1 character
        //Date of birth must be in dd-mm-yyyy format but can be blank.
        //Address number must be a number and not empty.
        //Street, City, State, Zip must not be empty.
        //Cell phone number must be filled and must be number, with no more than 10 digits
        //Home phone number must be filled and must be number, with no more than 10 digits.
        //Agent does not need to be filled. Will not be invalid as form will not allow it.

        //validate name
        if (strlen($name_first) == 0){
            $firstNameError = "Please enter first name.";
            $valid = false;
        }
        if (strlen($name_last) == 0){
            $lastNameError = "Please enter last name.";
            $valid = false;
        }
        if (!strlen($name_MI) == 0 && (strlen($name_MI) > 1)){
            $middleNameError = "Middle initial must be 1 character.";
            $valid = false;
        }

        //date validation algorithm
        $test_date = $Date_of_birth;
        $test_arr  = explode('-', $test_date);
        if (empty($Date_of_birth)) {
            $dateOfBirthError = "Please enter valid date of birth.";
            $valid = false;
        }
        else if (count($test_arr) == 3) {
            
            if (!is_numeric($test_arr[0]) || !is_numeric($test_arr[1]) || !is_numeric($test_arr[2])){
                $dateOfBirthError = "Please enter valid date of birth. (mm-dd-yyyy)";
                $valid = false;
            }

            else if (checkdate($test_arr[0], $test_arr[1], $test_arr[2])) {
                //PASS
            }
            else {
                $dateOfBirthError = "Please enter valid date of birth. (mm-dd-yyyy)";
                $valid = false;
            }
        }
        else {
            $dateOfBirthError = "Please enter valid date of birth. (mm-dd-yyyy)";
            $valid = false;
        }
        //validate address
        if (strlen($Address_street) == 0) {
            $addressStreetError = "Please enter valid street name.";
            $valid = false;
        }
        if (strlen($Address_city) == 0){
            $addressCityError = "Please enter valid city.";
            $valid = false;
        }
        if ($Address_state == "NULL"){
            $addressStateError = "Please choose a state.";
            $valid = false;
        }
        if (!is_numeric($Address_zip) || empty($Address_zip)){
            $addressZipError = "Please enter valid zip code.";
            $valid = false;
        }

        //validate phone numbers
        if (!is_numeric($Cell_phone) || empty($Cell_phone)){
            $cellPhoneError = "Please enter valid cell phone number.";
            $valid = false;
        }
        if (!is_numeric($Home_phone) || empty($Home_phone)){
            $homePhoneError = "Please enter valid home phone number.";
            $valid = false;
        }

        //If there are no errors, insert into the database
        if ($valid) {
            $sql = "INSERT INTO customers (Name_first, Name_MI, Name_last, Referrer, "
                    . "Employer, Date_of_birth, Address_street, Address_city, "
                    . "Address_state, Address_zip, Cell_phone, Home_phone, agent_ID) "
                    ."VALUES ('".$name_first."', '".$name_MI."','".$name_last."','"
                    .$Referrer."','".$Employer."','".$Date_of_birth."','".$Address_street."','"
                    .$Address_city."','".$Address_state."','".$Address_zip."','".$Cell_phone."','"
                    .$Home_phone."','".$agent_ID."');";
            $res = odbc_exec($dbConnection,$sql);
            
            //clear variables.
            $name_first = null;
            $name_last = null;
            $name_MI = null;
            $Referrer = null;
            $Employer = null;
            $Date_of_birth = null;
            $Address_street = null;
            $Address_city = null;
            $Address_state = null;
            $Address_zip = null;
            $Cell_phone = null;
            $Home_phone = null;
            $agent_ID = null;
        }
    } //end of session management.
?>
<html>
    <head>
        <title>Project 1 Student Web Page</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>David Walters</h1>
        <h2>
            CMIS 485<br>
            Project 1
        </h2>
        <b>Step 3: </b>Develop a PHP script, initiated from your Student Web Page, that allows the
        <br>retrieval (i.e. SQL SELECTs) of data from your CUSTOMERS table in your Access database.
        <h3>Reston Real Estate Company Customers</h3>
        <table>
            <thead bgcolor="#c1cdc1">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Referrer</th>
                    <th>Employer</th>
                    <th>Date of Birth</th>
                    <th>Address</th>
                    <th>Cell Phone</th>
                    <th>Home Phone</th>
                    <th>Agent ID</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>   
            <?php                  
              //create the SQL statement and execute it.
                $sql = 'SELECT Customers.* FROM Customers '
                        . 'LEFT JOIN Agents ON Agents.agent_ID = Customers.agent_ID '
                        . 'ORDER BY Customers.cust_ID';
                $results = odbc_exec($dbConnection, $sql);
                $x = 0;

                while (odbc_fetch_row($results)){
                  //separates the rows by color for ease of reading  
                    if ($x%2 == 0){
                        $color = '#E6E6E6';
                    }
                    else {
                        $color = '#FFFFFF';
                    }
                    echo '<tr bgcolor="'.$color.'">';
                    echo '<td>'. odbc_result($results, "cust_ID"). '</td>';
                    echo '<td>'. odbc_result($results, "Name_first"). '</td>';
                    echo '<td>'. odbc_result($results, "Name_last"). '</td>';
                    echo '<td>'. odbc_result($results, "Referrer"). '</td>';
                    echo '<td>'. odbc_result($results, "Employer"). '</td>';
                    $date = new DateTime(odbc_result($results, "Date_of_birth")); //date formatting
                    echo '<td>'. $date->format('m-d-y')  .'</td>';
                    echo '<td>'. odbc_result($results, "Address_street").
                            ', '. odbc_result($results, "Address_city").
                            ', '. odbc_result($results, "Address_state").
                            ', '. odbc_result($results, "Address_zip").
                            '</td>';
                    echo '<td>'. odbc_result($results, "Cell_phone"). '</td>';       
                    echo '<td>'. odbc_result($results, "Home_phone"). '</td>';
                    echo '<td>'. odbc_result($results, "agent_ID"). '</td>';
                    echo '<td><a class="btn" href="deleteRecord.php?id='.odbc_result($results, "cust_ID").'">Delete</a>'
                            . ' | <a class="btn" href="editRecord.php?id='.odbc_result($results, "cust_ID").'">Edit</a></td>';
                    echo '</tr>';
                    $x++;
                } //end while statement.
            ?>
            </tbody>
        </table>
        <p><b>Step 4: </b>Develop a PHP script, initiated from your Student Web Page, that allows the
        <br>addition (i.e. SQL INSERTs) of data to your CUSTOMERS table in your Access database.<br>
        <h3>Create A New Customer</h3>
        <font color="red"><p>
        <?php
            //error tracking and display of errors.
            if (!empty($firstNameError)){
                echo "<br><b>".$firstNameError."</b>";
            }
            if (!empty($lastNameError)){
                echo "<br><b>".$lastNameError."</b>";
            }
            if (!empty($middleNameError)){
                echo "<br><b>".$middleNameError."</b>";
            }
            if (!empty($dateOfBirthError)){
                echo "<br><b>".$dateOfBirthError."</b>";
            }
            if (!empty($addressStreetError)){
                echo "<br><b>".$addressStreetError."</b>";
            }
            if (!empty($addressCityError)){
                echo "<br><b>".$addressCityError."</b>";
            }
            if (!empty($addressStateError)){
                echo "<br><b>".$addressStateError."</b>";
            }
            if (!empty($addressZipError)){
                echo "<br><b>".$addressZipError."</b>";
            }
            if (!empty($cellPhoneError)){
                echo "<br><b>".$cellPhoneError."</b>";
            }
            if (!empty($homePhoneError)){
                echo "<br><b>".$homePhoneError."</b>";
            }
        ?>
        </font></p>
        <form  action="project1.php" method="post">
        <table>
                <tr>
                    <td><b>First Name</b></td>
                    <td><b>Last Name</b></td>
                    <td><b>Middle Initial</b></td>
                </tr>
                <tr>
                    <td><input type="text" name="Name_first" value="<?php echo !empty($name_first)?$name_first:'';?>"/></td>
                    <td><input type="text" name="Name_last" value="<?php echo !empty($name_last)?$name_last:'';?>"/></td>
                    <td><input type="text" size="2" name="Name_MI" value="<?php echo !empty($name_MI)?$name_MI:'';?>"></td>
                </tr>
                <tr>
                    <td><b>Referrer</b></td>
                    <td><b>Employer</b></td>
                </tr>
                <tr>
                    <td><input type="text" name="Referrer" value="<?php echo !empty($Referrer)?$Referrer:'';?>" ></td>
                    <td><input type="text" name="Employer" value="<?php echo !empty($Employer)?$Employer:'';?>"></td>
                </tr>
                <tr>
                    <td><b>Date of Birth</b><br>dd-mm-yyyy</td>
                </tr>
                <tr>
                    <td><input type="datetime" size="15" name="Date_of_birth" value="<?php echo !empty($Date_of_birth)?($Date_of_birth):''; ?>"></td>
                </tr>
                <tr>
                    <td><b>Address</b></td>
                    <td><b>City</b</td>
                    <td><b>State</b></td>
                    <td><b>Zip</b></td>
                </tr>
                <tr>
                    <td><input type="text" name="Address_street" value="<?php echo !empty($Address_street)?$Address_street:'';?>"/></td>
                    <td><input type="text" name="Address_city" value="<?php echo !empty($Address_city)?$Address_city:'';?>"/></td>
                    <td><select name="Address_state" value="<?php echo !empty($Address_state)?$Address_state:'';?>">
                     <?php if (empty($_POST)){
                                $Address_state = "NULL";
                    }?>
                            <option value="NULL" <?php if ($Address_state == "NULL"){echo " selected";}?>>Please choose state...</option>
                            <option value="AL" <?php if ($Address_state == "AL"){echo " selected";}?>>Alabama</option>
                            <option value="AK" <?php if ($Address_state == "AK"){echo " selected";}?>>Alaska</option>
                            <option value="AZ" <?php if ($Address_state == "AZ"){echo " selected";}?>>Arizona</option>
                            <option value="AR" <?php if ($Address_state == "AR"){echo " selected";}?>>Arkansas</option>
                            <option value="CA" <?php if ($Address_state == "CA"){echo " selected";}?>>California</option>
                            <option value="CO" <?php if ($Address_state == "CO"){echo " selected";}?>>Colorado</option>
                            <option value="CT" <?php if ($Address_state == "CT"){echo " selected";}?>>Connecticut</option>
                            <option value="DE" <?php if ($Address_state == "DE"){echo " selected";}?>>Delaware</option>
                            <option value="FL" <?php if ($Address_state == "FL"){echo " selected";}?>>Florida</option>
                            <option value="GA" <?php if ($Address_state == "GA"){echo " selected";}?>>Georgia</option>
                            <option value="HI" <?php if ($Address_state == "HI"){echo " selected";}?>>Hawaii</option>
                            <option value="ID" <?php if ($Address_state == "ID"){echo " selected";}?>>Idaho</option>
                            <option value="IL" <?php if ($Address_state == "IL"){echo " selected";}?>>Illinois</option>
                            <option value="IN" <?php if ($Address_state == "IN"){echo " selected";}?>>Indiana</option>
                            <option value="IA" <?php if ($Address_state == "IA"){echo " selected";}?>>Iowa</option>
                            <option value="KS" <?php if ($Address_state == "KS"){echo " selected";}?>>Kansas</option>
                            <option value="KY" <?php if ($Address_state == "KY"){echo " selected";}?>>Kentucky</option>
                            <option value="LA" <?php if ($Address_state == "LA"){echo " selected";}?>>Louisiana</option>
                            <option value="ME" <?php if ($Address_state == "ME"){echo " selected";}?>>Maine</option>
                            <option value="MD" <?php if ($Address_state == "MD"){echo " selected";}?>>Maryland</option>
                            <option value="MA" <?php if ($Address_state == "MA"){echo " selected";}?>>Massachusetts</option>
                            <option value="MI" <?php if ($Address_state == "MI"){echo " selected";}?>>Michigan</option>
                            <option value="MN" <?php if ($Address_state == "MN"){echo " selected";}?>>Minnesota</option>
                            <option value="MS" <?php if ($Address_state == "MS"){echo " selected";}?>>Mississippi</option>
                            <option value="MO" <?php if ($Address_state == "MO"){echo " selected";}?>>Missouri</option>
                            <option value="MT" <?php if ($Address_state == "MT"){echo " selected";}?>>Montana</option>
                            <option value="NE" <?php if ($Address_state == "NE"){echo " selected";}?>>Nebraska</option>
                            <option value="NV" <?php if ($Address_state == "NV"){echo " selected";}?>>Nevada</option>
                            <option value="NH" <?php if ($Address_state == "NH"){echo " selected";}?>>New Hampshire</option>
                            <option value="NJ" <?php if ($Address_state == "NJ"){echo " selected";}?>>New Jersey</option>
                            <option value="NM" <?php if ($Address_state == "NM"){echo " selected";}?>>New Mexico</option>
                            <option value="NY" <?php if ($Address_state == "NY"){echo " selected";}?>>New York</option>
                            <option value="NC" <?php if ($Address_state == "NC"){echo " selected";}?>>North Carolina</option>
                            <option value="ND" <?php if ($Address_state == "ND"){echo " selected";}?>>North Dakota</option>
                            <option value="OH" <?php if ($Address_state == "OH"){echo " selected";}?>>Ohio</option>
                            <option value="OK" <?php if ($Address_state == "OK"){echo " selected";}?>>Oklahoma</option>
                            <option value="OR" <?php if ($Address_state == "OR"){echo " selected";}?>>Oregon</option>
                            <option value="PA" <?php if ($Address_state == "PA"){echo " selected";}?>>Pennsylvania</option>
                            <option value="RI" <?php if ($Address_state == "RI"){echo " selected";}?>>Rhode Island</option>
                            <option value="SC" <?php if ($Address_state == "SC"){echo " selected";}?>>South Carolina</option>
                            <option value="SD" <?php if ($Address_state == "SD"){echo " selected";}?>>South Dakota</option>
                            <option value="TN" <?php if ($Address_state == "TN"){echo " selected";}?>>Tennessee</option>
                            <option value="TX" <?php if ($Address_state == "TX"){echo " selected";}?>>Texas</option>
                            <option value="UT" <?php if ($Address_state == "UT"){echo " selected";}?>>Utah</option>
                            <option value="VT" <?php if ($Address_state == "VT"){echo " selected";}?>>Vermont</option>
                            <option value="VA" <?php if ($Address_state == "VA"){echo " selected";}?>>Virginia</option>
                            <option value="WA" <?php if ($Address_state == "WA"){echo " selected";}?>>Washington</option>
                            <option value="WV" <?php if ($Address_state == "WV"){echo " selected";}?>>West Virginia</option>
                            <option value="WI" <?php if ($Address_state == "WI"){echo " selected";}?>>Wisconsin</option>
                            <option value="WY" <?php if ($Address_state == "WY"){echo " selected";}?>>Wyoming</option>
                            <option value="DC" <?php if ($Address_state == "DC"){echo " selected";}?>>Washington DC</option>
                            <option value="AA" <?php if ($Address_state == "AA"){echo " selected";}?>>Armed Forces Americas</option>
                            <option value="AE" <?php if ($Address_state == "AE"){echo " selected";}?>>Armed Forces Europe</option>
                            <option value="AP" <?php if ($Address_state == "AP"){echo " selected";}?>>Armed Forces Pacific</option>
                        </select></td>
                        <td><input type="number" name="Address_zip" value="<?php echo !empty($Address_zip)?$Address_zip:'';?>"/></td>
                </tr>
                <tr>
                    <td><b>Cell Number</b></td>
                    <td><b>Home Number</b></td>
                </tr>
                <tr>
                    <td><input type="text" size="15" name="Cell_phone" value="<?php echo !empty($Cell_phone)?$Cell_phone:'';?>"/></td>
                    <td><input type="text" size="15" name="Home_phone" value="<?php echo !empty($Home_phone)?$Home_phone:'';?>"/></td>

                </tr>
                <tr>
                    <td><b>Agent</b></td>
                </tr>
                <tr>
                    <td>
                        <?php
                        //to get the agent's names and ensure proper referential integrity
                            $agentSQL = "SELECT agent_ID, Name_first, Name_last from Agents";
                            $agentResults = odbc_exec($dbConnection, $agentSQL);
                        ?>
                        <select name="agent">
                            <?php
                            
                            if (empty($_POST)){
                                $agent_ID = "null";
                            }
                                //creates option listing of all available agents.
                                while (odbc_fetch_row($agentResults)){
                                    //checks to see if agent is current one selected and makes dynamic form
                                    if ($agent_ID == odbc_result($agentResults, "agent_ID")){
                                        $agentSelectedString = "selected";
                                    }
                                    else {
                                        $agentSelectedString = "";
                                    }
                                    echo '<option value="'.odbc_result($agentResults, "agent_ID").'" '.$agentSelectedString.'>'
                                            .odbc_result($agentResults, "Name_first").' '.odbc_result($agentResults, "Name_last").'</option>';
                                }
                            ?>
                        </select>
                    </td>
                    <td><input type="submit"></td>
                </tr>
            </table>    
        </form>
        <P>
        <b>Step 5: </b>Develop a PHP script, initiated from your Student Web Page, that allows the
        <br>deletion (i.e. SQL DELETEs) of data in your CUSTOMERS table in your Access database.<br>
        <i>This functionality is displayed by clicking the <b>Delete</b> button next to any of the customers<br>
            in the table.</i>
        <P>
        <b>Step 6: </b>Develop a PHP script, initiated from your Student Web Page, that allows the
        <br>updating (i.e. SQL UPDATEs) of data in your CUSTOMERS table in your Access database.<br>
        <i>This functionality is displayed by clicking the <b>Edit</b> button next to any of the customers<br>
            in the table.</i>
    </body>
    
    
</html>
