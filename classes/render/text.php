<?php defined('SYSPATH') OR die('No direct access allowed.');

class Render_Text extends Render {

public static function render(array $vars=array(), array $globals=array(), $file=false, array $options=array()) {

  if ( empty($vars['_no_header']) ) {
    Request::instance()->headers['Content-Type'] = 'text/plain; charset='.Kohana::$charset;
    $result = $vars['content'];

  } elseif ( empty($vars['_plain']) ) {
    // if we are not sending this as text/plain we probably want this
    $result = htmlspecialchars($vars['content']);

  } else {
    $result = $vars['content'];
  }

  return $result;
}

}
