<?php

  require_once "budget_functions.php";

	if ( !check_login() )
		return;

	$con = connect();

  $qry_str = 'SELECT category FROM budget WHERE id='.$_POST['id'].';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  echo $entry['category'];

	$con = null;

?>
