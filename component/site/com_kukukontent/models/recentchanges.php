<?php
class KuKuKontentModelRecentChanges extends JModel
{
    public function getList()
    {
        $limit = JRequest::getInt('limit', 10);

        $model = JModel::getInstance('KuKuKontent', 'KuKuKontentModel');



        $query = $this->_db->getQuery(true);

        $query->from('#__kukukontent_versions AS v');
        $query->select('v.id, v.title, v.modified, u.name, u.username');
        $query->order('v.modified DESC');
        $query->leftJoin($this->_db->nameQuote('#__users').' AS u ON u.id = v.id_user');

        $this->_db->setQuery($query, 0, $limit);

        $list = $this->_db->loadObjectList();

        $result = array();

        foreach ($list as $item)
        {
            $previous = $model->getPrevious($item->id, $item->title);

            $item->link = KuKuKontentHelper::getLink($item->title);

//             $item->diffLink =($previous) ?  $item->link.'?task=diff&v1='.$previous->id.'&v2='.$item->id : '';
            $item->diffLink =($previous) ? KuKuKontentHelper::getDiffLink($item->title, $previous->id, $item->id) : '';

            $item->versionsLink = $item->link.'?task=versions';

            $result[] = $item;
        }//foreach

        return $result;
    }//function

}//class
