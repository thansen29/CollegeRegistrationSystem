<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "admin") {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Create Person</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
      <script src= "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   </head>

   <body>
      <header>
	      <center><div id = "headimage"><img src = "Images\campus.jpg" width = "950" height = "400"
	       	alt = "Logo"></div></center>
	      <center><h1>Al Gore's Rhythm College for the Focused Student</h1></center>
      	</header>

	  <!-- Navigation bar -->
      <center>
          <a href = "adminHomepage.php" class = "myButton">Home</a>
      </center>

    <form method = "post" action = "createPerson.php" novalidate>

      <h1><strong>Create Person</strong></h1>

      <!-- Person Type -->
      <select name = "personType" id = "personType" required>
        <option>Select a person type</option>
        <option>student</option>
        <option>faculty</option>
        <option>admin</option>
        <option>researcher</option>
      </select>

     <!-- hidden fields until student is selected -->
     <p id = "hsText" style = "display:none">High School Size</p>
     <p><input style = "display:none" id = "hsSize" name = "hsSize" type = "number" required /></p>

     <!-- hidden field until faculty is selected -->
     <p id = "deptName" style = "display:none">Department Name</p>
      <p><select style = "display:none" id = "deptID" name = "deptID" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getDepartmentNames",
               "deptName": $('#deptID').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               for (let i=0; i < data.length; i++) {
                 $('#deptID').append("<option value = " + data[i].deptID + ">" + data[i].deptName + "</option>");
               }
             }
           });
         });
      </script>



      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            $('#personType').change( function() {
             var value = $('#personType').val();
             //if value is student, show the high school size box for them to enter into
             if(value == "student"){
                $('#hsText').show();
                $('#hsSize').show();
             }
             //if the value is faculty, show the department for them to select
             else if(value == "faculty"){
                $('#hsText').hide();
                $('#hsSize').hide();
                $('#deptName').show();
                $('#deptID').show();
              }
              //if admin or researcher is selected, hide all those fields
              else{
                $('#hsText').hide();
                $('#hsSize').hide();
                $('#deptName').hide();
                $('#deptID').hide();
              }

            });
          });
      </script>

      <!-- email -->
      <p>Email Address</p>
        <label>
          <input id = "email" name = "email" type = "text" required/>
        </label>

        <!-- password -->
        <p>Password</p>
          <label>
            <input id = "password" name = "password" type = "text" required/>
          </label>

        <!-- first name -->
        <p>First Name</p>
          <label>
            <input id = "firstName" name = "firstName" type = "text" required/>
          </label>

        <!-- last name -->
        <p>Last Name</p>
          <label>
            <input id = "lastName" name = "lastName" type = "text" required/>
          </label>

        <!-- birthdate -->
        <p>Birth Date</p>
          <label>
            <input id = "birthDate" name = "birthDate" type = "date" required/>
          </label>

        <!-- phone num -->
        <p>Phone Number</p>
          <label>
            <input id = "phoneNumber" name = "phoneNumber" type = "text" required/>
          </label>

        <!-- email -->
        <p>Street Address</p>
          <label>
            <input id = "streetAddress" name = "streetAddress" type = "text" required/>
          </label>


      <!-- Submit and reset buttons -->

      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>
    </form>
      <?php

        error_reporting(E_ALL ^ E_DEPRECATED);

        if(isset($_POST['submit'])){
        require_once 'mysqli.php';

        //Connect to databasea
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
           {
              die( "Could not connect to database" );
           }
         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
        {
           die( "Could not open database" );
        }

        $personType = $_POST["personType"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $birthDate = $_POST["birthDate"];
        $phoneNumber = $_POST["phoneNumber"];
        $streetAddress = $_POST["streetAddress"];
        $hsSize = $_POST["hsSize"];
        $deptID = $_POST["deptID"];


        //query to insert what was entered in the fields into the person table
        $query = "INSERT INTO `person` (personId, person_type, email, password, firstName, lastName, birthDate, phoneNum, streetAddress)
           VALUES (NULL, '$personType', '$email', '$password', '$firstName', '$lastName', '$birthDate', '$phoneNumber', '$streetAddress')";

        //no duplicates based on email address
        $emailRestriction = mysqliQuery("SELECT * FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);
        $emailRows = mysqli_num_rows($emailRestriction);
        if($emailRows > 0){
          print("<h1>The email address you entered already exists, please choose another one</h1>");
          die();
        }

         //if user doesnt select a person type
         if($personType == "Select a person type"){
         echo "<h1>You must select a person type!</h1>";
         die();
         }

        if ( !( $result = mysql_query( $query, $database ) ) )
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         else {
           echo "<h1>New person successfully added</h1>";
         }


         //if the person type is student
         if($personType == "student"){

           //get the ID from person table that was just created
           $studIDQuery = mysqliQuery("SELECT `personID` FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);
           while($row = mysqli_fetch_array($studIDQuery)){
            $studID = $row['personID'];
           }

           //query to insert into the student table
           $insertStudent = mysqliQuery("INSERT INTO `student` (studentID, fullTime, hsSize) VALUES ('$studID', '0', '$hsSize')", MYSQLI_USE_RESULT);
         }

         //if the person type is faculty
         if($personType == "faculty"){

           //get the ID from person table that was just created
           $facIDQuery = mysqliQuery("SELECT `personID` FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);
           while($row = mysqli_fetch_array($facIDQuery)){
            $facID = $row['personID'];
           }

           //insert into the faculty table
           $insertFaculty = mysqliQuery("INSERT INTO `faculty` (facultyID, fullTime, deptID) VALUES ('$facID', '0', '$deptID')", MYSQLI_USE_RESULT);
         }

         //if the person type is admin
         else if($personType == "admin"){
           //get the ID from person table that was just created
           $adminIDQuery = mysqliQuery("SELECT `personID` FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);
           while($row = mysqli_fetch_array($adminIDQuery)){
            $adminID = $row['personID'];
           }

           //insert into the faculty table
           $insertAdmin = mysqliQuery("INSERT INTO `admin` (adminID) VALUES ('$adminID')", MYSQLI_USE_RESULT);
         }

         //if the person type is researcher
         else if ($personType == "researcher"){
           //get the ID from person table that was just created
           $researcherIDQuery = mysqliQuery("SELECT `personID` FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);
           while($row = mysqli_fetch_array($researcherIDQuery)){
            $researcherID = $row['personID'];
           }

           //insert into the faculty table
           $insertResearcher = mysqliQuery("INSERT INTO `researcher` (researcherID) VALUES ('$researcherID')", MYSQLI_USE_RESULT);
         }




       }
       ?>


	    <footer>
        <center>
  	  		<h6>&copy; 2016 by Tom Matt Jack Software. All Rights Reserved.<h6>
  	  		<address>
  	  			Contact us at <a href = "mailto:tmj@leagueofrockets.com">
  	  		     	tmj@leagueofrockets.com</a>
  	  		</address>
        </center>
		</footer>
   </body>
</html>
