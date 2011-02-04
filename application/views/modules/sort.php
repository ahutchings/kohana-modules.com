<div class="sort <?php echo HTML::chars(Arr::get($_GET, 'sort', 'watchers')) ?>">
    <span>Sort by</span>
    <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('sort' => 'watchers')), 'Watchers', array('id' => 'sort-watchers')) ?>
    <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('sort' => 'forks')), 'Forks', array('id' => 'sort-forks')) ?>
    <?php echo HTML::anchor(Arr::get($_SERVER, 'PATH_INFO').URL::query(array('sort' => 'added')), 'Added', array('id' => 'sort-added')) ?>
</div>
