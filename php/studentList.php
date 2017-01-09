<!DOCTYPE html>
<html>
   <head>
   </head>
<body>

  <?php
    //show the students in the course that was selected
    require_once "mysqli.php";
      $q = intval($_GET['q']);

      //Connect to database
      error_reporting(E_ALL ^ E_DEPRECATED);

      if ( !($database = mysql_connect( "fdb4.biz.nf", "2218832_reg", "wasd1324" ) ) )
         {
           die( "Could not connect to database" );
         }
       // open database
      if ( !mysql_select_db( "2218832_reg", $database ) )
        {
          die( "Could not open database" );
        }

      //query to show the students enrolled in the course selected

      $query = <<<EOD
      SELECT `person`.`firstName`, `person`.`lastName`, `person`.`personID`, `student`.`studentID`, `section`.`sectID`
      FROM `person`
      JOIN `student` ON `person`.`personID` = `student`.`studentID`
      JOIN `enrollment` ON `student`.`studentID` = `enrollment`.`studentID`
      JOIN `section` ON `enrollment`.`sectID` = `section`.`sectID`
      WHERE `section`.`sectID` = '$q'

EOD;

    if ( !( $query = mysql_query( $query, $database ) ) )
    {
       print( "<p>Could not execute query!</p>" );
       die( mysql_error() . "</body></html>" );
    }
    //select box to display the students
    echo '<p>Students</p>';
    echo '<p><select name = "studentNames" id = "studentNames">';
    //$studentList = mysqliQuery($query, MYSQLI_USE_RESULT);

    while($row = mysql_fetch_array($query))
    {
      echo "<option value '" .$row['studentID'] . "'>" . $row['firstName'] . ' ' . $row['lastName'] . "</option>";
    }
    echo '</select>';
      ?>

</body>
</html>
