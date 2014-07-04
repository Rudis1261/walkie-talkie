<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <style>
      body {
        background: #e1e3e6;
        padding: 90px 15px;
        color: #333;
      }

      #header {
        position: fixed;
        top: 0px;
        width: 100%;
        background: #333;
        color: white;
        z-index: 1;
        border-bottom: 4px solid #008dc8;
      }

      #header a { margin-top: 20px; }
      #header .container { padding: 0px 12px; }
      #loading { margin-top: 170px; }
      #loading h1 { font-size: 50px; }
      #timezone { display: none; }
      #end { color: #ccc; }

      #main {
        background: white;
        padding: 20px;
        min-height: 500px;
        border: 1px solid #CCC;
        display: block;
      }

      #add-comment {
        background: white;
        padding: 10px;
        border-top: 4px solid maroon;
        position: fixed;
        width: 100%;
        bottom: 0px;
      }

      .shadow {
        -webkit-box-shadow: 8px 8px 8px -5px rgba(0,0,0,0.15);
           -moz-box-shadow: 8px 8px 8px -5px rgba(0,0,0,0.15);
                box-shadow: 8px 8px 8px -5px rgba(0,0,0,0.15);
      }

      .rounded {
        -webkit-border-radius: 8px;
           -moz-border-radius: 8px;
                border-radius: 8px;
      }

      .uppercase {
        text-decoration: uppercase;
        color: #333;
        font-weight: bold;
      }

      .child {
        margin-left: 25px;
        border-left: 1px dotted #CCC;
      }

      .wrapper {
        padding: 5px 10px;
        margin-bottom: 10px;
      }

      .small {
        font-size: 80%;
        color: #333;
        text-transform: uppercase;
      }

      .transparent {
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=94)";
        filter: alpha(opacity=94);
        -moz-opacity: 0.94;
        -khtml-opacity: 0.94;
        opacity: 0.94;
      }

      .btn-danger {
        background: maroon;
      }

    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>

  <!-- Header -->
  <div class="row transparent" id="header">
    <div class="container">
      <div class="pull-right">
        <a href="/Admin" class="btn btn-default">
          Admin
          <span class="glyphicon glyphicon-cog"></span>
        </a>
      </div>
      <h1>
        <span class="glyphicon glyphicon-<?php echo $icon; ?>"></span>
        <?php echo $title; ?>
      </h1>
    </div>
  </div>

  <!-- Main content container -->
  <div class="container shadow rounded" id="main">
    <div id="loading" align="center">
      <h1>Loading...</h1>
    </div>
  </div>

  <!-- Add a new comment button -->
  <div class="row transparent" id="add-comment">
    <div class="container" align="center">
      <button class="btn btn-lg btn-danger reply" onClick="reply(0);">Join the conversation <span class="glyphicon glyphicon-pencil"></span></button>
    </div>
  </div>

  <div id="end" align="center"><h1>THE END<h1></div>
  <div id="timezone"><?php echo time(); ?></div>

  <div id="modal" class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <a href="#" class="close" data-dismiss="modal">
            <span class="glyphicon glyphicon-remove-circle"></span>
          </a>
          <h4 class="modal-title">Join the conversation</h4>
        </div>
        <div class="modal-body">
          <p>Great to see you want to join the conversation.
          Please provide us with the details below in order to do so.</p>
          <p>
          <?php

            // Inject the default form
            echo Form::open("Ajax/Add", array("id" => "add-form", "class" => "form form-vertical"));
            echo Form::label("Name", NULL, array("class" => "input-label"));
            echo Form::hidden("parent", 0);
            echo Form::input("first_name", NULL, array("id" => "first_name", "placeholder" => "John Doe", "class" => "form-control", "minlength" => 2, "required" => true));
            echo "<br />";
            echo Form::label("Email Address", NULL, array("class" => "input-label"));
            echo Form::input("email", NULL, array("id" => "email", "class" => "form-control", "type" => "email", "placeholder" => "John.Doe@gmail.com", "minlength" => 2, "required" => true));
            echo "<br />";
            echo Form::label("Comment", NULL, array("class" => "input-label"));
            echo Form::textarea("comment", NULL, array("id" => "comment", "class" => "form-control", "placeholder" => "Jump, Shout. Let it all out", "minlength" => 4, "required" => true));
            echo "<br />";
            echo Form::button("add", "Submit", array("class" => "btn btn-primary btn-lg"));
            echo Form::close();
          ?>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- JS, at the bottom -->
  <!-- Loading all the required Javascript for this assignment -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script async src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script>

  // We need to be able to handle new comments and replies
  function reply(parent) {

      // Prevent the button from doing anything
      event.preventDefault();
      $("#modal input[name='parent']").val(parent);

      // Show the modal
      $("#modal").modal();
  }

  // Ensure that the page has loaded
  $( document ).ready(function() {

    // Create an instance of our ajax form
    $('#add-form').ajaxForm({ dataType:  'json', success: processJson, beforeSubmit:  validator });
    $('#add-form').children('input').keyup(function(event){ validator(); });

    function validator(){
      $("#add-form").validate();
    }

    // Process the server response from the form submission
    function processJson(data) {
      alert(data.message);
    }

    // I would like to make the call to the back-end generic for the updating of the comments
    function backendCall(URL, clear)
    {
      // Get the list of comments
      $.getJSON( URL, function( data ) {

        // Check if the response was successful
        if (data['state'] == 'success') {

          if (clear == true) {
            // Clear the loading
            $("#main").html("");
          }

          // Use a holder for the data
          $("#timezone").html(data['timestamp']);

          // Loop through all the data
          $.each( data['data'], function( key, val ) {

            // Create a blank class
            var element_class = "";

            // Should it be a child entry, add the child class
            if (val['parent'] > 0) {
              element_class = "child";
            }

            // Create the item
            var new_element = "<div class='comments " + element_class + "' id='" + val['id'] + "'>\
                                <div class='wrapper'>\
                                  <span class='glyphicon glyphicon-comment'></span>\
                                  <!--<i class='pull-right small'>" + val['date'] + "</i>-->\
                                  <a class='uppercase' href='mailto:" + val['email'] + "'>" + val['first_name'] + "</a> \
                                  <i class='small' title='" + val['date'] + "'>" + val['age'] + "</i>\
                                  <!--" + val['id'] + "," + val['parent'] + "-->\
                                  <p>" + val['comment'] + "</p>\
                                  <a onClick='reply(\"" + val['id'] + "\"); return false;' href=''>Reply to comment <span class='glyphicon glyphicon-share-alt'></span></a>\
                                </div>\
                              </div>";

            // Parent comment, append to the main container
            if (val['parent'] == 0) {
              $("#main").append( new_element );
            }

            // Child comment, append to parent
            else {
              $("#" + val['parent']).append( new_element );
            }
          });
        }

        // We ran into some sort of error
        else {
          // Print it out
          $("#loading").html("<h1>ERROR</h1>" + data['message']);
        }
      });
    }

    // When the page loads the first time we will need to get all the comments in the system
    backendCall("/Ajax/Comments", true);

    // Check for updates every 2 seconds
    setInterval(function() {

      // Get the last updated timestamp
      var getTimestamp = $("#timezone").html();

      // We are looking for any comments since then
      backendCall("/Ajax/Since/" + getTimestamp, false);
    }, 5000);

  });
  </script>
</body>
</html>