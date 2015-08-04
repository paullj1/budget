<?php

  require_once "budget_functions.php";
	
	if ( !check_login() )
		return;

	$con = connect();

  // Sanitize input
  $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);
  $goal = filter_var($_POST['goal'], FILTER_SANITIZE_NUMBER_FLOAT,
					 FILTER_FLAG_ALLOW_FRACTION);

  // Build Query
  $qry_str = 'INSERT INTO categories(category, goal, visible) VALUES(\''.$category.'\','.$goal.',1);';
  query_db($con, $qry_str);

	$con = null;
?>
