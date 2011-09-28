<?php
//-- No direct access
defined('_JEXEC') || die('=;)');

JLoader::register('KISSTableException', dirname(__FILE__).'/exception.php');

/**
 * KISSKontent table.
 */
class TableKISSKontent extends JTable
{
    /**
     * @param	JDatabase	A database connector object
     */
    public function __construct(&$db)
    {
        parent::__construct('#__kisskontent', 'id', $db);
    }//function

    public function load($keys = null, $reset = true)
    {
        if( ! parent::load($keys, $reset))
        throw new KISSTableException($this->getError());

        return $this;
    }//function

    public function bind($src, $ignore = array())
    {
        if( ! parent::bind($src, $ignore))
        throw new KISSTableException($this->getError());

        return $this;
    }//function

    public function check()
    {
        if( ! parent::check())
        throw new KISSTableException($this->getError());

        if( ! $this->title)//should not happen :|
        throw new KISSTableException(jgettext('No title given'));

        if( ! $this->text)
        throw new KISSTableException(jgettext('Text can not be empty'));

        return $this;
    }//function

    public function store($updateNulls = false)
    {
        if( ! parent::store($updateNulls))
        throw new KISSTableException($this->getError());

        return $this;
    }//function
}//class
