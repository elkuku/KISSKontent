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

function KuKuKontentBuildRoute(&$query)
{
    $segments = array();

    if(isset($query['p']))
    {
        $segments[] = $query['p'];

        unset($query['p']);
    }

    return $segments;
}

function KuKuKontentParseRoute($segments)
{
    $vars = array();

    if(count($segments))
    {
        $vars['p'] = implode('/', $segments);
    }

    return $vars;
}//function
