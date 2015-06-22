/**********************************/
/*                                */
/* Copyright (c) Paul Jordan 2013 */
/* paullj1@gmail.com              */
/*                                */
/**********************************/

//////////////////////////
//  CURRENT MONTH VIEW  //
//////////////////////////

$(document).on("pagebeforeshow","#current",function() {

  // Get current month for current view
  $.post("get_month.php",
    { 
      // Get current date first
      year:new Date().getFullYear(),
      month:new Date().getMonth() +1,
      current:true
    },
    function(data, status) {

      // Clear list
      $("#current_month_list").empty();

      // Add items to list
      $("#current_month_list").append(data);

      // Refresh List
      $("#current_month_list").listview("refresh");
    }
  );
});

$(document).on("pagecreate","#edit",function() {
  get_categories("#e_category");
});

// Gets selected entry details and fills the form for editing
function entry_press(entry_id, amount) {
  // Set Category
  $.post("get_category.php",
    { id:entry_id },
    function(data, status) {
      $("#e_category").val(data);
      $("#e_category").selectmenu("refresh");
    }
  );
  $.mobile.changePage("#edit", {transition: "slide"});

  // Set amount
  $("#e_amount").val(amount);

  // Set note
  $.post("get_note.php", { id:entry_id },
    function(data, status) { $("#e_note").val(data); });

  // Set ID
  $("#e_id").val(entry_id);
}

function edit_entry_save() {
  $.post("mod_entry.php",
    {
      command:"update",
      id:$("#e_id").val(),
      category:$("#e_category").val(),
      amount:$("#e_amount").val(),
      note:$("#e_note").val()
    }, function(data, status) { }
  );
}

function edit_entry_delete() {
  $.post("mod_entry.php",
    {
      command:"delete",
      id:$("#e_id").val()
    },
    function(data, status) { }
  );
}

////////////////////////
//  ADD EXPENSE PAGE  //
////////////////////////

function get_categories(sel) {
  // Get current categories
  $.post("get_categories.php", {
      year:new Date().getFullYear(),
      month:new Date().getMonth() +1
    },
    function(data, status) {
      $(sel).empty();
      $(sel).html("<select name=\"category\" id=\"category\">"+data+"</select>");
      $(sel).selectmenu("refresh");
    }
  );
}

$(document).on("pagebeforeshow","#add",function() {

  // Update Categories
  get_categories("#ae_category");

  // Event Handlers
  $("#ae_form").submit(function() {
    $.post("add_expense.php",
      {
        category:$("#ae_category").val(),
        amount:$("#ae_amount").val(),
        note:$("#ae_note").val()
      },
      function(data, status) {
        location.reload();
      });
  });

});
  
////////////////////
//  HISTORY PAGE  //
////////////////////
$(document).on("pagebeforeshow","#history",function() {

  // Build history view
  $.post("get_history.php", {},
    function(data, status) {
      // Clear list
      $("#history_list").empty();

      // Add items to list
      $("#history_list").append(data);

      // Refresh List
      $("#history_list").listview("refresh");
  });
});

// Gets selected entry details and fills the form for editing
function month_press(in_month, in_year) {
  // Get current month for current view
  $.post("get_month.php",
    { 
      // Get current date first
      year:in_year,
      month:in_month,
      current:false
    },
    function(data, status) {

      // Clear list
      $("#history_month_list").empty();

      // Add items to list
      $("#history_month_list").append(data);

      // Refresh List
      $("#history_month_list").listview("refresh");
    }
  );
  $.mobile.changePage("#month", {transition: "slide"});
}

