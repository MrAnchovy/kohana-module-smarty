<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Acts as an object wrapper for HTML pages with embedded PHP, called "views".
 * Variables can be assigned with the view object and referenced locally within
 * the view.
 *
 * @package    Kohana
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class View extends Kohana_View {

protected $_renderer;
protected $_default_renderer = 'parent'; // note cannot use Kohana_View->render because it is not a static method;
protected $_config = array();

public function __construct($file = null, array $data = null) {
  $token = Kohana::$profiling ? Profiler::start('renderer', 'new kohana view') : false;

  $this->_config = Kohana::config('render');

  parent::__construct($file, $data);
  $token ? Profiler::stop($token) : null;

}

/**
 * Returns a new View object.
 *
 * @param   string  view filename
 * @param   array   array of values
 * @return  View
 */
public static function factory($file = null, array $data = null) {
  return new View($file, $data);
}

/**
 * Sets the view filename.
 *
 * @throws  View_Exception
 * @param   string  filename
 * @return  View
 */
public function set_filename($file) {

  // first extract the renderer, if any
  $pos = strpos($file, ':');
  if ( $pos===false ) {
    $this->_renderer = $this->_default_renderer;
  } elseif ( $pos==0 ) {
    $this->_renderer = 'parent';
    $file = substr($file, 1);
  } else {
    $this->_renderer = substr($file, 0, $pos);
    $file = substr($file, $pos + 1);
  }

  // allow the parent method to do it its own way
  if ( $this->_renderer=='parent' ) {
    return parent::set_filename($file);
  }

  // TODO allow the renderer to set the file
  $this->_set_filename($file);

}

public function _set_filename($file) {

  // work out the extension and find the template file
  if ( array_key_exists($this->_renderer, $this->_config['extensions']) ) {
    $ext = $this->_config['extensions'][$this->_renderer];
    if ( $ext===true ) {
      // does not use template files
      $path = true;
    } elseif ( ($path = Kohana::find_file('views', $file, $ext)) === false ) {
      throw new Kohana_View_Exception('The requested view file :file.:ext could not be found', array(
        ':file' => $file, ':ext' => $ext,
      ));
    }
  } else { 
    throw new Kohana_View_Exception('There is no extension set for the :renderer renderer',
      array(':renderer' => $this->_renderer));
  }

  // Store the file path locally
  $this->_file = $path;
}


/**
 * Renders the view object to a string. Global and local data are merged
 * and extracted to create local variables within the view file.
 *
 * Note: Global variables with the same key name as local variables will be
 * overwritten by the local variable.
 *
 * @throws   View_Exception
 * @param    view filename
 * @return   string
 */
public function render($file = null, array $options=array()) {
  if ( $this->_renderer=='parent' ) {
    return parent::render($file);
  }

  if ($file!==null) {
    $this->set_filename($file);
  }

  if (empty($this->_file)) {
    throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
  }
  $method = 'Render_'.ucfirst($this->_renderer).'::render';
  return call_user_func($method, $this->_data, View::$_global_data, $this->_file, $options);
}

public function collapse_vars($name, array $list=array(), $include=false, $delete=true) {
  $new = array();
  foreach ( $this->_data as $var=>$val ) {
    if ( in_array($var, $list) ? $include : !$include ) {
      $new[$var] = $val;
    }
  }
  if ( $delete ) {
    $this->_data = array();
  }
  $this->$name = $new;
}

} // End View
