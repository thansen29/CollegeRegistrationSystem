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
      <title>View Holds</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
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
              <a href = "studentHomepage.php" class = "myButton">Home</a>
              <a href = "registration.php" class = "myButton">Add Classes</a>
              <a href = "dropCourse.php" class = "myButton">Drop Classes</a>
              <a href = "studentSchedule.php" class = "myButton">View Schedule</a>
              <a href = "viewStudentGrades.php" class = "myButton">View Grades</a>
              <a href = "viewStudentHolds.php" class = "myButton">View Holds</a>
              <a href = "transcript.php" class = "myButton"> View Transcript</a>
              <a href = "applyMajor.php" class = "myButton"> Apply for a major</a>
              <a href = "applyMinor.php" class = "myButton"> Apply for a minor</a>
              <a href = "changePW.php" class = "myButton">Change Password</a>
              <a href = "courseCatalog.php" class = "myButton">Course Catalog</a>
              <a href = "logoff.php" class = "myButton">Log Off</a>
      	    </p>
        </center>

        <!-- Drop down list of terms -->
        <center><h1>Current Holds On Account</h1></center>

        <?php
          //PHP code to get holds
          require_once 'mysqli.php';
          $getHolds = <<<EOD
          SELECT `studentHolds`.`holdName`, `hold`.`holdType`, `hold`.`Description`
          FROM `studentHolds`
          JOIN `hold` ON `hold`.`holdName` = `studentHolds`.`holdName`
          WHERE `studentID` = {$_SESSION["id"]};
EOD;
          $getTable = mysqliQuery($getHolds, MYSQLI_USE_RESULT);
          $results = mysqli_num_rows($getTable);
          if($results > 0 )
          {
            echo "<center><table cellspacing='0' class='GreenBlack'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Hold</th>";
            echo "<th>Type</th>";
            echo "<th>Description</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($getTable))
            {
              echo "<tr id='idk'>";
              echo "<td>" . $row['holdName'] . "</td>";
              echo "<td>" . $row['holdType'] . "</td>";
              echo "<td>" . $row['Description'] . "</td>";
            }
            echo "</tr>";
            echo "</tbody>";
            echo "</table></center>";
          }
          else
          {
            echo "<center><strong><h1 id = 'bf'>You have no holds on your account.</h1></strong></center>";
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
