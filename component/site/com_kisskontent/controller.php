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
            JError::raiseWarning(403, jgettext('You are not allowed to edit Kontent pages.'));
        }

        parent::display();
    }//function

    public function translate()
    {
        if( ! KISSKontentHelper::getActions()->get('core.translate'))
        {
            JError::raiseWarning(403, jgettext('You are not allowed to translate Kontent pages.'));
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

    public function dotranslate()
    {
        JRequest::checkToken() || jexit(jgettext('Invalid token'));

        try
        {
            $this->getModel()->saveTranslation();

            JFactory::getApplication()->enqueueMessage(jgettext('Your translation has been saved'));
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

    public function listkontent()
    {
        ;
    }

    public function nukeKonfirmed()
    {
        $this->nuke(true);
    }//function

    public function nuke($konfirmed = false)
    {

        try
        {
            $model = $this->getModel();

            $toNuke = $model->nuke($konfirmed);

            if( ! $konfirmed)
            {
                echo '<h1>'.sprintf(jgettext('Do you really want to...')).'</h1>';

                echo sprintf(jgettext('Delete the Kontent item: %s'), $toNuke->kiss->title).'<br />';

                echo sprintf(jngettext('Delete %d version (ID: %s)', 'Delete %d versions (IDs: %s)', count($toNuke->versions))
                ,count($toNuke->versions), implode(', ', $toNuke->versions)).'<br />';

                echo sprintf(jngettext('Delete %d translation (ID: %s)', 'Delete %d translations (IDs: %s)', count($toNuke->translations))
                ,count($toNuke->translations), implode(', ', $toNuke->translations)).'<br />';

                echo JHtml::link(JRoute::_('&task=nukeKonfirmed')
                , jgettext('Yes I really want to nuke this Kontent')
                , array('style' => 'font-weight: bold; color: red;'));
                //         var_dump($_REQUEST);

                //                 var_dump($toNuke);
            }
            else
            {
                JFactory::getApplication()->enqueueMessage(jgettext('Your Kontent has been nuked'));
            }
        }
        catch (Exception $e)
        {
            JError::raiseWarning(1, $e->getMessage());
        }

        JRequest::setVar('view', 'listkontent');

        parent::display();
    }//function

    public function help()
    {
        $defaultLang = 'en-GB';

        if(KISS_ML)
        {
            $lang = g11n::getDefault();
            $lang =($lang) ?: $defaultLang;
        }

        $lang =($lang) ?: $defaultLang;

        $file = JPATH_COMPONENT_SITE.'/demo/help_'.$lang.'.md';

        if( ! JFile::exists($file))
        {

            $lang = $defaultLang;

            $file = JPATH_COMPONENT_SITE.'/demo/help_'.$defaultLang.'.md';

            if( ! JFile::exists($file))
            {
                echo 'No help available';

                return;
            }
        }

        $p = null;

        $raw = JFile::read($file);

        $parts = explode("\n", $raw);

        $chapters = array();

        $t = '';

        foreach ($parts as $part)
        {
            if(preg_match('/^# (\w+)/', $part, $matches))
            {
                $t = $matches[1];
                $chapters[$t] = array();

                continue;
            }

            if( ! isset($chapters[$t]))
            continue;//smthg went wrong..

            $chapters[$t][] = $part;
        }//foreach

        JPluginHelper::importPlugin('content');

        $dispatcher = JDispatcher::getInstance();

        $o = new stdClass;

        $html = array();

        foreach ($chapters as $title => $chapter)
        {
            $html[] = '<h1>'.$title.'</h1>';

            $html[] = '<table border="1">';
            $html[] = '<tr><th>a</th><th>b</th><th>c</th></tr>';

            foreach ($chapter as $line)
            {
                $html[] = '<tr>';

                $html[] = '<td style="font-family: monospace;">';
                $html[] = $line;
                $html[] = '</td>';

                $o->text = $line;
                $o->text = KISSKontentHelper::preParse($o->text);
                $dispatcher->trigger('onContentPrepare'
                , array('text', &$o, &$p));

                $html[] = '<td nowrap="nowrap">';
                $html[] = $o->text;
                $html[] = '</td>';

                $html[] = '<td style="font-family: monospace;">';
                $html[] = htmlentities($o->text);
                $html[] = '</td>';

                $html[] = '</tr>';
            }
            $html[] = '</table>';
        }//foreach

        echo implode("\n", $html);

        exit;
    }//function
}//class
