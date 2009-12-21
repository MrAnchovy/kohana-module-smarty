<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Smarty module for Kohana 3
 * - <a href="http://wiki.github.com/MrAnchovy/kohana-module-smarty">homepage</a>
 *
 * Override the Kohana View class to provide template name parsing and independent
 * rendering.
 *
 * @package    Smarty
 * @author     Mr Anchovy (mr dot anchovy at mranchovy dot com)
 * @copyright  (c) 2009 MrAnchovy (http://www.mranchovy.com)
 * @license    http://kohanaphp.com/license.html
 */
class View extends Kohana_View {

/**
 * @var  string  Name of rendering class to use
 */
protected $_renderer;

/**
 * @var  string  Name of default renderer
 */
protected $_default_renderer = 'parent';

/**
 * @var  string  Configuration
 */
protected $_config = array();

public function __construct($file = null, array $data = null) {
  $token = Kohana::$profiling ? Profiler::start('renderer', 'new kohana view') : false;

  $this->_config = Kohana::config('render');

  parent::__construct($file, $data);
  $token ? Profiler::stop($token) : null;

}

/**
 * Parse a template name and set the renderer and filename.
 *
 * @throws  View_Exception (parent)
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

  // REVISIT we should implement this in the renderer
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

} // End View
