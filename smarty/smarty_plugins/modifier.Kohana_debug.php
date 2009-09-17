<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty debug_print_var modifier plugin
 *
 * Type:     modifier<br>
 * Name:     debug_print_var<br>
 * Purpose:  formats variable contents for display in the console
 * @link http://smarty.php.net/manual/en/language.modifier.debug.print.var.php
 *          debug_print_var (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param array|object
 * @param integer
 * @param integer
 * @return string
 */
function smarty_modifier_Kohana_debug($var) {
  return Kohana::debug($var);
}
