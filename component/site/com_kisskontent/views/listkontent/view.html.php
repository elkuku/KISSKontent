<?php
/**
 * @package    KISSKontent
 * @subpackage Views
 * @author     Nikolai Plath {@link http://nik-it.de}
 * @author     Created on 09-Sep-2011
 * @license    GNU/GPL
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

//-- Import the JView class
jimport('joomla.application.component.view');

/**
 * HTML View class for the KISSKontent Component.
 *
 * @package KISSKontent
 */
class KISSKontentViewListKontent extends JView
{
    protected $list = array();

    /**
     * KISSKontent view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('stylesheet', 'com_kisskontent/kisskontent.css', array(), true);
        //         JHtml::_('script', 'com_kisskontent/kisskontent.js', array(), true);

        $appParams = JFactory::getApplication()->getParams();

        $this->title = $appParams->get('page_title') ?: jgettext('Kontent list');

        $this->pageclass_sfx = htmlspecialchars($appParams->get('pageclass_sfx'));

        $menu = JFactory::getApplication()->getMenu('site')->getActive()->query;

        $this->startLevel =(isset($menu['start'])) ? $menu['start'] : '';
        $this->maxLevel =(isset($menu['depth'])) ? $menu['depth'] : 0;

        $list = $this->get('list');

        $this->translationList = $this->get('translationlist');

        $this->canNuke = KISSKontentHelper::getActions()->get('core.nuke');
// var_dump($this->translationList );
        $this->processItems($list);

        parent::display($tpl);
    }//function

    protected function processItems($items, $level = 0)
    {
        static $list, $cItems, $found;

        foreach($items as $name => $entries)
        {
            if($this->startLevel)
            {
                if($level == 0)
                {
                    if($name != $this->startLevel)
                    continue;

                    $found == true;//@todo: really found ¿
                }
            }

            if($this->maxLevel
            && $level > $this->maxLevel)
            {
                continue;
            }

            $item = new stdClass;

            $cItems[] = $name;

            $full = implode('/', $cItems);

            $item->href = KISSKontentHelper::getLink($full);
            $item->title = $name;
            $item->indentString = str_repeat('&nbsp;&bull;', $level);
            $item->level = $level;
            $item->isLink = KISSKontentHelper::isLink($full);

            $class = 'internal';
            $class .=($item->isLink) ? '' : ' redlink';

            $item->class = $class;

            $item->nukeHref =($this->canNuke && $item->isLink)
            ? KISSKontentHelper::getLink($full, '&task=nuke')
            : '';

            $item->translations = array();

            if(array_key_exists($full, $this->translationList))
            {
                $item->translations = explode(',', $this->translationList[$full]->{'GROUP_CONCAT(t.lang)'});
            }

//             ? '&nbsp; &rArr;'.JHtml::link(KISSKontentHelper::getLink($full, '&task=nuke'), jgettext('Nuke')
//             , array('style' => 'font-weight: bold; color: red'))
//             : '';

            $list[] = $item;

            if(count($entries))//-- recurse...
            $this->processItems($entries, ++$level);

            if($level && count($entries))
            $level --;

            array_pop($cItems);
        }//foreach

        $this->list = $list;
    }//function
}//class
