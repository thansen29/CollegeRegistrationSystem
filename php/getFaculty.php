<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 100%;
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left;}
</style>
</head>
<body>

<?php
$q = intval($_GET['q']);

  //require_once "mysqli.php";
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

  //$query = mysqliQuery("SELECT * FROM `department` WHERE `deptID` = '".$q."'", MYSQLI_USE_RESULT);

  $query = <<<EOD
  SELECT `person`.`firstName`, `person`.`lastName`, `department`.`deptID`, `department`.`deptName`, `faculty`.`facultyID`
  FROM `person`
  JOIN `faculty` ON `person`.`personID` = `faculty`.`facultyID`
  JOIN `department` ON `department`.`deptID` = `faculty`.`deptID`
  WHERE `department`.`deptID` = '$q'

EOD;

if ( !( $query = mysql_query( $query, $database ) ) )
{
   print( "<p>Could not execute query!</p>" );
   die( mysql_error() . "</body></html>" );
}

echo "<p>Names</p>";
echo '<p><select required name="Names" id="Names">';
while($row = mysql_fetch_array($query))
{
  echo "<option>". $row['firstName'] .' '. $row['lastName'] . "</option>";

}

  /*echo "<table>
  <tr>
  <th>Department Name</th>
  <th>First Name</th>
  <th>Last Name</th>
  </tr>";
  while($row = mysql_fetch_array($query)) {
      echo "<tr>";
      echo "<td>" . $row['deptName'] . "</td>";
      echo "<td>" . $row['firstName'] . "</td>";
      echo "<td>" . $row['lastName'] . "</td>";
      echo "</tr>";
  }
  echo "</table>";*/
?>
</body>
</html>
