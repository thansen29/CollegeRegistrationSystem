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
      <title>Departments</title>
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
          <a href = "adminHomepage.php" class = "myButton">Home</a>

    </center>

      <h1><strong>Create department</strong></h1>

    <form method = "post" action = "department.php">

    <!-- Department Name -->
    <p>Department Name</p>
      <label>
        <input id ="deptName" name = "deptName" type = "text" required />
      </label>

    <!-- Dropdown Menu of faculty Names -->
    <p>Department Chair</p>
    <p><select id = "chairID" name = "chairID" required></select></p>
    <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getFacultyNames",
            "chairID": $('#chairID').val()
          },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            for (let i=0; i < data.length; i++) {
              $('#chairID').append("<option value = " + data[i].facultyID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
            }
          }
        });
      });
      </script>
      <!-- Submit and reset buttons -->

      <p>
        <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Submit">
        <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
      </p>
    </form>

      <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

<?php

      error_reporting(E_ALL ^ E_DEPRECATED);

      if(isset($_POST['submit'])){
          createDepartment();
         }
?>
<?php
        function createDepartment()
        {
           require_once "mysqli.php";

           $deptName = $_POST['deptName'];
           $chairID = $_POST['chairID'];

           //query to check if the department you are attempting to create already exists
           $restriction = mysqliQuery("SELECT `deptName` FROM `department` WHERE `deptName` = '$deptName'", MYSQLI_USE_RESULT);
           $restrictionResults = mysqli_num_rows($restriction);
           if($restrictionResults)
           {
               print("<p><h1>The department you are attempting to create already exists</h1></p>");
               die(mysql_error() . "</body></html>");
           }

           //restriction check that the chair  you are trying to make isnt already a chair
           $chairRestriction = mysqliQuery("SELECT `chairID` FROM `department` WHERE `chairID` = '$chairID'", MYSQLI_USE_RESULT);
           $chairRestrictionResult = mysqli_num_rows($chairRestriction);
           if($chairRestrictionResult > 0)
           {
             print("<p><h1>Faculty can not be chair for more than one department</h1></p>");
             die();
           }

           $query = "INSERT INTO `department` (deptID, chairID, deptName) VALUES (NULL, '$chairID', '$deptName')";

             //run the query
             if ( !( $result = mysqliQuery( $query, MYSQLI_USE_RESULT ) ) )
            {
               print( "<p>Could not execute query!</p>" );
               die( mysql_error() . "</body></html>" );
            } // end if
             else {
               echo "New department successfully added";
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
