<?php

/**
 * Smarty {report_detail}{/report_detail} block plugin
 *
 * Banded Report Generator Framework
 *
 * The detail block is output on every iteration of the main {report} block.
 *
 * @type        block
 * @name        report_detail
 * @version     0.1.6
 * @requires    {report}{/report} block plugin.
 * @see         http://www.phpinsider.com/smarty-forum/viewtopic.php?t=4125
 *
 * @author      boots < jayboots @at@ yahoo com >
 * @copyright   brainpower, boots, 2004, 2005
 * @license     LGPL
 *
 * @thanks      messju mohr, sophistry
 */
function smarty_block_report_detail($params, $content, &$smarty, &$repeat)
{
    $_parent_params =& smarty_get_parent_plugin_params($smarty, 'report');

    if (!is_null($content)) {
        /* handle block close tag */
        // add content to {report} tag's current buffer
        $_parent_params['report']['buffer'] .= $content;
    }
    return;
}


