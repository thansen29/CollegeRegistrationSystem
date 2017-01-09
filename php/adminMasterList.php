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
      <title>Admin Master Section List</title>
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
        <form name = "form" method = "post" action = "adminMasterList.php">
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
          SELECT `department`.`deptName`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `section`.`roomNum`, `person`.`firstName`, `person`.`lastName`, `section`.`sectID`, `section`.`regCap`, `section`.`term`
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
                  echo "<th>term</th>";
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
                  echo "<th>Reg Cap</th>";
                  echo "<th>Section ID</th>";
                  echo "<th>Edit</th>";
                  echo "</tr>";
                  echo "</thead>";
                  echo "<tbody>";
                  while($row = mysqli_fetch_array($getTable))
                  {
                    echo "<tr id='idk'>";
                    echo "<td class = 'term'><div = contenteditable>" . $row['term'] . "</div></td>";
                    echo "<td class = 'deptID'><div = contenteditable>" . $row['deptName'] . "</div></td>";
                    echo "<td class = 'courseName'><div = contenteditable>" . $row['courseName'] . "</div></td>";
                    echo "<td class = 'courseNum'><div = contenteditable>" . $row['courseNum'] . "</div></td>";
                    echo "<td class = 'sectNum'><div = contenteditable>" . $row['sectNum'] . "</div></td>";
                    echo "<td class = 'days'><div = contenteditable>" . $row['days'] . "</div></td>";
                    echo "<td class = 'periodStart'><div = contenteditable>" . $row['periodStart'] . "</div></td>";
                    echo "<td class = 'periodEnd'><div = contenteditable>" . $row['periodEnd'] . "</div></td>";
                    echo "<td class = 'buildingName'><div = contenteditable>" . $row['buildingName'] . "</div></td>";
                    echo "<td class = 'roomNum'><div = contenteditable>" . $row['roomNum'] . "</div></td>";
                    echo "<td class = 'firstName'><div = contenteditable>" . $row['firstName'] . "</div></td>";
                    echo "<td class = 'lastName'><div = contenteditable>" . $row['lastName'] . "</div></td>";
                    echo "<td class = 'regCap'><div = contenteditable>" . $row['regCap'] . "</div></td>";
                    echo "<td class='section'>" . $row['sectID'] . "</th>";
                    echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Edit'>Edit</button> ". "</td>";


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
              var term = $(this).parent().siblings('.term').text();
              var deptID = $(this).parent().siblings('.deptID').text();
              var courseName = $(this).parent().siblings('.courseName').text();
              var courseNum = $(this).parent().siblings('.courseNum').text();
              var sectNum = $(this).parent().siblings('.sectNum').text();
              var days = $(this).parent().siblings('.days').text();
              var periodStart = $(this).parent().siblings('.periodStart').text();
              var periodEnd = $(this).parent().siblings('.periodEnd').text();
              var buildingName = $(this).parent().siblings('.buildingName').text();
              var roomNum = $(this).parent().siblings('.roomNum').text();
              var firstName = $(this).parent().siblings('.firstName').text();
              var lastName = $(this).parent().siblings('.lastName').text();
              var regCap = $(this).parent().siblings('.regCap').text();
              var sectID = $(this).parent().siblings('.section').text();

              console.log("term: " + deptID + " courseName: " + courseName +
              " courseNum: " + courseNum + " sectNum: " + sectNum + "days: " + days + " periodStart: " + periodStart +
              " periodEnd: " + periodEnd + " buildingName: " + buildingName + " roomNum: " + roomNum +
              " firstName: " + firstName + " lastName: " + lastName + " regCap: " + regCap + " sectID: " + sectID);
              $.ajax({
                  url: 'api.php',
                  data: {
                      'fname':'editAFuckingClass',
                      'term': term,
                      'deptID': deptID,
                      'courseName': courseName,
                      'courseNum': courseNum,
                      'sectNum': sectNum,
                      'days': days,
                      'periodStart': periodStart,
                      'periodEnd': periodEnd,
                      'buildingName': buildingName,
                      'roomNum': roomNum,
                      'firstName': firstName,
                      'lastName': lastName,
                      'regCap': regCap,
                      'sectID': sectID

                  },
                  dataType: 'json',
                  success: function(data) {
                   alert("Course Successfully edited");
                  },
                  error: function(request, status, errorThrown){
                   alert("Course Edit Failed. Invalid input");
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
