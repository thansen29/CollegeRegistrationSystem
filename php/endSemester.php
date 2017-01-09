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
      <title>End Semester</title>
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

    <form method = "post" action = "endSemester.php">


      <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "End Semester" />
    </form>
    <?php

      require_once 'mysqli.php';

      if(isset($_POST['submit'])){

        //see if a student is currently taking a course that they have already taken in the past - gets the student ID, final grade from ENROLLMENT and sectID, grade from history
        $retakingClass = mysqliQuery("SELECT `currentClasses`.`studentID`, `pastClasses`.`sectID`, `currentClasses`.`final_grade`,`pastClasses`.`grade`
                                      FROM
                                      	(SELECT `course`.`courseID`, `enrollment`.`studentID`, `enrollment`.`sectID`, `enrollment`.`final_grade`
                                      	FROM `enrollment`
                                          JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                                          JOIN `course` ON `course`.`courseID` = `section`.`courseID`) as currentClasses
                                      LEFT OUTER JOIN
                                      	(SELECT `course`.`courseID`, `student_history`.`studentID`, `student_history`.`sectID`, `student_history`.`grade`
                                       	FROM `student_history`
                                       	JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
                                       	JOIN `course` ON `course`.`courseID` = `section`.`courseID`) as pastClasses

                                       ON `currentClasses`.`courseID` = `pastClasses`.`courseID`
                                       WHERE `currentClasses`.`studentID` = `pastClasses`.`studentID` AND `currentClasses`.`courseID` = `pastClasses`.`courseID`", MYSQLI_USE_RESULT);
        if(!$retakingClass){
          echo "retakingClass query didnt work";
        }

        //$retakingClassRows = mysqli_num_rows($retakingClass);

        //while there are cases of a student is retaking a class
        while($row = mysqli_fetch_array($retakingClass)){
          echo "<script>console.log('inside the first while loop')</script>";
          //get the studentID
          $student = $row['studentID'];

          //gets the section id from student history
          $sectID = $row['sectID'];

          //get the final grade of that case from enrollment
          $finalGrade = $row['final_grade'];

          //now update the row in student history with the new  grade
          $updateQuery = mysqliQuery("UPDATE `student_history`
                                      SET `grade` = '$finalGrade'
                                      WHERE `student_history`.`studentID` = '$student' AND `student_history`.`sectID` = '$sectID'", MYSQLI_USE_RESULT);

      }

      //get all the studentIDs, sectIDs, final grades from the enrollment table
      $enrollmentInfo = mysqliQuery("SELECT `enrollment`.`studentID`, `enrollment`.`sectID`, `enrollment`.`final_grade`
                                     FROM `enrollment`");
      //loop through and insert each row into the history table
      while($newRow = mysqli_fetch_array($enrollmentInfo)){
        echo "<script>console.log('inside the second while loop')</script>";
        //get all the values
        $studID = $newRow['studentID'];
        $sectionID = $newRow['sectID'];
        $grade = $newRow['final_grade'];

        //insert into the student history table
        $insertQuery = mysqliQuery("INSERT INTO `student_history` (studentID, sectID, grade) VALUES ('$studID', '$sectionID', '$grade')", MYSQLI_USE_RESULT);
      }

      //now empty the enrollment table
      $deleteQuery = mysqliQuery("DELETE FROM `enrollment`", MYSQLI_USE_RESULT);

      //reset the faculty and students to not being fullTime
      $fullTimeUpdate = mysqliQuery("UPDATE `student`, `faculty` SET `student`.`fullTime` = 0, `faculty`.`fullTime` = 0", MYSQLI_USE_RESULT);



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
