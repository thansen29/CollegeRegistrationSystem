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
      <title>Edit user schedule</title>
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


    <form method = "post" action = "editUserSchedule.php">

    <h1>Search Faculty</h1>

        <p><select id="term" name = "term"></select></p>
          <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getTerms",
                  "term": $('#term').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#term').append("<option>Select a term</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#term').append("<option name='term'>" + data[i].seasonYear + "</option>");
                  }
                }
              });
            });
        </script>

    <!-- SEARCH FACULTY BY NAME -->
    <p>Search by Name</p>
    <p><select id = "facultyNames" name = "facultyNames"></select></p>
    <script id="source" language="javascript" type="text/javascript">
    $('document').ready( function() {
      console.log("after document ready");
        $.ajax({
          url: 'api.php',
          data: {
            "fname": "getFacultyNames",
            "facultyNames": $('#facultyNames').val()
          },
          dataType: 'json',
          success: function(data) {
            console.log("in success block of $.ajax()");
            for (let i=0; i < data.length; i++) {
              $('#facultyNames').append("<option value = "+ data[i].facultyID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
            }
          }
        });
      });
    </script>

    <p>Courses</p>
      <p><select id = "courseList" name = "courseList"></select></p>
      <script id="source" language="javascript" type="text/javascript">
      $('document').ready( function() {
        $('#facultyNames').change(function() {
         $('#courseList').empty();
        console.log("after document ready");
          $.ajax({
            url: 'api.php',
            data: {
              "fname": "getCoursesByFacultyAlt",
              "term": $('#term').val(),
              "facultyName":$('#facultyNames').val()
            },
            dataType: 'json',
            success: function(data) {
              $('#courseList').append("<option>Select a course</option>");
              for (let i=0; i < data.length; i++) {
                $('#courseList').append("<option value = "+ data[i].sectID + ">" + data[i].courseName + " - " + data[i].sectNum + "</option>");
              }
            }
          });
         });
       });
        </script>

    <input id "facNameButton" class = "myButton" name = "facNameButton" type = "submit" value = "Drop course" />

    <?php
            require_once 'mysqli.php';

            if(isset($_POST['facNameButton'])){

              //variables to grab user input
              $faculty = $_POST["facultyNames"];
              $course = $_POST["courses"];
              $sectID = $_POST["courseList"];

              //change the facultyID in section table to that of TBA
              $query = mysqliQuery("UPDATE `section` SET `facultyID` = '689' WHERE `section`.`sectID` = '$sectID' ", MYSQLI_USE_RESULT);
              echo "<script>alert('Instructor removed from teaching that section')</script>";
            }
    ?>


    <h1>Search Students</h1>

    <p><select id="studentTerm" name = "studentTerm"></select></p>
          <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getTerms",
                  "term": $('#term').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#term').append("<option>Select a term</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#studentTerm').append("<option name='studentTerm'>" + data[i].seasonYear + "</option>");
                  }
                }
              });
            });
        </script>

       <!-- SEARCH STUDENT BY NAME -->
       <p>Search by Name</p>
       <p><select id = "studentNames" name = "studentNames"></select></p>
       <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getStudentNames",
               "studentNames": $('#studentNames').val()
             },
             dataType: 'json',
             success: function(data) {
               console.log("in success block of $.ajax()");
               $('#studentNames').append("<option>Select a student</option>");
               for (let i=0; i < data.length; i++) {
                 $('#studentNames').append("<option value = "+ data[i].studentID + ">" + data[i].firstName + " " + data[i].lastName + "</option>");
               }
             }
           });
         });
       </script>


       <p><select id = "studentCourse" name = "studentCourse"></select></p>
       <script id="source" language="javascript" type="text/javascript">
       $('document').ready( function() {
         $('#studentNames').change( function () {
         $('#studentCourse').empty();
         console.log("after document ready");
           $.ajax({
             url: 'api.php',
             data: {
               "fname": "getCoursesByStudentAlt",
               "term": $('#studentTerm').val(),
               "studentNames": $('#studentNames').val()
             },
             dataType: 'json',
             success: function(data) {
               $('#studentCourse').append("<option>Select a course</option>");
               for (let i=0; i < data.length; i++) {
                 $('#studentCourse').append("<option value = "+ data[i].sectID + ">" + data[i].courseNum + " " + data[i].courseName + " - " + data[i].sectNum + "</option>");
               }
             }
           });
         });
       });
       </script>

      <input id "studentButton" class = "myButton" name = "studNameButton" type = "submit" value = "Drop Course" />

      <?php
        require_once 'mysqli.php';
               if(isset($_POST["studNameButton"])){

                 $student = $_POST["studentNames"];
                 $term = $_POST["studentTerm"];
                 $course = $_POST["studentCourse"];

                 //query to remove from enrollment table
                 $query = mysqliQuery("DELETE FROM `enrollment` WHERE `enrollment`.`studentID` = '$student' AND `enrollment`.`sectID` = '$course'", MYSQLI_USE_RESULT);

                //query to switch full time off
                $fullTimeQuery = "SELECT `enrollment`.`studentID`
                FROM `enrollment`
                JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                WHERE `enrollment`.`studentID` = '$student' AND `section`.`term` = '$term'";


                $fullTimeResult = mysqliQuery($fullTimeQuery);
                $numberOfClasses = mysqli_num_rows($fullTimeResult);


                if($numberOfClasses == 2)
                {
                  $updateStatus = "UPDATE `student` SET `fullTime` = 0 WHERE `studentID` = '$student'";
                  $updateQuery = mysqliQuery($updateStatus);
                }


                 echo "<script>alert('Student removed from course')</script>";

               }

      ?>
      <h1>Add Course</h1>

        <p><select id="courseTerm" name = "courseTerm"></select></p>
          <script id="source" language="javascript" type="text/javascript">
          $('document').ready( function() {
            console.log("after document ready");
              $.ajax({
                url: 'api.php',
                data: {
                  "fname": "getTerms",
                  "term": $('#term').val()
                },
                dataType: 'json',
                success: function(data) {
                  console.log("in success block of $.ajax()");
                  $('#term').append("<option>Select a term</option>");
                  for (let i=0; i < data.length; i++) {
                    $('#courseTerm').append("<option name='courseTerm'>" + data[i].seasonYear + "</option>");
                  }
                }
              });
            });
        </script>

      <p>
      <button class = "myButton" name = "searchButton" type = "submit" value = "Add Course">Search Courses</button>
    </p>
  </form>

       <?php

        if(isset($_POST['searchButton'])){
           require_once 'mysqli.php';
           $term = $_POST["courseTerm"];

           $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `course`.`courseID`, `department`.`deptName`, `section`.`sectNum`,
                    `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                    `person`.`firstName`, `person`.`lastName`, `section`.`sectID`, `section`.`term`
                    FROM `section`
                    JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                    JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                    JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                    JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                    JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                    WHERE `section`.`term` = '$term'
                    order by `department`.`deptID`, `courseNum`, `courseName`, `sectNum`";

                     $getTable = mysqliQuery($query, MYSQLI_USE_RESULT);
                     $results = mysqli_num_rows($getTable);
                     if($results > 0 )
                     {
                             echo "<center><table cellspacing='0' class='GreenBlack'>";
                             echo "<caption><strong><h1 id = 'bf'>" . $term . " Classes</h1></strong></caption>";
                             echo "<thead>";
                             echo "<tr>";
                             echo "<th style ='display:none'>Term</th>";
                             echo "<th>Department</th>";
                             echo "<th style ='display:none'>CourseID</th>";
                             echo "<th>Course</th>";
                             echo "<th>Course Number</th>";
                             echo "<th>Section</th>";
                             echo "<th>Day(s)</th>";
                             echo "<th>Period Start</th>";
                             echo "<th>Period End</th>";
                             echo "<th>Building</th>";
                             echo "<th>Room</th>";
                             echo "<th>Professor First Name</th>";
                             echo "<th>Professor Last Name</th>";
                             echo "<th style = 'display:none'>Section ID</th>";
                             echo "<th>Register</th>";
                             echo "</tr>";
                             echo "</thead>";
                             echo "<tbody>";
                             while($row = mysqli_fetch_array($getTable))
                             {
                               echo "<tr id='idk'>";
                               echo "<td class = 'courseTerm' style = 'display:none'>" . $row['term'] . "</td>";
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
                         }
              ?>
    <script>
    $('document').ready( function() {
         $('.j-register').click( function() {
             var sectionID = $(this).parent().siblings('.section').text();
             var courseID = $(this).parent().siblings('.courseID').text();
             var term = $(this).parent().siblings('.courseTerm').text();
             var studentID = $('#studentNames').val();
             $.ajax({
                 url: 'api.php',
                 data: {
                     'fname':'checkStudentRestrictionsAlt',
                     'sectID': sectionID,
                     'courseID': courseID,
                     'term': term,
                     'studentID': studentID
                 },
                 dataType: 'json',
                 success: function(data) {
                   console.log("restrictions checked");
                   console.log("studentID: " + studentID);
                   console.log("term: " + term);
                   console.log("courseID: " + courseID);
                   console.log("sectionID: " + sectionID);
                   console.log("length: " + data.reasons.length);
                     if (data.reasons.length == 0) {
                             $.ajax({
                                 url: 'api.php',
                                 data: {
                                     'fname':'registerForAFuckingClassAlt',
                                     'sectID': sectionID,
                                     'studentID': studentID
                                 },
                                 success: function(data) {
                                     alert("You are registered.");
                                     console.log(data);

                                     $.ajax({
                                        url: 'api.php',
                                        data: {
                                            'fname': 'updateFulltimeStatusAlt',
                                            'sectID': sectionID,
                                            'studentID': studentID
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
