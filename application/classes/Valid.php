<?php defined('SYSPATH') or die('No direct script access.');

class Valid extends Kohana_Valid
{
    /**
     * Validate that a URL is external.
     *
     * @param   string   URL
     * @return  boolean
     */
    public static function external_url($url)
    {
        if ( ! Valid::url($url))
            return FALSE;

        $host = parse_url($url, PHP_URL_HOST);

        // Check that the host name has a `.` character, hopefully indicating
        // that it contains a top-level domain.
        return stripos($host, '.');
    }
}
