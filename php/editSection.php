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
      <title>Edit Section</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
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
	  <!-- Navigation bar -->
      <center>
          <a href = "adminHomepage.php" class = "myButton">Home</a>

    </center>

    <form method = "post" action = "editSection.php">

    <!-- Term -->

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

      <!-- Course Name -->

      <p>Course Name</p>
      <p><select id = "courseID" name = "courseID" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getCourseNames",
                  "courseID": $('#courseID').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#courseID').append("<option>Select a course</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#courseID').append("<option value = "+ data[i].courseID + ">" + data[i].courseName + "</option>");
                  }
                }
              });
            });

       </script>

      <!--Faculty Name-->

      <p>Instructor Name</p>
      <p><select id = "facultyID" name = "facultyID" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
            $('#courseID').change( function() {
            console.log("after changing course");
            $('#facultyID').empty();
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getFacCourseDept",
                  "courseID": $('#courseID').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax() faculty");
                  $('#facultyID').append("<option>TBA</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#facultyID').append("<option value = "+ data[i].facultyID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
                  }
                }
              });
            });
          });
      </script>

  <!-- Timeslot -->

     <p>Timeslot</p>
     <p><select id ="timeslot" name = "timeslot" required></select></p>
     <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            $('#facultyID').change(function(){
             $('#timeslot').empty();
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getAvailableTimeslots",
                  "term": $('#term').val(),
                  "facultyID": $('#facultyID').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#timeslot').append("<option>Select a timeslot</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#timeslot').append("<option value = " + data[i].timeslotID + ">" + data[i].days + " " + data[i].periodStart + " - " + data[i].periodEnd + "</option>");
                  }
                }
              });
            });
           });
        </script>


     <!-- Building Name -->
     <p>Building Name</p>
     <p><select id="buildingSelect"  name = "buildingSelect" required> </select></p>
     <script id="source" language="javascript" type="text/javascript">
             $('document').ready( function() {
               $('#timeslot').change(function() {
                 $('#buildingSelect').empty();
                 console.log("changed timeslot");
                 $.ajax({
                   url: 'api.php',
                   data: {
                     "fname": "getAvailableBuildings",
                     "timeslotID": $('#timeslot').val(),
                     "term": $('#term').val()
                   },
                   dataType: 'json',
                   success: function(data) {
                     console.log("in success block of $.ajax()");
                     $('#buildingSelect').append("<option>Select a building</option>");
                     for (let i=0; i < data.length; i++) {
                       $('#buildingSelect').append("<option value=" + data[i].buildingID+ ">" + data[i].buildingName + "</option>");
                     }
                   }
                 });
               });
               console.log("after document ready");
               });
        </script>


        <!-- Room Type -->
        <p>Room Type</p>
        <select id="roomType" name = "roomType" required></select>
        <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
            $('#buildingSelect').change( function() {
              $('#roomType').empty();
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getRoomTypesByBuilding",
                  "buildingID": $('#buildingSelect').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#roomType').append("<option>Select a room type</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#roomType').append("<option>" + data[i].roomType + "</option>");
                  }
                }
              });
            });
          });
        </script>

      <!-- Room Number -->
      <p>Room Number</p>
      <p><select id = "roomNumber" name = "roomNumber" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
            $('#roomType').change( function() {
              $('#roomNumber').empty();
              console.log("hello? " + $('#buildingSelect').val());
              console.log("hello? " + $('#roomType').val());
              console.log("hello? " + $('#timeslot').val());
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getSpecificRoomNumbers",
                  "buildingID": $('#buildingSelect').val(),
                  "roomType": $('#roomType').val(),
                  "timeslotID": $('#timeslot').val(),
                  "term": $('#term').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("success");
                  $('#roomNumber').append("<option>Select a room number</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#roomNumber').append("<option>" + data[i].roomNum + "</option>");
                  }
                }
              });
            });
          });
       </script>

      <!-- RegistrationCap -->
      <p>Registration Cap</p>
        <label>
          <input id ="regCap" name = "regCap" type = "number" required />
        </label>

      <!-- Submit and reset buttons -->

      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>
  </form>


    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

    <?php
      error_reporting(E_ALL ^ E_DEPRECATED);

      require_once 'mysqli.php';

      if(isset($_POST['submit'])){
        editSection();
      }

      function editSection()
      {
        $courseID = $_POST["courseID"];
        $facultyID = $_POST["facultyID"];
        $timeslot = $_POST["timeslot"];
        $buildingSelect = $_POST["buildingSelect"];
        $roomNumber = $_POST["roomNumber"];
        $term = $_POST["term"];
        $regCap = $_POST["regCap"];

        //query to update the section table. need to figure out how to get the sectionID from the adminMasterList
        $updateQuery = mysqliQuery("UPDATE `section` (courseID, facultyID, roomNum, buildingID, sectNum, term, timeslotID, regCap)
                              VALUES ('$courseID', '689', '$roomNumber', '$buildingSelect', '$finalSectNum', '$term', '$timeslot',
                                        '$regCap') WHERE `section`.`sectID` = '$sectID'"), MYSQLI_USE_RESULT);
      }

     ?>

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
