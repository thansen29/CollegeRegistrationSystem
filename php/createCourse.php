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
      <title>Create Course</title>
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


    <form name = "form" method = "post" action = "createCourse.php">

    <!-- Course Name -->
    <p>Course Name</p>
      <label>
        <input id ="courseName" name = "courseName" type = "text" required />
      </label>

      <!-- Course Numbmer -->
      <p>Course Number</p>
        <label>
          <input id ="courseNum" name = "courseNum" type = "number" required />
        </label>

      <!-- Course Description -->
      <p>Course Description</p>
        <label>
          <textarea id = "courseDescription" name = "courseDescription"
  	    			rows = "5" cols = "36" required />
          </textarea>
        </label>

      <p>Credits</p>
        <label>
          <input id ="credits" name = "credits" type = "number" required />
        </label>

      <!-- Dropdown menu to display departments -->
      <p>Department Name</p>
      <p><select id = "deptName" name = "deptName" required></select></p>
      <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getDepartmentNames",
               "deptName": $('#deptName').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               for (let i=0; i < data.length; i++) {
                 $('#deptName').append("<option value = " + data[i].deptID + ">" + data[i].deptName + "</option>");
               }
             }
           });
         });
      </script>

      <!-- Menu to display pre requisites(course names) **note need to figure out how to handle when more than one pre req is entered-->
      <p>Prerequisites</p>
      <p><select multiple = "multiple" id = "prereq" name = "prereq[]"></select></p>
      <script id="source" language="javascript" type="text/javascript">
                $('document').ready( function() {
                  console.log("after document ready");
                    $.ajax({
                      url: 'api.php',
                      data: {
                        "fname": "getCourseNames",
                        "prereq": $('#prereq').val()
                      },
                      dataType: 'json',
                      success: function(data) {
                        console.log("in success block of $.ajax()");
                        for (let i=0; i < data.length; i++) {
                          $('#prereq').append("<option value = "+ data[i].courseID + ">" + data[i].courseName + "</option>");
                        }
                      }
                    });
                  });
             </script>

      <!-- Submit and reset buttons -->
      <p></p>
      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit" />
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>
    </form>


    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

      <?php
        error_reporting(E_ALL ^ E_DEPRECATED);

        //createCourse();
        if(isset($_POST['submit'])){
            createCourse();
           }
?>
<?php
        function createCourse(){

        //Connect to database
        if ( !($database = mysql_connect( "fdb4.biz.nf", "2218832_reg", "wasd1324" ) ) )
           {
             die( "Could not connect to database" );
           }
         // open database
        if ( !mysql_select_db( "2218832_reg", $database ) )
          {
            die( "Could not open database" );
          }

        require_once "mysqli.php";

        //variables to grab user input
        $courseNum = $_POST["courseNum"];
        $courseName = $_POST["courseName"];
        $deptName = $_POST["deptName"];
        $courseDescription = $_POST["courseDescription"];
        $credits = $_POST["credits"];

        //query to insert into course what was entered in the fields
        $query = "INSERT INTO `course` (courseID, courseNum, courseName, deptID, courseDescription, credits)
         VALUES (NULL, '$courseNum', '$courseName', '$deptName', '$courseDescription', '$credits')";

         //query to check if the course you are attempting to create already exists
         $restriction = mysqliQuery("SELECT `courseName` FROM `course` WHERE `courseName` = '$courseName'", MYSQLI_USE_RESULT);
         $restrictionResults = mysqli_num_rows($restriction);
         if($restrictionResults)
         {
             print("<p><h1>The course you are attempting to create already exists</h1></p>");
             die(mysql_error() . "</body></html>");

         }

         if ( !( $result = mysql_query( $query, $database ) ) )
         {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
         } // end if
         else {
           echo "<p>New course successfully added<p>";
         }


        //query to get the ID of the course that was just now created
        $courseIDQuery = "SELECT `courseID` FROM `course` WHERE `courseName` = '$courseName'";
        $courseIDQueryResult = mysql_query($courseIDQuery, $database);
        $courseIDVALUE = mysql_result($courseIDQueryResult, 0);

         if ( !$courseIDQueryResult )
         {
            print( "<p>Could not execute query! course ID fail</p>" );
            die( mysql_error() . "</body></html>" );
         }


         //query to insert into prereqCourse what was entered in the fields
         //if something is entered in the field
           //gets an array of the pre requisites
           $prereq = $_POST['prereq'];
         if(!empty($prereq))
         {

           //get the length of the array
           $length = sizeof($prereq);
           //loop through, inserting each element
           for($i = 0; $i<$length; $i++)
           {
             $prereqQuery = mysqliQuery("INSERT INTO `prereqCourse` (courseID, prereqID) VALUES ('$courseIDVALUE', '$prereq[$i]')", MYSQLI_USE_RESULT);
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
