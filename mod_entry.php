<?php

  require_once "connect.php"; // Gives us $con
  require_once "budget_functions.php";

  // Process POST vars
  $command = $_POST['command'];
  if ( $command == 'delete' )
    $qry_str = 'DELETE FROM budget WHERE id='.$_POST['id'].';';
  else if ( $command == 'update' ) {

    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT,
					   FILTER_FLAG_ALLOW_FRACTION);
    $note = filter_var($_POST['note'], FILTER_SANITIZE_STRING);

    $qry_str = 'UPDATE budget SET category='.$_POST['category'].', amount='.
                $amount.', note="'.$note.'" WHERE id='.$_POST['id'].';';
  } else {
    return;  // Unsupported function
  }

  query_db($con, $qry_str);
  return;

?>
