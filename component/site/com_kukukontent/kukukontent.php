<?php
/**
 * @version SVN: $Id: builder.php 469 2011-07-29 19:03:30Z elkuku $
 * @package    KuKuKontent
 * @subpackage Base
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import the class JController
jimport('joomla.application.component.controller');

//-- Load the special Language
try
{
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
            g11n::setDebug(ECR_DEBUG_LANG);
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

//-- Get an instance of the controller with the prefix 'KuKuKontent'
$controller = JController::getInstance('KuKuKontent');

//-- Execute the 'task' from the Request
$controller->execute(JRequest::getCmd('task'));

//-- Redirect if set by the controller
$controller->redirect();
