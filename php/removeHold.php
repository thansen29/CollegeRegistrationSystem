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
      <title>Remove Hold</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
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

        <!-- Navigation Bar -->
          <center>
            <p>
                  <a href = "adminHomepage.php" class = "myButton">Home</a>
      	    </p>
        </center>

        <!-- Drop down list of terms -->
        <center><h1>Remove Hold</h1></center>

        <form name = "form" method = "post" action = "removeHold.php">

        <!-- Dropdown showing the students with their holds -->
        <p>Select a student with a hold</p>
        <p><select required name="studentHeld" id="studentHeld" ></select></p>
        <script id="source" language="javascript" type="text/javascript">
            $('document').ready( function() {
              console.log("after document ready");
                $.ajax({
                  url: 'api.php',
                  data: {
                    "fname": "getStudentsWithHolds",
                    "studentHeld": $('#studentHeld').val()
                  },
                  dataType: 'json',
                  success: function(data) {
                    console.log("in success block of $.ajax()");
                    for (let i=0; i < data.length; i++) {
                      $('#studentHeld').append("<option value = " + data[i].studentID + "," + data[i].holdName + ">"
                      + data[i].firstName + " " + data[i].lastName + " - " + data[i].holdName + "</option>");
                    }
                  }
                });
              });
           </script>


      <p></p>
      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Remove Hold from Student" />
      </p>


      <?php
        if(isset($_POST['submit']))
        {
          $getValue = $_POST['studentHeld'];
          $separate = explode(",", $getValue);
          $student = $separate[0];
          $hold = $separate[1];

          //PHP code to remove hold
          require_once 'mysqli.php';
          $removeHold = <<<EOD
          DELETE FROM `studentHolds`
          WHERE `studentID` = '$student' AND `holdName` = '$hold'
EOD;
          $updateTable = mysqliQuery($removeHold, MYSQLI_USE_RESULT);
          echo "<center><strong><h1 id = 'bf'>Hold successfully removed.</h1></strong></center>";


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
