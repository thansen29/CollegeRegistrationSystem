<?php
  session_start();
  if ($_SESSION["loggedIn"] == true && $_SESSION["type"] == "student")
  {
    header("Location: http://www.agru.co.nf/php/studentHomepage.php");
    die();
  }
  elseif($_SESSION["loggedIn"] == true && $_SESSION["type"] == "faculty")
  {
    header("Location: http://www.agru.co.nf/php/facultyHomepage.php");
    die();
  }
  elseif($_SESSION["loggedIn"] == true && $_SESSION["type"] == "admin")
  {
    header("Location: http://www.agru.co.nf/php/adminHomepage.php");
    die();
  }
  elseif($_SESSION["loggedIn"] == true && $_SESSION["type"] == "researcher")
  {
    header("Location: http://www.agru.co.nf/php/researcherHomepage.php");
    die();
  }
?>

<!DOCTYPE html>
<html>
   <head>
      <meta charset = "utf-8">
      <title>Log In Page</title>
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
          <a href="index.html" class="myButton">Home</a>
          <a href="/php/login.php" class="myButton">Log In</a>
          <a href="admissions.html" class="myButton">Admissions</a>
          <a href="academics.html" class="myButton">Academics</a>
          <a href = "/php/courseCatalog.php" class = "myButton">Course Catalog</a>
  	    </p>
    </center>


    <!-- login form -->
    <form method = "post" action = "login.php">
      <!-- User name field -->
      <center>
        <label>Username
          <input id="username" name="name" type="text" required />
        </label>
      <!-- Password Field -->
        <label>Password
          <input id="password" name="password" type="password" required />
        </label>
      <!-- Login and reset buttons -->
        <p>
          <input id="loginButton" class="myButton" name="login" type="submit" value="Login" />
          <input id="resetButton" class= "myButton" type="reset" value="Reset" />
          <div id="response"></div>
        </p>
      </center>
      <br>
    </form>
    <!-- end login form -->
    <?php
    if(isset($_POST['login'])){
      echo $_SESSION["loginAttempts"];
      if ($_SESSION["loginAttempts"] < 3) {
        login();
        $_SESSION["loginAttemps"]++;
      }
      else {
        echo "TOO MANY TIMESSSSSSSSS";
      }
    }
    ?>
    <?php

    function login() {
      /* This php code is supposed to validate the login information from user
         if it does we set their $_SESSION["loggedIn"] to true
         and direct them to their homepage based on the type of person they are
         like naughty or nice
         if it doesn't we say try again
      */
      //session_start();
      $_SESSION["loggedIn"] = false;

      // save the name and password from input fields
      // what exactly does $_GET do?
      $emailEntered = $_POST['name'];
      $passwordEntered = $_POST['password'];

      /* first query
         1. get where on email = $emailEntered
         3. if no results, not a valid email, try again
         4. if results, compare, if its not a match, gtfo try again.
       */
      require_once "mysqli.php";
      $firstResult = mysqliQuery("SELECT `password` FROM `person` WHERE `email` = '$emailEntered'", MYSQLI_USE_RESULT);
      if ($firstResult->num_rows > 0) {
        $row = $firstResult->fetch_assoc();
        $actualPassword = $row["password"];
        if ($actualPassword == $passwordEntered) {
          /* second query
            1. get the ID and type
            2. where email = $emailEntered
            3. save as session variables
            4. set session variable loggedIn to true
          */
          $secondResult = mysqliQuery("SELECT `personID`, `person_type` FROM `person` WHERE `email` = '$emailEntered'", MYSQLI_USE_RESULT);
          $row = $secondResult->fetch_assoc();
          $_SESSION["type"] = $row["person_type"];
          $_SESSION["id"] = $row["personID"];
          $_SESSION["loggedIn"] = true;
          if ($_SESSION["type"] == "student") {
            echo "<script> window.location.assign('studentHomepage.php'); </script>";
          } else if ($_SESSION["type"] == "faculty") {
            echo "<script> window.location.assign('facultyHomepage.php'); </script>";
          } else {
            echo "<script> window.location.assign('adminHomepage.php'); </script>";
          }


          //echo "<script></script>";

        } else {
          echo "<script>
            document.getElementById('response').innerHTML = 'Wrong email or password.';
          </script>";
        }
      } else { // email was not on record so,
        echo "<script>
            document.getElementById('response').innerHTML = 'Wrong email or password.';
          </script>";
      }

      // close connection
      //$conn->close();



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
