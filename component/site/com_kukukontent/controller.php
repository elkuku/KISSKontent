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

jimport('joomla.application.component.controller');

/**
 * KuKuKontent Controller.
 *
 * @package    KuKuKontent
 * @subpackage Controllers
 */
class KuKuKontentController extends JController
{
    public function edit()
    {
        //@TODO access check !

        parent::display();
    }

    public function save()
    {
        JRequest::checkToken() or jexit(jgettext('Invalid token'));

        try
        {
            $this->getModel()->save();

            JFactory::getApplication()->enqueueMessage(jgettext('Your content has been saved'));
        }
        catch (Exception $e)
        {
            JError::raiseWarning(1, $e->getMessage());
        }//try

        parent::display();
    }//function

    public function preview()
    {
        $p = JRequest::getString('p');

        if( ! $p)
        return;

        $content = $this->getModel()->getContent();

        $params = null;

        JPluginHelper::importPlugin('content');

        JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$content, &$params));


        echo $content->text;
    }//function
}//class
