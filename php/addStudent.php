<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "admin") {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>
<!-- another test -->

<html>
   <head>
      <meta charset = "utf-8">
      <title>Add Student</title>
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
          <a href = "adminHomepage.php" class = "myButton">Home</a>
    </center>

      <h1><strong>Add a Student</strong></h1>

      <form method = "post" action = "addStudent.php">

      <p>Student Name</p>
      <!-- Dropdown menu to show people with person type student but are not in the student table -->
      <p><select id = "studentNames" name = "studentNames" required></select></p>
      <option></option>
      <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getStudentsInPerson",
               "studentNames": $('#studentNames').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               for (let i=0; i < data.length; i++) {
                 $('#studentNames').append("<option value = " + data[i].personID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
               }
             }
           });
         });
     </script>

    <p>Major Name</p>
    <!-- Dropdown to show all major names -->
    <p><select id = "majorNames" name = "majorNames"></select></p>
    <option></option>
    <script id="source" language="javascript" type="text/javascript">
     $('document').ready( function() {
       console.log("after document ready");
         $.ajax({
           url: 'api.php',
           data: {
             "fname": "getMajorNames",
             "majorNames": $('#majorNames').val()
           },
           dataType: 'json',
           success: function(data) {
             console.log("in success block of $.ajax()");
             $('#majorNames').append("Select a major");
             for (let i=0; i < data.length; i++) {
               $('#majorNames').append("<option value = " + data[i].majorID + ">" + data[i].majorName + "</option>");
             }
           }
         });
       });
   </script>


   <p>Minor Name</p>
   <!-- Dropdown to show all minor Names -->
   <p><select id = "minorNames" name = "minorNames"></select></p>
   <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      //$('#studentNames').change(function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getMinorNames",
            "minorNames": $('#minorNames').val()
          },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            $('#minorNames').append("<option>Select a minor</option>");
            for (let i=0; i < data.length; i++) {
              $('#minorNames').append("<option value = " + data[i].minorID + ">" + data[i].minorName + "</option>");
            }
          }
        //});
      });
    });
  </script>

    <!-- High School Size -->
    <p>High School Size</p>
      <label>
        <input id ="hsSize" name = "hsSize" type = "number" required />
      </label>

      <!-- Submit and reset buttons -->

      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

      <?php

        error_reporting(E_ALL ^ E_DEPRECATED);


        if(isset($_POST['submit'])){
            createStudent();
           }
      ?>
      <?php

        function createStudent(){

        require_once "mysqli.php";
        //Connect to database
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
         die( "Could not connect to database" );

         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
           die( "Could not open database" );

        //variables to grab user input
        $studentNames = $_POST["studentNames"];
        $majorID = $_POST["majorNames"];
        $minorID = $_POST["minorNames"];
        $hsSize = $_POST["hsSize"];

        //no minor entered
        if($minorID == 'Select a minor')
        {
          $query = mysqliQuery("INSERT INTO `student` (studentID, hsSize) VALUES ('$studentNames', '$hsSize')", MYSQLI_USE_RESULT);
          $studMaj = mysqliQuery("INSERT INTO `studentsMajor` (majorID, studentID) VALUES ('$majorID', '$studentNames')", MYSQLI_USE_RESULT);

          if($query)
          {
            print("<p>Student added</p>");
          }

          if($studMaj)
          {
            print("<p>Student added to studentsMajor</p>");
          }
        }

        //everything is filled out
        else{
          //query to insert what was entered in the fields
          $query = mysqliQuery("INSERT INTO `student` (studentID, hsSize) VALUES ('$studentNames', '$hsSize')", MYSQLI_USE_RESULT);
          $studMin = mysqliQuery("INSERT INTO `studentsMinor` (minorID, studentID) VALUES ('$minorID', '$studentNames')", MYSQLI_USE_RESULT);
          $studMaj = mysqliQuery("INSERT INTO `studentsMajor` (majorID, studentID) VALUES ('$majorID', '$studentNames')", MYSQLI_USE_RESULT);

          if($query)
          {
            print("<p>Student added</p>");
          }
          if($studMin)
          {
            print("<p>Student added to studentsMinor</p>");
          }
          if($studMaj)
          {
            print("<p>Student added to studentsMajor</p>");
          }
        }
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
