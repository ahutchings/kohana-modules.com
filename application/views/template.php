<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

  <title><?php echo HTML::chars($title) ?>KohanaModules.com</title>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />

  <link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
  <!--[if IE]>
    <link rel="stylesheet" href="/css/ie.css" type="text/css" media="screen, projection" />
  <![endif]-->
  <link rel="stylesheet" href="/css/style.css" type="text/css" media="screen, projection" />

  <?php if (Kohana::$environment === Kohana::PRODUCTION): ?>
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-3588987-13']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
  <?php endif ?>

</head>

<body>
  <div class="container">
      
    <div id="header" class="span-24">
        <h1><a href="/">KohanaModules.com</a></h1>
        <form action="/search">
            <input type="text" id="query" name="query" />
            <button type="submit">Search Modules</button>
        </form>
    </div><!-- end #header -->
    
    <div id="main" class="span-19 colborder">
        <?php echo $content ?>
    </div><!-- end #main -->
    
    <div id="sidebar" class="span-4 last">
        
        <div id="popular-tags">
            <h3>Popular Tags</h3>
        
            <ul>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
                <li><a href="#">tag1 (1231)</a></li>
            </ul>
        </div>
    </div>
   
   
    <div id="" class="span-24">
        <div class="span-5 colborder">
            <h3>Most Watchers</h3>
            
            <ul>
            <?php foreach (ORM::factory('module')->limit(5)->order_by('watchers', 'DESC')->find_all() as $module): ?>
                <li>
                    <?php echo HTML::anchor("modules/$module->username", $module->username)
                        .'/'.HTML::anchor("modules/$module->username/$module->name", $module->name)
                        .' ('.$module->watchers.')' ?>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        
        <div class="span-5 colborder">
            <h3>Recently Added</h3>
            
            <ul>
            <?php foreach (ORM::factory('module')->limit(5)->order_by('created_at', 'DESC')->find_all() as $module): ?>
                <li>
                    <?php echo HTML::anchor("modules/$module->username", $module->username)
                        .'/'.HTML::anchor("modules/$module->username/$module->name", $module->name)
                        .' ('.Date::fuzzy_span($module->created_at).')' ?>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        
        <div class="span-5 colborder">
            <h3>Recently Updated</h3>
            
            <ul>
            <?php foreach (ORM::factory('module')->limit(5)->order_by('updated_at', 'DESC')->find_all() as $module): ?>
                <li>
                    <?php echo HTML::anchor("modules/$module->username", $module->username).'/'
                        .HTML::anchor("modules/$module->username/$module->name", $module->name)
                        .' ('.Date::fuzzy_span($module->updated_at).')' ?>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        
        <div class="span-6 last">
            <h3>Most Prolific Authors</h3>
            
            <ul>
            <?php foreach (DB::select('username', DB::expr('COUNT(1) as module_count'))->
                from('modules')->limit(5)->order_by('module_count', 'DESC')->
                group_by('username')->as_object()->execute() as $module): ?>
                <li><?php echo HTML::anchor("modules/$module->username", $module->username).' ('.$module->module_count.')' ?></li>
            <?php endforeach ?>
            </ul>
        </div>
    </div>
   
    <div id="footer" class="span-24">
        <ul id="primary-nav" class="span-14">
            <li><a href="/pages/about">About</a></li>
            <li><a href="http://github.com/ahutchings/kohana-modules">Code</a></li>
            <li><a href="http://twitter.com/KohanaModules">Status</a></li>
            <li><a href="/pages/suggest">Suggest a Module</a></li>
            <li><a href="/pages/feedback">Feedback</a></li>
        </ul>

        <p class="span-10 last" id="footnote">
            Indexing <b><?php echo ORM::factory('module')->count_all() ?></b> Kohana modules. Built with Kohana <?php echo Kohana::VERSION ?>.
        </p>
    </div><!-- end #footer -->

  </div> <!-- end .container -->

  <script type="text/javascript">
  var disqus_shortname = 'kohana-modules';
  (function () {
    var s = document.createElement('script'); s.async = true;
    s.src = 'http://disqus.com/forums/kohana-modules/count.js';
    (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
  }());
  </script>

</body>
</html>

