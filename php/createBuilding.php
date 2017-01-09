<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "admin") {
        header("Location: http://www.agru.co.nf/index.html");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Create Building</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
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

        <!-- Navigation Bar -->
          <center>
            <p>
                  <a href = "login.php" class = "myButton">Home</a>
                  <a href = "createPerson.php" class = "myButton">Create Person</a>
                  <a href = "createCourse.php" class = "myButton">Create Course</a>
                  <a href = "department.php" class = "myButton">Departments</a>
                  <a href = "createHold.php" class = "myButton">Create Hold</a>
                  <a href = "assignHold.php" class = "myButton">Assign Hold</a>
                  <a href = "createSection.php" class ="myButton">Create Section</a>
                  <a href = "addStudent.php" class ="myButton">Add Student<a>
                  <a href = "addFaculty.php" class ="myButton">Add Faculty<a>
                  <a href = "addAdmin.php" class ="myButton">Add Admin<a>
                  <a href = "addResearcher.php" class ="myButton">Add Researcher<a>
                  <a href = "createBuilding.php" class ="myButton">Create Building<a>
      	    </p>
        </center>

        <!-- Form gets building info -->
        <center><h1>Create Building</h1></center>
        <form name = "form" method = "post" action = "createBuilding.php">
          <p>Building Name:</p>
            <label>
              <input id = "firstName" name = "bname" type = "text" required/>
            </label>
          <p>Street Address:</p>
            <label>
              <input id = "lastName" name = "saddress" type = "text" required/>
            </label>
        <p></p>
        <p>
          <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Create building" />
        </p>


      <?php
        if(isset($_POST['submit']))
        {
          //PHP code insert building info.
          $id = null;
          $name = $_POST["bname"];
          $street = $_POST["saddress"];
          require_once 'mysqli.php';
          //Checks if a previous building matches info. If yes, then it prevents adding it to system.
          $restriction = mysqliQuery("SELECT `buildingName`, `buildingAddress` FROM `building` WHERE `buildingName` = '$name' AND `buildingAddress` = '$street' ", MYSQLI_USE_RESULT);
          $restrictionResults = mysqli_num_rows($restriction);
          if($restrictionResults)
          {
              print("<center><p><h1>This building already exists in the system. Building not added.</h1></p></center>");
              die(mysql_error() . "</body></html>");
          }
          $insertBuild = "INSERT INTO `building` VALUES ('$id','$name', '$street')";
          if(!($updateTable = mysqliQuery($insertBuild, MYSQLI_USE_RESULT)))
          {
            echo "<center><strong><h1 id = 'bf'>Building could not be created.</h1></strong></center>";
          }
          else
          {
            echo "<center><strong><h1 id = 'bf'>Building created.</h1></strong></center>";
          }
        }
      ?>



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
