<?php

  require 'connect.php';
  include 'budget_functions.php';
  sec_session_start();

  if (isset($_POST['username'], $_POST['p'])) {
    $username = $_POST['username'];
    $password = $_POST['p']; // Hashed password
    if (login($username, $password, $con) == true) {
      // Login success
      header('Location: ./');
    } else {
      // Login failed
      header('Location: ./#login');
    }
  } else {
    // Correct POST variables weren't sent
    echo 'Invalid Request';
  }
?>
