<?php

  require_once "connect.php"; // Gives us $con
  require_once "budget_functions.php";

  $qry_str = 'SELECT note FROM budget WHERE id='.$_POST['id'].';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  echo $entry['note'];
  return;

?>
