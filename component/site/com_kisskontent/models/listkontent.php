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

        return $tree;
    }//function
}//class
