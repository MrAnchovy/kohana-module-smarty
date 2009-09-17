<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (

  // template renderers and their filename extensions
  'extensions' => array(
    'smarty' => 'tpl',
    'text' => true, // enabled, but doesn't use templates
    'json' => true, // enabled, but doesn't use templates
  ),

  'expose' => array('title', 'content'),

);
