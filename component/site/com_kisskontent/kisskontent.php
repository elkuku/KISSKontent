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

JHtml::_('stylesheet'//@todo debug
, 'com_kisskontent/debug.css', array(), true);

jimport('kuku.util');//@todo debug

define('KISS_DBG', 1);

defined('NL') || define('NL', "\n");

jimport('joomla.application.component.controller');

$params = JFactory::getApplication('site')->getParams('com_kisskontent');

//-- Handle multilanguage functionality
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
        //TEMP@@debug
        if(0)
        {
            g11n::cleanStorage();//@@DEBUG
            g11n::setDebug(1);
        }

        //-- Get our special language file
        g11n::loadLanguage();
    }

}
catch(Exception $e)
{
    JError::raiseWarning(0, $e->getMessage());
}//try

if( ! $params->get('kiss_ml', 0))
{
    define('KISS_ML', 0);
}
else
{
    if( ! class_exists('g11n'))
    {
        echo 'Please install the <a href="...">g11n language library</a> to use the multilanguage functionality.';

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

// g11n::debugPrintTranslateds();
echo KuKuUtility::dump();
