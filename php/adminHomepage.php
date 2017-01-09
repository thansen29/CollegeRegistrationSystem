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
      <title>Admin Home Page</title>
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
          <a href = "index.html" class = "myButton">Home</a>
          <a href = "createPerson.php" class = "myButton">Create Person</a>
          <a href = "createCourse.php" class = "myButton">Create Course</a>
          <a href = "viewUsers.php" class = "myButton">View Users</a>
          <a href = "editUserSchedule.php" class = "myButton">Edit User Schedules</a>
          <a href = "department.php" class = "myButton">Create Department</a>
          <a href = "editDepartment.php" class = "myButton">Edit Department</a>
          <a href = "createHold.php" class = "myButton">Create Hold</a>
          <a href = "assignHold.php" class = "myButton">Assign Hold</a>
          <a href = "removeHold.php" class = "myButton">Remove Hold</a>
          <a href = "createSection.php" class ="myButton">Create Section</a>
          <a href = "addAdvisor.php" class ="myButton">Add Advisor<a>
          <a href = "reviewMajorApplication.php" class ="myButton">Review Major<a>
          <a href = "reviewMinorApplication.php" class ="myButton">Review Minor<a>
          <a href = "createBuilding.php" class ="myButton">Create Building<a>
          <a href = "adminMasterList.php" class = "myButton">View Sections</a>
          <a href = "adminCourseCatalog.php" class = "myButton">Course Catalog</a>
          <a href = "changePW.php" class = "myButton">Change Password</a>
          <a href = "adminChangePassword.php" class = "myButton">Change a user's password</a>
          <a href = "logoff.php" class = "myButton">Log Off</a>
          <a href = "endSemester.php" class = "myButton">End Semester</a>
        </p>
    </center>


	  <center><p>ADMIN HOME PAGE</p></center>

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
