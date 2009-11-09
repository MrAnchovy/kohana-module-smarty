<?php

/**
 * Smarty {report_header}{/report_header} block plugin
 *
 * Banded Report Generator Framework
 *
 * The header block is output at the start of the report. If a group name is
 * specified, then the block is output before the start of the group level.
 *
 * @type        block
 * @name        report_header
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
function smarty_block_report_header($params, $content, &$smarty, &$repeat)
{
    $_parent_params =& smarty_get_parent_plugin_params($smarty, 'report');

    if (is_null($content)) {
        /* handle block open tag */
        if (!array_key_exists('group', $params)) {
            // report header
            if ($_parent_params['report']['record']['first'])  {
                $_parent_params['report']['buffer'] .= '##SMARTY_BLOCK_REPORT_HEADER##';
            }
            if ($_parent_params['report']['record']['last']) {
                foreach ($_parent_params['report']['stats'] as $stat_type=>$stat) {
                    $smarty->assign($stat_type, $stat);
                }
            }

        } else {
            // group header
            if (!in_array($params['group'], $_parent_params['report']['record']['fields'], true)) {
                $smarty->trigger_error("{report_header}: given group '{$params['group']}' does not have a corresponding record field in given recordset.", E_USER_ERROR);
            }
            if ($_parent_params['report']['group'][$params['group']]['first']) {
                $_parent_params['report']['buffer'] .= "##SMARTY_BLOCK_GROUP_HEADER_{$params['group']}##";
            }
            if ($_parent_params['report']['group'][$params['group']]['last']) {
                foreach ($_parent_params['report']['group'][$params['group']]['stats'] as $stat_type=>$stat) {
                    $smarty->assign($stat_type, $stat);
                }
            }
        }

    } else {
        /* handle block close tag */
        if (!array_key_exists('group', $params) && $_parent_params['report']['record']['last']) {
           $_parent_params['report']['header']['report']['buffer'] = $content;
        }
        if ($_parent_params['report']['group'][$params['group']]['last']) {
           $_parent_params['report']['header']['group']['buffer'][$params['group']][] = $content;
        }
    }
    return;
}
