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
      <title>Change Password</title>
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


    <form method = "post" action = "adminChangePassword.php">

    <p>Search by Name</p>
    <p><select id = "allusers" name = "allusers"></select></p>
    <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "viewAllUsers"
          },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            $('#allusers').append("<option>Select a user</option>");
            for (let i=0; i < data.length; i++) {
              $('#allusers').append("<option value = "+ data[i].personID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
            }
          }
        });
      });
    </script>


      <!-- Submit and reset buttons -->

      <!-- Password form -->
      <h1>Change Password</h1>
        <p>Enter the new password</p>
          <label>
            <input id = "newPW" name = "newPW" type = "text" required/>
          </label>
        <p>Re-enter the new password</p>
          <label>
            <input id = "secNewPW" name = "secNewPW" type = "text" required/>
          </label>
        <p></p>
        <p>
          <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Change Password">
        </p>

    </form>

    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>


      <?php

        error_reporting(E_ALL ^ E_DEPRECATED);


        if(isset($_POST['submit'])){
            changePassword();
           }
      ?>

      <?php
        function changePassword(){

             require_once 'mysqli.php';
           $getFirst = $_POST["newPW"];
           $getSecond = $_POST["secNewPW"];
           $allUsers = $_POST["allusers"];
           if($getFirst != $getSecond)
           {
             echo "<center><strong><h1 id = 'bf'>Your password re-entry does not match. Please edit it and try again.</h1></strong></center>";
           }
           else
           {
             $updatePass = "UPDATE person
                            SET `password` = '$getFirst'
                            WHERE `personID` = '$allUsers'";

             $updateTable = mysqliQuery($updatePass, MYSQLI_USE_RESULT);
             echo "<center><strong><h1 id = 'bf'>Password sucessfully changed.</h1></strong></center>";
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
