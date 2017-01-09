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
      <title>View Users</title>
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
                <a href = "adminHomepage.php" class = "myButton">Home</a>
      	    </p>
        </center>


      <?php
          //PHP code to get all people.
          require_once 'mysqli.php';
          $query = "SELECT `email`, `firstName`, `lastName`, `phoneNum`, `streetAddress`, `personID`, `person_type`
                    FROM `person`
                    order by `person_type`, `lastName`";
          $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);

          echo "<center><table cellspacing='0' class='GreenBlack'>";
          echo "<caption><strong><h1 id = 'bf'>All Users</h1></strong></caption>";
          echo "<thead>";
          echo "<tr>";
          echo "<th style = 'display:none'>Person ID</th>";
          echo "<th>First Name</th>";
          echo "<th>Last Name</th>";
          echo "<th>Email</th>";
          echo "<th>Phone Number</th>";
          echo "<th>Street Address</th>";
          echo "<th>Edit</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
          while($row = mysqli_fetch_array($getTable))
          {
            echo "<tr id='idk'>";
            echo "<td class = 'personID' style = 'display:none'>" . $row['personID'] . "</td>";
            echo "<td>" . $row['firstName'] . "</td>";
            echo "<td>" . $row['lastName'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td class = 'phoneNum'><div = contenteditable>" . $row['phoneNum'] . "</div></td>";
            echo "<td class = 'streetAddress'><div = contenteditable>" . $row['streetAddress'] . "</div></td>";
            echo "<td>" . "<button class = 'myButton j-register' name='edit' type = 'submit' value='edit'>Edit</button> ". "</td>";


          }
          echo "</tr>";
          echo "</tbody>";
          echo "</table></center>";

      ?>


      <script>
      $('document').ready( function() {
          $('.j-register').click( function() {
              var phoneNum = $(this).parent().siblings('.phoneNum').text();
              var streetAddress = $(this).parent().siblings('.streetAddress').text();
              var personID = $(this).parent().siblings('.personID').text();
              $.ajax({
                  url: 'api.php',
                  data: {
                      'fname':'editUserInfo',
                      'phoneNum': phoneNum,
                      'streetAddress': streetAddress,
                      'personID': personID
                  },
                  dataType: 'json',
                  success: function(data) {
                      alert("User successfully edited")
                  },
                  error: function(request, status, errorThrown) {
                      alert(errorThrown);
                  }
              });
          });
      });
</script>


    <p>
    <center><div id = "summaryimage"><img src = "Images\graduation.jpg" width = "500" height = "333"
      alt = "Logo"></div></center>
    </p>

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
