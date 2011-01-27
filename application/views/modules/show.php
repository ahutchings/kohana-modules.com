<h2>
    <?php echo HTML::anchor("modules/$module->username", $module->username) ?>
    /
    <?php echo HTML::chars($module->name) ?>
</h2>

<p class="description"><?php echo HTML::chars($module->description) ?></p>
<p class="stats"><b><?php echo $module->watchers ?></b> watchers, <b><?php echo $module->forks ?></b> forks</p>

    <div class="links">
        <h4>Links</h4>

        <?php echo HTML::anchor($module->url(), 'GitHub', array('class' => 'github')) ?>
        <?php if ($module->homepage AND Validate::external_url($module->url('homepage'))): ?>
            <?php echo HTML::anchor($module->url('homepage'), 'Homepage', array('class' => 'homepage')) ?>
        <?php endif ?>
        <?php if ($module->has_wiki): ?>
            <?php echo HTML::anchor($module->url('wiki'), 'Wiki', array('class' => 'wiki')) ?>
        <?php endif ?>
        <?php if ($module->has_issues): ?>
            <?php echo HTML::anchor($module->url('issues'), "Issues ($module->open_issues)", array('class' => 'issues')) ?>
        <?php endif ?> 
    </div>

    <div class="compatibility">
        <h4>Kohana Compatibility</h4>

        <ul>
        <?php foreach (Model_Kohana_Version::names() as $name): ?>
        <?php $class = $module->kohana_versions->where('name', '=', $name)->count_all() ? 'positive' : 'negative' ?>
            <li class="<?php echo $class ?>">
                <?php echo $name ?>
            </li>
        <?php endforeach ?>
        </ul>
    </div>

    <div class="versions">
        <h4>Latest Versions</h4>

        <?php if (count($module->tags->find_all())): ?>
        <ul>
        <?php foreach ($module->tags->order_by('name', 'DESC')->limit(5)->find_all() as $tag): ?>
            <li>
                <a href="<?php echo $module->url() ?>/tree/<?php echo HTML::chars($tag->name) ?>">
                    <?php echo HTML::chars($tag->name) ?>
                </a>
            </li>
        <?php endforeach ?>
        </ul>
        <?php endif ?>
    </div>

<div id="disqus_thread"></div>
<script type="text/javascript">
  /**
    * var disqus_identifier; [Optional but recommended: Define a unique identifier (e.g. post id or slug) for this thread] 
    */
  <?php if (Kohana::$environment !== Kohana::PRODUCTION): ?>
  var disqus_developer = true;
  <?php endif ?>
  (function() {
   var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
   dsq.src = 'http://kohana-modules.disqus.com/embed.js';
   (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
  })();
</script>
<noscript><p>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript=kohana-modules">comments powered by Disqus.</a></p></noscript>
<a href="http://disqus.com" class="dsq-brlink">Comments powered by <span class="logo-disqus">Disqus</span></a>
