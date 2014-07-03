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
    <?php
        // Loop through the comments on load and display the comments
        foreach($comments as $id => $comment)
        {

        }
    ?>
  </div>
  <div id="timezone">asdlkj</div>

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
        // Use a holder for the data
        $("#timezone").html(data['timestamp']);
      }

      $.each( data['data'], function( key, val ) {
        var new_element = "<div id='" + val['id'] + "'>\
                            <small><a href='mailto:" + val['email'] + "'>" + val['first_name'] + "</a></small> (posted) \
                            <p>" + val['comment'] + "</p>\
                          </div>";
        $("#main").append( new_element );
      });
    });

  });
  </script>
</body>
</html>