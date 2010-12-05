<h3>Ignored Repositories</h3>

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
    <?php foreach ($ignored as $module): ?>
        <tr>
            <td><?php echo HTML::chars($module->username) ?></td>
            <td><?php echo HTML::chars($module->name) ?></td>
            <td><?php echo HTML::chars($module->description) ?></td>
            <td align="center">
                <?php echo HTML::anchor("https://github.com/$module->username/$module->name", 'GitHub') ?>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
