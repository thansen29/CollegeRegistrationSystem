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
      <title>Add Faculty</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
      <script src= "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   </head>

   <!-- Website content that is shown. -->
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

    <h1><strong>Add a Faculty</strong></h1>


    <p>Faculty Name</p>

    <form method = "post" action = "addFaculty.php">

    <!-- Dropdown of people who are person type faculty but not in the faculty table -->
    <p><select id = "facultyNames" name = "facultyNames" required></select></p>
    <option></option>
    <script id="source" language="javascript" type="text/javascript">
     $('document').ready( function() {
       console.log("after document ready");
         $.ajax({
           url: 'api.php',
           data: {
             "fname": "getFacNames",
             "facultyNames": $('#facultyNames').val()
           },
           dataType: 'json',
           success: function(data) {
             console.log("in success block of $.ajax()");
             for (let i=0; i < data.length; i++) {
               $('#facultyNames').append("<option value = " + data[i].personID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
             }
           }
         });
       });
   </script>

   <p>Department</p>
   <p><select id="deptName" name = "deptName" required></select></p>
   <option></option>
   <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getDepartmentNames",
            "deptName": $('#deptName').val()
          },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            for (let i=0; i < data.length; i++) {
              $('#deptName').append("<option value = " + data[i].deptID + ">" + data[i].deptName + "</option>");
            }
          }
        });
      });
  </script>
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
            createFaculty();
           }
      ?>
      <?php

        function createFaculty(){

        require_once "mysqli.php";

        //Connect to database
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
         die( "Could not connect to database" );

         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
           die( "Could not open database" );

        //variables to grab user input
        $facultyNames = $_POST["facultyNames"];
        $deptName = $_POST["deptName"];


        //query to check if the faculty you are attempting to add already exists in the faculty table
        $restriction = mysqliQuery("SELECT `facultyID` FROM `faculty` WHERE `facultyID` = '$facultyNames'", MYSQLI_USE_RESULT);
        $restrictionResults = mysqli_num_rows($restriction);
        if($restrictionResults)
        {
            print("<p><h1>The faculty you are attempting to add already exists</h1></p>");
            die(mysql_error() . "</body></html>");
        }

        //query to insert what was entered in the fields
        $query = "INSERT INTO `faculty` (facultyID, deptID) VALUES ('$facultyNames', '$deptName')";
          //run the query
          if ( !( $result = mysql_query( $query, $database ) ) )
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         else {
           echo "<p>New faculty successfully added</p>";
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
