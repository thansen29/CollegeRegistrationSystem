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
      <title>Student Home Page</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
   </head>

   <!-- Website content that is shown. -->
   <body>

      <header>
	      <center><div id = "headimage"><img src = "Images\campus.jpg" width = "950" height = "400"
	       	alt = "Logo"></div></center>
	      <center><h1>Al Gore's Rhythm University for the Focused Student</h1></center>
      	</header>
	  <!-- Navigation bar -->
      <center>
        <p>
              <a href = "studentHomepage.php" class = "myButton">Home</a>
              <a href = "selectTerm.php" class = "myButton">Add Classes</a>
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

	  <!-- Introduction text -->

	  <center><p>STUDENT HOME PAGE
    </p></center>


    <?php
         require_once 'mysqli.php';

         $getAdvisor = "SELECT `firstName`, `lastName`, `faculty`.`facultyID`
              FROM `student`
              JOIN `person`
              JOIN `faculty` ON `person`.`personID` = `faculty`.`facultyID`
              JOIN `advisement` ON `advisement`.`studentID` = `student`.`studentID` AND `advisement`.`facultyID` = `faculty`.`facultyID`
              WHERE `advisement`.`studentID` = {$_SESSION["id"]}
              order by `lastName`";
         $result = mysqliQuery($getAdvisor);
         $resultRows = mysqli_num_rows($result);
         if(!$resultRows)
         {
           echo "<center><h1>You do not have an advisor</center></h1>";
         }

         else
         {
         while($row = mysqli_fetch_array($result))
         {
           $firstName = $row['firstName'];
           $lastName = $row['lastName'];
         }

         echo "<center>Your advisor is:<h1> " . $firstName. " " . $lastName . "</h1></center>";
         }

         //show the majors the student has
         $majorQuery = "SELECT `major`.`majorID`, `major`.`majorName`
                               FROM `studentsMajor`
                               JOIN `major` ON `major`.`majorID` = `studentsMajor`.`majorID`
                               WHERE `studentsMajor`.`studentID` = {$_SESSION["id"]}";
         $majTable = mysqliQuery($majorQuery, MYSQLI_USE_RESULT);
              $majResults = mysqli_num_rows($majTable);
              if($majResults > 0)
              {
                echo "<center><table cellspacing='0' class='GreenBlack'>";
                echo "<caption><strong><h1 id = 'bf'>Major(s)</h1></strong></caption>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Major(s)</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($row = mysqli_fetch_array($majTable))
                {
                  echo "<tr id='idk'>";
                  echo "<td>" . $row['majorName'] . "</td>";
                }
                echo "</tr>";
                echo "</tbody>";
                echo "</table></center>";
        }
        else
        {
          echo "<center><strong><h1 id = 'bf'>You do not have any majors" . "</h1></strong></center>";
        }

        echo "<br>";

        //show the minors the student has
        $minorQuery = "SELECT `minor`.`minorID`, `minor`.`minorName`
                               FROM `studentsMinor`
                               JOIN `minor` ON `minor`.`minorID` = `studentsMinor`.`minorID`
                               WHERE `studentsMinor`.`studentID` = {$_SESSION["id"]}";
         $minTable = mysqliQuery($minorQuery, MYSQLI_USE_RESULT);
              $minResults = mysqli_num_rows($minTable);
              if($minResults > 0)
              {
                echo "<center><table cellspacing='0' class='GreenBlack'>";
                echo "<caption><strong><h1 id = 'bf'>Minor(s)</h1></strong></caption>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Minor(s)</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($row = mysqli_fetch_array($minTable))
                {
                  echo "<tr id='idk'>";
                  echo "<td>" . $row['minorName'] . "</td>";
                }
                echo "</tr>";
                echo "</tbody>";
                echo "</table></center>";
        }
        else
        {
          echo "<center><strong><h1 id = 'bf'>You do not have any minors" . "</h1></strong></center>";
        }





    ?>


    <p>
    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>
    </p>

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
