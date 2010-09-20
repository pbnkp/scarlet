#Welcome to Scarlet

Scarlet is a high-level PHP 5.3 web framework that uses commonly known design patterns such as Active Record, MVC and event-driven programming. Our primary goal is to provide a structured framework that enforces convention over configuration.

__This is alpha software. Things *are* going to change.__


##High Performance

Built with performance in mind, Scarlet is already 3 times faster than Zend Framework 1.10 and 5 times faster than CakePHP. The event-driven nature of Scarlet means that plugin code is loaded and executed only when necessary.


##Extensible & Flexible

Scarlet is built around a hybrid of MVC and event-driven design patterns. Application code utilises the MVC architecture whilst Scarlet plugins can hook into core, and custom, events.

Namespacing is a core component of Scarlet. All core framework code is available in `\Scarlet`, app code in `\App` - plugins define their own namespaces. These namespaces define the application structure and are used extensively by the class autoloader.

The Scarlet event dispatcher allows both plugin and application code to redefine core framework code, add event listeners and define custom events. Anonymous (lambda) functions are another core component of Scarlet.

Want to define your own custom Router? Need to change the way controllers are named? Or maybe you just want to add an extra layer of caching to the views? No problem, the event dispatcher can do all this and more.


##Requirements

Scarlet is only supported on PHP 5.3.2 and up. It will not run on any versions of that do not support namespaces or anonymous functions.