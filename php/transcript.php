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
      <title>View Transcript</title>
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
      	    </p>
        </center>

        <?php
          //PHP to get transcript
          require_once 'mysqli.php';
          //Query to get transcript info
          $getTranscript =
          "SELECT `course`.`courseNum`, `section`.`sectNum`, `course`.`courseName`, `student_history`.`grade`, `course`.`credits`, `section`.`term`
          FROM `student_history`
          JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
          JOIN `course` ON `course`.`courseID` = `section`.`courseID`
          WHERE `studentID` = {$_SESSION["id"]}";

          //Code to call database w/ query
          $getTable = mysqliQuery($getTranscript, MYSQLI_USE_RESULT);
          $results = mysqli_num_rows($getTable);
          //Prints table
          if($results > 0 )
          {
            echo "<center><table cellspacing='0' class='GreenBlack'>";
            echo "<caption><strong><h1 id = 'bf'>Courses Taken</h1></strong></caption>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Course Number</th>";
            echo "<th>Section</th>";
            echo "<th>Course Name</th>";
            echo "<th>Grade</th>";
            echo "<th>Credits</th>";
            echo "<th>Term</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = mysqli_fetch_array($getTable))
            {
              echo "<tr id='idk'>";
              echo "<td>" . $row['courseNum'] . "</td>";
              echo "<td>" . $row['sectNum'] . "</td>";
              echo "<td>" . $row['courseName'] . "</td>";
              echo "<td>" . $row['grade'] . "</td>";
              echo "<td>" . $row['credits'] . "</td>";
              echo "<td>" . $row['term'] . "</td>";
            }
            echo "</tr>";
            echo "</tbody>";
            echo "</table></center>";
          }
          else
          {
            echo "<center><strong><h1 id = 'bf'>You have no history on your account.</h1></strong></center>";
          }

          //Gets total number of credits
          $getCreditTotal = "SELECT SUM(credits) FROM `student_history` JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
          JOIN `course` ON `course`.`courseID` = `section`.`courseID` WHERE `studentID` = {$_SESSION['id']}";
          $getCredits = mysqliQuery($getCreditTotal, MYSQLI_USE_RESULT);
          $credits = mysqli_fetch_array($getCredits);
          echo "<center><strong><h1 id = 'bf'>You have a total of ".$credits[0]." credits.</h1></strong></center>";

          //Calculates a GPA and amount of hours.
          $getGrades = "SELECT `grade` FROM `student_history` WHERE `studentID` = {$_SESSION['id']}";
          $getGradeBook = mysqliQuery($getGrades, MYSQLI_USE_RESULT);

         //gets number of rows returned
         $gradeRows = mysqli_num_rows($getGradeBook);

         //if rows are returned
         if($gradeRows > 0 )
         {
           //array to store all the grades
           $totalGrades = array();
           while($gradeData = mysqli_fetch_array($getGradeBook))
           {
             array_push($totalGrades, $gradeData['grade']);

           }
           //gets the amount of grades
           $querynumOfGrades = "SELECT *
                                FROM `student_history`
                                WHERE `student_history`.`studentID` = {$_SESSION["id"]}";
          $numOfGradesResult = mysqliQuery($querynumOfGrades, MYSQLI_USE_RESULT);
          $numOfGrades = mysqli_num_rows($numOfGradesResult);

           //adds the values inside the array
           $actualGrades = array_sum($totalGrades);

           echo "<script>console.log('totalGrades:' + $actualGrades)</script>";
           echo "<script>console.log('numOfGrades:' + $numOfGrades)</script>";

           //actual grades / the amount gives you the average
           $gpa = $actualGrades / $numOfGrades;
           echo "<script>console.log('GPA: ' + $gpa)</script>";
           //gives you number like 4.0 for example
           $numberGrade = $gpa / 25;

           echo "<center><strong><h1 id = 'bf'>The average GPA is: ".$numberGrade."</h1></strong></center>";
         }
         else
         {
           echo "<center><strong><h1 id = 'bf'>There are no grades to calculate a GPA with.</h1></strong></center>";
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
