<?php

  require_once "connect.php"; // Gives us $con
  require_once "budget_functions.php";

  $qry_str = 'SELECT category FROM budget WHERE id='.$_POST['id'].';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  echo $entry['category'];
  return;

?>
