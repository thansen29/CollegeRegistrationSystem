<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "student") {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Apply Minor</title>
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
          <a href = "studentHomepage.php" class = "myButton">Home</a>

    </center>

    <h1><strong>Apply for a minor</strong></h1>

    <form method = "post" action = "applyMinor.php">

      <p>Minor</p>
      <p><select id = "minorNames" name = "minorNames"></select></p>
      <option></option>
      <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getMinorNames",
               "minorNames": $('#minorNames').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               for (let i=0; i < data.length; i++) {
                 $('#minorNames').append("<option value = " + data[i].minorID + ">" + data[i].minorName + "</option>");
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

      </form>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>



    <?php

      error_reporting(E_ALL ^ E_DEPRECATED);


      if(isset($_POST['submit'])){
          applyMinor();
         }
    ?>
    <?php

      function applyMinor(){

      require_once "mysqli.php";

      //Connect to database
      if ( !( $database = mysql_connect( "fdb4.biz.nf",
         "2218832_reg", "wasd1324" ) ) )
       die( "Could not connect to database" );

       // open database
      if ( !mysql_select_db( "2218832_reg", $database ) )
         die( "Could not open database" );

      //variables to grab user input
      $minorID = $_POST["minorNames"];
      $studentID = $_SESSION["id"];

      //query to check if the minor you are already part of the minor you are applying for
      $restriction = mysqliQuery("SELECT `studentID`, `minorID` FROM `studentsMinor` WHERE `studentID` = '$studentID' AND `minorID` = '$minorID'", MYSQLI_USE_RESULT);
      $restrictionResults = mysqli_num_rows($restriction);
      if($restrictionResults)
      {
          print("<p><h1>You are already part of the minor you're applying for!</h1></p>");
          die(mysql_error() . "</body></html>");
      }

      //query to check if the minor you are already part of the minor you are applying for
      //$duplicate = mysqliQuery("SELECT `studentID`, `minorID` FROM `minorApplication` WHERE `studentID` = '$studentID' AND `minorID` = '$minorID'", MYSQLI_USE_RESULT);
     // $duplicateResults = mysqli_num_rows($duplicate);
      //if($duplicateResults)
      //{
       //   print("<p><h1>You already applied for this minor!</h1></p>");
       //   die(mysql_error() . "</body></html>");
     // }

      //check if you already have 2 minors(business rule maximum)
      $minorLimit = mysqliQuery("SELECT `studentsMinor`.`minorID` FROM `studentsMinor` WHERE `studentsMinor`.`studentID` = '$studentID'", MYSQLI_USE_RESULT);
      $minorLimitResults = mysqli_num_rows($minorLimit);//count the rows that the query returns
      if($minorLimitResults == 2)//if it returns 2 rows, say fuck no you cant do that
      {
          print("<p><h1>You cannot have more than 2 minors!</h1></p>");
          die(mysql_error() . "</body></html>");
      }

      //can only apply for one minor at a time
      $applicationNumber = mysqliQuery("SELECT `minorApplication`.`studentID` FROM `minorApplication`
      WHERE `minorApplication`.`studentID` = '$studentID'", MYSQLI_USE_RESULT);
      $applicationNumberResults = mysqli_num_rows($applicationNumber);
      if($applicationNumberResults)
      {
          print("<p><h1>You can only apply for one minor at a time!</h1></p>");
          die(mysql_error() . "</body></html>");
      }

      //query to insert what was entered in the fields
      $query = "INSERT INTO `minorApplication` (studentID, minorID) VALUES ('$studentID', '$minorID')";
        //run the query
        if ( !( $result = mysql_query( $query, $database ) ) )
       {
          print( "<p>Could not execute query!</p>" );
          die( mysql_error() . "</body></html>" );
       } // end if
       else {
         echo "<p>New application successfully added</p>";
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
