<?php

  require "connect.php"; // Gives us $con
  include "budget_functions.php";
  
  sec_session_start();
  if(login_check($con) == false) {  // NOT LOGGED IN
    echo '<li>Please Login to view current month</li>';
    return;
  }  // ELSE, DISPLAY INFO

  $month = $_POST['month'];
  $year = $_POST['year'];
  $current = $_POST['current'];

  $qry_str = 'SELECT budget.id, categories.category, budget.category 
              as cat_id, budget.amount, budget.note FROM budget JOIN 
              categories ON categories.id=budget.category WHERE 
              budget.month='.$month.' AND budget.year='.$year.' 
              ORDER BY categories.category ASC;';

  $qry = query_db($con, $qry_str);
  $divider = "";
  while ($entry = fetch_array_db($qry)) {

    // Add Divider by category
    if ( $entry['category'] != $divider ) {

      // Current month view will show amount remaining in budget
      if ( $current == 'true' ) {
        // If Negative, red and in parenthesis
        $remaining = get_remaining($entry['cat_id'], $con, $month, $year);
        if ($remaining < 0) { $remaining = '<font color="Crimson">($'.
                                            -$remaining.')</font>'; }
        // Else plain black text
        else { $remaining = '$'.$remaining; }

      // Historic Month view will show how much was spent
      } else {
        $remaining = '$'.get_total_spent($entry['cat_id'], $con, $month, $year);
      }

      echo '<li data-role="list-divider">'.$entry['category'].': '
           .$remaining.'</li>';
      $divider = $entry['category'];
    }
    
    // Add entry to list
    echo '<li><a href="#" id="'.$entry['id'].'" onclick="entry_press('.
          $entry['id'].','.$entry['amount'].')">$'.$entry['amount'].': '.
          $entry['note'].'</a></li>';
  }

?>
