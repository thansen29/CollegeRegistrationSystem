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
      <title>Assign Hold</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
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

        <!-- Navigation Bar -->
          <center>
            <p>
                <a href = "adminHomepage.php" class = "myButton">Home</a>
      	    </p>
        </center>

        <center><h1>Assign Hold</h1></center>

        <form method = "post" action = "assignHold.php">

          <!-- Drop down list of student names -->
          <p>Select a student</p>
          <p><select id = "studentNames" name = "studentNames" required></select></p>
          <script id="source" language="javascript" type="text/javascript">
           $('document').ready( function() {
             console.log("after document ready");
               $.ajax({
                 url: 'api.php',
                 data: {
                   "fname": "getStudentNames",
                   "studentNames": $('#studentNames').val()
                 },
                 dataType: 'json',
                 success: function(data) {
                   console.log("in success block of $.ajax()");
                   for (let i=0; i < data.length; i++) {
                     $('#studentNames').append("<option value = " + data[i].studentID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
                   }
                 }
               });
             });
         </script>

         <!-- Drop down list of hold names -->
         <p>Select a hold</p>
         <p><select id = "holdNames" name = "holdNames" required></select></p>
         <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getHoldNames",
                  "holdNames": $('#holdNames').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  for (let i=0; i < data.length; i++) {
                    $('#holdNames').append("<option value = " + data[i].holdName + ">" + data[i].holdName + " - " + data[i].Description + "</option>");
                  }
                }
              });
            });
        </script>

      <!--Submit Button -->
      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
      </p>
      </form>


      <?php
        if(isset($_POST['submit']))
        {
          $studentID = $_POST["studentNames"];
          $holdName = $_POST["holdNames"];
          //PHP code to get all courses.
          require_once 'mysqli.php';

          //query to check if the student you are attempting to assign a hold to already has that hold
          $restriction = mysqliQuery("SELECT `studentID`, `holdName` FROM `studentHolds` WHERE `studentID` = '$studentID' AND `holdName` = '$holdName' ", MYSQLI_USE_RESULT);
          $restrictionResults = mysqli_num_rows($restriction);
          if($restrictionResults)
          {
              print("<p><h1>The student you are attempting to add a hold to already has that hold</h1></p>");
              die(mysql_error() . "</body></html>");
          }

          $insertHold = "INSERT INTO `studentHolds` VALUES ('$studentID', '$holdName')";
          if(!($updateTable = mysqliQuery($insertHold, MYSQLI_USE_RESULT)))
          {
            echo "<center><strong><h1 id = 'bf'>Hold failed to apply.</h1></strong></center>";
          }
          else
          {
            echo "<center><strong><h1 id = 'bf'>Hold successfully applied.</h1></strong></center>";
          }
        }
      ?>



    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>


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
