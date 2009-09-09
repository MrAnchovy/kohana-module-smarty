{* smarty_demo.tpl
 * This template is used to demonstrate Smarty working as a Kohana view.
 *}
<h1>Success!</h1>

<h3>The smarty module is installed and working in your Kohana framework.</h3>

<p>This page will be disabled when Kohana is set to production mode.</p>

<p>Documentation and support for this module can be found at <a href="http://wiki.github.com/MrAnchovy/kohana-module-smarty">the Kohana Smarty module page on Github</a>.</p>

<p>This module is using smarty version {$smarty.version}. Compare this with the latest version shown on the <a href="http://www.smarty.net/download.php">download page at the Smarty site</a>.</p>

The files that are used for this demo are shown below. Note that Smarty can be used for views and template controllers independently.

<ul>
<li>To use Smarty for a view simply invoke that view using <code>Smarty_View::factory(template_name)</code> or <code>new Smarty_View(template_name)</code> and create a Smarty template <code>template_name.tpl</code> for your view in your application's views directory  You can mix Smarty views with normal Kohana views in the same controller if you want.</li>

<li>To use the Smarty template controller simply define your controller as
<code>class Controller_MyController extends Smarty_Controller_Template</code>
and create a Smarty template <code>page.tpl</code> for your page in your application's views directory. If you want to use a different name, follow the example below.</li>
</ul>

<h3>Controller (modules/smarty/controller/smarty.php)</h3>
<pre>
{$controller|htmlspecialchars}
</pre>

<h3>View template file (modules/smarty/views/smarty_demo.tpl)</h3>
<pre>
{$smarty.template|file_get_contents|htmlspecialchars}
</pre>

<h3>Page template file (modules/smarty/views/smarty_demo_page.tpl)</h3>
<pre>
{$wrapper|htmlspecialchars}
</pre>
