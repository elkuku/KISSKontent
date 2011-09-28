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
class KISSKontentModelKISSKontent extends JModel
{
    private $nukeResult = null;

    public function __construct($config = array())
    {
        $this->nukeResult = new stdClass;

        parent::__construct($config);
    }//function

    /**
     *
     * Enter description here ...
     *
     * @return KISSKontent
     */
    public function getContent($lang = '', $title = '')
    {
        //-- Force the language from request
        //-- .. forcing is only needed if we have a translated title and want to get the same item in another language.
        $forceLang = JRequest::getCmd('lang');
        $forceId = 0;

        if('default' != $lang)
        {
            $translation = $this->getTranslation($lang);

            if($translation->id)
            {
                if($forceLang
                && $translation->lang != $forceLang)
                {
                    $translation = $this->getTranslation($forceLang, '', $translation->id_kiss);
                    $forceId = $translation->id_kiss;
                }
                else
                {
                    return $this->splitTitle($translation);
                }
            }
        }

        $p =($title) ?: JRequest::getString('p', 'default');
        $p =($p) ?: 'default';

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
                if($forceId)
                {
                    $table->load($forceId);

                    return $this->splitTitle($translation);
                }
                else
                {
                    $table->load(array('title' => $p));
                }
            }
            catch(Exception $e)
            {
                //-- Must be a new page
                $table->title = $p;
            }//try
        }

        $table->lang = 'default';
        $table->id_kiss = 0;

        return $this->splitTitle($table);
    }//function

    protected function splitTitle($table)
    {
        $parts = explode('/', $table->title);

        $table->fullPath = $table->title;
        $table->xtitle = array_pop($parts);//@todo rrremove... - change to title
        $table->path = implode('/', $parts);

        return $table;
    }//function

    public function getTranslation($lang = '', $title = '', $id = 0)
    {
        $p =($title) ?: JRequest::getString('p', 'default');
        $p =($p) ?: 'default';

        if( ! $lang //-- Get the language from g11n
        && class_exists('g11n'))
        $lang = g11n::getDefault();

        if( ! $lang)//-- Do you speak english ¿
        $lang = 'en-GB';//@TODO: todo, to do...

        $tableOrig = $this->getTable();

        $tableTrans = $this->getTable('KISSTranslations');

        try
        {
            if($id)
            {
                //-- Loading by id is prefered !
                $tableOrig->load($id);
            }
            else
            {
                //-- Load by title
                $tableOrig->load(array('title' => $p));
            }

            //-- Title exists - look for a translation by title id
            $tableTrans->load(array('id_kiss' => $tableOrig->id, 'lang' => $lang));

            return $this->splitTitle($tableTrans);

            /*
             //-- Title exists - look for a translation by title id
            $tableTrans->load(array('id_kiss' => $tableOrig->id, 'lang' => $lang));

            return $this->splitTitle($tableTrans);
            *             if($id)
            {
            $tableOrig->load($id);
            //-- Title exists - look for a translation by title id
            $tableTrans->load(array('id_kiss' => $tableOrig->id, 'lang' => $lang));

            return $this->splitTitle($tableTrans);
            }

            $tableOrig->load(array('title' => $p));

            //-- Title exists - look for a translation by title id
            $tableTrans->load(array('id_kiss' => $tableOrig->id, 'lang' => $lang));

            return $this->splitTitle($tableTrans);

            */
        }//try
        catch(Exception $e)
        {
            $foo = '';
            //-- @todo ignore only database exceptions (empty row)
            //             JError::raiseWarning(1, $e->getMessage());
            //foo
            //             if($tableTrans->id)
            //             $tableTrans->load(array('title' => $p));
        }//try

        try
        {
            //-- A title with the name does not exist - look for a translation by name and the current language
            $tableTrans->load(array('title' => $p, 'lang' => $lang));

            return $this->splitTitle($tableTrans);
        }//try
        catch(Exception $e)
        {
            $foo = '';
            //foo
        }//try

        try
        {
            //-- A title with the name does not exist - look for a translation by name and any language
            if($p && 'default' != strtolower($p))
            {
                $tableTrans->load(array('title' => $p));

                return $this->splitTitle($tableTrans);
            }
        }//try
        catch(Exception $e)
        {
            $foo = '';
            //foo
        }//try

        return $this->splitTitle($tableTrans);
    }//function

    public function getTranslations($id)
    {
        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->from($db->nameQuote('#__kiss_translations').' AS t');

        //         $query->select('k.id, t.title');
        //         $query->select('t.lang');
        $query->select('GROUP_CONCAT(DISTINCT(t.lang)) AS langs');
        $query->select('GROUP_CONCAT(DISTINCT(t.title)) AS titles');

        $query->where('t.id_kiss = '.(int)$id);

        //         $query->group('k.id');

        if(KISS_DBG) KuKuUtility::logQuery($query);

        $db->setQuery($query);

        $result = $this->_db->loadObject();

        if( ! isset($result->langs))
        return array();

        if('default' == strtolower($result->titles))
        return array();
        //         return explode(',', $result->langs);

        return array_combine(explode(',', $result->langs), explode(',', $result->titles));
    }//function

    public function getVersions($title = '', $lang = '')
    {
        static $versions;

        $db = $this->_db;

        if('all' == $lang
        || ! $lang)
        $lang = '';

        if(KISS_ML)
        $lang =($lang) ?: g11n::getDefault();

        $title = '';//($title) ?: JRequest::getString('p', 'default');

        $key = serialize($lang.$title);

        if(isset($versions[$key]))
        return $versions[$key];

        $query = $db->getQuery(true);

        $content = $this->getContent($lang, $title);

        if( ! $content->id)
        {
            $versions[$key] = array();

            return $versions[$key];
        }

        $ids = array($content->id);

        if(KISS_ML)
        {
            $query->from($this->_db->nameQuote('#__kiss_translations').' AS t');
            $query->select('t.id');
            $query->where('t.id_kiss='.(int)$content->id);

            if(KISS_DBG) KuKuUtility::logQuery($query);

            $db->setQuery($query);

            $is = $db->loadResultArray();

            $ids = array_merge($ids, $is);

            $query->clear('where');
        }

        $query = $db->getQuery(true);

        $query->from($this->_db->nameQuote('#__kisskontent_versions').' AS t');
        $query->where('t.id_kiss IN ('.implode(',', $ids).')');

        $query->select('t.id, t.id_user, t.text, t.summary, t.modified, t.lang, u.name, u.username');

        if($title)
        $query->where('t.title='.$this->_db->quote($title));

        if($lang)
        $query->where('t.lang='.$this->_db->quote($lang));

        $query->leftJoin($this->_db->nameQuote('#__users').' AS u ON u.id = t.id_user');
        $query->order('t.modified DESC');

        if(KISS_DBG) KuKuUtility::logQuery($query);

        $this->_db->setQuery($query);

        $versions[$key] = $this->_db->loadObjectList();

        return $versions[$key];
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
        return (isset($versions[0])) ? $versions[0] : false;

        foreach($versions as $version)
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
        $lang = JRequest::getCmd('kissLang');

        //-- If 'kissLang' is set, we save a translation.
        if($lang
        && ('default' != $lang))
        return $this->saveTranslation();

        $src = new stdClass;

        $src->id = JRequest::getInt('id', 0);
        $src->title = JRequest::getString('p', 'Default');
        $src->summary = JRequest::getString('summary', 'No comment');

        $src->text = JRequest::getVar('content', '', 'post', 'none'
        , JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        //-- ACL check
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
            //-- Save the Kontent
            $this->getTable()
            ->bind($src)->check()->store();

            //-- Save a version
            if( ! $src->id)
            {
                //-- New Kontent
                $src->id_kiss = $this->_db->insertId();
            }
            else
            {
                $src->id_kiss = $src->id;
                $src->id = 0;//always a new version
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

        $path = JRequest::getString('transPath');
        $tTitle = JRequest::getString('transTitle');

        $src->title =($path) ? $path.'/'.$tTitle : $tTitle;

        $src->text = JRequest::getVar('content', '', 'post', 'none'
        , JREQUEST_ALLOWRAW);//@todo clean me up mom =;)

        $src->summary = JRequest::getString('summary', 'No comment');
        $src->lang = JRequest::getCmd('kissLang');

        try
        {
            $this->getTable('KISSTranslations')
            ->bind($src)->check()->store();

            if( ! $src->id)
            {
                //-- New kiss
                $src->id_kiss = $this->_db->insertId();
            }
            else
            {
                $src->id_kiss = $src->id;
                $src->id = 0;//always a new version
            }

            $this->getTable('KISSKontentVersions')
            ->bind($src)->check()->store();
        }
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try
    }//function

    protected function deleteKISS($p, $confirmed)
    {
        if( ! KISSKontentHelper::getActions()->get('core.delete'))
        throw new Exception(jgettext('You are not allowed to nuke Kontent pages'));

        $db = $this->_db;

        try
        {
            //-- Delete the main KISS
            $table = $this->getTable();

            $table->load(array('title' => $p));

            if( ! $confirmed)
            {
                $this->nukeResult->kiss = JArrayHelper::toObject($table->getProperties());
            }
            else
            {
                //-- NUKE
                $query = $db->getQuery(true);
                $query->from('#__kisskontent');
                $query->delete();
                $query->where('id='.(int)$table->id);

                $db->setQuery($query);

                if( ! $db->query())
                throw new Exception(jgettext('Unable to nuke your Kontent item'));
            }
        }//try
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try

        return $table->id;
    }//function

    protected function deleteVersions($kissId, $confirmed)
    {
        //-- Delete versions
        if( ! KISSKontentHelper::getActions()->get('core.delete'))
        throw new Exception(jgettext('You are not allowed to nuke Kontent pages'));

        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->from('#__kisskontent_versions');
        $query->select('id');
        $query->where('id_kiss='.(int)$kissId);

        $db->setQuery($query);

        $ids = $db->loadResultArray();

        $this->nukeResult->versions = $ids;

        if($ids
        && $confirmed)
        {
            $ids = implode(',', $ids);

            $query->clear('select');
            $query->clear('where');

            $query->delete();
            $query->where('id IN ('.$ids.')');

            $db->setQuery($query);

            if( ! $db->query())
            throw new Exception('Unable to delete the versions - '.$db->getError());
        }
    }//function

    protected function deleteTranslations($kissId, $confirmed)
    {
        //-- Delete translations
        if( ! KISSKontentHelper::getActions()->get('core.delete'))
        throw new Exception(jgettext('You are not allowed to nuke Kontent pages'));

        $db = $this->_db;

        $query = $db->getQuery(true);

        $query->from('#__kiss_translations');
        $query->select('id');
        $query->where('id_kiss='.(int)$kissId);

        $db->setQuery($query);

        $ids = $db->loadResultArray();

        $this->nukeResult->translations = $ids;

        if($ids
        && $confirmed)
        {
            $ids = implode(',', $ids);

            $query->clear('select');
            $query->clear('where');

            $query->delete();
            $query->where('id IN ('.$ids.')');

            echo $query.'<br />';

            $db->setQuery($query);

            if( ! $db->query())
            throw new Exception('Unable to delete the translations - '.$db->getError());
        }
    }//function

    public function nuke($confirmed = false)
    {
        if( ! KISSKontentHelper::getActions()->get('core.nuke'))
        throw new Exception(jgettext('You are not allowed to nuke Kontent pages'));

        $p = JRequest::getString('p');

        try
        {
            $kissId = $this->deleteKISS($p, $confirmed);

            $this->deleteVersions($kissId, $confirmed);
            $this->deleteTranslations($kissId, $confirmed);

            return $this->nukeResult;
        }//try
        catch(Exception $e)
        {
            throw new Exception($e->getMessage());
        }//try

        throw new Exception(jgettext('Unable to nuke your Kontent'));
    }//function
}//class
