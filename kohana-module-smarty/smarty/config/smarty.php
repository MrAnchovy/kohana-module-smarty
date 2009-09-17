<?php defined('SYSPATH') OR die('No direct access allowed.');

/* To override any of Smarty's configuration, use the same format as below in
 * application/config/smarty.php. 
 */

return array (

  'smarty_class_file' => MODPATH.'smarty/thirdparty/smarty/Smarty.class.php',

  // some smarty settings need to be dealt with separately...

  // this is perhaps more useful than the default _PASSTHRU or _REMOVE or _ALLOW
  // but needs special handling because the constants are not defined yet
  'php_handling'    => 'SMARTY_PHP_QUOTE', 

  // ... but most can be overridden in this automagically handled array
  'smarty_config'   => array(

    // we can use Kohana's cache directory for our compiled templates
    'compile_dir'     => Kohana::$cache_dir . '/smarty_compiled',
    // ... and the smarty cache (only used if it is enabled)
    'cache_dir'       => Kohana::$cache_dir . '/smarty_compiled',

    // TODO think about some theme overriding. At the moment the Kohana interface
    // provides an absolute path to template files, but the path here needs to be
    // set for the smarty {include} function
    'template_dir'    =>  array(
      APPPATH.'views',
      MODPATH.'smarty/views',
    ),

    // TODO need to create some useful Kohana plugins. Investigate whether
    // pre-registering gives a meaningful speed gain.
    'plugins_dir'     =>  array(
      APPPATH.'smarty_plugins',
      MODPATH.'smarty/smarty_plugins',
      'plugins',
    ),

    // If you want to use smarty config files, put them in this place
    'config_dir'      =>  APPPATH.'smarty_config',

    // useful when developing, override to false in your application's config
    // for a small speed increase
    'compile_check'   => true,

    // use the current error reporting level rather than Smarty's none
    'error_reporting' => error_reporting(),

    // Is the debugging console useful in a Kohana environment?
    'debugging'       =>  false,
    
    // override this to URL to enable debug console by using SMARTY_DEBUG in the request
    'debugging_ctrl'  =>  'NONE',

    // TODO investigate whether smarty caching is useful in a Kohana environment
    'caching'         =>  0,

    // smarty security is not useful unless you are going to parse user input as smarty templates
    'security'        =>   false,

    // use different delimiters throughout your templates if you really want to!
    'left_delimiter'  =>  '{',
    'right_delimiter' =>  '}',

    // this could be useful if there are very many templates
    'use_sub_dirs'    => false, 

    // use the smarty_modifier_default if you want to be lazy about setting template variables
    'default_modifiers' => array(),

  ),

);
