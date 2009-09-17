<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Smarty demonstration controller. This controller is not required for Smarty to
 * work with Kohana and should NOT be used in production - it is for demonstration
 * purposes only!
 */
class Controller_Smarty extends Controller_Template {

// Disable this controller when Kohana is set to production mode.
// See http://docs.kohanaphp.com/installation/deployment for more details.
const ALLOW_PRODUCTION = FALSE;

// Set the name of the template to use
public $template = 'smarty:smarty_demo_page';

public function action_index() {

  $view = View::factory('smarty:smarty_demo');
  $view->controller = file_get_contents(__FILE__);
  $view->wrapper = file_get_contents(dirname(__FILE__) . '../../../views/smarty_demo_page.tpl');

  $this->template->title = 'Smarty Demonstration';
  $this->template->content = $view;

}


public function action_demo_text() {

  $this->template->content = file_get_contents(__FILE__);
  $this->template->set_filename('text:');
  $this->template->render();
}

public function action_demo_json() {

  // we can either put the data explicitly in a variable called _data, or do this
  // which is useful if the same controller is used for HTML and AJAX
  $this->template->title = 'This page has been updated';
  $this->template->content = 'This content has been delivered with <strong>AJAX</strong> - beware it contains &lt;script&gt; tags <script>alert(\'Told you so!\')</script>';
  $this->template->hidden = 'This page has been updated';
//  $this->template->_no_header = true;
  $this->template->set_filename('json:');
  $this->template->render();
}

}
