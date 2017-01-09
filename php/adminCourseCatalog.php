<?php
/*
session_start();
if ($_SESSION["loggedIn"] == false) {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}*/
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
              <a href="adminHomepage.php" class="myButton">Home</a>
              <a href="logoff.php" class="myButton">Log Out</a>
      	    </p>
          </center>

      <?php
        //PHP code to get all courses.
        require_once 'mysqli.php';
        $getTable = mysqliQuery("SELECT `courseNum`, `courseName`, `course`.`courseID`, `course`.`deptID`, `department`.`deptName`, `courseDescription`, `credits`
                                FROM `course`
                                JOIN `department` ON `department`.`deptID` = `course`.`courseID`");
        $results = mysqli_num_rows($getTable);
        if($results > 0)
        {
          echo "<center><table cellspacing='0' class='GreenBlack'>";
          echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
          echo "<thead>";
          echo "<tr>";
          echo "<th>Course Number</th>";
          echo "<th>Course Name</th>";
          echo "<th>Department</th>";
          echo "<th>Description</th>";
          echo "<th>Credits</th>";
          echo "<th>CourseID</th>";
          echo "<th>Edit</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";
        while($row = mysqli_fetch_array($getTable))
        {
          echo "<tr id='idk'>";
          echo "<td class = 'courseNum'><div = contenteditable>". $row['courseNum'] ."</div></td>";
          echo "<td class = 'courseName'><div = contenteditable>". $row['courseName'] . "</div></td>";
          echo "<td class = 'deptName'><div = contenteditable>". $row['deptName']. "</div></td>";
          echo "<td class = 'courseDescription'><div = contenteditable>". $row['courseDescription'] ."</div></td>";
          echo "<td class = 'credits'><div = contenteditable>". $row['credits'] ."</div></td>";
          echo "<td class = 'courseID'>". $row['courseID'] ."</td>";
          echo "<td>" . "<button class = 'j-register' name='submitRegister' type = 'submit' value='Edit'>Edit</button> ". "</td>";
        }

        echo "</tr>";
        echo "</tbody>";
        echo "</table></center>";
      }

?>

<script>
$('document').ready( function() {
    $('.j-register').click( function() {
        var courseNum = $(this).parent().siblings('.courseNum').text();
        var courseName = $(this).parent().siblings('.courseName').text();
        var deptName = $(this).parent().siblings('.deptName').text();
        var courseDescription = $(this).parent().siblings('.courseDescription').text();
        var credits = $(this).parent().siblings('.credits').text();
        var courseID = $(this).parent().siblings('.courseID').text();

  //console.log("courseNum: " + courseNum + " courseName: " + courseName + " deptName: " + deptName + " courseDescription: " + courseDescription + "credits: " + credits + " courseID: " + courseID);

        $.ajax({
            url: 'api.php',
            data: {
                'fname':'editTheFuckingCatalog',
                'courseNum': courseNum,
                'courseName': courseName,
                'deptName': deptName,
                'courseDescription': courseDescription,
                'credits': credits,
                'courseID': courseID
            },
            dataType: 'json',
            success: function(data) {
             alert("Course Successfully edited");
            },
            error: function(request, status, errorThrown){
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
