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

function smarty_block_Kohana_profiler($params, $content, &$smarty, &$repeat) {
static $stack = array();
static $profiles = array();
static $firsttime = true;

  if ( $firsttime ) {
    $smarty->assign_by_ref('Kohana_profiles', $profiles);
    $firsttime = false;
  }

  if ( $repeat ) {
    // opening tag
    if ( Kohana::$profiling ) {
      $name = isset($params['name']) ? $params['name'] : 'other';
      $token = Profiler::start('Smarty_rendering', $name);
    } else {
      $token = $name = '';
    }
    // always need to do this in case the status of Kohana::$profiling is changed before closing tag
    array_push($stack, array($token, $name));
  } else {
    // closing tag
    list($token, $name) = array_pop($stack);
    if ( Kohana::$profiling ) {
      Profiler::stop($token);

      // array (time, mem, size)
      $total = Profiler::total($token);
      $total[2] = strlen($content);
      $total[3] = microtime(true) - KOHANA_START_TIME;
      $total[4] = memory_get_usage();
      $smarty->assign('Kohana_profiler', $total);

      if ( isset($profiles[$name]) ) {
        $profiles[$name][0] += $total[0];
        $profiles[$name][1] += $total[1];
        $profiles[$name][2] += $total[2];
      } else {
        $profiles[$name] = $total;
      }
    } else {
      $total = array(0,0,
        strlen($content),
        microtime(true) - KOHANA_START_TIME,
        memory_get_usage(),
      );
      $smarty->assign('Kohana_profiler', $total);
    }

    return $content;
  }
}
