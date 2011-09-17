<?php
/**
 * @package    KISSKontent
 * @subpackage Plugins
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 16-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

jimport('joomla.plugin.plugin');

/**
 * KISSKontent Search plugin.
 *
 * @package    KISSKontent
 * @subpackage Plugin
 */
class plgSearchKISSKontent extends JPlugin
{
    /**
     * Constructor.
     *
     * @param object $subject The object to observe
     * @param array $config  An array that holds the plugin configuration
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);

        //Loads the plugin language file:
        //$this->loadLanguage();
    }//function

    /**
     * Sets the checkbox(es) to be diplayed in the Search Only box:
     * @return array An array of search areas
     */
    public function onContentSearchAreas()
    {
        return array(
            'Kontent' => 'Kontent'
        );
    }//function

    /**
     * Example Search method
     *
     * The sql must return the following fields that are used in a common display
     * routine:
     - title;
     - href:            link associated with the title;
     - browsernav    if 1, link opens in a new window, otherwise in the same window;
     - section        in parenthesis below the title;
     - text;
     - created;

     * @param string Target search string
     * @param string matching option, exact|any|all
     * @param string ordering option, newest|oldest|popular|alpha|category
     * @param mixed An array if the search it to be restricted to areas, null if search all
     *
     * @return array Search results
     */
    public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
    {
        if(is_array($areas))
        {
            if( ! array_intersect($areas, array_keys($this->onContentSearchAreas())))
            {
                return array();
            }
        }

        $limit = $this->params->def('search_limit', 50);

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        switch ($phrase)
        {
            case 'exact':
                $text = $db->Quote('%'.$db->getEscaped($text, true).'%', false);

                $query->where('k.title LIKE '.$text.' OR k.text LIKE '.$text);

                break;

            case 'all':
            case 'any':
            default:
                $words	= explode(' ', $text);

                foreach ($words as $word)
                {
                    $word = $db->Quote('%'.$db->getEscaped($word, true).'%', false);

                    $query->where('k.title LIKE '.$word.' OR k.text LIKE '.$word);
                }//foreach

                break;
        }

        switch ($ordering)
        {
            case 'oldest':
                $query->order('k.modified ASC');
                break;

            case 'popular':
                $query->order('k.modified ASC');
                break;

            case 'alpha':
                $query->order('k.title ASC');
                break;

            case 'category':
                $query->order('k.modified ASC');
                break;

            case 'newest':
            default:
                $query->order('k.id DESC');
        }//switch

        $query->from('#__kisskontent AS k');
        $query->select('k.title, k.text, k.id as created');

        $db->setQuery($query, 0, $limit);

        $rows = $db->loadObjectList();

        foreach ($rows as $row)
        {
            $row->href = 'aaa';
            $row->section = 'Kontent';
            $row->browsernav = false;
        }//foreach

        return $rows;
    }//function
}//class
