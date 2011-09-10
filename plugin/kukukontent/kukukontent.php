<?php

// No direct access allowed to this file
defined('_JEXEC') || die('=;)');

// Import Joomla! Plugin library file
jimport('joomla.plugin.plugin');

class plgContentKuKuKontent extends JPlugin
{
    /**
     * Plugin...
     */
    public function onContentPrepare($context, $row, &$params, $page = 0)
    {
        include_once JPATH_SITE.'/plugins/content/kukukontent/parser/markdown.php';

        $row->text = Markdown($row->text);

        return true;

        include_once JPATH_SITE.'/plugins/content/kukukontent/parser/classTextile.php';
//         include_once JPATH_SITE.'/plugins/content/jTextile/textile/smartypants.php';

        $textile = new Textile;

//         $row->text = '<!-- jTextile -->'."\n".SmartyPants($textile->TextileThis($row->text));
        $row->text = '<!-- jTextile -->'."\n".$textile->TextileThis($row->text);

        return true;
    }//function
}//class
