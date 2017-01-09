<?php

  session_start();

  // connect to database
  require_once 'mysqli.php';

  // get function
  $fname = $_GET['fname'];

  if($fname == 'dropCourse') {
    dropCourse();
}
  function dropCourse(){
    $studentID = $_SESSION['id'];
    $term = $_GET['term'];
    $sectID = $_GET['sectID'];
    $query = "DELETE FROM `enrollment` WHERE `enrollment`.`studentID` = {$_SESSION["id"]} AND `enrollment`.`sectID` = '$sectID'";
    $result = mysqliQuery($query);

    echo json_encode($result);
  }


  //**createSection**
  if ($fname == "getCourseNames"){
    getCourseNames();
  }
  function getCourseNames() {
    $response = array();
    $query = "SELECT `course`.`courseName`, `course`.`courseID`, `department`.`deptID`
              FROM `course`
              JOIN `department` ON `department`.`deptID` = `course`.`deptID`
              order by `deptID`, `courseName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getFacultyNames"){
    getFacultyNames();
  }
  function getFacultyNames() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `facultyID`
              FROM `faculty`
              JOIN `person` ON `person`.`personID` = `faculty`.`facultyID`
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getFacCourseDept"){
    getFacCourseDept();
  }
  function getFacCourseDept() {
    $response = array();
    $courseID = $_GET['courseID'];
    //this query gives us the deptID of the course selected
    $deptIDQuery = "SELECT `deptID`
                    FROM `course`
                    WHERE `courseID` = '$courseID'";
    $deptID = mysqliQuery($deptIDQuery);
    while($row = mysqli_fetch_array($deptID))
            {
                $dep = $row['deptID'];
            }

    $query = "SELECT `firstName`, `lastName`, `facultyID`, `faculty`.`deptID`
              FROM `faculty`
              JOIN `person` ON `person`.`personID` = `faculty`.`facultyID`
              JOIN `department` ON `department`.`deptID` = `faculty`.`deptID`
              WHERE `department`.`deptID` = '$dep'
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getBuildingNames"){
    getBuildingNames();
  }
  function getBuildingNames() {
    $response = array();
    $query = "SELECT `buildingName`, `buildingID` FROM `building`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getRoomTypesByBuilding"){
    getRoomTypesByBuilding();
  }
  function getRoomTypesByBuilding() {
    $response = array();
    $buildingID = $_GET['buildingID'];
    $query = "SELECT DISTINCT `roomType` FROM `room` WHERE `room`.`buildingID` = '$buildingID'";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getSpecificRoomNumbers"){
    getSpecificRoomNumbers();
  }
  function getSpecificRoomNumbers() {
    $response = array();
    $buildingID = $_GET['buildingID'];
    $roomType = $_GET['roomType'];
    $timeslotID = $_GET['timeslotID'];
    $term = $_GET['term'];
    $query = "SELECT `availableRooms`.`roomNum` FROM
              (SELECT `allRooms`.`roomNum`
                FROM
                (SELECT `room`.`roomNum`
                  FROM `room`
                  JOIN `building` ON `building`.`buildingID` = `room`.`buildingID`
                  WHERE `room`.`roomType` = '$roomType' AND `room`.`buildingID` = '$buildingID') AS allRooms
                LEFT OUTER JOIN
                  (SELECT `section`.`roomNum`
                    FROM `section`
                    WHERE `section`.`timeslotID` = '$timeslotID' AND `section`.`buildingID` = '$buildingID' AND `section`.`term` = '$term') AS takenRooms
                ON `allRooms`.`roomNum` = `takenRooms`.`roomNum`
                WHERE `takenRooms`.`roomNum` IS NULL) AS availableRooms";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getTerms"){
    getTerms();
  }
  function getTerms() {
    $response = array();
    $query = "SELECT `seasonYear` FROM `term`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getTimeslots"){
    getTimeslots();
  }
  function getTimeslots() {
    $response = array();
    $query = "SELECT `timeslotID`, `days`, `periodStart`, `periodEnd` FROM `timeslot`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getAvailableTimeslots"){
    getAvailableTimeslots();
  }
  function getAvailableTimeslots() {
  $response = array();
  $term = $_GET['term'];
  $facultyID = $_GET['facultyID'];
  $query = "SELECT `availableTimes`.`timeslotID`, `availableTimes`.`days`, `availableTimes`.`periodStart`, `availableTimes`.`periodEnd`
            FROM
              (SELECT `allTimes`.`timeslotID`, `allTimes`.`days`, `allTimes`.`periodStart`, `allTimes`.`periodEnd`
               FROM
                 (SELECT `timeslot`.`timeslotID`, `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`
                  FROM `timeslot`) as allTimes
            LEFT OUTER JOIN
            (SELECT `section`.`timeslotID`
             FROM `section`
             WHERE `section`.`facultyID` = '$facultyID' AND `section`.`term` = '$term') as takenTimes
            ON `allTimes`.`timeslotID` = `takenTimes`.`timeslotID`
            WHERE `takenTimes`.`timeslotID` IS NULL) AS availableTimes";
  $result = mysqliQuery($query);
   while ($row = mysqli_fetch_assoc($result)) {
     $response[] = $row;
   }
   echo json_encode($response);
 }


if($fname == 'getSectionsByTerm') {
    getSectionsByTerm();
}
function getSectionsByTerm() {
    $response = array();
    $term = $_GET['term'];
    $query = "SELECT DISTINCT `course`.`courseName`, `course`.`courseNum`, `course`.`courseID`, `section`.`term`, `department`.`deptID`
                FROM `section`
                JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                WHERE `section`.`term` = '$term'
                order by `department`.`deptID`, `courseName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
     $response[] = $row;
   }
    echo json_encode($response);
    }

  //**getFacultySchedule**
  if ($fname == "getFacultySchedule"){
    getFacultySchedule();
  }
  function getFacultySchedule() {
    $response = array();
    $query ="
    SELECT `course`.`courseNum`, `section`.`sectNum`, `course`.`courseName`, `timeslot`.`days`,
            `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `room`.`roomNum`
            FROM `faculty`
            JOIN `section` ON `section`.`facultyID` = `faculty`.`facultyID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
            JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
            JOIN `room` ON (`building`.`buildingID` = `room`.`buildingID` AND  `section`.`roomNum` = `room`.`roomNum`)
            WHERE `faculty`.`facultyID` = {$_SESSION["id"]} AND `section`.`term` = '$term'";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  //**Department**
  //getFacultyNames
  /*
  if ($fname == "getDepartmentFaculty"){
    getDepartmentFaculty();
  }
  function getDepartmentFaculty() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `facultyID`
              FROM `faculty`
              JOIN `person` ON `person`.`personID` = `faculty`.`facultyID`
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }*/


  //**addAdmin**
  if ($fname == "getAdminNames"){
    getAdminNames();
  }
  function getAdminNames() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `personID` FROM `person` WHERE NOT EXISTS (SELECT `adminID` FROM `admin` WHERE person.personID = admin.adminID)
      AND `person_type` = 'admin' order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  //**addFaculty**
  if ($fname == "getFacNames"){
    getFacNames();
  }
  function getFacNames() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `personID` FROM `person` WHERE NOT EXISTS (SELECT `facultyID` FROM `faculty` WHERE person.personID = faculty.facultyID)
      AND `person_type` = 'faculty' order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }
  if ($fname == "getDepartmentNames"){
    getDepartmentNames();
  }
  function getDepartmentNames() {
    $response = array();
    $query = "SELECT `deptName`, `deptID` FROM `department` order by `deptName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getDepartmentNamesByTerm"){
    getDepartmentNamesByTerm();
  }
  function getDepartmentNamesByTerm() {
    $response = array();
    $term = $_SESSION['term'];
    $query = "SELECT DISTINCT `department`.`deptName`, `department`.`deptID`, `section`.`term`
              FROM `department`
              JOIN `course` ON `course`.`deptID` = `department`.`deptID`
              JOIN `section` ON `section`.`courseID` = `course`.`courseID`
              WHERE `section`.`term` = '$term'
              order by `deptName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  //**addStudent**
  if ($fname == "getStudentsInPerson"){
    getStudentsInPerson();
  }
  function getStudentsInPerson() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `personID` FROM `person` WHERE NOT EXISTS (SELECT `studentID` FROM `student` WHERE person.personID = student.studentID)
      AND `person_type` = 'student' order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getMajorNames"){
    getMajorNames();
  }
  function getMajorNames() {
    $response = array();
    $query = "SELECT `majorName`, `majorID` FROM `major`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getMinorNames"){
    getMinorNames();
  }
  function getMinorNames() {
    $response = array();
    $query = "SELECT `minorName`, `minorID` FROM `minor`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  //**addResearcher**
  if ($fname == "getResearcherNames"){
    getResearcherNames();
  }
  function getResearcherNames() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `personID` FROM `person` WHERE NOT EXISTS (SELECT `researcherID` FROM `researcher` WHERE person.personID = researcher.researcherID)
      AND `person_type` = 'researcher' order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }


  //**assignHold**
  if ($fname == "getStudentNames"){
    getStudentNames();
  }
  function getStudentNames() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `studentID`
              FROM `student`
              JOIN `person` ON `person`.`personID` = `student`.`studentID`
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  if ($fname == "getHoldNames"){
    getHoldNames();
  }
  function getHoldNames() {
    $response = array();
    $query = "SELECT `holdName`, `Description` FROM `hold` order by `holdName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }


  //**reviewMajorApplication**
  if ($fname == "getMajAppStudents"){
     getMajAppStudents();
  }
  function getMajAppStudents() {
     $response = array();
     $query = "SELECT `student`.`studentID`,`person`.`firstName`, `person`.`lastName`, `major`.`majorName`, `major`.`majorID`
     FROM `person`
     JOIN `student` ON `person`.`personID` = `student`.`studentID`
     JOIN `major` ON `major`.`majorID` = `major`.`majorID` AND `major`.`majorName` = `major`.`majorName`
     JOIN `majorApplication` ON `student`.`studentID` = `majorApplication`.`studentID` AND `major`.`majorID` = `majorApplication`.`majorID`
     order by `lastName`";
     $result = mysqliQuery($query);
     while ($row = mysqli_fetch_assoc($result)) {
     $response[] = $row;
    }
    echo json_encode($response);
   }


  //**reivewMinorApplication
  if ($fname == "getMinAppStudents"){
     getMinAppStudents();
  }
  function getMinAppStudents() {
     $response = array();
     $query = "SELECT `student`.`studentID`,`person`.`firstName`, `person`.`lastName`, `minor`.`minorName`, `minor`.`minorID`
     FROM `person`
     JOIN `student` ON `person`.`personID` = `student`.`studentID`
     JOIN `minor` ON `minor`.`minorID` = `minor`.`minorID` AND `minor`.`minorName` = `minor`.`minorName`
     JOIN `minorApplication` ON `student`.`studentID` = `minorApplication`.`studentID` AND `minor`.`minorID` = `minorApplication`.`minorID`
     order by `lastName`";
     $result = mysqliQuery($query);
     while ($row = mysqli_fetch_assoc($result)) {
     $response[] = $row;
    }
    echo json_encode($response);
   }


  //**enterGrades**
  if ($fname == "getCoursesByFaculty"){
    getCoursesByFaculty();
  }
  function getCoursesByFaculty() {
  $response = array();
  $term = $_GET['term'];
  //AND `section`.`term` = '$term'
  $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `section`.`sectID`,`section`.`term`
            FROM `faculty`
            JOIN `section` ON `section`.`facultyID` = `faculty`.`facultyID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `term` ON `section`.`term` = `term`.`seasonYear`
            WHERE `faculty`.`facultyID` = {$_SESSION["id"]} AND `section`.`term` = '$term'
            order by `courseName`, `sectNum`";
  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }


  if ($fname == "getStudentsBySection"){
    getStudentsBySection();
  }
  function getStudentsBySection() {
  $response = array();
  $courseList = $_GET['courseList'];
  $query = "SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `student`.`studentID`, `section`.`sectID`
            FROM `person`
            JOIN `student` ON `person`.`personID` = `student`.`studentID`
            JOIN `enrollment` ON `student`.`studentID` = `enrollment`.`studentID`
            JOIN `section` ON `enrollment`.`sectID` = `section`.`sectID`
            WHERE `section`.`sectID` = '$courseList'";
  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }


  //**removeHold**
  if ($fname == "getStudentsWithHolds"){
    getStudentsWithHolds();
  }
  function getStudentsWithHolds() {
  $response = array();
  $courseList = $_GET['courseList'];
  $query = "SELECT `person`.`firstName`, `person`.`lastName`, `studentHolds`.`studentID`, `studentHolds`.`holdName`
            FROM `studentHolds`
            JOIN `person` ON `studentHolds`.`studentID` = `person`.`personID`
            order by `lastName`";
  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }


  //**login**
  if ($fname == "login") {
    login();
  }
  function login() {

      // set loggedIn to false
      $_SESSION["loggedIn"] = false;

      // get email and password from ajax
      $email = $_GET["email"];
      $password = $_GET["password"];

      // query for the password associated with that email
      $firstResult = mysqliQuery("SELECT `password` FROM `person` WHERE `email` = '$email'", MYSQLI_USE_RESULT);

      if ($firstResult->num_rows > 0) {

        // get first row
        $row = $firstResult->fetch_assoc();

        // obtain actual password stored in database from result set
        $actualPassword = $row["password"];

        // if it is the correct password, get the following information from the databse
        if ($actualPassword == $passwordEntered) {

          // get id and type
          $secondResult = mysqliQuery("SELECT `personID`, `person_type` FROM `person` WHERE `email` = '$emailEntered'", MYSQLI_USE_RESULT);
          $row = $secondResult->fetch_assoc();

          // set session variables -- can we do this here?
          $_SESSION["type"] = $row["person_type"];
          $_SESSION["id"] = $row["personID"];
          $_SESSION["loggedIn"] = true;
          echo json_encode("{loggedin: true}");

        } else { // bad password
          $_SESSION["loggedIn"] = false;
          echo json_encode("{loggedin: false}");
        }
      } else { // bad email
        $_SESSION["loggedIn"] = false;
        echo json_encode("{loggedin: false}");
      }
      //echo json_encode("{loggedin: true}");
    }

    //**courseCatalog**
    if ($fname == "getCourseCatalog")
    {
    getCourseCatalog();
    }
    function getCourseCatalog()
    {
      $response = array();
      $query = "SELECT `courseNum`, `courseName`, `department`.`deptName`, `courseDescription`, `credits`
                FROM `course`
                JOIN `department` ON `department`.`deptID` = `course`.`deptID`";
      $result = mysqliQuery($query);
      while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
      }
      echo json_encode($response);
    }

    //**masterList**
    if ($fname == "getMasterList")
    {
    getMasterList();
    }
    function getMasterList()
    {
      $response = array();
      $query ="
         SELECT `department`.`deptName`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`.`buildingName`, `section`.`roomNum`, `person`.`firstName`, `person`.`lastName`
                FROM `section`
                JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                WHERE `section`.`term` = '$term'";
      $result = mysqliQuery($query);
      while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
      }
      echo json_encode($response);
    }

  if ($fname == "getAvailableBuildings") {
    getAvailableBuildings();
  }
  function getAvailableBuildings() {
    $response = array();
    $term = $_GET['term'];
    $timeslotID = $_GET['timeslotID'];
    $query = "SELECT `availableRooms`.`buildingName`, `availableRooms`.`buildingID` FROM
              (SELECT DISTINCT `allRooms`.`buildingID`, `allRooms`.`buildingName`
                FROM
                (SELECT `room`.`roomNum`, `room`.`buildingID`, `building`.`buildingName`
                  FROM `room`
                  JOIN `building` ON `building`.`buildingID` = `room`.`buildingID`) AS allRooms
                LEFT OUTER JOIN
                  (SELECT `section`.`buildingID`, `section`.`roomNum`
                    FROM `section`
                    WHERE `section`.`timeslotID` = '$timeslotID' AND `section`.`term` = '$term') AS takenRooms
                ON `allRooms`.`buildingID` = `takenRooms`.`buildingID` AND `allRooms`.`roomNum` = `takenRooms`.`roomNum`
                WHERE `takenRooms`.`buildingID` IS NULL) AS availableRooms";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

  //**searchUsers**
 if ($fname == "getFacultyByDepartment"){
    getFacultyByDepartment();
  }
  function getFacultyByDepartment() {
    $response = array();
    $deptNames = $_GET['deptNames'];
    $query = "SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `faculty`.`facultyID`, `department`.`deptID`
              FROM `person`
              JOIN `faculty` ON `person`.`personID` = `faculty`.`facultyID`
              JOIN `department` ON `department`.`deptID` = `faculty`.`deptID`
              WHERE `department`.`deptID` = '$deptNames'";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
    }

if ($fname == "getStudentsByMajor"){
    getStudentsByMajor();
  }
  function getStudentsByMajor() {
    $response = array();
    $major = $_GET['majors'];
    $query = "SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `student`.`studentID`, `studentsMajor`.`majorID`
              FROM `person`
              JOIN `student` ON `person`.`personID` = `student`.`studentID`
              JOIN `studentsMajor` ON `studentsMajor`.`studentID` = `student`.`studentID`
              WHERE `studentsMajor`.`majorID` = '$major'
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
    }


//**registration**
if ($fname == "getSectionByDept"){
       getSectionByDept();
     }
     function getSectionByDept() {
       $response = array();
       $dept = $_GET['deptNames'];
       $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `department`.`deptName`, `section`.`sectNum`,
                `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                `person`.`firstName`, `person`.`lastName`
                FROM `section`
                JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                WHERE `department`.`deptID` = '$dept'";
       $result = mysqliQuery($query);
       while ($row = mysqli_fetch_assoc($result)) {
         $response[] = $row;
       }
       echo json_encode($response);
       }



    //**updatePassword**


    if ($fname == "updatePassword"){
      updatePassword();
    }
    function updatePassword() {
      $response = array();
      //$firstPW = $_GET["newPW"];
      $query ="UPDATE person
               SET `password` = '$firstPW'
               WHERE `personID` = 2";
      $result = mysqliQuery($query);
      while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;
      }
      echo json_encode($response);
    }

    //**viewAdvisees**
if ($fname == "viewAdvisees"){
    viewAdvisees();
  }
  function viewAdvisees() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `student`.`studentID`
              FROM `faculty`
              JOIN `person`
              JOIN `student` ON `person`.`personID` = `student`.`studentID`
              JOIN `advisement` ON `advisement`.`studentID` = `student`.`studentID` AND `advisement`.`facultyID` = `faculty`.`facultyID`
              WHERE `advisement`.`facultyID` = {$_SESSION["id"]}
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }


    //**viewAdvisor**
if ($fname == "viewAdvisor"){
    viewAdvisor();
  }
  function viewAdvisor() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `faculty`.`facultyID`
              FROM `student`
              JOIN `person`
              JOIN `faculty` ON `person`.`personID` = `faculty`.`facultyID`
              JOIN `advisement` ON `advisement`.`studentID` = `student`.`studentID` AND `advisement`.`facultyID` = `faculty`.`facultyID`
              WHERE `advisement`.`studentID` = {$_SESSION["id"]}
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

//**viewAllUsers**
if ($fname == "viewAllUsers"){
    viewAllUsers();
  }
  function viewAllUsers() {
    $response = array();
    $query = "SELECT `firstName`, `lastName`, `person`.`personID`
              FROM `person`
              order by `lastName`";
    $result = mysqliQuery($query);
    while ($row = mysqli_fetch_assoc($result)) {
      $response[] = $row;
    }
    echo json_encode($response);
  }

//**dropCourse**
if ($fname == "getCoursesByStudent"){
    getCoursesByStudent();
}
function getCoursesByStudent() {
  $response = array();
  $term = $_GET['term'];
  $studentID = $_SESSION['id'];
  $query = "SELECT `enrollment`.`sectID`, `enrollment`.`studentID`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `section`.`term`
            FROM `section`
            JOIN `enrollment` ON `enrollment`.`sectID` = `section`.`sectID`
            JOIN `student` ON `student`.`studentID` = `enrollment`.`studentID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `term` ON `section`.`term` = `term`.`seasonYear`
            WHERE `student`.`studentID` = '$studentID' AND `section`.`term` = '$term'
            order by `courseName`";

  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }

  if ($fname == "getCoursesByStudentAlt"){
    getCoursesByStudentAlt();
}
function getCoursesByStudentAlt() {
  $response = array();
  $term = $_GET['term'];
  $studentNames = $_GET['studentNames'];
  $query = "SELECT `enrollment`.`sectID`, `enrollment`.`studentID`, `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `section`.`term`
            FROM `section`
            JOIN `enrollment` ON `enrollment`.`sectID` = `section`.`sectID`
            JOIN `student` ON `student`.`studentID` = `enrollment`.`studentID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `term` ON `section`.`term` = `term`.`seasonYear`
            WHERE `student`.`studentID` = '$studentNames' AND `section`.`term` = '$term'
            order by `courseNum`";

  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }

  //**transcript**
if ($fname == "getTranscript"){
    getTranscript();
}
function getTranscript() {
  $response = array();
  $term = $_GET['term'];
  //AND `section`.`term` = '$term'
  $query = "SELECT `course`.`courseNum`, `section`.`sectNum`, `course`.`courseName`, `student_history`.`grade`, `course`.`credits`, `section`.`term`
            FROM `student_history`
            JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            WHERE `studentID` = {$_SESSION["id"]}";

  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }

  if ($fname == "getCurrentChair"){
    getCurrentChair();
}
function getCurrentChair() {
  $response = array();
  $deptID = $_GET['deptName'];
  $query = "SELECT `person`.`firstName`, `person`.`lastName`, `department`.`chairID`
                            FROM `department`
                            JOIN `person` ON `person`.`personID` = `department`.`chairID`
                            WHERE `department`.`deptID` = '$deptID'";

  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
}

if ($fname == 'checkStudentRestrictions') {
        checkStudentRestrictions();
}
function checkStudentRestrictions() {

  // variables
  $student = $_SESSION['id'];
  $section = $_GET['sectID'];
  $course = $_GET['courseID'];
  $term = $_SESSION['term'];

  // number of restrictions
  $count = 0;
  $reasons = array();

  // check holds
  $holdQuery = "SELECT * FROM `studentHolds` WHERE `studentHolds`.`studentID` = '$student'";
  $holdResult = mysqliQuery($holdQuery);
  if (mysqli_num_rows($holdResult) != 0) {
    $count += 1;
    array_push($reasons, "You have holds on your account, you should focus on that.");
  }

  // check prereqs
  // get courseid from section
  $bitchQuery = "SELECT `courseID` FROM `section` WHERE `section`.`sectID` = '$section'";
  $bitchResult = mysqliQuery($bitchQuery);
  while ($row = mysqli_fetch_array($bitchResult)) {
    $course = $row['courseID'];
  }
  $prereqQuery = "SELECT *
    FROM
      (SELECT `prereqID`
        FROM `prereqCourse`
        WHERE `prereqCourse`.`courseID` = '$course') as requiredCourses
    LEFT OUTER JOIN
      (SELECT `courseID`
        FROM `student_history`
        JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
        WHERE `student_history`.`studentID` = '$student') as takenCourses
    ON `requiredCourses`.`prereqID` = `takenCourses`.`courseID`
    WHERE `takenCourses`.`courseID` IS NULL";
  $prereqResult = mysqliQuery($prereqQuery);
  if (mysqli_num_rows($prereqResult) != 0) {
    $count++;
    array_push($reasons, "You do not satisfy prerequisite requirements because you didn't focus last semester.");
  }

  // check if already taking course
  $courseQuery = "SELECT `section`.`courseID` FROM `enrollment`
                  JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                  WHERE `enrollment`.`studentID` = '$student' AND `section`.`courseID` = '$course' AND `section`.`term` = '$term'";
  $courseResult = mysqliQuery($courseQuery);
  if (mysqli_num_rows($courseResult) != 0) {
    $count++;
    array_push($reasons, "You're already registered for a section of this course, please focus.");
  }

  // check if they are signed up for a class already at this time bitch
  // get the timeslot ids from enrollment table where studentid = student
  $timeQuery = "SELECT `currentTimeslots`.`timeslotID`
                FROM
                  (SELECT `section`.`timeslotID`
                   FROM `section`
                   WHERE `section`.`sectID` = '$section') as desiredTimeslot
                LEFT OUTER JOIN
                  (SELECT `section`.`timeslotID`
                   FROM `enrollment`
                   JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                   JOIN `timeslot` ON `timeslot`.`timeslotID` = `section`.`timeslotID`
                 WHERE `enrollment`.`studentID` = '$student' AND `section`.`term` = '$term') as currentTimeslots
                 ON `desiredTimeslot`.`timeslotID` = `currentTimeslots`.`timeslotID`
                 WHERE `desiredTimeslot`.`timeslotID` IS NOT NULL";
  $timeResult = mysqliQuery($timeQuery);

   while ($row = mysqli_fetch_array($timeResult)) {
     $test = $row['timeslotID'];
  }
  if ($test !== null) {
    $count++;
    array_push($reasons, "You are registered for a class at this time already, and you need to focus on that.");
  }

  // reg cap
  $regQuery = "SELECT `regCap` FROM `section` WHERE `section`.`sectID` = '$section'";
  $regResult = mysqliQuery($regQuery);
  while ($row = mysqli_fetch_array($regResult)) {
    $regCap = $row['regCap'];
  }
  $actQuery = "SELECT COUNT(`studentID`) as numStudents FROM `enrollment` WHERE `enrollment`.`sectID` = '$section'";
  $actResult = mysqliQuery($actQuery);
  while ($row = mysqli_fetch_array($actResult)) {
    $regAct = $row['numStudents'];
  }
  if ($regAct == $regCap) {
    $count++;
    array_push($reasons, "The class is full students who are more focused than you, git gud.");
  }

  // can't take more than 4 classes
  $maxQuery = "SELECT COUNT(`studentID`) as numClasses
               FROM `enrollment`
               JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID` AND `section`.`term` = '$term'
               WHERE `enrollment`.`studentID` = '$student'";
  $maxResult = mysqliQuery($maxQuery);
  while ($row = mysqli_fetch_array($maxResult)) {
    $numClassesTaken = $row['numClasses'];
  }
  if ($numClassesTaken == 4) {
    $count++;
    array_push($reasons, "You can't take more than 4 classes, stay focused!");
  }
  $response = array('reasons' => $reasons);

  echo json_encode($response);

}

if($fname == 'registerForAFuckingClass') {
    registerForAFuckingClass();
}
function registerForAFuckingClass() {
    $studentID = $_SESSION['id'];
    $sectID = $_GET['sectID'];
    $query = "INSERT INTO `enrollment` (studentID, sectID) VALUES ('$studentID', '$sectID')";
    $result = mysqliQuery($query);
    echo json_encode($result);
}

if($fname == 'updateFulltimeStatus') {
    updateFulltimeStatus();
}
function updateFulltimeStatus() {
    $studentID = $_SESSION['id'];
    $term = $_SESSION['term'];
    $query = "SELECT `enrollment`.`studentID`
              FROM `enrollment`
              JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
              WHERE `enrollment`.`studentID` = '$studentID' AND `section`.`term` = '$term'";
    $result = mysqliQuery($query);
    $numOfClasses = mysqli_num_rows($result);
    if($numOfClasses == 3)
    {
      $updateStatus = "UPDATE `student` SET `fullTime` = 1 WHERE `studentID` = '$studentID'";
      $updateQuery = mysqliQuery($updateStatus);
      echo json_encode($updateQuery);

    }
    else{
    echo json_encode($result);
    }
}

if($fname == 'editAFuckingClass') {
    editAFuckingClass();
}
function editAFuckingClass() {
     $term = $_GET['term'];
     $deptID = $_GET['deptID'];
     $courseName = $_GET['courseName'];
     $courseNum = $_GET['courseNum'];
     $sectNum = $_GET['sectNum'];
     $days = $_GET['days'];
     $periodStart = $_GET['periodStart'];
     $periodEnd = $_GET['periodEnd'];
     $buildingName = $_GET['buildingName'];
     $roomNum = $_GET['roomNum'];
     $firstName = $_GET['firstName'];
     $lastName = $_GET['lastName'];
     $regCap = $_GET['regCap'];
     $sectID = $_GET['sectID'];

     //get the actual deptID
     $getDeptID = "SELECT `deptID` FROM `department` WHERE `deptName` = '$deptID'";
     $getDeptIDResult = mysqliQuery($getDeptID);
      while($row = mysqli_fetch_array($getDeptIDResult))
      {
        $realDeptID = $row['deptID'];
      }

      //get the courseID
      $getCourseID = "SELECT `courseID` FROM `course` WHERE `courseName` = '$courseName'";
      $getCourseIDResult = mysqliQuery($getCourseID);
      while($row = mysqli_fetch_array($getCourseIDResult))
      {
        $realCourseID = $row['courseID'];
      }

      //get the timeslot ID
      $getTimeslotID = "SELECT `timeslotID` FROM `timeslot` WHERE `days` = '$days' AND `periodStart` = '$periodStart' AND `periodEnd` = '$periodEnd'";
      $getTimeslotIDResult = mysqliQuery($getTimeslotID);
      while($row = mysqli_fetch_array($getTimeslotIDResult))
      {
        $realTimeslotID = $row['timeslotID'];
      }

      //get the buildingID
      $getBuildingID = "SELECT `buildingID` FROM `building` WHERE `buildingName` = '$buildingName'";
      $getBuildingIDResult = mysqliQuery($getBuildingID);
      while($row = mysqli_fetch_array($getBuildingIDResult))
      {
        $realBuildingID = $row['buildingID'];
      }

      //get the facultyID
      $getFacultyID = "SELECT `personID` FROM `person` WHERE `firstName` = '$firstName' AND `lastName` = '$lastName'";
      $getFacultyIDResult = mysqliQuery($getFacultyID);
      while($row = mysqli_fetch_array($getFacultyIDResult))
      {
        $realFacultyID = $row['personID'];
      }

     $query = "UPDATE `section` SET `courseID` = '$realCourseID', `facultyID` = '$realFacultyID', `roomNum` = '$roomNum',
                `buildingID` = '$realBuildingID', `sectNum` = '$sectNum', `term` = '$term', `timeslotID` = '$realTimeslotID', `regCap` = '$regCap'
                     WHERE `section`.`sectID` = '$sectID'";

      $result = mysqliQuery($query);
      echo json_encode($result);
}

if($fname == 'editTheFuckingCatalog') {
    editTheFuckingCatalog();
}
function editTheFuckingCatalog() {
     $courseID = $_GET['courseID'];
     $courseNum = $_GET['courseNum'];
     $courseName = $_GET['courseName'];
     $deptName = $_GET['deptName'];
     $courseDescription = $_GET['courseDescription'];
     $credits = $_GET['credits'];

     //get the actual deptID
     $getDeptID = "SELECT `deptID` FROM `department` WHERE `deptName` = '$deptName'";
     $getDeptIDResult = mysqliQuery($getDeptID);
      while($row = mysqli_fetch_array($getDeptIDResult))
      {
        $realDeptID = $row['deptID'];
      }


     $query = "UPDATE `course` SET `courseNum` = '$courseNum', `courseName` = '$courseName', `deptID` = '$realDeptID',
                     `courseDescription` = '$courseDescription', `credits` = '$credits'
                     WHERE `course`.`courseID` = '$courseID'";

      $result = mysqliQuery($query);
      echo json_encode($result);
}

if($fname == 'manageAttendance') {
    manageAttendance();
}
function manageAttendance() {
     $sectID = $_GET['sectID'];
     $meetingDate = $_GET['meetingDate'];
     $studentID = $_GET['studentID'];

     $query = "INSERT INTO `attendance` (studentID, sectID, meeting_date) VALUES ('$studentID', '$sectID', '$meetingDate')";
     $result = mysqliQuery($query);
     echo json_encode($result);
}

if($fname == 'editUserInfo') {
    editUserInfo();
}
function editUserInfo() {
     $phoneNum = $_GET['phoneNum'];
     $streetAddress = $_GET['streetAddress'];
     $personID = $_GET['personID'];

     $query = "UPDATE `person` SET `phoneNum` = '$phoneNum', `streetAddress` = '$streetAddress'
               WHERE `person`.`personID` = '$personID'";

      $result = mysqliQuery($query);
      echo json_encode($result);
}


if ($fname == "getCoursesByFacultyAlt"){
    getCoursesByFacultyAlt();
  }
  function getCoursesByFacultyAlt() {
  $response = array();
  $term = $_GET['term'];
  $facultyName = $_GET['facultyName'];
  $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `section`.`sectNum`, `section`.`sectID`,`section`.`term`
            FROM `faculty`
            JOIN `section` ON `section`.`facultyID` = `faculty`.`facultyID`
            JOIN `course` ON `course`.`courseID` = `section`.`courseID`
            JOIN `term` ON `section`.`term` = `term`.`seasonYear`
            WHERE `faculty`.`facultyID` = '$facultyName' AND `section`.`term` = '$term'
            order by `courseName`, `sectNum`";
  $result = mysqliQuery($query);
  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }
  echo json_encode($response);
  }

  if ($fname == "getSections"){
              getSections();
            }
            function getSections() {
              $response = array();
              $query = "SELECT `course`.`courseName`, `course`.`courseNum`, `course`.`courseID`, `department`.`deptName`, `section`.`sectNum`,
                       `timeslot`.`days`, `timeslot`.`periodStart`, `timeslot`.`periodEnd`, `building`. `buildingName`, `section`. `roomNum`,
                       `person`.`firstName`, `person`.`lastName`
                       FROM `section`
                       JOIN `course` ON `course`.`courseID` = `section`.`courseID`
                       JOIN `department` ON `department`.`deptID` = `course`.`deptID`
                       JOIN `timeslot` ON `section`.`timeslotID` = `timeslot`.`timeslotID`
                       JOIN `person` ON `section`.`facultyID` = `person`.`personID`
                       JOIN `building` ON `section`.`buildingID` = `building`.`buildingID`
                       order by `department`.`deptID`, `course`.`courseNum`, `course`.`courseName`";
              $result = mysqliQuery($query);
              while ($row = mysqli_fetch_assoc($result)) {
                $response[] = $row;
              }
              echo json_encode($response);
              }




if ($fname == 'checkStudentRestrictionsAlt') {
        checkStudentRestrictionsAlt();
}
function checkStudentRestrictionsAlt() {

  // variables
  $student = $_GET['studentID'];
  $section = $_GET['sectID'];
  $course = $_GET['courseID'];
  $term = $_GET['term'];

  // number of restrictions
  $count = 0;
  $reasons = array();

  // check holds
  $holdQuery = "SELECT * FROM `studentHolds` WHERE `studentHolds`.`studentID` = '$student'";
  $holdResult = mysqliQuery($holdQuery);
  if (mysqli_num_rows($holdResult) != 0) {
    $count += 1;
    array_push($reasons, "You have holds on your account, you should focus on that.");
  }

  // check prereqs
  // get courseid from section
  $bitchQuery = "SELECT `courseID` FROM `section` WHERE `section`.`sectID` = '$section'";
  $bitchResult = mysqliQuery($bitchQuery);
  while ($row = mysqli_fetch_array($bitchResult)) {
    $course = $row['courseID'];
  }
  $prereqQuery = "SELECT *
    FROM
      (SELECT `prereqID`
        FROM `prereqCourse`
        WHERE `prereqCourse`.`courseID` = '$course') as requiredCourses
    LEFT OUTER JOIN
      (SELECT `courseID`
        FROM `student_history`
        JOIN `section` ON `section`.`sectID` = `student_history`.`sectID`
        WHERE `student_history`.`studentID` = '$student') as takenCourses
    ON `requiredCourses`.`prereqID` = `takenCourses`.`courseID`
    WHERE `takenCourses`.`courseID` IS NULL";
  $prereqResult = mysqliQuery($prereqQuery);
  if (mysqli_num_rows($prereqResult) != 0) {
    $count++;
    array_push($reasons, "You do not satisfy prerequisite requirements because you didn't focus last semester.");
  }

  // check if already taking course
  $courseQuery = "SELECT `section`.`courseID` FROM `enrollment`
                  JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                  WHERE `enrollment`.`studentID` = '$student' AND `section`.`courseID` = '$course' AND `section`.`term` = '$term'";
  $courseResult = mysqliQuery($courseQuery);
  if (mysqli_num_rows($courseResult) != 0) {
    $count++;
    array_push($reasons, "You're already registered for a section of this course, please focus.");
  }

  // check if they are signed up for a class already at this time bitch
  // get the timeslot ids from enrollment table where studentid = student
  $timeQuery = "SELECT `section`.`timeslotID`
                FROM `enrollment`
                JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
                JOIN `timeslot` ON `timeslot`.`timeslotID` = `section`.`timeslotID`
                WHERE `enrollment`.`sectID` = '$section' AND `enrollment`.`studentID` = '$student' AND `section`.`term` = '$term'";
  $timeResult = mysqliQuery($timeQuery);
  if (mysqli_num_rows($timeResult) != 0) {
    $count++;
    array_push($reasons, "You are registered for a class at this time already, and you need to focus on that.");
  }

  // reg cap
  $regQuery = "SELECT `regCap` FROM `section` WHERE `section`.`sectID` = '$section'";
  $regResult = mysqliQuery($regQuery);
  while ($row = mysqli_fetch_array($regResult)) {
    $regCap = $row['regCap'];
  }
  $actQuery = "SELECT COUNT(`studentID`) as numStudents FROM `enrollment` WHERE `enrollment`.`sectID` = '$section'";
  $actResult = mysqliQuery($actQuery);
  while ($row = mysqli_fetch_array($actResult)) {
    $regAct = $row['regCap'];
  }
  if ($regAct == $regCap) {
    $count++;
    array_push($reasons, "The class is full students who are more focused than you, git gud.");
  }

  // can't take more than 4 classes
  $maxQuery = "SELECT COUNT(`studentID`) as numClasses
               FROM `enrollment`
               JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID` AND `section`.`term` = '$term'
               WHERE `enrollment`.`studentID` = '$student'";
  $maxResult = mysqliQuery($maxQuery);
  while ($row = mysqli_fetch_array($maxResult)) {
    $numClassesTaken = $row['numClasses'];
  }
  if ($numClassesTaken == 4) {
    $count++;
    array_push($reasons, "You can't take more than 4 classes, stay focused!");
  }
  $response = array('reasons' => $reasons);

  echo json_encode($response);

}

if($fname == 'registerForAFuckingClassAlt') {
    registerForAFuckingClassAlt();
}
function registerForAFuckingClassAlt() {
    $studentID = $_GET['studentID'];
    $sectID = $_GET['sectID'];
    $query = "INSERT INTO `enrollment` (studentID, sectID) VALUES ('$studentID', '$sectID')";
    $result = mysqliQuery($query);
    echo json_encode($result);
}

if($fname == 'updateFulltimeStatusAlt') {
    updateFulltimeStatusAlt();
}
function updateFulltimeStatusAlt() {
    $studentID = $_GET['studentID'];
    $term = $_GET['term'];
    $query = "SELECT `enrollment`.`studentID`
              FROM `enrollment`
              JOIN `section` ON `section`.`sectID` = `enrollment`.`sectID`
              WHERE `enrollment`.`studentID` = '$studentID' AND `section`.`term` = '$term'";
    $result = mysqliQuery($query);
    $numOfClasses = mysqli_num_rows($result);
    if($numOfClasses == 3)
    {
      $updateStatus = "UPDATE `student` SET `fullTime` = 1 WHERE `studentID` = '$studentID'";
      $updateQuery = mysqliQuery($updateStatus);
      echo json_encode($updateQuery);

    }
    else{
    echo json_encode($result);
    }
}







?>
