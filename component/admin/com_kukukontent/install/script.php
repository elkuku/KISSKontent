<?php
/**
 * @version SVN: $Id$
 * @package    KuKuKontent
 * @subpackage Install
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 12-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * Script file for KuKuKontent component.
 */
class com_kukukontentInstallerScript
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
        echo '<p>'.JText::_('COM_KUKUKONTENT_PREFLIGHT_'.$type.'_TEXT').'</p>';
    }//function

    /**
     * Method to install the component.
     *
     * @return void
     */
    public function install($parent)
    {
        // $parent is the class calling this method
        //	$parent->getParent()->setRedirectURL('index.php?option=com_kukukontent');
        echo '<p>'.JText::_('COM_KUKUKONTENT_INSTALL_TEXT').'</p>';
    }//function

    /**
     * Method to update the component.
     *
     * @return void
     */
    public function update($parent)
    {
        // $parent is the class calling this method
        echo '<p>'.JText::_('COM_KUKUKONTENT_UPDATE_TEXT').'</p>';
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
        echo '<p>'.JText::_('COM_KUKUKONTENT_POSTFLIGHT_'.$type.'_TEXT').'</p>';
    }//function

    /**
     * Method to uninstall the component.
     *
     * @return void
     */
    public function uninstall($parent)
    {
        // $parent is the class calling this method
        echo '<p>'.JText::_('COM_KUKUKONTENT_UNINSTALL_TEXT').'</p>';
    }//function
}//class
