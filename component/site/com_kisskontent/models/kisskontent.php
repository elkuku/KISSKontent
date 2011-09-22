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

class KISSKontentModelKISSKontent extends JModel
{
    /**
     *
     * Enter description here ...
     *
     * @return KISSKontent
     */
    public function getContent($lang = '', $title = '')
    {
        if('default' != $lang)
        {
            $translation = $this->getTranslation($lang);

            if($translation->id)
            return $translation;
            //            && 'default' != strtolower($translation->title))
        }

        $p =($title) ?: JRequest::getString('p', 'default');

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
        $table->lang = 'default';

        return $table;
    }//function

    public function getTranslation($lang = '')
    {
        $p = JRequest::getString('p', 'default');

        if( ! $lang
        && class_exists('g11n'))
        $lang = g11n::getDefault();

        if( ! $lang)
        $lang = 'en-GB';//@TODO: todo, to do...

        $tableOrig = $this->getTable();

        $tableTrans = $this->getTable('KISSTranslations');

        try
        {
            $tableOrig->load(array('title' => $p));

            //-- Title exists - look for a translation by title id
            $tableTrans->load(array('id_kiss' => $tableOrig->id, 'lang' => $lang));

            return $tableTrans;
        }//try
        catch (Exception $e)
        {
            //-- @todo ignore only database exceptions (empty row)
            //             JError::raiseWarning(1, $e->getMessage());
            //foo
            //             if($tableTrans->id)
            //             $tableTrans->load(array('title' => $p));
        }//catch

        try
        {
            //-- A title with the name does not exist - look for a translation by name and the current language
            $tableTrans->load(array('title' => $p, 'lang' => $lang));

            return $tableTrans;
        }//try
        catch (Exception $e)
        {
            //foo
        }//catch

        try
        {
            //-- A title with the name does not exist - look for a translation by name and any language
            $tableTrans->load(array('title' => $p));

            return $tableTrans;
        }//try
        catch (Exception $e)
        {
            //foo
        }//catch

        return $tableTrans;
    }//function

    public function getVersions($title = '')
    {
        static $versions;

        $p =($title) ?: JRequest::getString('p', 'default');

        if(isset($versions[$p]))
        return $versions[$p];

        $query = $this->_db->getQuery(true);

        $content = $this->getContent('default', $title);

        $query->from($this->_db->nameQuote('#__kisskontent_versions').' AS k');
        $query->select('k.id, k.text, k.summary, k.modified, u.name, u.username');

        if($title)
        $query->where('k.title='.$this->_db->quote($p));

        $query->where('k.id_kiss='.(int)$content->id);
        $query->leftJoin($this->_db->nameQuote('#__users').' AS u ON u.id = k.id_user');
        $query->order('k.modified DESC');

        $this->_db->setQuery($query);

        $versions[$p] = $this->_db->loadObjectList();

        return $versions[$p];
    }//function

    public function getPrevious($id, $title = '')
    {
        $versions = $this->getVersions($title);

        foreach($versions as $i => $version)
        {
            if($id == $version->id)
            {
                if(isset($versions[$i + 1]))
                return $versions[$i + 1];

                return false;
            }
        }//foreach

        return false;
    }//function

    public function getNext($id, $title = '')
    {
        $versions = $this->getVersions($title);

        foreach($versions as $i => $version)
        {
            if($id == $version->id)
            {
                if(isset($versions[$i - 1]))
                return $versions[$i - 1];

                return false;
            }
        }//foreach

        return false;
    }//function

    public function getVersionOne()
    {
        $v = JRequest::getInt('v1');

        return $this->findVersion($v);
    }//function

    public function getVersionTwo()
    {
        $v = JRequest::getInt('v2');

        return $this->findVersion($v);
    }//function

    public function findVersion($id)
    {
        $versions = $this->getVersions();

        if(0 == $id)
        return(isset($versions[0])) ? $versions[0] : false;

        foreach ($this->getVersions() as $version)
        {
            if($id == $version->id)
            return $version;
        }//foreach

        throw new Exception('Illegal version');
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
            //@todo create simple default =;)
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
        $src->summary = JRequest::getString('summary', 'No comment');

        $src->text = JRequest::getVar('content', '', 'post', 'none'
        , JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        if($src->id)
        {
            //-- Existing Kontent
            if( ! KISSKontentHelper::getActions()->get('core.edit'))
            throw new Exception(jgettext('You are not allowed to edit Kontent pages.'));
        }
        else
        {
            //-- New Kontent
            if( ! KISSKontentHelper::getActions()->get('core.create'))
            throw new Exception(jgettext('You are not allowed to create Kontent pages'));
        }

        try
        {
            $this->getTable()
            ->bind($src)->check()->store();

            if( ! $src->id)
            {
                $src->id_kiss = $this->_db->getLastInsertId();
            }
            else
            {
                $src->id_kiss = $src->id;
            }

            $this->getTable('KISSKontentVersions')
            ->bind($src)->check()->store();

        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try
    }//function

    public function saveTranslation()
    {
        if( ! KISSKontentHelper::getActions()->get('core.translate'))
        throw new Exception(jgettext('You are not allowed to translate Kontent pages'));

        $src = new stdClass;

        $src->id = JRequest::getInt('id', 0);
        $src->id_kiss = JRequest::getInt('id_kiss');

        $title = JRequest::getString('transPath');
        $tTitle = JRequest::getString('transTitle');

        $src->title =($title) ? $title.'/'.$tTitle : $tTitle;

        //         $src->title = JRequest::getString('p', 'Default');

        $src->text = JRequest::getVar('text', '', 'post', 'none'
        , JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        $src->summary = JRequest::getString('summary', 'No comment');
        $src->lang = JRequest::getCmd('lang');

        try
        {
            $this->getTable('KISSTranslations')
            ->bind($src)->check()->store();

            $this->getTable('KISSKontentVersions')
            ->bind($src)->check()->store();
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try
    }//function

    public function nuke($confirmed = false)
    {
        if( ! KISSKontentHelper::getActions()->get('core.nuke'))
        throw new Exception(jgettext('You are not allowed to nuke Kontent pages'));

        $p = JRequest::getString('p');

        try
        {
            //-- Delete the main KISS

            $table = $this->getTable();
            $table->load(array('title' => $p));

            $kissId = $table->id;

            if( ! $confirmed)
            {
                echo 'Found Id: '.$kissId.'<br />';
            }
            else
            {
                //-- NUKE
            }

        }//try
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//catch

        try
        {
            //-- Delete versions

            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query->from('#__kisskontent_versions');
            $query->select('id');
            $query->where('id_kiss='.(int)$kissId);

            echo $query.'<br />';

            $db->setQuery($query);

            $ids = $db->loadResultArray();

            var_dump($ids);

            if($ids)
            {
                $ids = implode(',', $ids);

            }

            //-- Delete translations

            $query->clear('from');
            $query->from('#__kiss_translations');

            echo $query.'<br />';

            $db->setQuery($query);

            $ids = $db->loadResultArray();

            var_dump($ids);

            if($ids)
            {
                $ids = implode(',', $ids);


                $table = $this->getTable('KISSKontentVersions');
                $table->load(array('id_kiss' => $kissId));
            }

            $kissId = $table->id;

            return true;
        }//try
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//catch

        throw new Exception(jgettext('Unable to nuke your Kontent'));
    }//function
}//class
