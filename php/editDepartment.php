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
      <title>Edit Departments</title>
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

      <h1><strong>Edit department</strong></h1>

    <form method = "post" action = "editDepartment.php">

    <!-- Department Name -->
    <p>Department </p>
    <p><select id = "deptName" name = "deptName" required></select></p>
    <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getDepartmentNames",
            "deptID": $('#deptName').val()
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

    <p>Select a new chair: </p>
    <!-- Dropdown Menu of faculty Names -->
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
        <input id "deleteDept" class = "myButton" type = "submit" name = "deleteDept" value = "Delete Department" />
      </p>
    </form>

      <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>

<?php

      error_reporting(E_ALL ^ E_DEPRECATED);

      if(isset($_POST['submit'])){
          editDepartment();
         }

      if(isset($_POST['deleteDept'])){
        deleteDepartment();
      }
?>
<?php
        function editDepartment()
        {
           require_once "mysqli.php";

           $deptName = $_POST['deptName'];
           $chairID = $_POST['chairID'];

           $chairRestrict = mysqliQuery("SELECT `chairID` FROM `department` WHERE `department`.`chairID` = '$chairID'", MYSQLI_USE_RESULT);
           $chairRestrictResult = mysqli_num_rows($chairRestrict);
           if($chairRestrictResult > 0)
           {
             print("<p><h1>Faculty member cannot be chair of more than one department</h1></p>");
             die();
           }

           $query = "UPDATE `department` SET `chairID` = '$chairID' WHERE `deptID` = '$deptName'";

             //run the query
             if ( !( $result = mysqliQuery( $query, MYSQLI_USE_RESULT ) ) )
            {
               print( "<p>Could not execute query!</p>" );
               die( mysql_error() . "</body></html>" );
            } // end if
             else {
               echo "Department chair successfully updated";
             }
       }

       function deleteDepartment()
       {
         require_once 'mysqli.php';

         $deptName = $_POST['deptName'];

         $deleteQuery = mysqliQuery("DELETE FROM `department` WHERE `deptID` = '$deptName'", MYSQLI_USE_RESULT);
         if(!$deleteQuery)
         {
           print( "<p>Could not execute query!</p>" );
           die( mysql_error() . "</body></html>" );
        } // end if
         else {
           echo "<p>Department successfully deleted</p>";
         }

         $editFac = mysqliQuery("UPDATE `faculty` SET `deptID` = NULL WHERE `deptID` = '$deptName'", MYSQLI_USE_RESULT);
         if(!$editFac)
         {
         print( "<p>Could not execute query!</p>" );
         die( mysql_error() . "</body></html>" );
         } // end if
       else {
         echo "<p>Faculty in the deleted department successfully updated</p>";
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
