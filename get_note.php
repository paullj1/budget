<?php

  require_once "budget_functions.php";

	if ( !check_login() )
		return;

	$con = connect();

  $qry_str = 'SELECT note FROM budget WHERE id='.$_POST['id'].';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  echo $entry['note'];

	$con = null;

?>
