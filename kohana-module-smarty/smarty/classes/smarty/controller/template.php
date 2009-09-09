<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Controller class for automatic templating with the Smarty template engine.
 */
class Smarty_Controller_Template extends Kohana_Controller_Template {

public $template = 'page';

  /**
	 * Loads the template View object.
	 *
	 * @return  void
	 */
	public function before()
	{
		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = Smarty_View::factory($this->template);
      $this->template->is_template = true; // tell Smarty_View that this is a page type template
		}
	}
}
