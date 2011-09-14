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
        if( ! KuKuKontentHelper::getActions()->get('core.edit'))
        {
            JError::raiseError(403, jgettext('You are not allowed to edit Kontent pages'));
        }

        parent::display();
    }//function

    public function save()
    {
        JRequest::checkToken() || jexit(jgettext('Invalid token'));

        try
        {
            $this->getModel()->save();

            JFactory::getApplication()->enqueueMessage(jgettext('Your content has been saved'));
        }
        catch(Exception $e)
        {
            JError::raiseWarning(1, $e->getMessage());
        }//try

        parent::display();
    }//function

    public function preview()
    {
        $p = JRequest::getString('p');

        //@todo clean me up mom =;)
        $raw = JRequest::getVar('kontent', '', 'post', 'none', JREQUEST_ALLOWRAW);

        //-- Process internal links
        $raw = KuKuKontentHelper::doInternalAnchors($raw);

        $o = new stdClass;
        $o->text = $raw;

        if( ! $p
        && ! $raw)
        return;

        $previewText = '<p class="previewMessage">'//@todo - move the preview message to a view ¿
        .jgettext('This is a preview only. The content has not been saved yet !')
        .'&nbsp;<a href="#" onclick="document.id(\'kukukontentPreview\').set(\'html\', \'\'); return false;">'.jgettext('Close preview').'</a>'
        .'</p>';

        //         $content = $this->getModel()->getContent();

        $params = null;

        JPluginHelper::importPlugin('content');

        JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$o, &$params));

        echo $previewText.$o->text;
    }//function
}//class
