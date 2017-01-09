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
      <title>Faculty Home Page</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, University, Focused, Student">
      <meta name = "description" content = "The perfect university for focused people.">
      <link rel = "stylesheet" type = "text/css"
         href = "AGstyle.css">
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

	  <!-- Introduction text -->

	  <center><p>FACULTY HOME PAGE
    </p></center>

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
