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
      <title>Review Major Applications</title>
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

      <h1><strong>Review Major Applications</strong></h1>

      <form method = "post" action = "reviewMajorApplication.php">

      <p>Applications</p>
      <!-- Dropdown to display all the students who have applied for a major -->
      <p><select id = "studentNames" name = "studentNames" ></select></p>
      <script id="source" language="javascript" type="text/javascript">
      $('document').ready( function() {
        console.log("after document ready");
          $.ajax({
            url: 'api.php',
            data: {
              "fname": "getMajAppStudents",
              "studentNames": $('#studentNames').val()
            },
            dataType: 'json',
            success: function(data) {
              console.log("in success block of $.ajax()");
              for (let i=0; i < data.length; i++) {
                $('#studentNames').append("<option value = "+ data[i].studentID + "," + data[i].majorID + ">"
                        + data[i].firstName + " " + data[i].lastName + " - " + data[i].majorName + "</option>");
              }
            }
          });
        });
        </script>


      <!-- Submit and reset buttons -->

      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Approve Major" />
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

    </form>

<?php
        error_reporting(E_ALL ^ E_DEPRECATED);

        if(isset($_POST['submit']))
        {

          $getValue = $_POST['studentNames'];
          $separate = explode(",", $getValue);
          $student = $separate[0];
          $major = $separate[1];

          require_once "mysqli.php";

          //query to remove the application from majorApplication
          $removeApp = "DELETE FROM `majorApplication` WHERE `majorApplication`.`studentID` = '$student'
          AND `majorApplication`.`majorID` = '$major'";

          if(!$removeCheck = mysqliQuery($removeApp, MYSQLI_USE_RESULT))
          {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
          }
          else{
            echo "<p>Application removed from majorApplication</p>";
          }

          //query to insert the student and major into studentsMajor
          $insertMajor = "INSERT INTO `studentsMajor` (studentID, majorID) VALUES ('$student', '$major')";

          if(!$insertCheck = mysqliQuery($insertMajor, MYSQLI_USE_RESULT))
          {
            print( "<p>Could not execute query!</p>" );
            die( mysql_error() . "</body></html>" );
          }
          else{
            echo "<p>Application added to studentsMajors</p>";
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
