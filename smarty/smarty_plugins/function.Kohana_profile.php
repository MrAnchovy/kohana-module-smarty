<?php
/**
 * Smarty {fetch} plugin
 *
 * Type:     function<br>
 * Name:     fetch<br>
 * Purpose:  fetch file, web or ftp data and display results
 * @link http://smarty.php.net/manual/en/language.function.fetch.php {fetch}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 * @return string|null if the assign parameter is passed, Smarty assigns the
 *                     result to a template variable
 */
function smarty_function_Kohana_profile($params, &$smarty) {
  $groups = Profiler::groups();
  $profile = array();
  foreach ( $groups as $group_name => $group ) {
    foreach ( $group as $name => $member ) {
      $stats = Profiler::stats($member);
      $profile[] = array(
        'group_name'     => $group_name,
        'name'           => $name,
        'count'          => count($member),
        'total_time'     => $stats['total']['time'],
        'min_time'       => $stats['min']['time'],
        'max_time'       => $stats['max']['time'],
        'average_time'   => $stats['average']['time'],
        'total_memory'   => $stats['total']['memory'],
        'min_memory'     => $stats['min']['memory'],
        'max_memory'     => $stats['max']['memory'],
        'average_memory' => $stats['average']['memory'],
      );
    }
  }

  $stats = Profiler::application();
  $profile[] = array(
        'group_name'     => 'Application timings',
        'name'           => 'Application timings',
        'count'          => $stats['count'],
        'total_time'     => $stats['total']['time'],
        'min_time'       => $stats['min']['time'],
        'max_time'       => $stats['max']['time'],
        'average_time'   => $stats['average']['time'],
        'total_memory'   => $stats['total']['memory'],
        'min_memory'     => $stats['min']['memory'],
        'max_memory'     => $stats['max']['memory'],
        'average_memory' => $stats['average']['memory'],
  );

  $smarty->assign('Kohana_profile', $profile);

};
