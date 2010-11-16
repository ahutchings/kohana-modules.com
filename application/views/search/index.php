<h3>Exact matches</h3>

<ul id="exact-matches">
    <li>
        <?php echo HTML::anchor("/modules/$exact->username/$exact->name", $exact->name) ?>
        <br />
        <?php echo HTML::chars($exact->description) ?>
    </li>
</ul>

<h3>Fuzzy matches</h3>

<ul id="fuzzy-matches">
<?php foreach ($fuzzy as $module): ?>
    <li>
        <?php echo HTML::anchor("/modules/$module->username/$module->name", $module->name) ?>
        <br />
        <?php echo HTML::chars($module->description) ?>
    </li>
<?php endforeach ?>
</ul>
