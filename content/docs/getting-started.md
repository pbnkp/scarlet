---
title: Getting Started with Scarlet
sidebar: docs/sidebar/getting_started
---

### Assumptions

This guide is designed for newcomers who want to get started with a Scarlet application from scratch. It does not assume that you have any prior experience with Scarlet. However, to get the most out of it, you need to have some prerequisites installed:

* The [PHP](http://php.net/) language version 5.3.2 or higher
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