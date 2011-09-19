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

defined('NL') || define('NL', "\n");

//-- Import the class JController
jimport('joomla.application.component.controller');

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
        if(0)//ECR_DEV_MODE && ECR_DEBUG_LANG)
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

    return;
}//try

JLoader::register('KISSKontentHelper', JPATH_COMPONENT_SITE.'/helpers/kisskontent.php');

//-- Get an instance of the controller with the prefix 'KISSKontent'
$controller = JController::getInstance('KISSKontent');

//-- Execute the 'task' from the Request
$controller->execute(JRequest::getCmd('task'));

//-- Redirect if set by the controller
$controller->redirect();

//g11n::debugPrintTranslateds(true);
