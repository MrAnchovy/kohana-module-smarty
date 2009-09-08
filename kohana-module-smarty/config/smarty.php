<?php defined('SYSPATH') OR die('No direct access allowed.');

return array (
  'smarty_class_file' => MODPATH . '/smarty/vendor/smarty/Smarty.class.php',
  'compile_dir'       => Kohana::$cache_dir . '/smarty_compiled',
  'template_extension'=> 'tpl',
);
