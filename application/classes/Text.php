<?php defined('SYSPATH') or die('No direct script access.');

class Text extends Kohana_Text
{
    /*
     * Highlights search terms in a string.
     *
     * @param   string  string to highlight terms in
     * @param   string  words to highlight
     * @return  string
     */ 
    public static function highlight($str, $keywords)
    {
        // Trim, strip tags, and replace multiple spaces with single spaces
        $keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($keywords)));

        // Highlight partial matches
        $var = '';

        foreach (explode(' ', $keywords) as $keyword)
        {
            $replacement = '<span class="highlight-partial">'.$keyword.'</span>';
            $var .= $replacement." ";

            $str = str_ireplace($keyword, $replacement, $str);
        }

        // Highlight full matches
        $str = str_ireplace(rtrim($var), '<span class="highlight">'.$keywords.'</span>', $str);

        return $str;
    }
}
