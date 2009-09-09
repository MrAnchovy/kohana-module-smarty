<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Acts as an object wrapper for the Smarty template engine, called "views".
 * Variables can be assigned with the view object and referenced locally within
 * the view.
 *
 * @package    SmartyView
 * @author     Mr Anchovy (www.mranchovy.com)
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Smarty_View extends View {

/* config: template_extension
smarty_path
compile_dir

*/
protected $template_extension;
protected $smarty;
public $is_template = false;

public function __construct($file = NULL, array $data = NULL, $ext = NULL) {

  // set the template extension before the parent constructor calls set_filename
  // this can go when core view is patched
  $config = Kohana::config('smarty');
  $this->template_extension = is_null($ext) ? $config->template_extension : $ext;

  parent::__construct($file, $data, $this->template_extension); // ready for patch to core view!

  $this->smarty = Smarty_Smarty::get_smarty(); // load singleton smarty object
}

	/**
	 * Returns a new Smarty_View object.
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  Smarty_View
	 */
	public static function factory($file = NULL, array $data = NULL) {
		return new Smarty_View($file, $data);
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
	public function render($file = NULL, $ext = NULL) {
		if ($file !== NULL) {
			$this->set_filename($file, $ext);
		}

		if (empty($this->_file)) {
			throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
		}

    // Combine local and global data and force it in to smarty
    // (quick and preserves variables bound by reference)
    $global_data = View::$_global_data;
		$this->smarty->_tpl_vars = $this->_data + $global_data;

		if ( $this->smarty->debugging ) {
      // have to do this to fool smarty into sending debugging pop-up
      ob_start();
      $this->smarty->display($this->_file);
      return ob_get_clean();
    } else {
      return $this->smarty->fetch($this->_file);
    }

  }

/**
 * Sets the view filename. This is an exact copy of the parent's method except
 * for the extension - TODO get Kohana core changed so we don't need this.
 *
 * @throws  View_Exception
 * @param   string  filename
 * @return  View
 */
public function set_filename($file, $ext=NULL) {
  if ( is_null($ext) ) {
    $ext = $this->template_extension;
  }
  if (($path = Kohana::find_file('views', $file, $ext)) === FALSE) {
    throw new Kohana_View_Exception('The requested view :file.:ext could not be found', array(
      ':file' => $file, ':ext' => $ext,
    ));
  }

  // Store the file path locally
  $this->_file = $path;

  return $this;
}

}
