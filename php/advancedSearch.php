<?php
session_start();
if ($_SESSION["loggedIn"] == false ){
header("Location: http://www.agru.co.nf/index.html");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Advanced Search</title>
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


    <form method = "post" action = "advancedSearch.php">

        <p>Search by Subjects</p>
        <p><select id = "deptNames" name = "deptNames"></select></p>
        <script id="source" language="javascript" type="text/javascript">
        $('document').ready( function() {
          console.log("after document ready");
          $('#deptNames').empty();
            $.ajax({
              url: 'api.php',
              data: {
                "fname": "getDepartmentNamesByTerm",
                "deptNames": $('#deptNames').val()
              },
              dataType: 'json',
              success: function(data) {
                console.log("in success block of $.ajax()");
                for (let i=0; i < data.length; i++) {
                  $('#deptNames').append("<option value = " + data[i].deptID + ">" + data[i].deptName + "</option>");
                 }
                }
              });
          });
          </script>

     <input id "deptButton" class = "myButton" name = "deptButton" type = "submit" value = "Search" />
     </form>

     <?php
           if(isset($_POST['deptButton']))
           {
             $dept = $_POST["deptNames"];
             $term = $_SESSION["term"];
             require_once 'mysqli.php';

             $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                      `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                      `person`.`firstName`, `person`.`lastName`, `section`.`sectID`
                      FROM `section`
                      JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                      JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                      JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                      JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                      JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                      WHERE `department`.`deptID` = '$dept' AND `section`.`term` = '$term'
                      order by `term`, `courseNum`, `courseName`, `sectNum`";

             $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
             $results = mysqli_num_rows($getTable);
             if($results > 0)
             {
               echo "<center><table cellspacing='0' class='GreenBlack'>";
               echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
               echo "<thead>";
               echo "<tr>";
               echo "<th>Department</th>";
               echo "<th style = 'display:none'>Course ID</th>";
               echo "<th>Course</th>";
               echo "<th>Course Number</th>";
               echo "<th>Section</th>";
               echo "<th>Days</th>";
               echo "<th>Period Start</th>";
               echo "<th>Period End</th>";
               echo "<th>Building</th>";
               echo "<th>Room</th>";
               echo "<th>Professor First Name</th>";
               echo "<th>Professor Last Name</th>";
               echo "<th style = 'display:none'>Section</th>";
               echo "<th>Submit</th>";

               echo "</tr>";
               echo "</thead>";
               echo "<tbody>";
               while($row = mysqli_fetch_array($getTable))
               {
                 echo "<tr id='idk'>";
                 echo "<td>" . $row['deptName'] . "</td>";
                 echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
                 echo "<td>" . $row['courseName'] . "</td>";
                 echo "<td>" . $row['courseNum'] . "</td>";
                 echo "<td>" . $row['sectNum'] . "</td>";
                 echo "<td>" . $row['days'] . "</td>";
                 echo "<td>" . $row['periodStart'] . "</td>";
                 echo "<td>" . $row['periodEnd'] . "</td>";
                 echo "<td>" . $row['buildingName'] . "</td>";
                 echo "<td>" . $row['roomNum'] . "</td>";
                 echo "<td>" . $row['firstName'] . "</td>";
                 echo "<td>" . $row['lastName'] . "</td>";
                 echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                 echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";

               }
               echo "</tr>";
               echo "</tbody>";
               echo "</table></center>";
       }
       else
       {
         echo "<center><strong><h1 id = 'bf'>No classes available" . "</h1></strong></center>";
       }
     }
     ?>


     <form method = "post" action = "advancedSearch.php">

    <!-- SEARCH BY COURSE NAME -->
    <p>Search by Course Name</p>
    <p><select id = "courseNames" name = "courseNames"></select></p>
    <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getCourseNames"
                },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            $('#courseNames').append("<option>Select a course</option>");
            for (let i=0; i < data.length; i++) {
              $('#courseNames').append("<option value = "+ data[i].courseID + ">" + data[i].courseName + "</option>");
            }
          }
        });
      });
    </script>

    <input id "courseButton" class = "myButton" name = "courseButton" type = "submit" value = "Search" />
    </form>




    <!-- php code to display all the courses with the selected course name - works -->
    <?php
      if(isset($_POST['courseButton']))
      {
        $courseName = $_POST['courseNames'];
        $term = $_SESSION['term'];
        require_once 'mysqli.php';

        $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                 `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                 `person`.`firstName`, `person`.`lastName`, `section`.`term`, `section`.`sectID`
                 FROM `section`
                 JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                 JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                 JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                 JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                 JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                 WHERE `course`.`courseID` = '$courseName' AND `section`.`term` = '$term'
                 order by `term`, `courseNum`, `courseName`, `sectNum`";

                 $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
                 $results = mysqli_num_rows($getTable);
                 if($results > 0)
                 {
                   echo "<center><table cellspacing='0' class='GreenBlack'>";
                   echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                   echo "<thead>";
                   echo "<tr>";
                   echo "<th>Department</th>";
                   echo "<th style = 'display:none'>Course ID</th>";
                   echo "<th>Course</th>";
                   echo "<th>Course Number</th>";
                   echo "<th>Section</th>";
                   echo "<th>Days</th>";
                   echo "<th>Period Start</th>";
                   echo "<th>Period End</th>";
                   echo "<th>Building</th>";
                   echo "<th>Room</th>";
                   echo "<th>Professor First Name</th>";
                   echo "<th>Professor Last Name</th>";
                   echo "<th style = 'display:none'>Section</th>";
                   echo "<th>Submit</th>";

                   echo "</tr>";
                   echo "</thead>";
                   echo "<tbody>";
                   while($row = mysqli_fetch_array($getTable))
                   {
                     echo "<tr id='idk'>";
                     echo "<td>" . $row['deptName'] . "</td>";
                     echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
                     echo "<td>" . $row['courseName'] . "</td>";
                     echo "<td>" . $row['courseNum'] . "</td>";
                     echo "<td>" . $row['sectNum'] . "</td>";
                     echo "<td>" . $row['days'] . "</td>";
                     echo "<td>" . $row['periodStart'] . "</td>";
                     echo "<td>" . $row['periodEnd'] . "</td>";
                     echo "<td>" . $row['buildingName'] . "</td>";
                     echo "<td>" . $row['roomNum'] . "</td>";
                     echo "<td>" . $row['firstName'] . "</td>";
                     echo "<td>" . $row['lastName'] . "</td>";
                     echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                    echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";
                   }
                   echo "</tr>";
                   echo "</tbody>";
                   echo "</table></center>";
           }
           else
           {
             echo "<center><strong><h1 id = 'bf'>No classes available with that name" . "</h1></strong></center>";
           }
         }

     ?>

     <form method = "post" action = "advancedSearch.php">
     <!-- Display the sections when searching by course number -->
     <p>Search by Course Number</p>
       <input id = "courseNumber" name = "courseNumber" type = "text">
     <p>
     <input id "courseNumberButton" class = "myButton" name = "courseNumberButton" type = "submit" value = "Search" />
     </p>
     </form>



     <?php
      if(isset($_POST['courseNumberButton']))
      {
        $courseNumber = $_POST['courseNumber'];
        $term = $_SESSION['term'];
        require_once 'mysqli.php';

        $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                 `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                 `person`.`firstName`, `person`.`lastName`, `section`.`sectID`
                 FROM `section`
                 JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                 JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                 JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                 JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                 JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                 WHERE `course`.`courseNum` = '$courseNumber' AND `section`.`term` = '$term'
                 order by `term`, `courseNum`, `courseName`, `sectNum`";

                 $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
                 $results = mysqli_num_rows($getTable);
                 if($results > 0)
                 {
                   echo "<center><table cellspacing='0' class='GreenBlack'>";
                   echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                   echo "<thead>";
                   echo "<tr>";
                   echo "<th>Department</th>";
                   echo "<th style = 'display:none'>Course ID</th>";
                   echo "<th>Course</th>";
                   echo "<th>Course Number</th>";
                   echo "<th>Section</th>";
                   echo "<th>Days</th>";
                   echo "<th>Period Start</th>";
                   echo "<th>Period End</th>";
                   echo "<th>Building</th>";
                   echo "<th>Room</th>";
                   echo "<th>Professor First Name</th>";
                   echo "<th>Professor Last Name</th>";
                   echo "<th style = 'display:none'>Section</th>";
                   echo "<th>Submit</th>";

                   echo "</tr>";
                   echo "</thead>";
                   echo "<tbody>";
                   while($row = mysqli_fetch_array($getTable))
                   {
                     echo "<tr id='idk'>";
                     echo "<td>" . $row['deptName'] . "</td>";
                     echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
                     echo "<td>" . $row['courseName'] . "</td>";
                     echo "<td>" . $row['courseNum'] . "</td>";
                     echo "<td>" . $row['sectNum'] . "</td>";
                     echo "<td>" . $row['days'] . "</td>";
                     echo "<td>" . $row['periodStart'] . "</td>";
                     echo "<td>" . $row['periodEnd'] . "</td>";
                     echo "<td>" . $row['buildingName'] . "</td>";
                     echo "<td>" . $row['roomNum'] . "</td>";
                     echo "<td>" . $row['firstName'] . "</td>";
                     echo "<td>" . $row['lastName'] . "</td>";
                     echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                     echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";
                   }
                   echo "</tr>";
                   echo "</tbody>";
                   echo "</table></center>";
           }
           else
           {
             echo "<center><strong><h1 id = 'bf'>No classes available with that number" . "</h1></strong></center>";
           }
         }
      ?>
      <form method = "post" action = "advancedSearch.php">
      <!-- Search by faculty -->
      <p>Search By Instructor</p>
      <p><select id = "facultyList" name = "facultyList"></select></p>
      <script id="source" language="javascript" type="text/javascript">
      $('document').ready( function() {
        console.log("after document ready");
          $.ajax({
            url: 'api.php',
            data: {
              "fname": "getFacultyNames"
                  },
            dataType: 'json',
            success: function(data) {
              console.log("in success block of $.ajax()");
              $('#facultyList').append("<option>Select a faculty</option>");
              for (let i=0; i < data.length; i++) {
                $('#facultyList').append("<option value = " + data[i].facultyID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
               }
              }
            });
          });
        </script>

        <p>
        <input id "facultyButton" class = "myButton" name = "facultyButton" type = "submit" value = "Search" />
        </p>
        </form>
        <?php
         if(isset($_POST['facultyButton']))
         {
           $facultyName = $_POST['facultyList'];
           $term = $_SESSION['term'];
           require_once 'mysqli.php';

           $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                    `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                    `person`.`firstName`, `person`.`lastName`, `section`.`term`, `section`.`sectID`
                    FROM `section`
                    JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                    JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                    JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                    JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                    JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                    WHERE `person`.`personID` = '$facultyName' AND `section`.`term` = '$term'
                    order by `term`, `courseNum`, `courseName`, `sectNum`";

                    $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
                    $results = mysqli_num_rows($getTable);
                    if($results > 0)
                    {
                      echo "<center><table cellspacing='0' class='GreenBlack'>";
                      echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                      echo "<thead>";
                      echo "<tr>";
                      echo "<th>Department</th>";
                      echo "<th style = 'display:none'>Course ID</th>";
                      echo "<th>Course</th>";
                      echo "<th>Course Number</th>";
                      echo "<th>Section</th>";
                      echo "<th>Days</th>";
                      echo "<th>Period Start</th>";
                      echo "<th>Period End</th>";
                      echo "<th>Building</th>";
                      echo "<th>Room</th>";
                      echo "<th>Professor First Name</th>";
                      echo "<th>Professor Last Name</th>";
                      echo "<th style = 'display:none'>Section</th>";
                      echo "<th>Submit</th>";

                      echo "</tr>";
                      echo "</thead>";
                      echo "<tbody>";
                      while($row = mysqli_fetch_array($getTable))
                      {
                        echo "<tr id='idk'>";
                        echo "<td>" . $row['deptName'] . "</td>";
                        echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
                        echo "<td>" . $row['courseName'] . "</td>";
                        echo "<td>" . $row['courseNum'] . "</td>";
                        echo "<td>" . $row['sectNum'] . "</td>";
                        echo "<td>" . $row['days'] . "</td>";
                        echo "<td>" . $row['periodStart'] . "</td>";
                        echo "<td>" . $row['periodEnd'] . "</td>";
                        echo "<td>" . $row['buildingName'] . "</td>";
                        echo "<td>" . $row['roomNum'] . "</td>";
                        echo "<td>" . $row['firstName'] . "</td>";
                        echo "<td>" . $row['lastName'] . "</td>";
                        echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                        echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";
                      }
                      echo "</tr>";
                      echo "</tbody>";
                      echo "</table></center>";
              }
              else
              {
                echo "<center><strong><h1 id = 'bf'>No classes are being taught by that professor" . "</h1></strong></center>";
              }
            }
         ?>

         <form method = "post" action = "advancedSearch.php">
        <!-- Search by timeslot -->
        <p>Search By Timeslot</p>
        <p><select id ="timeslot" name = "timeslot" required></select></p>
        <script id="source" language="javascript" type="text/javascript">
             $('document').ready( function() {
               console.log("after document ready");
                 $.ajax({
                   url: 'api.php',
                   data: {
                     "fname": "getTimeslots"
                         },
                   dataType: 'json',
                   success: function(data) {
                     console.log("in success block of $.ajax()");
                     $('#timeslot').append("<option>Select a timeslot</option>")
                     for (let i=0; i < data.length; i++) {
                       $('#timeslot').append("<option value = " + data[i].timeslotID + ">" + data[i].days + " " + data[i].periodStart + " - " + data[i].periodEnd + "</option>");
                     }
                   }
                 });
               });
           </script>

         <p>
         <input id "timeslotButton" class = "myButton" name = "timeslotButton" type = "submit" value = "Search" />
         </p>
         </form>

         <?php
          if(isset($_POST['timeslotButton']))
          {
            $timeslot = $_POST['timeslot'];
            $term = $_SESSION['term'];
            require_once 'mysqli.php';

            $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                     `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                     `person`.`firstName`, `person`.`lastName`, `section`.`term`, `section`.`sectID`
                     FROM `section`
                     JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                     JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                     JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                     JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                     JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                     WHERE `section`.`timeslotID` = '$timeslot' AND `section`.`term` = '$term'
                     order by `term`, `deptName`, `courseName`,`courseNum`, `sectNum`";

                     $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
                     $results = mysqli_num_rows($getTable);
                     if($results > 0)
                     {
                       echo "<center><table cellspacing='0' class='GreenBlack'>";
                       echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                       echo "<thead>";
                       echo "<tr>";
                       echo "<th>Department</th>";
                       echo "<th style = 'display:none'>Course ID</th>";
                       echo "<th>Course</th>";
                       echo "<th>Course Number</th>";
                       echo "<th>Section</th>";
                       echo "<th>Days</th>";
                       echo "<th>Period Start</th>";
                       echo "<th>Period End</th>";
                       echo "<th>Building</th>";
                       echo "<th>Room</th>";
                       echo "<th>Professor First Name</th>";
                       echo "<th>Professor Last Name</th>";
                       echo "<th style = 'display:none'>Section</th>";
                       echo "<th>Submit</th>";
                       echo "</thead>";
                       echo "<tbody>";
                       while($row = mysqli_fetch_array($getTable))
                       {
                         echo "<tr id='idk'>";
                         echo "<td>" . $row['deptName'] . "</td>";
                         echo "<td class = 'courseID' style = 'display:none'>" . $row['courseID'] . "</td>";
                         echo "<td>" . $row['courseName'] . "</td>";
                         echo "<td>" . $row['courseNum'] . "</td>";
                         echo "<td>" . $row['sectNum'] . "</td>";
                         echo "<td>" . $row['days'] . "</td>";
                         echo "<td>" . $row['periodStart'] . "</td>";
                         echo "<td>" . $row['periodEnd'] . "</td>";
                         echo "<td>" . $row['buildingName'] . "</td>";
                         echo "<td>" . $row['roomNum'] . "</td>";
                         echo "<td>" . $row['firstName'] . "</td>";
                         echo "<td>" . $row['lastName'] . "</td>";
                         echo "<td class='section' style = 'display:none'>" . $row['sectID'] . "</th>";
                         echo "<td>" . "<button class = 'myButton j-register' name='submitRegister' type = 'submit' value='Register'>Register</button> ". "</td>";
                       }
                       echo "</tr>";
                       echo "</tbody>";
                       echo "</table></center>";
               }
               else
               {
                 echo "<center><strong><h1 id = 'bf'>No classes are being taught in that timeslot" . "</h1></strong></center>";
               }
             }
          ?>
      </form>
    <script>
      $('document').ready( function() {
          $('.j-register').click( function() {
              var sectionID = $(this).parent().siblings('.section').text();
              console.log(sectionID);
              $.ajax({
                  url: 'api.php',
                  data: {
                      'fname':'checkStudentRestrictions',
                      'sectID': sectionID
                  },
                  dataType: 'json',
                  success: function(data) {
                      if (data.reasons.length == 0) {
                              $.ajax({
                                  url: 'api.php',
                                  data: {
                                      'fname':'registerForAFuckingClass',
                                      'sectID': sectionID
                                  },
                                  success: function(data) {
                                      alert("You are registered.");
                                      console.log(data);

                                      $.ajax({
                                         url: 'api.php',
                                         data: {
                                             'fname': 'updateFulltimeStatus'
                                         },
                                         success: function (data) {
                                           console.log("Fulltime status updated");
                                         },
                                         error: function(request, status, errorThrown) {
                                           console.log(errorThrown);
                                          }
                                     });
                                  }
                               });
                       }
                       else {
                               var alertString = "";
                               for (var i = 0; i<data.reasons.length; i++) {
                                   alertString += data.reasons[i] + "\n";
                               }
                               alert(alertString);
                       }
                  },
                  error: function(request, status, errorThrown) {
                      console.log(errorThrown);
                  }
              });
          });
      });
</script>




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
