<?php

function connectMysqli(){
    $servername = "fdb4.biz.nf";
    $username = "2218832_reg";
    $password = "wasd1324";
    $dbname = "2218832_reg";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function mysqliQuery($query){
    $conn = connectMysqli();
    $result = mysqli_query($conn, $query);

    if(!$result){
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    return $result;
}



?>
