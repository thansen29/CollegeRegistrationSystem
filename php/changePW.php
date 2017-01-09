<?php
session_start();
if ($_SESSION["loggedIn"] == false) {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Change Password</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
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

        <!-- Navigation Bar -->
          <center>
            <p>
                  <?php
                    $type = $_SESSION['type'];
                    if ($type == "student") {
                            echo "<a href='./studentHomepage.php' class='myButton'>Home</a>";
                    }
                    else if ($type == "faculty") {
                            echo "<a href='./facultyHomepage.php' class='myButton'>Home</a>";
                    }
                    else if ($type == "admin") {
                            echo "<a href='./adminHomepage.php' class='myButton'>Home</a>";
                    }
                    else {
                            echo "<a href='../index.html' class='myButton'>Home</a>";
                    }
                   ?>
      	    </p>
        </center>

        <!-- Password form -->
        <center><h1>Change Password</h1></center>
        <form id = "pwForm" name = "pwForm" method = "post">
          <p>Enter the new password you want to use:</p>
            <label>
              <input id = "newPW" name = "newPW" type = "text" required/>
            </label>
          <p>Re-enter the new password:</p>
            <label>
              <input id = "secNewPW" name = "secNewPW" type = "text" required/>
            </label>
          <p></p>
          <p>
            <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Change Password">
          </p>
        </form>

          <?php
              if(isset($_POST['submit']))
              {
                $getFirst = $_POST["newPW"];
                $getSecond = $_POST["secNewPW"];
                if($getFirst != $getSecond)
                {
                  echo "<center><strong><h1 id = 'bf'>Your password re-entry does not match. Please edit it and try again.</h1></strong></center>";
                }
                else
                {
                  require_once 'mysqli.php';
                  $updatePass =<<<EOD
                      UPDATE person
                      SET `password` = '$getFirst'
                      WHERE `personID` = {$_SESSION["id"]}
EOD;
                  $updateTable = mysqliQuery($updatePass, MYSQLI_USE_RESULT);
                  echo "<center><strong><h1 id = 'bf'>Password sucessfully changed.</h1></strong></center>";
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
