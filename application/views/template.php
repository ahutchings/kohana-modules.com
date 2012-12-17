<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

  <title><?php echo HTML::chars($title), Arr::get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']) ?></title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <?php if (isset($meta_description)): ?>
  <meta name="description" content="<?php echo HTML::chars($meta_description) ?>" />
  <?php endif ?>

  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
  <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" />
  <![endif]-->
  <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />
  <link rel="alternate" href="http://feeds.feedburner.com/KohanaModules" type="application/rss+xml" title="kohana-modules.com - Recently Added Modules" />
  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
  <script type="text/javascript" src="/javascripts/jquery.clearinginput.js"></script>
  <?php echo HTML::script('packages/jquery-timeago/jquery.timeago.js') ?>

  <?php if (Kohana::$environment === Kohana::PRODUCTION): ?>
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-3588987-13']);
    _gaq.push(['_trackPageview']);
    _gaq.push(['_trackPageLoadTime']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
  <?php endif ?>

  <?php if (isset($_GET['query']) OR isset($_GET['page'])): ?>
  <meta name="robots" content="noindex, follow" />
  <?php endif ?>

</head>

<body>

    <div id="container">

        <div id="header" class="clearfix">
            <div class="container">
                <div class="span-17">
                    <h1><a href="/"><?php echo Arr::get($_SERVER, 'HTTP_HOST', $_SERVER['SERVER_NAME']) ?></a></h1>
                    <p><?php echo $tagline ?></p>
                </div>

                <div id="search" class="span-7 last">
                    <form action="/search" method="get" accept-charset="utf-8">
                        <div class="yform-item yform-text" id="query-container">
                           <input type="text" id="query" name="query" value="<?php echo Arr::get($_GET, 'query') ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- end #header -->

      <div id="content" class="container">

        <div id="main" class="span-16 colborder">
            <?php echo $content ?>
        </div><!-- end #main -->

        <div id="sidebar" class="span-7 last">

            <div>
                <h3>Keep Updated</h3>

                <ul id="subscribe-links">
                    <li class="feed">
                        <a href="http://feeds.feedburner.com/KohanaModules" rel="alternate"
                            type="application/rss+xml">Subscribe via RSS</a>
                    </li>

                    <li class="twitter">
                        <a href="http://twitter.com/KohanaModules">Follow @KohanaModules on Twitter</a>
                    </li>
                </ul>
            </div>

            <div id="recently-added">
                <h3>Recently Added</h3>

                <ol>
                <?php foreach (ORM::factory('Module')->limit(7)->order_by('created_at', 'DESC')->find_all() as $module): ?>
                    <li>
                        <h4>
                            <span class="username"><?php echo HTML::anchor("modules/$module->username", $module->username) ?></span>
                            /
                            <span class="name"><?php echo HTML::anchor("modules/$module->username/$module->name", $module->name) ?></span>
                        </h4>
                        <p class="description"><?php echo HTML::chars($module->description) ?></p>
                        <p class="added">
                            Added
                            <abbr class="timeago" title="<?php echo date(DATE_ISO8601, $module->created_at) ?>">
                                <?php echo date(DATE_ISO8601, $module->created_at) ?>
                            </abbr>
                        </p>
                    </li>
                <?php endforeach ?>
                </ol>
            </div>

            <div id="prolific-authors">
                <h3>Most Prolific Authors</h3>

                <ol>
                <?php foreach (DB::select('username', DB::expr('COUNT(1) as module_count'))->
                    from('modules')->limit(7)->order_by('module_count', 'DESC')->
                    group_by('username')->as_object()->execute() as $module): ?>
                    <li>
                        <?php echo HTML::anchor("modules/$module->username", $module->username) ?>
                        <span class="count"><?php echo $module->module_count ?> modules</span>
                    </li>
                <?php endforeach ?>
                </ol>
            </div>

        </div>

      </div> <!-- end #content -->

      <div id="footer" class="clearfix">
        <div class="container">
          <ul id="primary-nav" class="span-14">
              <li><a href="/pages/about">About</a></li>
              <li><a href="https://github.com/ahutchings/kohana-modules.com">Code</a></li>
              <li><a href="http://twitter.com/KohanaModules">Status</a></li>
              <li><a href="/pages/add-a-module">Add a Module</a></li>
              <li><a href="/pages/feedback">Feedback</a></li>
          </ul>

          <p class="span-10 last" id="footnote">
              Powered by <?php echo HTML::anchor('http://kohanaframework.org', 'Kohana') ?> v<?php echo Kohana::VERSION ?>
          </p>
        </div>
      </div><!-- end #footer -->

  </div><!-- end #container -->

  <script type="text/javascript">
  var disqus_shortname = 'kohana-modules';
  (function () {
    var s = document.createElement('script'); s.async = true;
    s.src = '//kohana-modules.disqus.com/count.js';
    (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
  }());

  $(document).ready(function() {
      $("#query").clearingInput({text: 'Search modules...'});
      $("abbr.timeago").timeago();
  });
  </script>

</body>
</html>

