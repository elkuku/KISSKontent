<?php
// No direct access allowed to this file
defined('_JEXEC') || die('=;)');

class KuKuKontentHelper
{
    public static function getLink($text)
    {
        $parsed = $text;

        $p = JRequest::getString('p');

        if(0 === strpos($text, '/'))
        {
            // The text starts with a / - This is a relative internal link.
            $parsed = $p.$text;
        }

        return JRoute::_('index.php?option=com_kukukontent&p='.$parsed);
    }//function

    public static function isLink($link)
    {
        static $query, $db, $p;

        if( ! $query)
        {
            $p = JRequest::getString('p');
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);

            $query->from('#__kukukontent');
            $query->select('count(*)');
        }

        $parsed =(0 === strpos($link, '/')) ? $p.$link : $link;

        $query->clear('where');
        $query->where('title='.$db->quote(urldecode($parsed)));

        $db->setQuery($query);

        $c = $db->loadResult();

        return($c) ? true : false;
    }//function
}//class
