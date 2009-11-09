<?php

/**
 * Smarty {report}{/report} block plugin
 *
 * Banded Report Generator Framework
 *
 * This is the main block for this framework and it acts as a container for the
 * rest of the {report_*} block types and handles the looping requirements of
 * the given dataset.
 *
 * @type        block
 * @name        report
 * @version     0.1.6
 * @see         http://www.phpinsider.com/smarty-forum/viewtopic.php?t=4125
 *
 * @author      boots < jayboots @at@ yahoo com >
 * @copyright   brainpower, boots, 2004-2005
 * @license     LGPL
 *
 * @thanks      messju mohr, sophistry
 *
 * @param recordset REQUIRED
 * @param record    REQUIRED
 * @param groups    default: null
 * @param resort    default: false
 */

function smarty_block_report($params, $content, &$smarty, &$repeat)
{
    $_params =& smarty_get_current_plugin_params($smarty);

    if (is_null($content)) {
        $_params['report'] = smarty_block_report__initialize($smarty, $repeat, $params);
        smarty_block_report__process_next($smarty, $repeat, $_params['report']);

    } else {
        smarty_block_report__process_next($smarty, $repeat, $_params['report']);
        if (!$repeat)
            return smarty_block_report__return_content($smarty, $repeat, $_params['report'], $content);
    }

}

function smarty_block_report__initialize(&$smarty, &$repeat, $params)
{
    if (!array_key_exists('recordset', $params)) {
        $smarty->trigger_error("{report}: parameter 'recordset' not given.", E_USER_ERROR);
    }
    if (!array_key_exists('record', $params)) {
        $smarty->trigger_error("{report}: parameter 'record' not given.", E_USER_ERROR);
    }

    $_params = array(
    // setup the basic report data structure
      'recordset'   => (is_array($params['recordset'])) ? $params['recordset'] : array()
    , 'buffer'      => ''
    , 'header'      => array('report'=>array('buffer'=>''), 'group'=>array('buffer'=>array()))
    , 'group'       => array()
    , 'groups'      => array()
    , 'stats'       => array()

    // setup the record data sub-structure
    , 'record'      => array(
        'name'      => $params['record']
      , 'count'     => (is_array($params['recordset'])) ? count($params['recordset']) : 0
      , 'first'     => false
      , 'last'      => false
      , 'iteration' => 0
      , 'prev'      => null
      , 'curr'      => null
      , 'next'      => null
      , 'fields'    => (is_array($params['recordset']) && count($params['recordset'])) ? array_keys($params['recordset'][0]) : array()
      )
    );

    // setup groups, if any
    if (array_key_exists('groups', $params)) {
        $groups = preg_split("/[\s,]+/", $params['groups'], -1, PREG_SPLIT_NO_EMPTY);
        $_params['groups'] = $groups;
        foreach ($_params['groups'] as $group) {
            if (!in_array($group, $_params['record']['fields'], true)) {
                $smarty->trigger_error("{report}: given group '$group' does not have a corresponding record field in given recordset.", E_USER_ERROR);
                return;
            }
        }
        // re-sort array
        if (array_key_exists('resort', $params) && $params['resort']) {
            smarty_block_report__group_sort($groups);
            usort($_params['recordset'], 'smarty_block_report__group_sort');
        }
    }
    return $_params;
}

function smarty_block_report__process_next(&$smarty, &$repeat, &$params)
{
    // iterate the recordset
    $recordset =& $params['recordset'];
    $record =& $params['record'];
    $group =& $params['group'];

    // increase current counts
    ++$record['iteration'];

    if (is_null($record['prev']) && is_null($record['curr']) && count($recordset)) {
        // on the first iteration we load current and next
        $record['curr'] = array_shift($recordset);
        $record['next'] = (count($recordset)) ? array_shift($recordset) : null;
        $record['first'] = true;
        foreach ($record['curr'] as $field=>$value) {
            $params['stats']['sum'][$field] = $value;
            $params['stats']['count'][$field] = 1;
            $params['stats']['avg'][$field] = $value;
        }

    } else if (!is_null($record['next'])) {
        // on a normal iteration we load current from next and then reload next
        $record['prev'] = $record['curr'];
        $record['curr'] = $record['next'];
        $record['next'] = (count($recordset)) ? array_shift($recordset) : null;
        $record['first'] = false;
        foreach ($record['curr'] as $field=>$value) {
            if (is_numeric($value)) {
                $params['stats']['sum'][$field] += $value;
                ++$params['stats']['count'][$field];
                $params['stats']['avg'][$field] = $params['stats']['sum'][$field]/$params['stats']['count'][$field];
            }
        }

    } else {
        // there were no iterations at all
        // decrease current counts to counter-act increase
        --$record['iteration'];    // should = -1
    }

    // is there anything left to do?
    $repeat = !$record['last'];
    $record['last'] = is_null($record['next']) && !is_null($record['curr']);

    // process grouping levels 
    foreach ($params['groups'] as $_group) { 
        if (!is_null($record['curr'])) { 
            if ($record['prev'][$_group] != $record['curr'][$_group] OR ($record['prev'][$prev_group] != $record['curr'][$prev_group])) { 
                $group[$_group]['first'] = true; 
                foreach ($record['curr'] as $field=>$value) { 
                    $group[$_group]['stats']['sum'][$field] = $value; 
                    $group[$_group]['stats']['count'][$field] = 1; 
                    $group[$_group]['stats']['avg'][$field] = $value; 
                } 
            } else { 
                $group[$_group]['first'] = false; 
                foreach ($record['curr'] as $field=>$value) { 
                    if (is_numeric($value)) { 
                        $group[$_group]['stats']['sum'][$field] += $value; 
                        ++$group[$_group]['stats']['count'][$field]; 
                        $group[$_group]['stats']['avg'][$field] = $group[$_group]['stats']['sum'][$field]/$group[$_group]['stats']['count'][$field]; 
                     } 
                } 
            } 


            if ($record['last']) { 
                $group[$_group]['last'] = true; 
            } else if (($record['curr'][$_group] == $record['next'][$_group]) AND ($record['curr'][$prev_group] != $record['next'][$prev_group])) { 
               $group[$_group]['last'] = true; 
            } else if (($record['curr'][$_group] != $record['next'][$_group])) { 
                $group[$_group]['last'] = true; 
            } else { 
                $group[$_group]['last'] = false; 
            } 
        } else { 
            $group[$_group]['first'] = is_null($record['prev']); 
            $group[$_group]['last'] = is_null($record['next']); 
        } 
        
        $prev_group = $_group; 
    } 

    if (isset($record['curr'])) {
        $smarty->assign($record['name'], $record['curr']);
    }
}

function smarty_block_report__return_content(&$smarty, &$repeat, &$params, $content)
{
    // only return content generated stored in the output buffer by sub {report_*}
    $buffer = str_replace('##SMARTY_BLOCK_REPORT_HEADER##', $params['header']['report']['buffer'], $params['buffer']);
    foreach ($params['header']['group']['buffer'] as $group=>$headers) {
        foreach ($headers as $group_buffer) {
            $buffer = preg_replace("/##SMARTY_BLOCK_GROUP_HEADER_{$group}##/", smarty_block_report__quote_replace($group_buffer), $buffer, 1);
        }
    }
    return $buffer;
}

function smarty_block_report__quote_replace($string) 
{ 
    return strtr($string, array('\\' => '\\\\', '$' => '\\$')); 
} 


/**
 * multi-column recordset sorter callback for use with usort et al.
 *
 * usage:
 *   smarty_block_report__group_sort($sort_keys_array);
 *   usort($recordset, 'smarty_block_report__group_sort');
 *
 * where:
 *  $recordset is an indexed array of records and each record is an associative
 *  array having the same set of fields in each record
 *
 *  $sort_key_array is an array of key names which are present in every record
 *  of the recordset and are given in the required sort order
 */
function smarty_block_report__group_sort($a, $b=null)
{
    static $sort_keys = array();
    if (is_null($b) && !is_null($a)) {
        // when $b is null, the $a is assumed to contain the keys to sort on
        $sort_keys = $a;
    } else {
        $result = 0; // assume equal
        foreach ($sort_keys as $key) {
            if ($a[$key] < $b[$key]) {
                $result = -1;
                break;
            } else if ($a[$key] > $b[$key]) {
                $result = 1;
                break;
            }
        }
        return $result;
    }
}


/**
 * GENERAL PLUGIN HELPERS
 */

function &smarty_get_parent_plugin_params(&$smarty, $parent_plugin_name)
{
    for ($i=count($smarty->_tag_stack)-1; $i>=0; $i--) {
        $tag_name = $smarty->_tag_stack[$i][0];
        if ($tag_name == $parent_plugin_name) break;
    }

    if ($i<0) {
        /* $parent_plugin_name not found */
        list($plugin_name, $plugin_params) = $smarty->_tag_stack[count($smarty->_tag_stack)-1];
        $smarty->trigger_error("\{$plugin_name\}: not inside \{$parent_plugin_name\}-context", E_USER_ERROR);
        return;
    } else {
        return $smarty->_tag_stack[$i][1];
    }
}

function &smarty_get_current_plugin_params(&$smarty)
{
    return $smarty->_tag_stack[count($smarty->_tag_stack)-1][1];
}

