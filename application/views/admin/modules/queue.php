<h3>User-Submitted</h3>

<table class="span-24">
    <thead>
        <tr>
            <th align="left">Username</th>
            <th align="left">Name</th>
            <th align="left">Description</th>
            <th>Links</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<h3>GitHub Search Results</h3>

<table class="span-24">
    <thead>
        <tr>
            <th align="left">Username</th>
            <th align="left">Name</th>
            <th align="left">Description</th>
            <th>Links</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($results as $module): ?>
        <tr>
            <td><?php echo HTML::chars($module->username) ?>}</td>
            <td><?php echo HTML::chars($module->name) ?></td>
            <td><?php echo HTML::chars($module->description) ?></td>
            <td align="center">
                <a href="http://github.com/<?php echo HTML::chars($module->username) ?>/<?php echo HTML::chars($module->name) ?>">GitHub</a>
                <a href="/admin/modules/add?username=<?php echo HTML::chars($module->username) ?>&amp;name=<?php echo HTML::chars($module->name) ?>">Add</a>
                <a href="/admin/modules/ignore?username=<?php echo HTML::chars($module->username) ?>&amp;name=<?php echo HTML::chars($module->name) ?>&amp;from=search">Ignore</a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
