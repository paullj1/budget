<?php

// Create connection
//$con = mysqli_connect("10.0.10.16","budget","password","budget");

// Check connection
//if (mysqli_connect_errno()) {
  //echo "Failed to connect to MySQL: " . mysqli_connect_error();
//}

$con = new SQLite3('/home/pj/db/budget.db');
$con->busyTimeout(10000); // 10 Seconds
?>
