<?php

  require "connect.php"; // Gives us $con
  require "budget_functions.php";
  
  sec_session_start();
  if(login_check($con) == false) {  // Not logged in...
    echo '<script>alert("You must login!")</script>';
    return;
  }  // Else, logged in

  // Sanitize input
  $category = $_POST['category'];
  $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT,
					 FILTER_FLAG_ALLOW_FRACTION);
  $note = filter_var($_POST['note'], FILTER_SANITIZE_STRING);

  // Build Query
  $qry_str = 'INSERT INTO budget(month, year, category, amount, note) VALUES('.date("m").','.date("Y").',\''.$category.'\','.$amount.',\''.$note.'\');';
  query_db($con, $qry_str);
  return;
?>
