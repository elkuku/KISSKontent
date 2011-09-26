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

class KISSKontentModelRecentChanges extends JModel
{
    public function getList()
    {
        $limit = JRequest::getInt('limit', 10);

        $filterLang = JRequest::getCmd('filterLang');

        $model = JModel::getInstance('KISSKontent', 'KISSKontentModel');

        $query = $this->_db->getQuery(true);

        $query->from($this->_db->nameQuote('#__kisskontent_versions').' AS v');
        $query->leftJoin($this->_db->nameQuote('#__users').' AS u ON u.id = v.id_user');
        $query->select('v.id, v.title, v.summary, v.modified, v.lang, u.name, u.username');
        $query->order('v.modified DESC');

        switch ($filterLang)
        {
            case 'none':
                $query->where('v.lang='.$this->_db->quote(''));
                break;

            case 'all':
                break;

            default:
                if($filterLang)
                $query->where('v.lang='.$this->_db->quote($filterLang));
            break;
        }//switch
KuKuUtility::logQuery($query);
        //         $query->where('v.lang='.$this->_db->quote('en-GB'));
        //         $query->order('v.title ASC');
        //         $query->group('v.title');
//                 echo $query;
        $this->_db->setQuery($query, 0, $limit);

        $list = $this->_db->loadObjectList();

        $result = array();

        if( ! $list)
        return $result;

        $langFilter = '&filterLang='.$filterLang;

        foreach ($list as $item)
        {
            $previous = $model->getPrevious($item->id, $item->title);

            $item->link = KISSKontentHelper::getLink($item->title);

            $item->diffLink =($previous) ? KISSKontentHelper::getDiffLink($item->title, $previous->id, $item->id, $langFilter) : '';

            $item->versionsLink = KISSKontentHelper::getLink($item->title, '&task=versions'.$langFilter);

            $result[] = $item;
        }//foreach

        return $result;
    }//function
}//class
