<?php defined('SYSPATH') OR die('No direct access allowed.');

class Render_Json extends Render {

public static function render(array $vars=array(), array $globals=array(), $file=false, array $options=array()) {

  if ( isset($vars['_data']) ) {
    $data = $vars['_data'];
  } else {
    $data = array();
    $config = Kohana::config('render');
    $expose = isset($config['json_expose'])
      ? $config['json_expose']
      : ( isset($config['expose']) ? $config['expose'] : array() );
    foreach ( $expose as $var ) {
      if ( isset($vars[$var]) ) {
        $data[$var] = $vars[$var];
      }
    }
  }

  if ( empty($vars['_no_header'])
  && ( !empty(Request::$is_ajax) || !empty($vars['_force_header']) ) ) {
    // only send json if it is an XMLHttpRequest (subject to overrides)
    Request::instance()->headers['Content-Type'] = 'application/json';
    $result = json_encode($data);

  } elseif ( empty($vars['_plain']) ) {
    // if we are sending it as HTML we probably want this
    $result = htmlspecialchars(json_encode($data));

  } else {
    // unless we are sure!
    $result = json_encode($data);
  }

  return $result;
}

}
