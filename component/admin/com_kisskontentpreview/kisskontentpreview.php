<?php
/**
 * @package    KISSKontentPreview
 * @subpackage Base
 * @author      {@link }
 * @author     Created on 09-Dec-2012
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');


// @todo This is an "add" for J! 3.0
JFactory::getApplication()->JComponentTitle = 'KISSKontentPreview';

//-- No direct access
defined('_JEXEC') || die('=;)');


//-- Get an instance of the controller with the prefix 'KISSKontentPreview'
$controller = JControllerLegacy::getInstance('KISSKontentPreview');

//-- Execute the 'task' from the Request
$controller->execute(JFactory::getApplication()->input->get('task'));

//-- Redirect if set by the controller
$controller->redirect();
