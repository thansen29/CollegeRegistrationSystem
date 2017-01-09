<?php
session_start();
if ($_SESSION["loggedIn"] == false) {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
if (isset($_POST['term'])) {
    $_SESSION['term'] = $_POST['term'];
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Registration</title>
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
                <a href = "studentHomepage.php" class = "myButton">Home</a>
      	    </p>
        </center>

        <form method = "post" action = "registration.php">

        <p><h1>Look up classes to add</h1></p>


        <!-- Dropdown menu to display the subjects (departments) -->
        <p>Subjects</p>
        <p><select id = "deptNames" name = "deptNames"></select></p>
        <script id="source" language="javascript" type="text/javascript">
        $('document').ready( function() {
          console.log("after document ready");
          $('#deptNames').empty();
            $.ajax({
              url: 'api.php',
              data: {
                "fname": "getDepartmentNamesByTerm",
                "deptNames": $('#deptNames').val()
              },
              dataType: 'json',
              success: function(data) {
                console.log("in success block of $.ajax()");
                for (let i=0; i < data.length; i++) {
                  $('#deptNames').append("<option value = " + data[i].deptID + ">" + data[i].deptName + "</option>");
                 }
                }
              });
          });
          </script>

          <p>
          <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
        </form>
          </p>

        <form method = "post" action = "advancedSearch.php">
          <input id "advancedSearch" class = "myButton" name = "submit" type = "submit" value = "Advanced Search" />
        </form>

        <p>
        <form method = "post" action = "masterList.php">
           <input id = "masterList" class = "myButton" name = "submit" type = "submit" value = "View All Sections" />
        </form>
        </p>

          <?php
            if(isset($_POST['submit']))
            {
              $dept = $_POST["deptNames"];
              $term = $_SESSION['term'];
              require_once 'mysqli.php';

              $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                       `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                       `person`.`firstName`, `person`.`lastName`, `section`.`sectID`
                       FROM `section`
                       JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                       JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                       JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                       JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                       JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                       WHERE `department`.`deptID` = '$dept' AND `section`.`term` = '$term'
                       order by `courseNum`, `courseName`, `sectNum`";

              $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
          $results = mysqli_num_rows($getTable);
          if($results > 0 )
          {

                  echo "<center><table cellspacing='0' class='GreenBlack'>";
                  echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                  echo "<thead>";
                  echo "<tr>";
                  echo "<th>Department</th>";
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
