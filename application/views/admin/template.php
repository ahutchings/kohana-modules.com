<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

  <title><?php echo HTML::chars($title) ?>Admin | KohanaModules.com</title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />

  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
  <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" />
  <![endif]-->
  <link rel="stylesheet" href="/css/admin.css" type="text/css" media="screen, projection" />

</head>

<body>

  <div class="container">
      
    <div id="header" class="span-24">
        <div class="span-8">
            <h1><a href="/admin"><?php echo $_SERVER['SERVER_NAME'] ?></a></h1>
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
