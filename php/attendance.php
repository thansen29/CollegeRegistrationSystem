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
      <title>Manage Attendance</title>
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


    <h1><strong>Manage Attendance</strong></h1>

    <form method = "post" action = "attendance.php">

      <p>Term</p>
      <p><select id = "term" name = "term" required></select></p>
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

          <!-- Dropdown menu to display the courses they are teaching -->
          <p>Courses</p>
          <p><select id = "courseList" name = "courseList"></select></p>
          <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            $('#term').change(function() {
             $('#courseList').empty();
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getCoursesByFaculty",
                  "courseList": $('#courseList').val(),
                  "term": $('#term').val()
                },
                dataType: 'json',
                success: function(data) {
                  $('#courseList').append("<option>Select a course</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#courseList').append("<option value = "+ data[i].sectID + ">" + data[i].courseName + " - " + data[i].sectNum + "</option>");
                  }
                }
              });
             });
           });
            </script>

      <!-- Submit and reset buttons -->
      <p>
        <input id = "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
      </p>
    </form>

      <?php
      error_reporting(E_ALL ^ E_DEPRECATED);

      require_once 'mysqli.php';
            if(isset($_POST['submit']))
            {
              $courseList = $_POST["courseList"];
              //$date = date("Y-m-d");

              //query to get the students in the class selected
              $query = "SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `student`.`studentID`, `section`.`sectID`
                FROM `person`
                JOIN `student` ON `person`.`personID` = `student`.`studentID`
                JOIN `enrollment` ON `student`.`studentID` = `enrollment`.`studentID`
                JOIN `section` ON `enrollment`.`sectID` = `section`.`sectID`
                WHERE `section`.`sectID` = '$courseList'";

              $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
              $results = mysqli_num_rows($getTable);
              if($results > 0)
              {
                echo "<center><table cellspacing='0' id = 'mytable' class='GreenBlack'>";
                echo "<caption><strong><h1 id = 'bf'>Classes</h1></strong></caption>";
                echo "<thead>";
                echo "<tr>";
                echo "<th style = 'display:none'>StudentID</th>";
                echo "<th>First Name</th>";
                echo "<th>Last Name</th>";
                echo "<th style = 'display:none'>Section</th>";
                echo "<th>Update</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while($row = mysqli_fetch_array($getTable))
                {
                  echo "<tr id='idk'>";
                  echo "<td class = 'studentID' style = 'display:none'>" . $row['studentID'] . "</td>";
                  echo "<td>" . $row['firstName'] . "</td>";
                  echo "<td>" . $row['lastName'] . "</td>";
                  echo "<td class = 'section' style = 'display:none'>" . $row['sectID'] . "</th>";
                  echo "<td>" . "<input type = 'checkbox' /> ". "</td>";
                }
                  echo "</tr>";
                  echo "</tbody>";
                  echo "</table></center>";
        }
        else
        {
          echo "<center><strong><h1 id = 'bf'>No students are in this class yet" . "</h1></strong></center>";
        }
      }
?>

  <!-- date and submit  -->

  <p>
    <center>
      <label>Date
        <input id = "date" name = "date" type = "date" />
      </label>

      <input id = "update" class = "myButton j-register" name = "update" type = "submit" value = "Update" />
    </center>
  </p>

  <script>
  //need to get the studentIDs attached to the row where the checkbox is checked
       $('document').ready( function() {
           $('.j-register').click( function() {
             var id = $(this).parent().siblings('.studentID').text();
             var students = $("#find-table input:checkbox:checked").map(function(){
               //return $(this).parent().siblings('.studentID').text();
               return $(this).val();
             }).get(id);
             console.log(students);
            // var date = $('#date').val();
             //var sectID = $(this).parent().siblings('.section').text();

            // console.log("studentID: " + studentID + " meetingDate: " + meetingDate +
            //    " sectID: " + sectID);

            //   $.ajax({
              //     url: 'api.php',
              //     data: {
              //         'fname': 'manageAttendance',
              //         'studentID': studentID,
              //         'meetingDate': date,
              //         'sectID': sectID
              //     },
              //     dataType: 'json',
              //     success: function(data) {
              //         alert("Attendance updated");
              //   },
              //      error: function(request, status, errorThrown) {
              //        alert(errorThrown);
                //  }
            // });
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
