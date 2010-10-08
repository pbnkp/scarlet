---
title: Getting Started with Scarlet
sidebar: docs/sidebar/getting_started
---

### Assumptions

This guide is designed for newcomers who want to get started with a Scarlet application from scratch. It does not assume that you have any prior experience with Scarlet. However, to get the most out of it, you need to have some prerequisites installed:

* The [PHP](http://php.net/) language version 5.3.2 or higher
* A working installation of the [Apache HTTP server](http://httpd.apache.org/) (or equivalent)
* A working installation of the [MySQL database](http://mysql.com/)

Scarlet is a web application framework running on the PHP programming language. If you have no prior experience with PHP you will find a steep learning curve diving straight into Scarlet. However, Scarlet leans on common design patterns found in other frameworks (e.g. [Rails](http://rubyonrails.com/)) so in some cases you may find the learning curve not quite so steep.


### What is Scarlet?

Scarlet is a web application framework written in the PHP programming language. It is designed to make programming web applications easier by making assumptions about what every developer needs to get started.

Scarlet is opinionated software. It makes the assumption that there is a "best" way to do things, and is designed to encourage that way. However, more experienced developers will find a reasonable amount of flexibility in application design and structure.

The Scarlet philosophy includes several guiding principles:

* **DRY** &ndash; "Don't Repeat Yourself" &ndash; suggests that writing the same code over and over again is a bad thing.
* **Convention Over Configuration** &ndash; means that Scarlet makes assumptions about what you want to do and how you're going to do it, rather than requiring you to specify every little thing through endless configuration files.
* **REST is the best pattern for web applications** &ndash; organising your application around resources and standard HTTP verbs is the fastest way to go.


#### The MVC Architecture

At the core of Scarlet is the Model, View, Controller architecture, usually just called MVC. MVC benefits include:

* Isolation of business logic from the user interface
* Ease of keeping code DRY
* Making it clear where different types of code belong for easier maintenance


##### Models

A model represents the information (data) of the application and the rules to manipulate that data. In the case of Scarlet, models are primarily used for managing the rules of interaction with a corresponding database table. In most cases, one table in your database will correspond to one model in your application. The bulk of your application's business logic will be concentrated in the models.


##### Views

Views represent the user interface of your application. In Scarlet, views are often HTML files with embedded PHP code that performs tasks related solely to the presentation of the data. Views handle the job of providing data to the web browser or other tool that is used to make requests from your application.


##### Controllers

Controllers provide the "glue" between models and views. In Scarlet, controllers are responsible for processing the incoming requests from the web browser, interrogating models for data and passing that data on to the views for presentation.


#### The Components of Scarlet

Scarlet has been designed to be modular so, even though the various modules ship together, there's no reason why components can't be removed or replaced. The shipping components are:

* Scarlet Core
* Citrus ORM
* Scarlet Helpers


##### Scarlet Core

Scarlet Core provides the key functionality to the framework including the "VC" part of "MVC", Routing and Event Handling.


##### Citrus ORM

Citrus <abbr title="Object Relational Mapper">ORM</abbr> provides the "M" of "MVC" and has been developed specifically for Scarlet and PHP 5.3. It should be possible to drop in other ORMs if Citrus isn't your cup of tea.


##### Scarlet Helpers

Scarlet Helpers provide functionality to your view and layouts - the "V" of "MVC".


#### REST

REST stands for Representational State Transfer and is the foundation of the RESTful architecture. REST in terms of Scarlet boils down to two main principles:

* Using resource identifiers such as URLs to represent resources.
* Transferring representations of the state of that resource between system components.

For example, to a Scarlet application a request such as this:

<pre><code>
DELETE /photos/17
</code></pre>

would be understood to refer to a photo resource with the ID of 17, and to indicate a desired action - deleting that resource. REST is a natural style for the architecture of web applications, and Scarlet hooks into this shielding you from many of the RESTful complexities and browser quirks.


### Creating a New Scarlet Project

In this guide we're going to create our own version of the weblog created in the Rails Getting Started guide. Before you can start building the application you're going to have to download a copy of Scarlet:

* **Edge Scarlet (unstable)** &ndash; [http://github.com/mattkirman/scarlet/zipball/master](http://github.com/mattkirman/scarlet/zipball/master)

Once you've downloaded the Scarlet source, extract it into a folder called `blog`.

> You will find Scarlet development on Windows is less pleasant than on other
> operating systems. If at all possible, we suggest that you install a Linux
> virtual machine and use that for Scarlet development instead of using Windows.


#### Creating the Blog Application

The best way to use this guide is to follow each step as it happens, no code or step needed to make this application has been left out, so you can literally follow along step by step.

To begin, open a terminal, navigate to a folder where you have rights to create files, and copy the Scarlet source files into a new folder:

<pre><code class="language-bash">
$ mkdir blog
$ cp -R path_to_scarlet_source blog
</code></pre>

This will create a Scarlet application in a directory called blog. After you create the blog application, switch to it's folder to continue work directly in that application:

<pre><code class="language-bash">
$ cd blog
</code></pre>

Open up that folder and explore it's contents. Most of the work in this tutorial will happen in the <code>app/</code> folder, but here's a basic rundown on the function of each folder that is included in a default Scarlet application:

<table>
    <thead>
        <tr>
            <th>File/Folder</th>
            <th>Purpose</th>
        </tr>
    </thead>
    
    <tbody>
        <tr>
            <td>scarlet</td>
            <td>A command line tool to help with scaffolding applications, running tests, etc.</td>
        </tr>
        
        <tr>
            <td>app/</td>
            <td>Contains the controllers, models and views for your application.</td>
        </tr>
        
        <tr>
            <td>config/</td>
            <td>Configure your application's runtime rules, routes, database and more.</td>
        </tr>
        
        <tr>
            <td>db/</td>
            <td>Shows your current database schema as well as the database migrations.</td>
        </tr>
        
        <tr>
            <td>log/</td>
            <td>Application log files.</td>
        </tr>
        
        <tr>
            <td>public/</td>
            <td>The only folder show to world as-is. This is where your images, javascript, stylesheets (CSS) and other static files go.</td>
        </tr>
        
        <tr>
            <td>test/</td>
            <td>Unit tests, fixtures and other test apparatus.</td>
        </tr>
        
        <tr>
            <td>vendor/</td>
            <td>A place for all third-party code including the Scarlet Core and third-party plugins.</td>
        </tr>
    </tbody>
</table>


#### Configuring a Database

The vast majority of web applications will use a database. Citrus, the Scarlet ORM, has been designed to support many different database engines and server setups. Your database configuration is specified in `config/database.php`. If you open this file you'll see a default database configuration using a single MySQL server.

By default, Scarlet can run in one of three different modes. For each of these environments you can define specific database configurations. These environments are:

* **`development`** &ndash; used on your development computer as you develop your application.
* **`test`** &ndash; used to run tests on your application. *Make sure that this database is different to both your `development` and `production` databases as the `test` database is erased and regenerated every time you run a test*.
* **`production`** &ndash; used when you deploy your application for the world to use.


##### Configuring a MySQL Database

For a typical application the default configuration will be adequate, obviously you will still have to set the parameters to match your database configuration:

<pre><code class="language-php">
Citrus\Base::config(function($db){
    $db->addConnection('development', array(
        'adapter' => 'mysql',
        'encoding' => 'UTF-8',
        'database' => 'scarlet',
        'username' => 'root',
        'password' => 'root',
        'host' => 'localhost',
    ));
});
</code></pre>


> ###### Setting up Master / Slave Databases
> 
> For more complex setups Citrus provides MySQL master/slave capabilities out of the box. Once you've set up your database servers for replication you can then use two optional configuration parameters to tell Citrus how to connect to them:
> 
> <pre><code class="language-php">
> Citrus\Base::config(function($db){
>     // Set up the master server
>     $db->addConnection('development', array(
>         ...
>         'role' => 'master',
>         'priority' => 1,
>     ));
>     
>     // And set up the slave
>     $db->addConnection('development', array(
>         ...
>         'role' => 'slave',
>         'priority' => 3,
>     ));
> });
> </code></pre>
> 
> `role` is pretty self-explanatory however, `priority` is somewhat more complex. Occasionally you may want to send more database queries to a particular server rather than splitting the traffic 50/50. The configuration shown above will send 3 out of 4 read requests to the slave.
> 
> There is technically no limit to the number of slaves you can add to your configuration - if Citrus is unable to connect to a slave it'll just move on to another. Also, Citrus is smart enough to automatically switch to your master database for the duration of the request whenever you modify data.


#### Creating the Database

Now that you have your database configured, it's time to have Scarlet create an empty database for you. You can do this by running the `scarlet` command from the application root:

<pre><code class="language-bash">
$ ./scarlet db create
</code></pre>

This will create your development and test databases on the servers defined in your database configuration.


### Hello, Scarlet!

The traditional way to get started with a new language is by getting some text up on screen quickly. To do this, just point your Apache server at the root of your application.

