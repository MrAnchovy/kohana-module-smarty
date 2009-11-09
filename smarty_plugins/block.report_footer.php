<?php

/**
 * Smarty {report_footer}{/report_footer} block plugin
 *
 * Banded Report Generator Framework
 *
 * The footer block is output at the end of the report. If a group name is
 * specified, then the block is output after the end of the group level.
 *
 * @type        block
 * @name        report_footer
 * @version     0.1.6
 * @requires    {report}{/report} block plugin.
 * @see         http://www.phpinsider.com/smarty-forum/viewtopic.php?t=4125
 *
 * @author      boots < jayboots @at@ yahoo com >
 * @copyright   brainpower, boots, 2004, 2005
 * @license     LGPL
 *
 * @thanks      messju mohr, sophistry
 *
 * @param group     default: null
 */
function smarty_block_report_footer($params, $content, &$smarty, &$repeat)
{
    $_parent_params =& smarty_get_parent_plugin_params($smarty, 'report');

    if (is_null($content)) {
        /* handle block open tag */
        if (!array_key_exists('group', $params)) {
           // report footer
            if ($_parent_params['report']['record']['last']) {
                foreach ($_parent_params['report']['stats'] as $stat_type=>$stat) {
                    $smarty->assign($stat_type, $stat);
                }
            }

        } else {
            // group footer
            if (!in_array($params['group'], $_parent_params['report']['record']['fields'], true)) {
                $smarty->trigger_error("{report_footer}: given group '{$params['group']}' does not have a corresponding record field in given recordset.", E_USER_ERROR);
            }
            if ($_parent_params['report']['group'][$params['group']]['last']) {
                foreach ($_parent_params['report']['group'][$params['group']]['stats'] as $stat_type=>$stat) {
                    $smarty->assign($stat_type, $stat);
                }
            }
        }

    } else {
        /* handle block close tag */
        if ($_parent_params['report']['record']['last'] || $_parent_params['report']['group'][$params['group']]['last']) {
            $_parent_params['report']['buffer'] .= $content;
        }
    }
    return;
}
