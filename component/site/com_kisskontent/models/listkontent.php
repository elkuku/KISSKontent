<?php
/**
 * @package    KISSKontent
 * @subpackage Models
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

class KISSKontentModelListKontent extends JModel
{
    public function getList()
    {
        $query = $this->_db->getQuery(true);

        $query->from('#__kisskontent');
        $query->select('title');
        $query->order('title');

        $this->_db->setQuery($query);

        $items = $this->_db->loadResultArray();

        $tree = array();

        foreach($items as $item)
        {
            //-- eval is evil :P
            eval('$tree[\''.implode("']['", explode('/', $item)).'\']=array();');
        }//foreach
// var_dump($tree);
        return $tree;
    }//function

    public function getTranslationList()
    {
        $query = $this->_db->getQuery(true);

        $query->from('#__kisskontent AS k, #__kiss_translations AS t');

        $query->select('k.id, k.title');
        $query->select('GROUP_CONCAT(t.lang)');

        $query->where('k.id = t.id_kiss');

        $query->group('k.id');

        $this->_db->setQuery($query);

        $items = $this->_db->loadObjectList();

        $result = array();

        if($items)
        {
            foreach ($items as $item)
            {
                $result[$item->title] = $item;
            }//foreach
        }

        return $result;
    }//function
}//class
