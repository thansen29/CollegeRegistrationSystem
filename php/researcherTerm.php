<?php
session_start();
if ($_SESSION["loggedIn"] == false) {
        header("Location: http://www.agru.co.nf/php/login.php");
        die();
}
?>
<!DOCTYPE html>


<html>
   <head>
      <meta charset = "utf-8">
      <title>Select Term</title>
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
                <a href = "studentHomepage.php" class = "myButton">Home</a>
            </p>
        </center>

        <form method = "post" action = "researcherHomepage.php">

        <p><h1>Look up classes to add</h1></p>

        <!-- Drop down list of terms -->
          <p>Search by term</p>
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

          <p>
          <input id "submitButton" class = "myButton" name = "submit" type = "submit" value = "Go" />
        </form>
          </p>



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
