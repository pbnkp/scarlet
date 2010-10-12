---
title: Layouts &amp; Rendering in Scarlet
sidebar: docs/sidebar/layouts-and-rendering-in-scarlet
---

### Overview

This guide outlines the interaction between the Controller and View. When it's time to send a response back to the user, the Controller normally hands things over to the View.

Usually this involves deciding what should be sent as a response and calling an appropriate method to create that response. If the response is a full-blown view, Scarlet will wrap the view in a layout and possibly pulls in partial views.


### Creating Responses

From the controller's point of view, there are three ways to create an HTTP response:

* Do nothing at all.
* Call <code>$this->render</code> to create a full response to send back to the browser. ***[Currently unstable, will be added in a future release]***
* Call <code>$this->redirect</code> to send an HTTP redirect status code to the browser. ***[Currently unstable, will be added in a future release]***


#### Rendering by Default

Default rendering is an excellent example of "convention over configuration" (as demonstrated by Rails). Implementing this, Scarlet, by default, will automatically render views with names that correspond to valid routes. For example, if you have this code in your `ProductController` class:

<pre><code class="language-php">
namespace App\Controllers;
class ProductController extends ApplicationController
{
    
}
</code></pre>

And you have a view file in `app/view/product/index.html.php`:

<pre><code class="language-html">
&lt;h1&gt;Some products will be coming soon!&lt;/h1&gt;
</code></pre>

Scarlet will automatically render `app/view/product/index.html.php` when you navigate to `/product` and you will see on your screen that "Some products will be coming soon!".


### Using Layouts

When we render a view as a response, it does so by combining the view with a layout. Within a layout, and even from within a view, you have access to four tools for combining different bits of output to form the overall response:

* `data`
* `content`
* Partials
* Helpers


<h4 id="understanding-data">Understanding <code>data</code></h4>

Scarlet places all data returned by your controller into the `$this->data` array.


<h4 id="understanding-content">Understanding <code>content</code></h4>

Within the context of a layout, `$this->content()` identifies a section where content from the view should be inserted. The simplest way to use this is to have a single `content`, into which the entire contents of the view currently being rendered is inserted:

<pre><code class="language-php">
&lt;html&gt;
    &lt;head&gt;
    &lt;/head&gt;
    
    &lt;body&gt;
        &lt;?php echo $this->content(); ?&gt;
    &lt;/body&gt;
&lt;/html&gt;
</code></pre>

You can also create layouts with multiple `content` sections:

<pre><code class="language-php">
&lt;html&gt;
    &lt;head&gt;
    &lt;?php echo $this->content_for('head'); ?&gt;
    &lt;/head&gt;
    
    &lt;body&gt;
        &lt;?php echo $this->content(); ?&gt;
    &lt;/body&gt;
&lt;/html&gt;
</code></pre>

The main body of the view will always render into the unnamed `content`. To render content into a named section, you use the `content_for` method.


<h4 id="using-content_for">Using <code>content_for</code></h4>

The `content_for` method allows you to insert content into a `content_for` block in your layout. You use `content_for` for both assigning and inserting content. For example, this view would work with the layout you just saw:

<pre><code class="language-php">
&lt;?php $this->content_for('head', '&lt;title&gt;Scarlet&lt;/title&gt;'); ?&gt;

&lt;p&gt;Hello, World!&lt;/p&gt;
</code></pre>

And would be rendered as:

<pre><code class="language-php">
&lt;html&gt;
    &lt;head&gt;
        &lt;title&gt;Scarlet&lt;/title&gt;
    &lt;/head&gt;
    
    &lt;body&gt;
        &lt;p&gt;Hello, World!&lt;/p&gt;
    &lt;/body&gt;
&lt;/html&gt;
</code></pre>

The `content_for` method is very helpful when your layout contains distinct regions, such as sidebars and footers, that should get their own page specific blocks of content inserted.


#### Using Partials

Partial template - usually just called "partials" - are another device for breaking apart the rendering process into more manageable chunks. With a partial, you can move code for rendering a particular piece of a response into it's own file.

Layouts, views and partials can all render partials.


##### Rendering Partials

To render a partial as part of a view you use the `partial` method and include the name of the partial as an argument.

<pre><code class="language-php">
&lt;?php echo $this->partial('menu'); ?&gt;
</code></pre>

This will render a file named `_menu.html.php` at that point within the view being rendered.  Note the leading underscore character: partials are named with a leading underscore to distinguish them from regular views, even though they are referred to without the underscore. This holds true even when you're pulling in a partial from another folder:

<pre><code class="language-php">
&lt;?php echo $this->partial('shared/menu'); ?&gt;
</code></pre>

This code will pull in the partial from `app/views/shared/_menu.html.php`.


##### Using Partials to Simplify Views

One way to use partials is to treat them as the equivalent of subroutines: as a way to move details out of the view so that you can grasp what's going on more easily.

Furthermore, this <abbr title="Don't Repeat Yourself">DRY</abbr>s up your code nicely as you can now reuse blocks of code between views. You no longer have to worry about the details of these sections when you're concentrating on a particular page.


##### Passing Local Variables

You can also pass local variables into partials, making them even more powerful and flexible:

<pre><code class="language-php">
&lt;?php
    echo $this->partial('menu', array(
        'current_page' => $this->data['current_page'],
    ));
?&gt;
</code></pre>




