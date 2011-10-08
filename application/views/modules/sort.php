<div class="actions">

    <div class="filter">
        <span>Compatible with</span>

        <ul>
            <?php foreach ($versions as $version): ?>
            <li>
                <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('compatibility' => $version->name)),
                    $version->name,
                    array('class' => (Arr::get($_GET, 'compatibility', Model_Kohana_Version::latest()) === $version->name) ? 'selected' : NULL)) ?>
            </li>
            <?php endforeach ?>

            <li>
                <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('compatibility' => 'any')), 'Any',
                    array('class' => (Arr::get($_GET, 'compatibility') === 'any') ? 'selected' : NULL)) ?>
            </li>
        </ul>
    </div>

    <div class="sort">
        <span>Sort by</span>

        <ul>
            <?php foreach (array('watchers', 'forks', 'added') as $key): ?>
            <li>
                <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('sort' => $key)),
                    ucfirst($key),
                    array('class' => (Arr::get($_GET, 'sort', 'watchers') === $key) ? 'selected' : NULL)) ?>
            </li>
            <?php endforeach ?>
        </ul>
    </div>

</div>
