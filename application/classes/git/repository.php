<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class for managing the kohana-modules git repository.
 */
class Git_Repository
{
    /**
     * @var  Submodules
     */
    protected $_submodules = array();
    
    /**
     * Finds a submodule in the branch matching the key and value.
     *
     * @param   string  Branch
     * @param   string  Key
     * @param   string  Value
     * @return  array
     */ 
    public function submodule($branch, $key, $value)
    {
        // Get the submodule
        return array_filter($this->submodules($branch), function($array) use ($key, $value)
	    {
	        if ($key === 'name')
	        {
	            $names = array_keys($array);
    	        return $names[0] === $value;
	        }
	        else
	        {
                return $array[$key] === $value;
	        }
	    });
    }
    
    /**
     * Get all submodules in the branch.
     *
     * @param   string  Branch name
     * @return  array
     */
    public function submodules($branch)
    {
        if ( ! isset($this->_submodules[$branch]))
        {
            $url = 'https://github.com/ahutchings/kohana-modules/raw/'.$branch.'/.gitmodules';

    	    $gitmodules = Request::factory($url)
                ->execute()
                ->body();

    	    $this->_submodules[$branch] = $this->_parse_gitmodules($gitmodules);
        }

        return $this->_submodules[$branch];
    }
    
    /**
     * Generates the command for removing a submodule.
     *
     * @param   array  Submodule
     * @return  array
     */
	public function remove_submodule($submodule)
	{
        $names = array_keys($submodule);
        $name  = $names[0];

	    return array(
	        // Remove the submodule from .git/config
	       'git config --remove-section submodule.'.$name,
	       
	       // Remove the module from .gitmodules
	       'git config --file .gitmodules --remove-section submodule.'.$name,
	       
	       // Remove the actual module files
	       'git rm --cached '.$submodule[$name]['path']
	    );
	}
	
	/**
	 * Parses the .gitmodules file into an associative array.
	 *
	 * @param   string  .gitmodules file content
	 * @return  array
	 */
	private function _parse_gitmodules($string)
	{
	    $pattern = '/'                      // Begin pattern
	        .'\[submodule "(?P<name>.*)"\]' // Match the submodule declaration, capturing the name
	        .'\s*'                          // Match whitespace
	        .'path\s*=\s*(?P<path>.*)'      // Match the path, capturing the value
	        .'\s*url\s*=\s*(?P<url>.*)'     // Match the url, capturing the value
	        .'/'                            // End pattern
	        ;
	    
	    preg_match_all($pattern, $string, $matches);
	    
	    $array = array();
        for ($i = 0, $j = count($matches['name']); $i < $j; $i++)
        {
            // Associate the path and url to the submodule name
            $array[$matches['name'][$i]] = array(
                'path' => $matches['path'][$i],
                'url'  => $matches['url'][$i]
            );
        }

	    return $array;
	}
}
