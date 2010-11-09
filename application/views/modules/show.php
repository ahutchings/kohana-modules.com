<h2><?php echo HTML::chars($module->name) ?></h2>
<p><?php echo HTML::chars($module->description) ?></p>

    <div class="links">
        <?php if ($module->homepage): ?>
            <?php echo HTML::anchor($module->homepage, 'Homepage') ?>
        <?php endif ?>
        <?php if ($module->has_wiki): ?>
            <?php echo HTML::anchor("http://github.com/$module->username/$module->name/wiki", 'Wiki') ?>
        <?php endif ?>
        <?php if ($module->has_issues): ?>
            <?php echo HTML::anchor("http://github.com/$module->username/$module->name/issues", "Issues ($module->open_issues)") ?>
        <?php endif ?> 
    </div>

    <div class="authors">
        <h4>Authors</h4>
        <a href="http://github.com/<?php echo HTML::chars($module->username) ?>"><?php echo HTML::chars($module->username) ?></a>
        
        <?php echo $module->watchers ?> watchers
        <?php echo $module->forks ?> forks
    </div>

    <div class="versions">
        <h4>Versions</h4>
        <ul>
        <?php foreach ($module->tags_array as $tag): ?>
            <li>
                <a href="http://github.com/<?php echo HTML::chars($module->username) ?>/<?php echo HTML::chars($module->name) ?>/tree/<?php echo HTML::chars($tag) ?>">
                    <?php echo HTML::chars($tag) ?>
                </a>
            </li>
        <?php endforeach ?>
        </ul>
    </div>

<div id="disqus_thread"></div>
<script type="text/javascript">
  /**
    * var disqus_identifier; [Optional but recommended: Define a unique identifier (e.g. post id or slug) for this thread] 
    */
  var disqus_developer = true;
  (function() {
   var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
   dsq.src = 'http://kohana-modules.disqus.com/embed.js';
   (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
  })();
</script>
<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript=kohana-modules">comments powered by Disqus.</a></noscript>
<a href="http://disqus.com" class="dsq-brlink">blog comments powered by <span class="logo-disqus">Disqus</span></a>