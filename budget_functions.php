<?php

use google\appengine\api\users\User;
use google\appengine\api\users\UserService;

// Using mysqli (connecting from App Engine)
function connect() {
	$con = mysqli_connect(
  	null,
  	'root', // username
  	'',     // password
  	'budget', // database name
  	null,
  	'/cloudsql/budget-paullj1:db'
  	);

	// Check connection
	if (mysqli_connect_errno())  {
  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		syslog(LOG_ERR,"Failed to connect to MySQL.");
		return null;
	}
	return $con;
}

function query_db($con, $query) {
  //return $con->query($query);
  return mysqli_query($con,$query);
}

function fetch_array_db($result) {
  //return $result->fetchArray();
  return mysqli_fetch_array($result);
}

function get_total_spent($category, $con, $month, $year) {

  $qry_str = 'SELECT SUM(amount) FROM budget WHERE category='.$category.' AND month='.$month.' AND year='.$year.';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  return $entry['SUM(amount)'];
}

function get_remaining($category, $con, $month, $year) {
  $qry_str = 'SELECT goal FROM categories WHERE id='.$category.';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $goal = $entry['goal'];
  $goal -= get_total_spent($category, $con, $month, $year);
  return floor($goal * 100) / 100;  // Not sure why, but w/o, returns long decimals
}

function get_percent_spent($category, $con, $month, $year) {
  $qry_str = 'SELECT goal FROM categories WHERE id='.$category.';';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $goal = $entry['goal'];
  $spent = get_total_spent($category, $con, $month, $year);
  return floor(($spent/$goal) * 100);
}

function get_authorized_users($con) {
  $qry_str = 'SELECT email FROM users;';
  $ret = query_db($con, $qry_str);
	$authorized_users = array();
  while ($entry = fetch_array_db($ret))
		array_push($authorized_users, $entry['email']);
	return $authorized_users;
}

function check_login() {
	$user = UserService::getCurrentUser();
	if (!isset($user)) {
		echo 'alert("Unauthorized User!");';
		return false;
	}

	$email = $user->getEmail();
	$con = connect();
	$authd_users = get_authorized_users($con);

	for ( $i = 0; $i < count($authd_users); $i++ )
		if ( $authd_users[$i] == $email ) {
			$con = null;
			return true;
		}
	$con = null;
	echo 'alert("Unauthorized User!");';
	return false;

}

?>
