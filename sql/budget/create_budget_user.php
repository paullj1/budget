<?php

function insert_user($username, $password) {

  $con = new SQLite3('/var/www/db/budget.db');
  $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
  $password = hash('sha512', $password);
  $password = hash('sha512', $password.$random_salt);

  $con->exec("INSERT INTO users (username,pass,salt) 
              VALUES ('$username','$password','$random_salt');");
}

$username = "";
$password = "";
//insert_user($username, $password);

?>
