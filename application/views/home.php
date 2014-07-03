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
        padding: 0px 15px;
      }

      #header {
        background: #333;
        color: white;
        border-bottom: 4px solid #008dc8;
      }

      #header a { margin-top: 20px; }
      #header .container { padding: 0px 15px; }
      #loading { margin-top: 170px; }
      #loading h1 { font-size: 50px; }

      #main {
        background: white;
        padding: 20px;
        min-height: 500px;
        border: 1px solid #CCC;
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
  <div class="row" id="header">
    <div class="container">
      <div class="pull-right">
        <a href="/Admin" class="btn btn-default">
          <span class="glyphicon glyphicon-cog"></span>
          Admin
        </a>
      </div>
      <h1>
        <span class="glyphicon glyphicon-<?php echo $icon; ?>"></span>
        <?php echo $title; ?>
      </h1>
    </div>
  </div>

  <br />

  <!-- Main content container -->
  <div class="container shadow rounded" id="main">
    <div id="loading" align="center">
      <h1>Loading...</h1>
    </div>
  </div>
  <div id="timezone"><?php echo time(); ?></div>

  <!-- JS, at the bottom -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script async src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script async src="http://malsup.github.com/jquery.form.js"></script>
  <script>

  // Ensure that the page has loaded
  $( document ).ready(function() {

    // Get the list of comments
    $.getJSON( "/Ajax/Comments", function( data ) {

      // Check if the response was successful
      if (data['state'] == 'success')
      {
        // Clear the loading
        $("#main").html("");

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
                                <a class='uppercase' href='mailto:" + val['email'] + "'>" + val['first_name'] + "</a>\
                                " + val['id'] + "," + val['parent'] + "\
                                <p>" + val['comment'] + "</p>\
                                <a data-id='" + val['id'] + "' href=''>Reply to comment <span class='glyphicon glyphicon-share-alt'></span></a>\
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
      else
      {
        // Print it out
        $("#loading").html("<h1>ERROR</h1>" + data['message']);
      }
    });

  });
  </script>
</body>
</html>