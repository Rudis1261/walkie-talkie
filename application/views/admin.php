<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//bootstrapvalidator.com/vendor/bootstrapvalidator/css/bootstrapValidator.min.css">
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
        border-bottom: 4px solid #428bca;
      }

      #header a { margin-top: 20px; }
      #header a h1,
      #header .no-line { text-decoration: none; color: white; }
      #header .container { padding: 0px 12px; }
      #loading { margin-top: 170px; }
      #loading h1 { font-size: 50px; }
      #timezone { display: none; }
      #counter { display: none; }
      #end { color: #ccc; }

      #main {
        background: white;
        padding: 20px;
        min-height: 500px;
        border: 1px solid #CCC;
        display: block;
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
        margin-left: 30px;
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
        border-color: #660606;
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
        <div class="btn-group">
          <a href="/" class="btn btn-default">
            Home
            <span class="glyphicon glyphicon-home"></span>
          </a>
          <a href="/Admin" class="btn btn-default active">
            Admin
            <span class="glyphicon glyphicon-cog"></span>
          </a>
        </div>
      </div>
      <a href="/" class="no-line">
        <h1>
          <span class="glyphicon glyphicon-<?php echo $icon; ?>"></span>
          <?php echo $title; ?>
        </h1>
      </a>
    </div>
  </div>

  <!-- Main content container -->
  <div class="container shadow rounded" id="main">
    <?php
      // Show messages should there be any
      if (!empty($messages))
      {
        foreach($messages as $type => $message)
        {
          $class = $type;
          if ($type == "error")
          {
            $class = "danger";
          }
            echo '<div class="alert alert-' . $class . '">
                    <div><b>' . strtoupper($type) . '</b></div>
                    <div>' . implode("</div>\n<div>", $message) . '</div>
                  </div>';
        }
      }

      // Show the comments
      echo $content;
      echo $comments; ?>
  </div>

  <!-- JS, at the bottom -->
  <!-- Loading all the required Javascript for this assignment -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="//bootstrapvalidator.com/vendor/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>