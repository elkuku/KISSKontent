<?php
//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * KISSKontent table.
 */
class TableKISSTranslations extends JTable
{
    /**
     * @param	JDatabase	A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__kiss_translations', 'id', $db);
    }//function

    public function load($keys = null, $reset = true)
    {
        if(KISS_DBG) KuKuUtilityLog::log(__CLASS__.' - Try to load: '.print_r($keys, 1));

        if( ! parent::load($keys, $reset))
        throw new Exception($this->getError());

        return $this;
    }//function

    public function bind($src, $ignore = array())
    {
        if( ! parent::bind($src, $ignore))
        throw new Exception($this->getError());

        return $this;
    }//function

    public function check()
    {
        if( ! parent::check())
        throw new Exception($this->getError());

        if( ! $this->title)//should not happen :|
        throw new Exception(jgettext('No title given'));

        if( ! $this->text)
        throw new Exception(jgettext('Text can not be empty'));

        return $this;
    }//function

    public function store($updateNulls = false)
    {
        if( ! parent::store($updateNulls))
        throw new Exception($this->getError());

        return $this;
    }//function
}//class
