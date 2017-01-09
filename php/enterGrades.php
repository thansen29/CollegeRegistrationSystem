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
      <title>Enter Grades</title>
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


    <h1><strong>Enter Grades</strong></h1>

    <form method = "post" action = "enterGrades.php">

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

            <!-- Dropdown menu to display the students in the class -->
            <p>Students in the course selected</p>
            <p><select id = "studentList" name = "studentList"></select></p>
            <script id="source" language="javascript" type="text/javascript">
            $('document').ready( function() {
              console.log("after document ready");
              $('#courseList').change( function() {
              $('#studentList').empty();
                $.ajax({
                  url: 'api.php',
                  data: {
                    "fname": "getStudentsBySection",
                    "courseList": $('#courseList').val()
                  },
                  dataType: 'json',
                  success: function(data) {
                    console.log("in success block of $.ajax()");
                    for (let i=0; i < data.length; i++) {
                      $('#studentList').append("<option value = " + data[i].studentID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
                    }
                  }
                });
              });
            });
            </script>

        <!--Midterm Grade field -->
        <p>Midterm Grade</p>
          <label>
            <input id = "midterm" name = "midterm" type = "number"
            min = "0"
            max = "100"
            step = "1" />
        </label>

        <!--Final Grade field -->
        <p>Final Grade</p>
          <label>
            <input id = "final" name = "final" type = "number"
            min = "0"
            max = "100"
            step = "1" />
        </label>

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
          submitGrade();
         }

      require_once "mysqli.php";
      function submitGrade(){

        //Connect to database
        if ( !( $database = mysql_connect( "fdb4.biz.nf",
           "2218832_reg", "wasd1324" ) ) )
         die( "Could not connect to database" );

         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
           die( "Could not open database" );

        //variables to grab user input
        $courseList = $_POST["courseList"];
        $midterm = $_POST["midterm"];
        $final = $_POST["final"];
        $studentName = $_POST["studentList"];

        //query to update midterm grade if the midterm grade is entered
        if(!empty($midterm))
        {
          $updateMidterm = "UPDATE `enrollment` SET `midterm_grade` = '$midterm'
            WHERE `enrollment`.`studentID` = '$studentName' AND `enrollment`.`sectID` = '$courseList'";

          if ( !( $updateMidtermResult = mysql_query( $updateMidterm, $database ) ) )
          {
             print( "<p>Could not execute query!</p>" );
             die( mysql_error() . "</body></html>" );
          } // end if
          else {
            echo "<p>Midterm grade successfully added</p>";
          }
              //nested if final is also entered
              if(!empty($final))
                {
                  $updateFinal = "UPDATE `enrollment` SET `final_grade` = '$final'
                    WHERE `enrollment`.`studentID` = '$studentName' AND `enrollment`.`sectID` = '$courseList'";

                  if ( !( $updateFinalResult = mysql_query( $updateFinal, $database ) ) )
                  {
                     print( "<p>Could not execute query!</p>" );
                     die( mysql_error() . "</body></html>" );
                  } // end if
                  else {
                    echo "<p>Final grade successfully added</p>";
                       }
                }
           }

           //else just final entered
          else if (empty($midterm) && !empty($final)){
            $updateFinal = "UPDATE `enrollment` SET `final_grade` = '$final'
                  WHERE `enrollment`.`studentID` = '$studentName' AND `enrollment`.`sectID` = '$courseList'";

            if ( !( $updateFinalResult = mysql_query( $updateFinal, $database ) ) )
            {
              print( "<p>Could not execute query!</p>" );
              die( mysql_error() . "</body></html>" );
             } // end if
            else {
              echo "<p>Final grade successfully added</p>";
            }
           }
           //nothing is entered
           else{
              print( "<p><h1>You didn't enter anything!</h1></p>" );
              die( mysql_error() . "</body></html>" );
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
