<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "faculty") {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>View Advisees</title>
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
          <a href = "facultyHomepage.php" class = "myButton">Home</a>

    </center>


    <form method = "post" action = "viewAdvisees.php">

      <p>Advisees</p>
      <p><select id = "advisees" name = "advisees" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "viewAdvisees",
                  "advisees": $('#advisees').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#advisees').append("<option>Select a student</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#advisees').append("<option value = " + data[i].studentID + ">" + ' ' + data[i].firstName + ' ' + data[i].lastName + "</option>");
                  }
                }
              });
            });
        </script>

        <p>Term</p>
        <p><select id="term" name = "term"></select></p>
        <script id="source" language="javascript" type="text/javascript">
        $('document').ready( function() {
          console.log("after document ready");
            $.ajax({
              url: 'api.php',
              data: {
                "fname": "getTerms",
                "term": $('#term').val()
              },
              dataType: 'json',
              success: function(data) {
                console.log("in success block of $.ajax()");
                $('#term').append("<option>Select a term</option>");
                for (let i=0; i < data.length; i++) {
                  $('#term').append("<option>" + data[i].seasonYear + "</option>");
                }
              }
            });
          });
      </script>

      <!-- Submit and reset buttons -->

      <p>
        <input id = "submitButton" class = "myButton" name = "submit" type = "submit" value = "View Schedule" />
        <input id = "transcript" class = "myButton" name = "transcript" type = "submit" value = "View Transcript" />
      </p>
    </form>

  <?php
      error_reporting(E_ALL ^ E_DEPRECATED);
      require_once "mysqli.php";

      if(isset($_POST['submit'])){
          viewAdvisee();
         }

      if(isset($_POST['transcript'])){
        viewTranscript();
      }
  ?>
  <?php

      function viewTranscript()
      {
        $studentID = $_POST['advisees'];

        //Query to get transcript info
        $getTranscript =
        "SELECT `course`.`courseNum`, `section`.`sectNum`, `course`.`courseName`, `student_history`.`grade`, `course`.`credits`, `section`.`term`
        FROM `student_history`
        JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
        JOIN `course` ON `course`.`courseID` = `section`.`courseID`
        WHERE `studentID` = '$studentID'
        GROUP BY `section`.`term`";

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
          echo "<th>Course</th>";
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
      }

      function viewAdvisee(){

        //variables to grab user input
        $studentID = $_POST['advisees'];

        $term = $_POST["term"];
         //PHP code to get all courses.
         $query =
         "SELECT `department`.`deptName`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `timeslot`.`days`,
         `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `room`.`roomNum`, `person`.`firstName`, `person`.`lastName`
         FROM `student`
         JOIN `enrollment` ON `enrollment`.`studentID` = `student`.`studentID`
         JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
         JOIN `course` ON `course`.`courseID` = `section`.`courseID`
         JOIN `department` ON `department`.`deptID` = `course`.`deptID`
         JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
         JOIN `person` ON `section`.`facultyID` = `person`.`personID`
         JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
         JOIN `room` ON (`building`.`buildingID` = `room`.`buildingID` AND  `section`.`roomNum` = `room`.`roomNum`)
         WHERE `student`.`studentID` = '$studentID' AND `section`.`term` = '$term'";

         $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
         $results = mysqli_num_rows($getTable);
         if($results > 0 )
         {

                 echo "<center><table cellspacing='0' class='GreenBlack'>";
                 echo "<caption><strong><h1 id = 'bf'>Student Schedule for " . $term . "</h1></strong></caption>";
                 echo "<thead>";
                 echo "<tr>";
                 echo "<th>Department</th>";
                 echo "<th>Course</th>";
                 echo "<th>Course Number</th>";
                 echo "<th>Section</th>";
                 echo "<th>Days</th>";
                 echo "<th>Period Start</th>";
                 echo "<th>Period End</th>";
                 echo "<th>Building</th>";
                 echo "<th>Room</th>";
                 echo "<th>Professor First Name</th>";
                 echo "<th>Professor Last Name</th>";
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
                   echo "<td>" . $row['days'] . "</td>";
                   echo "<td>" . $row['periodStart'] . "</td>";
                   echo "<td>" . $row['periodEnd'] . "</td>";
                   echo "<td>" . $row['buildingName'] . "</td>";
                   echo "<td>" . $row['roomNum'] . "</td>";
                   echo "<td>" . $row['firstName'] . "</td>";
                   echo "<td>" . $row['lastName'] . "</td>";
                 }
                 echo "</tr>";
                 echo "</tbody>";
                 echo "</table></center>";
         }
         else
         {
              echo "<center><strong><h1 id = 'bf'>You have no classes registered for " . $term . "</h1></strong></center>";
         }

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
