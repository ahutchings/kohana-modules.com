<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo HTML::chars($title) ?>Admin | <?php echo Arr::get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']) ?></title>

    <link rel="stylesheet" href="/css/screen.css" media="screen, projection">
    <link rel="stylesheet" href="/css/print.css" media="print">
    <!--[if IE]>
      <link rel="stylesheet" href="/css/ie.css" media="screen, projection">
    <![endif]-->
    <link rel="stylesheet" href="/css/admin.css" media="screen, projection">
  </head>

  <body>

    <div class="container">

      <div id="header" class="span-24">
          <div class="span-8">
              <h1><a href="/admin"><?php echo Arr::get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']) ?></a></h1>
              <div id="user-navigation">
                  <a href="/">site</a>
                  <a href="/user/logout">logout</a>
              </div>
          </div>

          <ul id="navigation">
            <li class="active"><a href="/admin/dashboard">Dashboard</a></li>
            <li><a href="/admin/modules">Modules</a></li>
            <li><a href="/admin/queue">Approval Queue</a></li>
            <li><a href="/admin/queue/ignored">Ignored</a></li>
            <li><a href="/admin/modules/pending_deletion">Pending Deletion</a></li>
          </ul>
      </div><!-- end #header -->

      <div id="main" class="span-24">
          <?php echo Notices::display() ?>
          <?php echo $content ?>
      </div><!-- end #main -->

    </div> <!-- end .container -->

  </body>
</html>
