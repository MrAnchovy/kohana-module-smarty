<?php defined('SYSPATH') OR die('No direct access allowed.');

class Render_Smarty extends Render {

public static $smarty;

public static function render(array $vars=array(), array $globals=array(), $file=false, array $options=array()) {
  $smarty = self::get_smarty();
  $token = Kohana::$profiling ? Profiler::start('smarty', 'rendering ' . basename($file)) : false;

  // save _tpl_vars in case we have a render within a render
  // - can be caused by variables which are objects with __toString methods
  $old_tpl_vars = $smarty->_tpl_vars;

  $smarty->_tpl_vars = $vars + $globals;

  // debugging pop-up code removed - too much pain for too little gain
  $result = $smarty->fetch($file);

  $smarty->_tpl_vars = $old_tpl_vars;

  $token ? Profiler::stop($token) : null;
  return $result;
}

public static function get_smarty() {

  if ( isset(self::$smarty) ) {
    return self::$smarty;
  }

  $token = Kohana::$profiling ? Profiler::start('smarty', 'load smarty') : false;

  $config = Kohana::config('smarty');

  try {
    include $config->smarty_class_file;
  } catch (Exception $e) {
    throw new Kohana_Exception('Could not load Smarty class file');
  }
    
  $smarty = new Smarty;

  // deal with initial config
  $smarty->php_handling = constant($config->php_handling);

  // deal with main config
  foreach ( $config->smarty_config as $key => $value ) {
    $smarty->$key = $value;
  }

  // check we can write to the compiled templates directory
  if ( !is_writeable($smarty->compile_dir) ) {
    self::create_dir($smarty->compile_dir, 'Smarty compiled template');
  }

  // if smarty caching is enabled, check we can write to the cache directory
  if ( $smarty->caching && !is_writeable($smarty->cache_dir) ) {
    self::create_dir($smarty->cache_dir, 'Smarty cache');
  }

  self::$smarty = $smarty;

  $token ? Profiler::stop($token) : null;


  return $smarty;
}

private static function create_dir($path, $name='') {
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
