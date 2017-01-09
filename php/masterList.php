<?php
session_start();
if ($_SESSION["loggedIn"] == false) {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Master Section List</title>
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
                <a href = "studentHomepage.php" class = "myButton">Home</a>
      	    </p>
        </center>


      <?php
          $term = $_SESSION["term"];
          //PHP code to get all courses.
          require_once 'mysqli.php';
          $query = <<<EOD
          SELECT `department`.`deptName`, `course`.`courseName`, `course`.`courseNum`, `course`.`courseID`, `section`.`sectNum`, `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `section`.`roomNum`, `person`.`firstName`, `person`.`lastName`, `section`.`sectID`
                FROM `section`
                JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                WHERE `section`.`term` = '$term'
                order by `deptName`, `courseNum`, `sectNum`
EOD;
          $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
          $results = mysqli_num_rows($getTable);
          if($results > 0 )
          {

                  echo "<center><table cellspacing='0' class='GreenBlack'>";
                  echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                  echo "<thead>";
                  echo "<tr>";
                  echo "<th>Department</th>";
                  echo "<th style = 'display:none'>Course ID</th>";
                  echo "<th>Course</th>";
                  echo "<th>Course Number</th>";
                  echo "<th>Section</th>";
                  echo "<th>Day(s)</th>";
                  echo "<th>Period Start</th>";
                  echo "<th>Period End</th>";
                  echo "<th>Building</th>";
                  echo "<th>Room</th>";
                  echo "<th>Professor First Name</th>";
                  echo "<th>Professor Last Name</th>";
                  echo "<th style = 'display:none'>Section ID</th>";
                  echo "<th>Register</th>";
                  echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($getTable))
                  {
                    echo "<tr id='idk'>";
                    echo "<td>" . $row['deptName'] . "</td>";
                    echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
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
                    echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                    echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";


                  }
                  echo "</tr>";
                  echo "</tbody>";
                  echo "</table></center>";
          }
          else
          {
            echo "<center><strong><h1 id = 'bf'>No classes available for " . $term . ".</h1></strong></center>";
          }
      ?>


    <script>
      $('document').ready( function() {
          $('.j-register').click( function() {
              var sectionID = $(this).parent().siblings('.section').text();
              console.log(sectionID);
              $.ajax({
                  url: 'api.php',
                  data: {
                      'fname':'checkStudentRestrictions',
                      'sectID': sectionID
                  },
                  dataType: 'json',
                  success: function(data) {
                      if (data.reasons.length == 0) {
                              $.ajax({
                                  url: 'api.php',
                                  data: {
                                      'fname':'registerForAFuckingClass',
                                      'sectID': sectionID
                                  },
                                  success: function(data) {
                                      alert("You are registered.");
                                      console.log(data);

                                      $.ajax({
                                         url: 'api.php',
                                         data: {
                                             'fname': 'updateFulltimeStatus'
                                         },
                                         success: function (data) {
                                           console.log("Fulltime status updated");
                                         },
                                         error: function(request, status, errorThrown) {
                                           console.log(errorThrown);
                                          }
                                     });
                                  }
                               });
                       }
                       else {
                               var alertString = "";
                               for (var i = 0; i<data.reasons.length; i++) {
                                   alertString += data.reasons[i] + "\n";
                               }
                               alert(alertString);
                       }
                  },
                  error: function(request, status, errorThrown) {
                      console.log(errorThrown);
                  }
              });
          });
      });
</script>


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
