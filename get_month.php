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
        if ($remaining < 0) { 
          $str_remaining = '<font color="Crimson">($'.-$remaining.')</font>';
        // Else plain black text
        } else { $str_remaining = '$'.$remaining; }

      // Historic Month view will show how much was spent
      } else {
        $str_remaining = '$'.get_total_spent($entry['cat_id'], $con, $month, $year);
      }

      // Build string containing Category: $remaining [=====%    ]
      $li_string = $entry['category'].': '.$str_remaining.' ';

      $percentage = get_percent_spent($entry['cat_id'], $con, $month, $year);
      if ($percentage > 100) $percentage = 100;
      if ($percentage < 90) { $color = 'Green'; }
      else if ($percentage > 90 && $percentage < 100) { $color = 'Yellow'; }
      else { $color = 'Crimson'; }
      $bg_color = 'LightGray';

      $div_percentage_container = '<div style="height:100%;width:100%;position:absolute;top:0;left:0;background-color:'.$bg_color.'"></div>';
      $div_percentage = '<div style="height:100%;width:'.$percentage.'%;position:absolute;top:0;left:0;z-index:10;background-color:'.$color.'"></div>';
      $div_progress = '<div style="position:relative;width:150px;height:15px">'.$div_percentage_container.$div_percentage.'</div>';

      echo '<li data-role="list-divider">'.$li_string.$div_progress.'</li>';

      // For the loop, which category are we currently working on?
      $divider = $entry['category'];
    }
    
    // Add entry to list
    echo '<li><a href="#" id="'.$entry['id'].'" onclick="entry_press('.
          $entry['id'].','.$entry['amount'].')">$'.$entry['amount'].': '.
          $entry['note'].'</a></li>';
  }

?>
