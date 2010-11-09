<div class="span-11 colborder">

    <h3>Open Tickets</h3>
    <?php echo $open_tickets ?>

    <h3>New Search Results</h3>
    <?php echo ORM::factory('searchresult')->count_all() ?>

    <h3>Pending Deletion</h3>
    <?php echo ORM::factory('module')
        ->where('flagged_for_deletion_at', 'IS NOT', NULL)
        ->count_all() ?>

    <h3>Next Cron</h3>
    <?php echo $next_cron ?>

</div>

<div class="span-12 last">

    <h3>Newest Modules</h3>
    <table>
        <tbody>
        <?php foreach ($newest as $module): ?>
            <tr>
                <td><?php echo HTML::chars("$module->username/$module->name") ?></td>
                <td><?php echo Date::fuzzy_span($module->created_at) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <h3>Recently Updated</h3>
    <table>
        <tbody>
        <?php foreach ($recently_updated as $module): ?>
            <tr>
                <td><?php echo HTML::chars("$module->username/$module->name") ?></td>
                <td><?php echo Date::fuzzy_span($module->updated_at) ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

</div>
