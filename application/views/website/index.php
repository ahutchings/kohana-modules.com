<div id="modules">
    <?php foreach ($modules as $module): ?>
    <div class="module">
        <h2>
            <?php echo HTML::anchor("/modules/$module->username/$module->name", $module->name) ?>
        </h2>
        <p><?php echo HTML::chars($module->description) ?></p>
    </div>
    <?php endforeach ?>
    
    <?php echo $pagination->render() ?>
</div>
