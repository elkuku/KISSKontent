<?php
class KuKuKontentModelListKontent extends JModel
{
    public function getList()
    {
        $query = $this->_db->getQuery(true);

        $query->from('#__kukukontent');
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
