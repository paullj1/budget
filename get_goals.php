<?php

  require_once "budget_functions.php";
  
	if ( !check_login() )
		return;

	$con = connect();

  $qry_str = 'SELECT id,category,goal,visible FROM categories;';

  $qry = query_db($con, $qry_str);
	$response = '{"status":"1","goals":[';
  while ($entry = fetch_array_db($qry)) {
		$response .= '{"id":"'.$entry['id'].'",';
		$response .= '"category":"'.$entry['category'].'",';
		$response .= '"goal":"'.$entry['goal'].'",';
		$response .= '"visible":"'.$entry['visible'].'"},';
	}
	$response = substr($response, 0, -1);
	$response .= ']}';

	$con = null;
	echo $response;

?>
