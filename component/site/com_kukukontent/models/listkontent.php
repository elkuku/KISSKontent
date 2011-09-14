<?php
class KuKuKontentModelListKontent extends JModel
{
    public function getList()
    {
        $table = $this->getTable('kukukontent');

        $query = $this->_db->getQuery(true);

        $query->from('#__kukukontent');
        $query->select('title');

        $this->_db->setQuery($query);

        $items = $this->_db->loadResultArray();

        sort($items);

        $tree = array();

        foreach ($items as $item)
        {
            $parts = explode('/', $item);

            $evilS = "['".implode("']['", $parts)."']";
            $evil = 'if( ! isset($tree'.$evilS.'))$tree'.$evilS."=array();";

            eval($evil);
        }//foreach

        return $tree;
    }//function
}//class
