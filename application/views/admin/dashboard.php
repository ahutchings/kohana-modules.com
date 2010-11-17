<div class="span-11 colborder widget">

    <h3>System Statistics</h3>

    <dl>
        <dt>Open Tickets</dt>
        <dd><?php echo $open_tickets ?></dd>
        
        <dt>New Search Results</dt>
        <dd><?php echo ORM::factory('searchresult')->count_all() ?></dd>
        
        <dt>Pending Deletion</dt>
        <dd>
            <?php echo ORM::factory('module')
                ->where('flagged_for_deletion_at', 'IS NOT', NULL)
                ->count_all() ?>
        </dd>
    </dl>

</div>

<div class="span-12 last widget">

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

</div>

<div class="span-11 colborder widget">

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
