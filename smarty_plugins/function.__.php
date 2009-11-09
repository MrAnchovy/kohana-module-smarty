<?php
/**
 * Kohana Smarty __ plugin
 *
 * Type:     function<br />
 * Name:     __<br />
 * Purpose:  Translate a text string, optionally substituting variables.
 * This Smarty function provides an interface to the Kohana __() function which
 * does the work.
 *
 * @author Mr Anchovy <mr dot anchovy at mranchovy dot com>
 * @param  array   ['t']    - the text to translate
 *                 ['lang'] - the language the text is in
 *                 [$key]   - the value of the variable $key to substitute
 *                            :$key in the string
 * @param  Smarty
 * @return string  The translation (and substitution) of the string
 */
function smarty_function___($params, &$smarty) {
  $string = $values = $lang = null;
  foreach ( $params as $key => $val ) {
    if ( $key == 't' ) {
      $string = $val;
    } elseif ( $key == 'lang' ) {
      $lang = $val;
    } else {
      $values[":$key"] = $val;
    }
  }

  if ( $lang===null ) {
    return __(arr::get($params, 't'), $values);
  } else {
    return __(arr::get($params, 't'), $values, $lang);
  }
}
