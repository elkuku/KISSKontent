<?php
/**
 * @version SVN: $Id: builder.php 469 2011-07-29 19:03:30Z elkuku $
 * @package    KuKuKontent
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
 * HTML View class for the KuKuKontent Component.
 *
 * @package KuKuKontent
 */
class KuKuKontentViewListKontent extends JView
{
    protected $list = array();

    /**
     * KuKuKontent view display method.
     *
     * @param string $tpl The name of the template file to parse;
     *
     * @return void
     */
    public function display($tpl = null)
    {
        JHtml::_('stylesheet', 'com_kukukontent/kukukontent.css', array(), true);
        //         JHtml::_('script', 'com_kukukontent/kukukontent.js', array(), true);

        $menu = JFactory::getApplication()->getMenu('site')->getActive()->query;

        $this->startLevel =(isset($menu['start'])) ? $menu['start'] : '';
        $this->maxLevel =(isset($menu['depth'])) ? $menu['depth'] : 0;

        $list = $this->get('list');

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

                    $found == true;
                }
            }

            if($this->maxLevel
            && $level > $this->maxLevel)
            {
                continue;
            }

            $cItems[] = $name;

            $full = implode('/', $cItems);

            $type = 'internal';
            $type .= KuKuKontentHelper::isLink($full) ? '' : ' redlink';

            $link = '<a class="'.$type.'" href="'.KuKuKontentHelper::getLink($full).'">'.$name.'</a>';
            $list[] = str_repeat('&nbsp;&bull;', $level).'&nbsp;'.$link;

            if(count($entries))//-- recurse...
            $this->processItems($entries, ++$level);

            if($level && count($entries))
            $level --;

            array_pop($cItems);
        }//foreach

        $this->list = $list;
    }//function
}//class
