<h3>Pending Deletion</h3>

<table class="span-24">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($modules as $module): ?>
        <tr>
            <td><?php echo $module->id ?></td>
            <td>
                <?php echo HTML::anchor($module->url('username'), $module->username) ?>
            </td>
            <td>
                <?php echo HTML::anchor("modules/$module->username/$module->name", $module->name) ?>
            </td>
            <td><?php echo HTML::chars($module->description) ?></td>
            <td><?php echo date(Date::$timestamp_format, $module->created_at) ?></td>
            <td><?php echo date(Date::$timestamp_format, $module->updated_at) ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<h4>Commands</h4>

<?php foreach (array_keys($commands) as $branch): ?>
<h5><?php echo $branch ?> Branch</h5>

<pre><?php echo implode($commands[$branch], "\n") ?></pre>
<?php endforeach ?>
