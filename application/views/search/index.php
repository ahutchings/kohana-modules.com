<?php echo View::factory('modules/sort', array(
    'versions' => $versions,
    'default_version' => 'any'
    ))->render() ?>

<div id="modules">
    <?php foreach ($modules as $module): ?>
    <div class="module clearfix">

        <div class="span-12">
            <h2>
                <?php echo HTML::anchor("modules/$module->username/$module->name",
                    Text::highlight("$module->username/$module->name", $_GET['query'])) ?>
            </h2>

            <p><?php echo Text::highlight(Text::widont(HTML::chars($module->description)), $_GET['query']) ?></p>
        </div>

        <div class="span-4 last">
            <div class="bubble span-2"><span><?php echo $module->watchers ?></span> watchers</div>
            <div class="bubble span-2 last"><span><?php echo $module->forks ?></span> forks</div>
        </div>

    </div>
    <?php endforeach ?>

    <?php echo $pagination->render() ?>
</div>
