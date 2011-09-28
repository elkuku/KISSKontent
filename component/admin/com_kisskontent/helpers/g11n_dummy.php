<?php
/**
 * @package    KISSKontent
 * @subpackage Helpers
 * @author     Nikolai Plath (elkuku) {@link http://www.nik-it.de NiK-IT.de}
 * @author     Created on 21-May-2011
 * @license    GNU/GPL, see JROOT/LICENSE.php
 */

//-- No direct access
defined('_JEXEC') || die('=;)');

/**
 * PHP dummy function.
 *
 * @param string $string The string
 *
 * @return string The same string..
 */
function jgettext($string)
{
    return $string;
}//function

/**
 * PHP dummy function.
 *
 * @param string $singular Singular string
 * @param string $plural Plural string
 * @param integer $count Count
 *
 * @return string The plural string if $count != 1
 */
function jngettext($singular, $plural, $count)
{
    return (1 == $count) ? $singular : $plural;
}//function
