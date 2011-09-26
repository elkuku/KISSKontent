<?php
/**
 * @version SVN: $Id$
 * @package    KISSKontent
 * @subpackage Install
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 12-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * Script file for the KISSKontent component.
 */
class com_kisskontentInstallerScript
{
    /**
     * Method to run before an install/update/uninstall method.
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        echo '<p>'.JText::_('COM_KISSKONTENT_PREFLIGHT_'.$type.'_TEXT').'</p>';
    }//function

    /**
     * Method to install the component.
     *
     * @return void
     */
    public function install($parent)
    {
        // $parent is the class calling this method
        //	$parent->getParent()->setRedirectURL('index.php?option=com_kisskontent');
        echo '<p>'.JText::_('COM_KISSKONTENT_INSTALL_TEXT').'</p>';
    }//function

    /**
     * Method to update the component.
     *
     * @return void
     */
    public function update($parent)
    {
        // $parent is the class calling this method
        echo '<p>'.JText::_('COM_KISSKONTENT_UPDATE_TEXT').'</p>';
    }//function

    /**
     * Method to run after an install/update/uninstall method.
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        echo '<p>'.JText::_('COM_KISSKONTENT_POSTFLIGHT_'.$type.'_TEXT').'</p>';

        $comId = JComponentHelper::getComponent('com_kisskontent')->id;

        $userId = JFactory::getUser()->id;

        $table = JTable::getInstance('extension');

        $table->load($comId);

        $params = new JRegistry($table->params);

        $params->set('id_superuser', $userId);

        $table->params = $params->toString();

        $table->store();
    }//function

    /**
     * Method to uninstall the component.
     *
     * @return void
     */
    public function uninstall($parent)
    {
        // $parent is the class calling this method
        echo '<p>'.JText::_('COM_KISSKONTENT_UNINSTALL_TEXT').'</p>';
    }//function
}//class
