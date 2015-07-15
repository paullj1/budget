<?php
 	require_once "budget_functions.php";

	use google\appengine\api\users\User;
	use google\appengine\api\users\UserService;

	$user = UserService::getCurrentUser();

	if (!$user || !check_login()) {
  	header('Location: ' . UserService::createLoginURL($_SERVER['REQUEST_URI']));
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

<script src="js/functions.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<script>

$(document).ready(function() {
	<?php 

		if ( check_login() ) {
			echo '$(".logout_button").each(function() {';
			echo '	$(this).attr("href","'.UserService::createLogoutUrl('/').'");';
			echo '});';
		}
	?>
	$("#rollover-button").click(function() {
			$.post("run_rollover.php",
				{ year:new Date().getFullYear(), month:new Date().getMonth(), },
 				function(data,status) { if(status != "success") { alert("Error running rollover.");} }
		);
	});

  $("#ae_button").click(function() {
    $.post("add_expense.php",
      {
        category:$("#ae_category").val(),
        amount:$("#ae_amount").val(),
        note:$("#ae_note").val()
      },
      function(data, status) {
        parent.window.location.reload();
      });
  });
	$("#save-button").click(save_changes);
});

</script>
</head>
<body>
<div data-role="page" id="add">
  <div data-role="header" data-position="fixed" >
    <a class="logout_button" href="#" data-role="button">Logout</a>
    <h1>Jordan's Budget</h1>
		<a href="#settings" data-transition="slideup" data-role="button" data-icon="grid" class="ui-btn-right">Settings</a>
  </div>
  <div data-role="content">

      <!-- Cateogry -->
      <label for="category">Select Category</label>
      <select name="category" id="ae_category"><!-- Dynamic --></select><br>

      <!-- Amount -->
      <label for="amount" class="ui-hidden-accessible">Amount ($)</label>
      <input type="text" name="amount" id="ae_amount" placeholder="Amount($)"><br>

      <!-- Note -->
      <label for="note" class="ui-hidden-accessible">Note</label>
      <textarea name="note" id="ae_note" placeholder="Note..."></textarea><br>

      <!-- Submit -->
      <button id="ae_button" data-inline="true" data-role="button">Submit</button>

  </div>
  <div data-role="footer" data-position="fixed" >
    <div data-role="navbar">
      <ul>
        <li><a href="#add" class="ui-btn-active ui-state-persist">Add</a></li>
        <li><a href="#current" data-transition="none">Current</a></li>
        <li><a href="#history" data-transition="none">History</a></li>
      </ul>
    </div>
  </div>
</div>

<div data-role="page" id="current">
  <div data-role="header" data-position="fixed" >
    <a class="logout_button" href="#" data-role="button">Logout</a>
    <h1>Current</h1>
  </div>
  <div data-role="content">
  <ul data-role="listview" id="current_month_list"><!--dynamic--></ul>
  </div>
  <div data-role="footer" data-position="fixed" >
    <div data-role="navbar">
      <ul>
        <li><a href="#add" data-transition="none">Add</a></li>
        <li><a href="#current" class="ui-btn-active ui-state-persist">Current</a></li>
        <li><a href="#history" data-transition="none">History</a></li>
      </ul>
    </div>
  </div>
</div>

<div data-role="page" id="history">
  <div data-role="header" data-position="fixed" >
    <a class="logout_button" href="#" data-role="button">Logout</a>
    <h1>History</h1>
  </div>
  <div data-role="content">
  <ul data-role="listview" id="history_list"><!--dynamic--></ul>
  </div>
  <div data-role="footer" data-position="fixed" >
    <div data-role="navbar">
      <ul>
        <li><a href="#add" data-transition="none">Add</a></li>
        <li><a href="#current" data-transition="none">Current</a></li>
        <li><a href="#history" class="ui-btn-active ui-state-persist">History</a></li>
      </ul>
    </div>
  </div>
</div>



<!--          --!>
<!--  POPUPS  --!>
<!--          --!>

<!-- Settings Functions -->
<div data-role="page" id="settings">
  <div data-role="header" data-position="fixed" >
    <a href="#" data-role="button" data-rel="back" data-icon="back">Back</a>
    <h1>Settings</h1>
  </div>
  <div data-role="content">
		<div>

			<!-- Run rollover with last month's goals -->
			<button id="rollover-button">Rollover</button>

			<!-- Set Goals -->
			<div id="list-of-goals"><!--Dynamic--></div>
			<!-- Save -->
			<button id="save-button">Save</button>

		</div>
  </div>
</div>

<!-- View old month -->
<div data-role="page" id="month">
  <div data-role="header" data-position="fixed" >
    <a href="#" data-role="button" data-rel="back" data-icon="back">Back</a>
    <h1>History</h1>
  </div>
  <div data-role="content">
  	<ul data-role="listview" id="history_month_list"><!--dynamic--></ul>
  </div>
</div>

<!-- Edit individual entry -->
<div data-role="page" id="edit">
  <div data-role="header" data-position="fixed" >
    <a href="#" data-role="button" data-rel="back" data-icon="back">Back</a>
    <h1>Edit</h1>
  </div>
  <div data-role="content">
    <form action="#" method="post" id="e_form"> <!-- Requires a label -->

      <!-- Cateogry -->
      <label for="e_category">Select Category</label>
      <select name="e_category" id="e_category"><!-- Dynamic --></select><br>

      <!-- Amount -->
      <label for="e_amount" class="ui-hidden-accessible">Amount ($)</label>
      <input type="text" name="e_amount" id="e_amount" placeholder="Amount($)"><br>

      <!-- Note -->
      <label for="e_note" class="ui-hidden-accessible">Note</label>
      <textarea name="e_note" id="e_note" placeholder="Note..."></textarea><br>

      <!-- Save -->
      <input type="submit" data-inline="true" value="Save" onclick="edit_entry_save()">

      <!-- Delete -->
      <input type="submit" data-inline="true" value="Delete" onclick="edit_entry_delete()">

      <!-- ID -->
      <label id="e_id" class="ui-hidden-accessible"></label>

    </form>
  </div>
</div>

</body>
</html>
