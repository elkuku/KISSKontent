<?php
// No direct access allowed to this file
defined('_JEXEC') || die('=;)');

class KuKuKontentHelper
{
    public static function getLink($text)
    {
        $parsed = $text;

        return JRoute::_('index.php?option=com_kukukontent&p='.$parsed);
    }//function

    public static function isLink($link)
    {
        static $db;

        if( ! $db)
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->from('#__kukukontent');
        $query->select('count(*)');
        $query->where('title='.$db->quote(urldecode($link)));

        $db->setQuery($query);

        $c = $db->loadResult();

        return($c) ? true : false;
    }//function
}//class
