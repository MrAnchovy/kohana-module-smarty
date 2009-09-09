<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Smarty demonstration controller. This controller is not required for Smarty to
 * work with Kohana and should NOT be used in production - it is for demonstration
 * purposes only!
 */
class Controller_Smarty extends Smarty_Controller_Template {

// Disable this controller when Kohana is set to production mode.
// See http://docs.kohanaphp.com/installation/deployment for more details.
const ALLOW_PRODUCTION = FALSE;

// Set the name of the template to use
public $template = 'smarty_demo_page';

public function action_index() {

  $view = Smarty_View::factory('smarty_demo');
  $view->controller = file_get_contents(__FILE__);
  $view->wrapper = file_get_contents(dirname(__FILE__) . '/../../views/smarty_demo_page.tpl');

  $this->template->title = 'Smarty Demonstration';
  $this->template->content = $view;

}

}
