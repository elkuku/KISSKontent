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

/**
 * KISSKontent model.
 */
class KISSKontentModelListKontent extends JModel
{
    public function getList()
    {
        $filterAlpha = JRequest::getCmd('filterAlpha');
        $filterAlpha =('all' == $filterAlpha) ? '' : $filterAlpha;

        $query = $this->_db->getQuery(true);

        $query->from('#__kisskontent AS k');
        $query->select('k.title');
        $query->order('k.title');

        if($filterAlpha)
        {
            $filterAlpha = substr($filterAlpha, 0, 1);
            $query->where('k.title LIKE '.$this->_db->quote($filterAlpha.'%'));
        }

        $this->_db->setQuery($query);

        $items = $this->_db->loadResultArray();

        $tree = array();

        if( ! $items)
        return $tree;

        foreach($items as $item)
        {
            //-- eval is evil :P
            eval('$tree[\''.implode("']['", explode('/', $item)).'\']=array();');
        }//foreach

        return $tree;
    }//function

    public function getAlphas()
    {
        $query = $this->_db->getQuery(true);

        $query->from('#__kisskontent');
        $query->select('distinct(UPPER(LEFT(title, 1)))');
        $query->order('title');

        if(KISS_DBG) KuKuUtilityQuery::log($query);

        $this->_db->setQuery($query);

        return $this->_db->loadResultArray();
    }//function

    public function getTranslationList()
    {
        $filterLang = JRequest::getCmd('filterLang');

        $filterLang =('all' == $filterLang) ? '' : $filterLang;

        $query = $this->_db->getQuery(true);

        $query->from('#__kisskontent AS k, #__kiss_translations AS t');

        $query->select('k.id, k.title');
//         $query->select('t.lang');
        $query->select('GROUP_CONCAT(t.lang) AS langs');
        $query->select('GROUP_CONCAT(t.title) AS titles');

        $query->where('k.id = t.id_kiss');

        if($filterLang)
        $query->where('t.lang='.$this->_db->quote($filterLang));

        $query->group('k.id');
        //         $query->order('t.lang');

        $query->order('t.lang');

        if(KISS_DBG) KuKuUtilityQuery::log($query);

        $this->_db->setQuery($query);

        $items = $this->_db->loadObjectList();

        $result = array();

        if($items)
        {
            foreach($items as $item)
            {
                $list = array_combine(explode(',', $item->langs), explode(',', $item->titles));

                ksort($list);

                $result[$item->title] = $list;
            }//foreach
        }

        return $result;
    }//function
}//class
