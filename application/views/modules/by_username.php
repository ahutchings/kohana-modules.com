<div class="developer-info">
    <h2><?php echo HTML::chars($username) ?></h2>
    
    <?php echo HTML::anchor("https://github.com/$username", 'GitHub profile') ?>
</div>

<h3 class="module-heading"><span><?php echo $count ?></span> <?php echo ($count == 1) ? 'Module' : 'Modules' ?></h3>

<?php include Kohana::find_file('views/modules', 'index');
