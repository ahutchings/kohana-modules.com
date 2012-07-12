<?php
    echo View::factory('modules/sort', array(
        'versions' => $versions,
        'default_version' => $default_version
        ))->render();
?>

<div id="modules">
    <?php foreach ($modules as $module): ?>
    <div class="module clearfix">

        <div class="span-12">
            <h2>
                <?php echo HTML::anchor("modules/$module->username/$module->name", "$module->username/$module->name") ?>
            </h2>

            <p><?php echo Text::widont(HTML::chars($module->description)) ?></p>
        </div>

        <div class="span-4 last">
            <div class="bubble span-2"><span><?php echo $module->watchers ?></span> watchers</div>
            <div class="bubble span-2 last"><span><?php echo $module->forks ?></span> forks</div>
        </div>

    </div>
    <?php endforeach ?>

    <?php echo $pagination->render() ?>
</div>
