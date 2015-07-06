<?php

  require_once "budget_functions.php";

	if ( !check_login() )
		return;

	$con = connect();

  // Process POST vars
  $categories = json_decode($_POST['categories'],true);
	$query_str = "INSERT INTO categories (id,category,goal,visible) VALUES ";

	foreach ($categories as $category) {
		$goal = filter_var($category['goal'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$query_str .= '('.$category['id'].',"'.$category['category'].'",'.$goal.','.(($category['visible']=='true')?'1':'0').'),';
	}
	$query_str = substr($query_str,0,-1);
	$query_str .= ' ON DUPLICATE KEY UPDATE goal=VALUES(goal), visible=VALUES(visible);';

  query_db($con, $query_str);
	$con = null;
	return;

?>
