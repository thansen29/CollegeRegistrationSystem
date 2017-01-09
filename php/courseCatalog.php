<?php
session_start();
?>

<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Course Catalog</title>
      <meta name = "keywords" content = "Al, Gore,
         Rhythm, Al Gore's, College, Focused, Student">
      <meta name = "description" content = "The perfect college for focused people.">
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
            else if ($type == "researcher") {
                    echo "<a href='./researcherHomepage.php' class='myButton'>Home</a>";
            }
            else {
                    echo "<a href='../index.html' class='myButton'>Home</a>";
            }
            ?>
              <!--<a href="\index.html" class="myButton">Home</a> -->
              <a href="login.php" class="myButton">Log In</a>
              <a href="\admissions.html" class="myButton">Admissions</a>
              <a href="\academics.html" class="myButton">Academics</a>
      	    </p>
          </center>

    <!-- Introduction text -->
    <center><table id="courseCatalog" cellspacing='0' class="GreenBlack">
   <caption><strong><h1 id = "bf">Course Catalog</h1></strong></caption>
    <thead>
    <tr>
        <th>Course Number</th>
        <th>Course Name</th>
        <th>Department</th>
        <th>Description</th>
        <th>Credits</th>
    </tr>
    </thead>
    <tbody>
      <!-- Insert code for get info here-->
      <script id="source" language="javascript" type="text/javascript">

         $('document').ready( function() {
           console.log("after document ready");
             $.ajax({
               url: 'api.php',
               data: {"fname": "getCourseCatalog"
               },
               dataType: 'json',
               success: function(data) {
                 console.log("in success block of $.ajax()");
                 for (let i=0; i < data.length; i++) {
                   var table = document.getElementById("courseCatalog");
                   var row = table.insertRow(-1);
                   var cell1 = row.insertCell(0);
                   var cell2 = row.insertCell(1);
                   var cell3 = row.insertCell(2);
                   var cell4 = row.insertCell(3);
                   var cell5 = row.insertCell(4);
                   cell1.innerHTML = data[i].courseNum;
                   cell2.innerHTML = data[i].courseName;
                   cell3.innerHTML = data[i].deptName;
                   cell4.innerHTML = data[i].courseDescription;
                   cell5.innerHTML = data[i].credits;
                 }
               }
             });
           });
        </script>
    </tbody>
</table></center>


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
