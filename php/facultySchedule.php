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
      <title>Faculty Schedule</title>
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
                  <a href = "facultyHomepage.php" class = "myButton">Home</a>
                  <a href = "facultySchedule.php" class = "myButton">View Schedule</a>
                  <a href = "viewClassInfo.php" class = "myButton">Class Info</a>
                  <a href = "enterGrades.php" class = "myButton">Enter Grades</a>
                  <a href = "changePW.php" class = "myButton">Change Password</a>
                  <a href = "facMasterList.php" class = "myButton">Search Sections</a>
                  <a href = "courseCatalog.php" class = "myButton">Course Catalog</a>
                  <a href = "viewAdvisees.php" class = "myButton">View Advisees</a>
                  <a href = "attendance.php" class = "myButton">Manage Attendance</a>
                  <a href = "logoff.php" class = "myButton">Log Off</a>
      	    </p>
        </center>

        <!-- Drop down list of terms -->
        <form name = "form" method = "post" action = "facultySchedule.php">
        <p>Select a term:</p>
        <p><select required name="term" id="term" ></select></p>
          <option></option>
          <!-- NEW JS CODE -->
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
                  for (let i=0; i < data.length; i++) {
                    $('#term').append("<option>" + data[i].seasonYear + "</option>");
                  }
                }
              });
            });
        </script>

      <p></p>
      <p>
      <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
      </p>
      </form>

      <?php
        if(isset($_POST['submit']))
        {
            $term = $_POST["term"];
            //PHP code to get all courses.
            require_once 'mysqli.php';
            $query = <<<EOD
            SELECT `course`.`courseNum`, `section`.`sectNum`, `course`.`courseName`, `timeslot`.`days`,
            `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `room`.`roomNum`
            FROM `faculty`
            JOIN `section` ON `section`.`facultyID` = `faculty`.`facultyID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
            JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
            JOIN `room` ON (`building`.`buildingID` = `room`.`buildingID` AND  `section`.`roomNum` = `room`.`roomNum`)
            WHERE `faculty`.`facultyID` = {$_SESSION["id"]} AND `section`.`term` = '$term'
EOD;
            $getTable = mysqliQuery($query);
            $results = mysqli_num_rows($getTable);
            if($results > 0 )
            {
              //Displays table here
              echo "<center><table cellspacing='0' class='GreenBlack'>";
              echo "<caption><strong><h1 id = 'bf'>Faculty Schedule for " . $term . "</h1></strong></caption>";
              echo "<thead>";
              echo "<tr>";
              echo "<th>Course Number</th>";
              echo "<th>Section</th>";
              echo "<th>Course Name</th>";
              echo "<th>Days</th>";
              echo "<th>Period Start</th>";
              echo "<th>Period End</th>";
              echo "<th>Building</th>";
              echo "<th>Room</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while($row = mysqli_fetch_array($getTable))
              {
                echo "<tr id='idk'>";
                echo "<td>" . $row['courseNum'] . "</td>";
                echo "<td>" . $row['sectNum'] . "</td>";
                echo "<td>" . $row['courseName'] . "</td>";
                echo "<td>" . $row['days'] . "</td>";
                echo "<td>" . $row['periodStart'] . "</td>";
                echo "<td>" . $row['periodEnd'] . "</td>";
                echo "<td>" . $row['buildingName'] . "</td>";
                echo "<td>" . $row['roomNum'] . "</td>";
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
