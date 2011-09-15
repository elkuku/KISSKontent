<?php
class KuKuKontentModelKuKuKontent extends JModel
{
    /**
     *
     * Enter description here ...
     *
     * @return KuKuKontent
     */
    public function getContent()
    {
        $p = JRequest::getString('p', 'default');

        $table = $this->getTable();

        if('default' == strtolower($p))
        {
            //-- Default page

            try
            {
                //-- Retrieve default page from database
                $table->load(array('title' => 'default'));
            }
            catch(Exception $e)
            {
                //-- No default page found in database
                $table->title = 'Default';
                $table->text = self::getDefault();
            }//try
        }
        else
        {
            //-- A page has been requested
            try
            {
                //-- Try to load the page from database
                $table->load(array('title' => $p));
            }
            catch(Exception $e)
            {
                //-- Must be a new page
                $table->title = $p;
            }//try
        }

        $table->path = $table->title;//@todo remove ?

        return $table;
    }//function

    public function getVersions()
    {
        static $versions;

        if($versions)
        return $versions;

        $query = $this->_db->getQuery(true);

        $p = JRequest::getString('p', 'default');

        $query->from($this->_db->nameQuote('#__kukukontent_versions').' AS k');
        $query->select('k.id, k.text, k.modified, u.name, u.username');
        $query->where('k.title='.$this->_db->quote($p));
        $query->leftJoin($this->_db->nameQuote('#__users').' AS u ON u.id = k.id_user');
        $query->order('k.modified DESC');

        $this->_db->setQuery($query);

        $versions = $this->_db->loadObjectList();

        return $versions;
    }//function

    public function getVersionOne()
    {
        $versions = $this->getVersions();

        $v = JRequest::getInt('v1', 'HEAD');

        if('HEAD' == $v
        || ! array_key_exists($v, $versions))
        {
            return(isset($versions[0])) ? $versions[0] : false;
        }

        return(isset($versions[$v])) ? $versions[$v] : false;
    }//function

    public function getVersionTwo()
    {

//         $versions = $this->getVersions();

        $v = JRequest::getInt('v2');
// var_dump($versions);
// die();
        if(! $v)
        throw new Exception('Missing version number two');

        return $this->findVersion($v);






//         || ! array_key_exists($v, $versions))
//         return $versions[$v];






        if( ! $vNo)

        $table = $this->getTable('KuKuKontentVersions');
        $table->load($vNo);

        $v = new stdClass;



        return $table;
    }//function

    protected function findVersion($id)
    {
        foreach ($this->getVersions() as $version)
        {
            if($id == $version->id)
            {
                return $version;
            }
        }//foreach

        throw new Exception('Illegal version two');
    }//function

    /**
     *
     * Enter description here ...
     */
    protected static function getDefault()
    {
        $tag = JFactory::getLanguage()->getTag();

        $path = JPATH_COMPONENT_SITE.'/demo';

        if(JFile::exists($path.'/'.$tag.'.md'))
        {
            $content = JFile::read($path.'/'.$tag.'.md');
        }
        else if(JFile::exists($path.'/en-GB.md'))
        {
            $content = JFile::read($path.'/en-GB.md');
        }
        else
        {
            //@todo create simple default
            $content = 'Hallo [[echo]](a/b/c/d/e/f/huhu) '
            .'[[den gibt\'s nüsch]](gar/nixda) und [hier](http://ga.ga)';
        }

        return ($content) ?: '';
    }//function

    /**
     *
     * Enter description here ...
     */
    public function save()
    {
        $src = new stdClass;

        $src->id = JRequest::getInt('id', 0);
        $src->title = JRequest::getString('p', 'Default');

        $src->text = JRequest::getVar('content', '', 'post', 'none'
        , JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        if($src->id)
        {
            //-- Existing Kontent
            if( ! KuKuKontentHelper::getActions()->get('core.edit'))
            {
                throw new Exception(jgettext('You are not allowed to edit Kontent pages.'));
            }
        }
        else
        {
            //-- New Kontent
            if( ! KuKuKontentHelper::getActions()->get('core.create'))
            {
                throw new Exception(jgettext('You are not allowed to create Kontent pages'));
            }
        }

        try
        {
            $this->getTable('KuKuKontentVersions')
            ->bind($src)->check()->store();

            $this->getTable()
            ->bind($src)->check()->store();


        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try
    }//function
}//class
