<?php
/**
 * @package    KISSKontent
 * @subpackage Base
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');
error_reporting(-1);
jimport('joomla.client.ftp');
$foo = new JFTP;
$x = $foo->get('a', 'b');
defined('NL') || define('NL', "\n");

define('KISS_DBG', 0);//@@DEBUG

if(KISS_DBG)
{
    jimport('kuku.utility.query');//@@DEBUG
    jimport('kuku.utility.log');//@@DEBUG

    JHtml::_('stylesheet'//@@DEBUG
    , 'com_kisskontent/debug.css', array(), true);
}

jimport('joomla.application.component.controller');

$params = JFactory::getApplication('site')->getParams('com_kisskontent');

//-- Handle multilanguage functionality
if( ! $params->get('kiss_ml', 0))
{
    define('KISS_ML', 0);
}
else
{
    try
    {
        //-- Load the special Language

        if( ! jimport('g11n.language'))
        {
            //-- Load dummy language handler -> english only !
            JLoader::import('g11n_dummy', JPATH_COMPONENT_ADMINISTRATOR.'/helpers');

            //         ecrScript('g11n_dummy');
            //         ecrScript('php2js');
        }
        else
        {
            if(0)//@@DEBUG
            {
                g11n::cleanStorage();
                g11n::setDebug(1);
            }

            //-- Get our special language file
            g11n::loadLanguage();
        }
    }
    catch(Exception $e)
    {
        JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
    }//try

    if( ! class_exists('g11n'))
    {
        echo 'Please install the '
        .'<a href="...">g11n language library</a>'
        .' to use the multilanguage functionality.';

        define('KISS_ML', 0);
    }
    else
    {
        /**
         * @var integer Multilanguage flag
         */
        define('KISS_ML', 1);
    }
}//if multilanguage

JLoader::register('KISSKontentHelper', JPATH_COMPONENT_SITE.'/helpers/kisskontent.php');

//-- Get an instance of the controller with the prefix 'KISSKontent'
$controller = JController::getInstance('KISSKontent');

//-- Execute the 'task' from the Request
$controller->execute(JRequest::getCmd('task'));

//-- Redirect if set by the controller
$controller->redirect();

if('raw' != JRequest::getCmd('format'))
{
    if(KISS_DBG) KuKuUtilityLog::dump();
    // g11n::debugPrintTranslateds();
    if(KISS_DBG) echo KuKuUtilityQuery::dump();
}
