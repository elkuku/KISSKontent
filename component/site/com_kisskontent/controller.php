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

jimport('joomla.application.component.controller');

/**
 * KISSKontent Controller.
 *
 * @package    KISSKontent
 * @subpackage Controllers
 */
class KISSKontentController extends JController
{
    public function edit()
    {
        if( ! KISSKontentHelper::getActions()->get('core.edit'))
        {
            JError::raiseError(403, jgettext('You are not allowed to edit Kontent pages.'));
        }

        parent::display();
    }//function

    public function save()
    {
        JRequest::checkToken() || jexit(jgettext('Invalid token'));

        try
        {
            $this->getModel()->save();

            JFactory::getApplication()->enqueueMessage(jgettext('Your Kontent has been saved'));
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
        $raw = KISSKontentHelper::preParse($raw);

        $o = new stdClass;
        $o->text = $raw;

        if( ! $p
        && ! $raw)
        return;

        //@todo - move the preview message to a view ¿
        $previewText = '<p class="previewMessage">'
        .jgettext('This is a preview only. The content has not been saved yet !')
        .'<br />'
        .'<a href="#" onclick="document.id(\'kisskontentPreview\').set(\'html\', \'\'); return false;">'.jgettext('Close preview').'</a>'
        .'</p>';

        $params = null;

        JPluginHelper::importPlugin('content');

        JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$o, &$params));

        echo $previewText.$o->text;
    }//function

    public function differences()
    {
        $p = JRequest::getString('p');

        //@todo clean me up mom =;)
        $raw = JRequest::getVar('kontent', '', 'post', 'none', JREQUEST_ALLOWRAW);
        $diffAll =(JRequest::getInt('diffAll')) ? true : false;

        $model = $this->getModel();

        $kontent = $model->getContent();
        $kontent->text = str_replace("\r", '', $kontent->text);//¿

        $this->diff = KISSKontentHelper::getDiffTable($kontent->text, $raw, $diffAll);

        $html = array();

        $html[] = '<table class="diff">
       <tr>
       <th colspan="2" class="diffLeft" style="background-color: #dfd;">'
        .jgettext('Saved version').'</th>'
        .'<th colspan="2" class="diffLeft" style="background-color: #ffc;">'
        .jgettext('Your version').'</th>'
        .'</tr>';

       	$html[] = $this->diff;

       	$html[] = '</table>';

       	$diff = implode("\n", $html);

        //@todo - move the preview message to a view ¿
        $previewText = '<p class="previewMessage">'
        .jgettext('This is a preview only. The content has not been saved yet !')
        .'<br />'
        .'<a href="#" onclick="document.id(\'kisskontentPreview\').set(\'html\', \'\'); return false;">'.jgettext('Close preview').'</a>'
        .'</p>';

        echo $previewText.$diff;

        return;

        //@ todo: display also a preview ¿

        //-- Process internal links
        $raw = KISSKontentHelper::preParse($raw);

        $o = new stdClass;
        $o->text = $raw;

        $params = null;

        JPluginHelper::importPlugin('content');

        JDispatcher::getInstance()->trigger('onContentPrepare'
        , array('text', &$o, &$params));

        echo $o->text;
    }//function
}//class
