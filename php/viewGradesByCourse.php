<?php
   session_start();
   if ($_SESSION["loggedIn"] == false || $_SESSION["type"] != "researcher") {
           header("Location: http://www.agru.co.nf/index.html");
           die();
   }
   ?>
   <!DOCTYPE html>


   <html>
      <head>
         <meta charset = "utf-8">
         <title>View Grades</title>
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


       <form method = "post" action = "viewGradesByCourse.php">

         <p>Select a term</p>
         <p><select required name="term" id="term" ></select></p>
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
                 for (let i=0; i < data.length; i++) {
                   $('#term').append("<option>" + data[i].seasonYear + "</option>");
                 }
               }
             });
           });
       </script>

       <h1>Select a course</h1>
       <p><select id = "sectionList" name = "sectionList"></select></p>
       <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         $('#term').change(function () {
         $('#sectionList').empty();
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getSectionsByTerm",
               "term": $('#term').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               for (let i=0; i < data.length; i++) {
                 $('#sectionList').append("<option value = "+ data[i].courseID + ">" + data[i].courseName + "</option>");
               }
             }
           });
         });
       });
       </script>

       <p>
         <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
       </p>
     </form>

     <?php
      require_once 'mysqli.php';
      if(isset($_POST['submit'])){

        $term = $_POST['term'];
        $courseID = $_POST['sectionList'];
        echo "<script>console.log('courseID: ' + $courseID)</script>";


        //query the student history table to view the grades of all sections of a course for a given semester
        $getGradeBook = mysqliQuery("SELECT `grade`
                                     FROM `student_history`
                                     JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
                                     JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                                     WHERE `section`.`term` = '$term' AND `section`.`courseID` = '$courseID' ", MYSQLI_USE_RESULT);
        //gets number of rows returned
        $gradeRows = mysqli_num_rows($getGradeBook);

         //if rows are returned
         if($gradeRows > 0 )
         {
           //array to store all the grades
           $totalGrades = array();
           while($gradeData = mysqli_fetch_array($getGradeBook))
           {
             array_push($totalGrades, $gradeData['grade']);

           }
           //gets the amount of grades
           $querynumOfGrades = "SELECT *
                                FROM `student_history`
                                JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
                                JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                                WHERE `section`.`term` = '$term' AND `section`.`courseID` = '$courseID'";
          $numOfGradesResult = mysqliQuery($querynumOfGrades, MYSQLI_USE_RESULT);
          $numOfGrades = mysqli_num_rows($numOfGradesResult);

           //adds the values inside the array
           $actualGrades = array_sum($totalGrades);

           echo "<script>console.log('totalGrades:' + $actualGrades)</script>";
           echo "<script>console.log('numOfGrades:' + $numOfGrades)</script>";

           //actual grades / the amount gives you the average
           $gpa = $actualGrades / $numOfGrades;
           echo "<script>console.log('GPA: ' + $gpa)</script>";

           echo "<center><strong><h1 id = 'bf'>The average GPA is: ".$gpa."</h1></strong></center>";
         }
         else
         {
           echo "<center><strong><h1 id = 'bf'>There are no grades to calculate a GPA with.</h1></strong></center>";
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
