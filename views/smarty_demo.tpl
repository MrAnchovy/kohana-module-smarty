{* smarty_demo.tpl
 * This template is used to demonstrate Smarty working as a Kohana view.
 *}

<h3>Success!</h3>

<p>The smarty module is installed and working in your Kohana framework. This page should be disabled when Kohana is set to production mode.<p>


<h3>Quick Start</h3>
<ul>
<li>To use Smarty for a view you need to create a Smarty template <code>template_name.tpl</code> in your application's views directory.</li>
<li>To use your template, simply invoke a view using <code>View::factory(smarty:template_name)</code> or <code>new View(smarty:template_name)</code>.</li>
<li>If your controller extends the <code>Controller_Template</code> class, you can use a Smarty template for your page layout too - just set <code>public $template = 'smarty:layout_template_name';</code> in your controller class.</li>
</ul>


<h3>Features</h3>
<table class=".smartyFeatures">
<thead>
<th>PHP Template</th><th>Smarty</th>
</thead>
<tbody style="text-align: left;">

<tr><th colspan="2">Assigning variables and global variables</th></tr>
<tr>
<td>$view->myvar = 'This is a variable'</td>
<td>$view->myvar = 'This is a variable'</td>
</tr>
<tr>
<td>$view->set_global('myglobalvar', 'This is a global variable')</td>
<td>$view->set_global('myglobalvar', 'This is a global variable')</td>
</tr>
<tr>
<td colspan="2">The View class works just the same for a Smarty template.
</tr>

<tr><th colspan="2">Displaying variables</th></tr>

<tr>
<td>&lt;strong&gt;Variable:&lt;/strong&gt; &lt;?php echo $myvar ?&gt;</td>
<td>{literal}&lt;strong>Variable:&lt;/strong&gt; {$myvar}{/literal}</td>
</tr>

<tr>
<td>&lt;strong>Global variable:&lt;/strong&gt; &lt;?php echo $myglobalvar ?&gt;</td>
<td>{literal}&lt;strong&gt;Global variable:&lt;/strong&gt; {$myglobalvar}{/literal}</td>
</tr>

<tr>
<td colspan ="2">Demo: <strong>Variable:</strong> {$myvar} <strong>Global variable:</strong> {$myglobalvar}</td>
</tr>


{php}$old_lang = i18n::$lang; i18n::$lang = 'fr-fr';{/php}
<tr><th colspan="2">Translation</th></tr>

<tr>
<td>&lt;?php echo __('Hello, world!') ?&gt;</td>
<td>{literal}{__ t='Hello, world!'}{/literal}</td>
</tr>

<tr><td colspan="2">Demo (Kohana language forced to French): {__ t='Hello, world!'}</td></tr>
{php}i18n::$lang = $old_lang;{/php}


{php}$old_lang = i18n::$lang; i18n::$lang = 'fr-fr';{/php}
<tr><th colspan="2">Translation with substitution</th></tr>

<tr>
<td>&lt;?php echo __('Welcome back, :user', array(':user' => $username)); ?&gt;</td>
<td>{literal}{__ t='Welcome back, :user' user=$username}{/literal}</td>
</tr>

<tr><td colspan="2">Demo (no example available in default language files): {__ t='Welcome back, :user' user=$username}</td></tr>

{php}i18n::$lang = $old_lang;{/php}

</tbody>
</table>


<h3>How does this magic work?</h3>
<p>The Smarty module creates a <code>View</code> class that extends <code>Kohana_View</code> to provide the parsing of the <code>smarty:</code> template prefix. On rendering, the extended <code>View</code> object uses a static class <code>Render_Smarty</code> to pass the template variables (and the globals) to a singleton instance of the unmodified third party <code>Smarty</code> object (so you can use Smarty methods or update the third party Smarty package at will).</p>

<p>Because the Smarty object is not instantiated until rendering, there is very little overhead in enabling this module if you only want to use Smarty views occasionally.<p>

<p>There is another side effect of the template name parsing. If you create a class <code>Render_Myrenderer</code> you can use the template prefix <code>myrenderer:</code> to use any rendering engine you want. So simply by using <code>$this->template->set_filename('text:')</code> your controller can deliver a text document, with the correct mime type, instead of an HTML page. Rendering classes for text:, json: and file: are included in the Smarty package.</p>

<p>More rendering packages are on their way: any contributions gratefully received</p>
<ul>
<li>XML</li>
<li>RSS</li>
<li>Atom</li>
<li>The <a href="http://dwoo.org">Dwoo</a> template engine</a></li>
</ul>


<h3>More information</h3>

<p>Documentation and support for this module can be found at <a href="http://wiki.github.com/MrAnchovy/kohana-module-smarty">the Kohana Smarty module page on Github</a>. Information and support for Smarty and its standard plugins is of course on the <a href="http://www.smarty.net">Smarty</a> site.</p>

<p>This module is using Smarty version {$smarty.version}. Compare this with the latest version shown on the Smarty <a href="http://www.smarty.net/download.php">Smarty download page</a>.</p>


<h3>Demonstration</h3>

<div>
<p>These are the files that are used for this demo.</p>

<h4>The controller (modules/smarty/controller/smarty.php)</h4>
<pre>
{$controller|htmlspecialchars}
</pre>

<h4>The template file (modules/smarty/views/smarty_demo.tpl)</h4>
<pre>
{$smarty.template|file_get_contents|htmlspecialchars}
</pre>

<h4>The page layout template file (modules/smarty/views/smarty_demo_page.tpl)</h4>
<pre>
{$wrapper|htmlspecialchars}
</pre>
