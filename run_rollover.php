<?php

  require_once "budget_functions.php";

	if ( !check_login() )
		return;

	$con = connect();

  $month = $_POST['month'];
  $year = $_POST['year'];

	if ($month == 0) { $month = 12; $year--; }

  $qry_str = 'SELECT SUM(amount) FROM budget WHERE month='.$month.' AND year='.$year.' AND (category=1 OR category=2);';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $amount_spent = $entry['SUM(amount)'];

  $qry_str = 'SELECT SUM(goal) FROM categories WHERE id=1 OR id=2;';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $goals = $entry['SUM(goal)'];

	$difference = $goals - $amount_spent;

	// PJ
  $qry_str = 'SELECT SUM(amount) FROM budget WHERE month='.$month.' AND year='.$year.' AND category=3;';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $pj_spent = $entry['SUM(amount)'];

  $qry_str = 'SELECT SUM(goal) FROM categories WHERE id=3;';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $pj_goal = $entry['SUM(goal)'];

	$pj_surplus = $pj_goal - $pj_spent;
	$pj_surplus = -1 * ($pj_surplus + ($difference / 2));

  $qry_str = 'INSERT INTO budget(month, year, category, amount, note) VALUES('.date("m").','.date("Y").',3,'.$pj_surplus.',\'ROLLOVER\');';
  query_db($con, $qry_str);

	// KJ
  $qry_str = 'SELECT SUM(amount) FROM budget WHERE month='.$month.' AND year='.$year.' AND category=4;';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $kj_spent = $entry['SUM(amount)'];

  $qry_str = 'SELECT SUM(goal) FROM categories WHERE id=4;';
  $entry = fetch_array_db(query_db($con, $qry_str));
  $kj_goal = $entry['SUM(goal)'];

	$kj_surplus = $kj_goal - $kj_spent;
	$kj_surplus = -1 * ($kj_surplus + ($difference / 2));

  $qry_str = 'INSERT INTO budget(month, year, category, amount, note) VALUES('.date("m").','.date("Y").',4,'.$kj_surplus.',\'ROLLOVER\');';
  query_db($con, $qry_str);

	$con = null;
	header('Location: /');

?>
