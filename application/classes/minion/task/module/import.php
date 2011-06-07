<?php

/**
 * Imports new repositories from the master module repository.
 */
class Minion_Task_Module_Import extends Minion_Task
{
    /**
     * Execute the task with the specified set of config
     *
     * @return boolean TRUE if task executed successfully, else FALSE
     */
    public function execute(array $config)
    {
        $versions = $this->_fetch_kohana_versions();

        $this->_sync_kohana_versions($versions);

        foreach ($versions as $branch)
        {
            $this->_sync_branch($branch);
        }

        $this->_prune_queue();
        $this->_prune_modules();
    }

    /**
     * Retrieves Kohana versions (branch names) from the kohana-modules repository.
     *
     * @return  array
     */
    private function _fetch_kohana_versions()
    {
        $results = Github::instance()
            ->getRepoApi()
            ->getRepoBranches('ahutchings', 'kohana-modules');

        return array_keys($results);
    }

    /**
     * Creates a kohana_version for each branch in the kohana-modules repo.
     *
     * @param   array  Kohana versions
     * @return  NULL
     */
    private function _sync_kohana_versions($versions)
    {
        foreach ($versions as $name)
        {
            $count = (int) DB::select('COUNT("*") AS mycount')
                ->from('kohana_versions')
                ->where('name', '=', $name)
                ->execute()
                ->get('mycount');

            if ($count === 0)
            {
                DB::query(Database::INSERT, 'INSERT INTO kohana_versions (name) VALUES (:name)')
                    ->param(':name', $name)
                    ->execute();
            }
        }
    }

    public static function array_diff_recursive(&$ar1, &$ar2)
    {
       $diff = array();

       foreach ($ar1 as $key => $val1)
       {
          if (array_search($val1, $ar2) === FALSE)
          {
             $diff[$key] = $val1;
          }
       }

       return $diff;
    }

    private function _sync_branch($branch)
    {
        $version_id = DB::select('id')
            ->from('kohana_versions')
            ->where('name', '=', $branch)
            ->execute()
            ->get('id');

        $new_modules = $this->_fetch_modules($branch);

        $existing_modules = DB::select('username', 'name')->from('modules')
            ->join('module_compatibilities')
            ->on('module_compatibilities.module_id', '=', 'modules.id')
            ->where('module_compatibilities.kohana_version_id', '=', $version_id)
            ->execute()->as_array();

        $import = self::array_diff_recursive($new_modules, $existing_modules);

        foreach ($import as $values)
        {
            $module = ORM::factory('module', $values);

            if ( ! $module->loaded())
            {
                $module->values($values)->save();

                $this->log("Created $module->username/$module->name");

                $module->sync();

                // throttle API requests
                sleep(2);
            }

            // Create a compatibility entry for the version
            DB::query(Database::INSERT,
                "INSERT INTO module_compatibilities (module_id, kohana_version_id)
                VALUES (:module_id, :version_id)")
                ->param(':module_id', $module->id)
                ->param(':version_id', $version_id)
                ->execute();

            $this->log("$branch: Added $module->username/$module->name");
        }

        $delete = self::array_diff_recursive($existing_modules, $new_modules);

        foreach ($delete as $values)
        {
            $module = ORM::factory('module', $values);

            DB::delete('module_compatibilities')
                ->where('module_id', '=', $module->id)
                ->where('kohana_version_id', '=', $version_id)
                ->execute();

            $this->log("$branch: Deleted $module->username/$module->name", 'red');
        }
    }

    private function _fetch_modules($branch)
    {
        $pattern = "/git:\/\/github\.com\/(?P<username>.*)\/(?P<name>.*)\.git/i";
        $url     = "https://github.com/ahutchings/kohana-modules/raw/$branch/.gitmodules";

        $request = Request::factory($url);

        $request->get_client()
            ->options(array(
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE
                ));

        $data = $request
            ->execute()
            ->body();

        preg_match_all($pattern, $data, $matches);

        $modules = array();
        for ($i = 0, $n = count($matches['name']); $i < $n; $i++)
        {
            $modules[] = array(
                'username' => $matches['username'][$i],
                'name'     => $matches['name'][$i],
                );
        }

        return $modules;
    }

    /**
     * Delete search results that have been added to the module index.
     */
    private function _prune_queue()
    {
        $modules = ORM::factory('module')->find_all();

        foreach ($modules as $module)
        {
            $queue = ORM::factory('queue')
                ->where('username', '=', $module->username)
                ->where('name', '=', $module->name)
                ->find();

            if ($queue->loaded())
            {
                $queue->delete();
            }
        }
    }

    /**
     * Delete modules that have no Kohana compatibilities.
     *
     * @return  NULL
     */
    private function _prune_modules()
    {
        $modules = DB::select('id', 'username', 'name', array('COUNT("module_compatibilities.kohana_version_id")', 'num_compatibilities'))
            ->from('modules')
            ->join('module_compatibilities', 'left')
            ->on('modules.id', '=', 'module_compatibilities.module_id')
            ->group_by('id')
            ->having('num_compatibilities', '=', 0)
            ->execute();

        foreach ($modules as $module)
        {
            DB::delete('modules')->where('id', '=', $module['id'])->execute();

            $this->log('Deleted '.$module['username'].'/'.$module['name'], 'red');
        }
    }

    /**
     * Either logs to file or prints to console, depending on the request type.
     */
    private function log($message, $color = NULL)
    {
        Kohana::$is_cli
            ? Minion_CLI::write($message, $color)
            : Log::instance()->add(Log::INFO, $message);
    }
}
