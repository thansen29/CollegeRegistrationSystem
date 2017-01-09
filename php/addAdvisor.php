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
      <title>Add Advisor</title>
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


    <form method = "post" action = "addAdvisor.php">

      <p>Student</p>
      <p><select id = "student" name = "student" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getStudentNames",
                  "student": $('#student').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#student').append("<option>Select a student</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#student').append("<option value = " + data[i].studentID + ">" + ' ' + data[i].firstName + ' ' + data[i].lastName + "</option>");
                  }
                }
              });
            });
        </script>

        <p>Faculty</p>
        <p><select id = "faculty" name = "faculty" required></select></p>
        <script id="source" language="javascript" type="text/javascript">
            $('document').ready( function() {
              console.log("after document ready");
                $.ajax({
                  url: 'api.php',
                  data: {
                    "fname": "getFacultyNames",
                    "faculty": $('#faculty').val()
                  },
                  dataType: 'json',
                  success: function(data) {
                    console.log("in success block of $.ajax()");
                    $('#faculty').append("<option>Select a faculty</option>");
                    for (let i=0; i < data.length; i++) {
                      $('#faculty').append("<option value = " + data[i].facultyID + ">" + ' ' + data[i].firstName + ' ' + data[i].lastName + "</option>");
                    }
                  }
                });
              });
          </script>

      <!-- Submit and reset buttons -->

      <p>
        <input id = "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id = "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>
    </form>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>


  <?php
      error_reporting(E_ALL ^ E_DEPRECATED);

      if(isset($_POST['submit'])){
          addAdvisor();
         }

      function addAdvisor(){
        require_once "mysqli.php";

        //Connect to database
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
         die( "Could not connect to database" );

         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
           die( "Could not open database" );

        //variables to grab user input
        $student = $_POST['student'];
        $faculty = $_POST['faculty'];

        //check if thats already a combo
        $check = mysqliQuery("SELECT `studentID`, `facultyID` FROM `advisement` WHERE `studentID` = '$student' AND `facultyID` = '$faculty'", MYSQLI_USE_RESULT);
        $checkResults = mysqli_num_rows($check);
        if($checkResults > 0)
        {
          print("<p><h1>This student is already being advised by this faculty member</h1></p>");
          die();
        }
        //insert
        $query = mysqliQuery("INSERT INTO `advisement` (studentID, facultyID) VALUES ('$student', '$faculty')", MYSQLI_USE_RESULT);
        if(!$query)
        {
          print("<p>Could not execute query!</p>");
          die(mysql_error());
        }

        else{
         print("<p><h1>Successfully added to advisement table</h1></p>");
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
