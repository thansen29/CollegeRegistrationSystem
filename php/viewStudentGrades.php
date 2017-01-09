<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "student") {
        header("Location: http://www.agru.co.nf/index.html");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Student Schedule</title>
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
        <form name = "form" method = "post" action = "viewStudentGrades.php">
          <?php
          require_once "mysqli.php";

          //create the dropdown menu
          echo "<p>Select a term:</p>";
          echo '<p><select required name="term" id="term" ></p>';

            //query to get the names
            $getTerm = mysqliQuery("SELECT `seasonYear` FROM `term`", MYSQLI_USE_RESULT);
            //loop through while putting each name in the dropdown
            while($row = mysqli_fetch_array($getTerm))
            {
              echo "<option>".$row['seasonYear']."</option>";
            }

            echo '</select></p>';//close the dropdown
          ?>
      <p></p>
      <p>
      <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
      </p>


      <?php
        if(isset($_POST['submit']))
        {
          $term = $_POST["term"];
          //PHP code to get all courses.
          require_once 'mysqli.php';
          $query = <<<EOD
          SELECT `department`.`deptName`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `enrollment`.`midterm_grade`, `enrollment`.`final_grade`
          FROM enrollment
          JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
          JOIN `course` ON `course`.`courseID` = `section`.`courseID`
          JOIN `department` ON `department`.`deptID` = `course`.`deptID`
          JOIN `student` ON `student`.`studentID` = `enrollment`.`studentID`
          WHERE `student`.`studentID` = {$_SESSION["id"]} AND `section`.`term` = '$term'
EOD;
          $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
          $results = mysqli_num_rows($getTable);
          if($results > 0 )
          {

                  echo "<center><table cellspacing='0' class='GreenBlack'>";
                  echo "<caption><strong><h1 id = 'bf'>Student Schedule</h1></strong></caption>";
                  echo "<thead>";
                  echo "<tr>";
                  echo "<th>Department</th>";
                  echo "<th>Course</th>";
                  echo "<th>Course Number</th>";
                  echo "<th>Section</th>";
                  echo "<th>Midterm</th>";
                  echo "<th>Final</th>";
                  echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($getTable))
                  {
                    echo "<tr id='idk'>";
                    echo "<td>" . $row['deptName'] . "</td>";
                    echo "<td>" . $row['courseName'] . "</td>";
                    echo "<td>" . $row['courseNum'] . "</td>";
                    echo "<td>" . $row['sectNum'] . "</td>";
                    echo "<td>" . $row['midterm_grade'] . "</td>";
                    echo "<td>" . $row['final_grade'] . "</td>";
                  }
                  echo "</tr>";
                  echo "</tbody>";
                  echo "</table></center>";
          }
          else
          {
               echo "<center><strong><h1 id = 'bf'>You have no classes registered.</h1></strong></center>";
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
