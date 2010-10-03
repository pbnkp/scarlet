---
title: Creating and Handling Events
sidebar: docs/sidebar/creating-and-handling-events
---

### Introduction to Events

Scarlet is a loosely event-driven framework. What this means is that whilst the core functionality of Scarlet is designed around the MVC paradigm we also provide an event-based interface that you can develop on.

This allows you to write helpers and plugins that can integrate deeply with both the core and application functionality without having to modify any existing code.


### Creating Event Listeners

All events, and their listeners, are handled by the <code>\Scarlet\Reactor</code> class. Registering an event listener is as simple as:

<pre><code lang="php">
\Scarlet\Reactor::bind($your_event, $your_callback);
</code></pre>

where <code>$your_event</code> is the name of the event you want to bind to (for example, <code>app.user.account.created</code>) and <code>$your_callback</code> is one of the following:

* **A lambda function** &ndash; perhaps the simplest way to add a custom event listener.
* **An instance method** &ndash; should be passed to the <code>bind</code> method as an array in the form <code>array($object, 'method_name')</code>.
* **A class method** &ndash; should be passed to the <code>bind</code> method as a string e.g. <code>\YourClass::static_method</code>. Make sure parentheses are removed and your class is fully namespaced.

The <code>bind</code> method also takes a third, optional, parameter called <code>$priority</code>. This is an integer value. Callbacks are executed in the order of priority i.e. priority 100 will get executed before priority 20. The default priority is 50.

*For example:*
<pre class="no-margin-top"><code lang="php">
\Scarlet\Reactor::bind('your.event', function(){
    echo 'This will get executed last';
});

\Scarlet\Reactor::bind('your.event', function(){
    echo 'This will get executed first';
}, 100);
</code></pre>

Some events pass arguments to their callbacks. These arguments are passed to the callbacks as an array. **It is currently not possible to inject return values from a listener back into the framework.**


### Creating Custom Events

It is extremely easy to create your own events. Scarlet does not require you to define your events in advance, all you have to do is call a single method:

<pre><code lang="php">
\Scarlet\Reactor::fire('your.event');
</code></pre>

Additionally, you can pass arguments over to the listeners by:

<pre><code lang="php">
\Scarlet\Reactor::fire('your.event', $arg_1, $arg_2, ... , $arg_n);
</code></pre>

Currently, event listeners cannot return values. This is something that may be fixed in a future release.