<?php

  require_once "budget_functions.php";
  
	if ( !check_login() )
		return;

	$con = connect();

  $qry_str = 'SELECT DISTINCT month, year FROM budget ORDER BY 
              year DESC, month DESC;';

  $qry = query_db($con, $qry_str);
  $divider = "";
  while ($entry = fetch_array_db($qry)) {
    
    switch ($entry['month']) {
      case 1:  $month = "January"; break;
      case 2:  $month = "February"; break;
      case 3:  $month = "March"; break;
      case 4:  $month = "April"; break;
      case 5:  $month = "May"; break;
      case 6:  $month = "June"; break;
      case 7:  $month = "July"; break;
      case 8:  $month = "August"; break;
      case 9:  $month = "September"; break;
      case 10: $month = "October"; break;
      case 11: $month = "November"; break;
      case 12: $month = "December"; break;
      default: $month = "Undefined"; break;
    }

    // Add entry to list
    echo '<li><a href="#" onclick="month_press('.
          $entry['month'].','.$entry['year'].')">'.$month.', '.
          $entry['year'].'</a></li>';
  }
  // Make whole list viewable
  echo '<br><br><br>';

	$con = null;

?>
