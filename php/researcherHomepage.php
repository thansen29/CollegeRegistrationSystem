<?php
session_start();
if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "researcher") {
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
      <title>Researcher Home Page</title>
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
        <p>
          <a href = "researcherTerm.php" class = "myButton">Select Term</a>
          <a href = "courseCatalog.php" class = "myButton">Course Catalog</a>
          <a href = "facMasterList.php" class = "myButton">Semester Schedule</a>
          <a href = "viewGradesByCourse.php" class = "myButton">View Grades</a>
          <a href = "logoff.php" class = "myButton">Log Off</a>
        </p>
    </center>




    <?php
      require_once 'mysqli.php';

      //if(isset($_POST['submit'])){

        $term = $_SESSION['term'];
        $totalStudentsQuery = "SELECT DISTINCT `studentID`
                                           FROM `enrollment`
                                           JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                                           WHERE `section`.`term` = '$term'";
        $totalStudentsQueryResults = mysqliQuery($totalStudentsQuery);
        $totalStudents = mysqli_num_rows($totalStudentsQueryResults);
  	     echo "<p>There are " .$totalStudents . " students currently enrolled in the ".$term . " semester</p>";
     //  }
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
