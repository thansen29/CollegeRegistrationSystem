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
      <title>View Class Info</title>
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
          <a href = "enterGrades.php" class = "myButton">Enter Grades</a>

    </center>


    <h1><strong>Class Info</strong></h1>

    <form method = "post" action = "viewClassInfo.php">

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
        <input id = "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id = "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>
    </form>

      <?php
     if(isset($_POST['submit']))
     {
       $courseList = $_POST["courseList"];
       require_once 'mysqli.php';

       $query = "SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `student`.`studentID`,
        `section`.`sectID`, `enrollment`.`midterm_grade`,`enrollment`.`final_grade`
            FROM `person`
            JOIN `student` ON `person`.`personID` = `student`.`studentID`
            JOIN `enrollment` ON `student`.`studentID` = `enrollment`.`studentID`
            JOIN `section` ON `enrollment`.`sectID` = `section`.`sectID`
            WHERE `section`.`sectID` = '$courseList'";

       $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
       $results = mysqli_num_rows($getTable);
       if($results > 0)
       {
         echo "<center><table cellspacing='0' class='GreenBlack'>";
         echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
         echo "<thead>";
         echo "<tr>";
         echo "<th>First Name</th>";
         echo "<th>Last Name</th>";
         echo "<th>Midterm Grade</th>";
         echo "<th>Final Grade</th>";
         echo "</tr>";
         echo "</thead>";
         echo "<tbody>";
         while($row = mysqli_fetch_array($getTable))
         {
           echo "<tr id='idk'>";
           echo "<td>" . $row['firstName'] . "</td>";
           echo "<td>" . $row['lastName'] . "</td>";
           echo "<td>" . $row['midterm_grade'] . "</td>";
           echo "<td>" . $row['final_grade'] . "</td>";
         }
         echo "</tr>";
         echo "</tbody>";
         echo "</table></center>";
 }
 else
 {
   echo "<center><strong><h1 id = 'bf'>No students are in this class" . "</h1></strong></center>";
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
