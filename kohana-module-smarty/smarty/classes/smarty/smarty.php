<?php defined('SYSPATH') OR die('No direct access allowed.');

class Smarty_Smarty {

public static function get_smarty() {
  static $smarty;

  if ( isset($smarty) ) {
    return $smarty;
  }

  $config = Kohana::config('smarty');

  try {
    include $config->smarty_class_file;
  } catch (Exception $e) {
    throw new Kohana_Exception('Could not load Smarty class file');
  }
    
  $smarty = new Smarty;

  // deal with initial config
  $smarty->compile_dir = $config->compile_dir;
  // check we can write to our directory
  if ( !is_writeable($smarty->compile_dir) ) {
    $smarty->create_dir($smarty->compile_dir, 'Smarty compiled template');
  }
  $smarty->php_handling = constant($config->php_handling);

  // deal with main config
  foreach ( $config->smarty_config as $key => $value ) {
    $smarty->$key = $value;
  }

  return $smarty;
}

public function create_dir($path, $name='') {
  if ( file_exists($path) ) {
    if ( is_dir($path) ) {
      throw new Kohana_Exception('Could not write to :name directory',
        array('name' => $name));
    } else {
      throw new Kohana_Exception(':name path is a file',
        array('name' => $name));
    }
  } else {
    try {
      mkdir($path);
    } catch (Exception $e) {
      throw new Kohana_Exception('Could not create :name directory',
        array('name' => $name));
    }
    if ( !is_writeable($path) ) {
      throw new Kohana_Exception('Created :name directory but could not write to it',
        array('name' => $name));
    }
  }
}

}
