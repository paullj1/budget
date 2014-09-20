
<?php
  require_once 'budget_functions.php';
  sec_session_start();
  
  // UNSET all session values
  $_SESSION = array();

  // get session parameters
  $params = session_get_cookie_params();

  // Delete cookie
  setcookie(session_name(), '', time() - 42000, $params["path"], 
            $params["domain"], $params["secure"], $params["httponly"]);
  session_destroy();
?>
