## Smarty Module for Kohana

This is a module for the [Kohana PHP framework](http://kohanaphp.com/) that
integrates the [Smarty Template Engine](http://www.smarty.net/). It also
provides a framework for other rendering plugins and provides a simple framework
for extensions of the Kohana Template Controller class to return more than one
type of output which is particularly useful in AJAX applications.

v 3.2.3 for Kohana 3

### Quick Start

* To use Smarty for a view you need to create a Smarty template
  `template_name.tpl` in your application's views directory.
* To use your template, simply invoke a view using
  `View::factory(smarty:template_name)` or `new View(smarty:template_name)`.
* If your controller extends the `Controller_Template` class, you can use a
  Smarty template for your page layout too - just set
  `public $template = 'smarty:layout_template_name';` in your controller class.

### How does this magic work?

The Smarty module creates a `View` class that extends `Kohana_View` to provide
the parsing of the `smarty:` template prefix. On rendering, the extended `View`
object uses a static class `Render_Smarty` to pass the template variables (and
the globals) to a singleton instance of the unmodified third party `Smarty`
object (so you can use Smarty methods or update the third party Smarty package
at will).`

Because the Smarty object is not instantiated until rendering, there is very
little overhead in enabling this module if you only want to use Smarty views
occasionally.

There is another side effect of the template name parsing. If you create a class
`Render_Myrenderer` you can use the template prefix `myrenderer:` to use any
rendering engine you want. So simply by using
`$this->template->set_filename('text:')` your controller can deliver a text
document, with the correct mime type, instead of an HTML page. Rendering classes
for text:, json: and file: are included in the Smarty package.

This side effect is not very useful on its own, its strength comes when used
with the Kohana `Controller_Template` class. Because this module decouples
rendering from the View class, the default action of a controller (rendering
some content within a page layout and delivering it as HTML) can easily be
overridden. This enables a single class extending `Controller_Template` to
deliver web pages, AJAX responses, RSS feeds etc.

More rendering packages are on their way - code contributions gratefully received:

* XML
* RSS
* Atom
* The [Dwoo](http://dwoo.org) template engine

### More information

Documentation and support for this module can be found on
[Github](http://wiki.github.com/MrAnchovy/kohana-module-smarty]Information).
Support for Smarty and its standard plugins is of course on the
[Smarty](http://www.smarty.net) site.


### Copyright

The Smarty module is copyright 2009 Mr Anchovy
  <http://www.mranchovy.com> email <mr.anchovy@mranchovy.com>  
Kohana is copyright 2008-2009 Kohana Team <http://kohanaphp.com/license.html>  
Smarty is copyright  copyright 2001-2005 New Digital Group, Inc.

The Smarty module for Kohana is free software:
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
