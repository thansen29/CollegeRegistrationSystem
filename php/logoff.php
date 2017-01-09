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
      <title>Log Off</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
      <link rel = "stylesheet" type = "text/css" href = "AGstyle.css">
      <script src= "https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
   </head>

   <header>
     <center>
       <div id = "headimage"><img src = "Images\campus.jpg" width = "950" height = "400"
       alt = "Logo">
       </div>
     </center>
     <center>
       <h1>Al Gore's Rhythm College for the Focused Student</h1>
     </center>
   </header>
   <!-- Website content that is shown. -->
   <body>
      <!-- Navigation Bar -->
      <center>
        <p>
          <a href="http://www.agru.co.nf/" class="myButton">Home</a>
  	    </p>
    </center>


    <!-- login form -->
    <center><strong><h1 id = 'bf'>You have been sucessfully logged off.</h1></strong></center>
    <!-- Log off code -->
    <?php
      logoff();
    ?>
    <?php
      function logoff()
      {
            $_SESSION["type"] = NULL;
            $_SESSION["id"] = NULL;
            $_SESSION["loggedIn"] = false;
      }
    ?>
    <center>
      <div id = "summaryimage">
        <img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo">
    </div>
    </center>

    <!-- footer -->
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
