<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
  $q = intval($_POST['q']);

  error_reporting(E_ALL ^ E_DEPRECATED);

  require_once "mysqli.php";

  $query = mysqliQuery("SELECT `major`.`majorID`, `major`.`majorName`
      FROM `major`
      JOIN `majorApplication` ON `major`.`majorID` = `majorApplication`.`majorID`
      WHERE `majorApplication`.`studentID` = '$q'", MYSQLI_USE_RESULT);


    echo "<p>Majors</p>";
    echo '<p><select required name="majorID" id="majorID">';
    while($row = mysqli_fetch_array($query))
    {
      echo "<option value = '". $row['majorID'] . "'>" . $row['majorName'] . "</option>";
    }
    echo '</select></p>';
?>
  <form method = "post" action = "showMajors.php">

    <p>
      <input id "submit" class = "myButton" name = "submit" type = "submit" value = "Approve Major"/>
      <input id "resetButton" class = "myButton" type = "reset" value = "Reset" />
    </p>
  </form>
    <?php

      if(isset($_POST['submit'])){
          approveMajor();
         }
    ?>

    <?php
    //it appears this is never being reached
      function approveMajor()
      {
        require_once "mysqli.php";

        $majorID = $_POST["majorID"];

        //query to remove the major application from the table
        $removeQuery = mysqliQuery("DELETE FROM `majorApplication` WHERE `majorApplication`.`studentID` = '$q' AND
        `majorApplication`.`majorID` = '$majorID'", MYSQLI_USE_RESULT);

        //test if query works
        $removeQueryResults = mysqli_num_rows($removeQuery);
        if(!$removeQueryResults)
        {
          print("<p><h1>removal fail</h1></p>");
          die(mysql_error() . "</body></html>");
        }
        else {
          print("<p><h1>removal success</h1></p>");
        }

        //query to add the student and major to the studentsMajor table
        $addQuery = mysqliQuery("INSERT INTO `studentsMajor` (`studentID`, `majorID`) VALUES ('$q', '$majorID')", MYSQLI_USE_RESULT);
        $addQueryResults = mysqli_num_rows($addQuery);
        if(!$addQueryResults)
        {
          print("<p><h1> add fail/h1></p>");
          die(mysql_error() . "</body></html>");
        }
        else {
          print("<p><h1> add success/h1></p>");

        }

      }

     ?>

</body>
</html>
