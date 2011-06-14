<div class="actions">

    <div class="filter">
        <span>Compatibile with</span>

        <ul>
            <li>
                <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('compatibility' => NULL)), 'Any',
                    array('class' => (Arr::get($_GET, 'compatibility') === NULL) ? 'selected' : NULL)) ?>
            </li>

            <?php foreach (ORM::factory('kohana_version')
                ->order_by('name', 'DESC')
                ->find_all() as $version): ?>
            <li>
                <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('compatibility' => $version->name)),
                    $version->name,
                    array('class' => (Arr::get($_GET, 'compatibility') === $version->name) ? 'selected' : NULL)) ?>
            </li>
        <?php endforeach ?>
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
