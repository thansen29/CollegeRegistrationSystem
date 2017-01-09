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
      <title>Create a Hold</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
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

    <form method = "post" action = "createHold.php">

      <h1><strong>Create a hold</strong></h1>

        <!-- Hold Name -->
        <p>Hold Name</p>
          <label>
            <input id ="holdName" name = "holdName" type = "text" required />
          </label>

      <!-- Hold Type -->
      <p>Hold Type</p>
        <select name = "holdType" id = "holdType">
          <option>Academic</option>
          <option>Disciplinary</option>
          <option>Financial</option>
          <option>Medical</option>
        </select>


        <!-- Hold Description -->
        <p>Hold Description</p>
          <label>
            <textarea id = "holdDescription" name = "holdDescription"
  	    			rows = "5" cols = "36" required />
            </textarea>
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
            createHold();
           }
      ?>
      <?php

        function createHold(){
        require_once "mysqli.php";

        //Connect to database
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
         die( "Could not connect to database" );

         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
           die( "Could not open database" );

        //variables to grab user input
        $holdName = $_POST["holdName"];
        $holdType = $_POST["holdType"];
        $holdDescription = $_POST["holdDescription"];

        //query to check if the hold you are attempting to add already exists in the hold table
        $restriction = mysqliQuery("SELECT `holdName` FROM `hold` WHERE holdName = '$holdName'", MYSQLI_USE_RESULT);
        $restrictionResults = mysqli_num_rows($restriction);
        if($restrictionResults)
        {
            print("<p><h1>The hold you are attempting to add already exists</h1></p>");
            die(mysql_error() . "</body></html>");
        }

        //query to insert what was entered in the fields
        $query = "INSERT INTO `hold` (holdName, holdType, Description) VALUES ('$holdName', '$holdType', '$holdDescription')";
          //run the query
          if ( !( $result = mysql_query( $query, $database ) ) )
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         else {
           echo "<p>New hold successfully added</p>";
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
