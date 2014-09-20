<?php

  require "connect.php"; // Gives us $con
  include "budget_functions.php";

  $qry_str = 'SELECT id,category,visible FROM categories;';
  $ret = query_db($con, $qry_str);
  
  $month = $_POST['month'];
  $year = $_POST['year'];

  while ($entry = fetch_array_db($ret)) {
 
    if ( $entry['visible'] == 1 ) {
      // If Negative, red and in parenthesis
      $remaining = get_remaining($entry['id'], $con, $month, $year);
      if ($remaining < 0) { $remaining = '($'.-$remaining.')'; }
      else { $remaining = '$'.$remaining; }

      echo '<option value="'.$entry['id'].'">'.$entry['category'].': 
      '.$remaining.'</option>';
    }
  }
?>
