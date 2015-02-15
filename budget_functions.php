<?php

function query_db($con, $query) {
  return $con->query($query);
  //return mysqli_query($con,$query);
}

function fetch_array_db($result) {
  return $result->fetchArray();
  //return mysqli_fetch_array($result);
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

function sec_session_start() {
  $session_name = 'PJKT_Budget';
  $secure = true;
  $httponly = true;
  
  ini_set('session.use_only_cookies', 1);
  $cookie_params = session_get_cookie_params();
  $cookie_params["lifetime"] = 60 * 60 * 24 * 30; // Thirty days
  session_set_cookie_params($cookie_params["lifetime"], $cookie_params["path"],
                            $cookie_params["domain"], $secure, $httponly);
  session_name($session_name);
  session_start();
  session_regenerate_id();

}

function login($username, $password, $con) {

  $qry_str = 'SELECT id,pass,salt FROM users WHERE username="'.$username.'" LIMIT 1;';
  $ret = fetch_array_db(query_db($con, $qry_str));

  if ($ret) {
    // Bind results from DB
    $user_id = $ret['id'];
    $db_pass = $ret['pass'];
    $salt = $ret['salt'];
    $password = hash('sha512', $password.$salt);

    if (check_brute($user_id, $con) == true) {

      // If account is locked, return false
      return false;

    } else {

      if ($db_pass == $password) { // SUCCESS!
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        // Replace any non-numeric in user_id for XSS Prevention
        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
        $_SESSION['user_id'] = $user_id;

        // Replace any non-alpha-numeric in username for XSS prevention
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
        $_SESSION['username'] = $username;
        $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
        return true;
      } else { // Incorrect password, log attempt
        $now = time();
        $con->exec("INSERT INTO login_attempts (user_id,time) VALUES ('$user_id','$now');");
        return false;
    }
  }
  } else { // No user exists
    return false;
  }
}

function check_brute($user_id, $con) {
  $now = time();
  // Count all invalid login attempts from the last two hours
  $valid_attempts = $now - (2 * 60 * 60);
  
  $qry_str = "SELECT time FROM login_attempts WHERE user_id='.$user_id.' AND time > '$valid_attempts';";
  $ret = fetch_array_db(query_db($con, $qry_str));
  if ( count($ret) > 5 ) // More than 5 invalid attempts in the last 2 hours
    return true;
  return false;
}

function login_check($con) {
  if ( ISSET($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $login_string = $_SESSION['login_string'];

    $user_browser = $_SERVER['HTTP_USER_AGENT'];
    $qry_str = 'SELECT pass FROM users WHERE id='.$user_id.' LIMIT 1;';
    $ret = fetch_array_db(query_db($con, $qry_str));
    if ( $ret ) { // User exists
      $password = $ret['pass'];
      $login_check = hash('sha512', $password.$user_browser);
      if ( $login_check == $login_string ) // Valid!
        return true;
    }
  }
  return false;
}

?>
