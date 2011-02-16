NerdyIsBack Plugin Framework
============================
Current Version: 0.5

   The NerdyIsBack Plugin framework began as a collection of classes for
   managing custom post types, meta boxes, and taxonomies for WordPress.
   When I realized I was using the same four classes in almost every
   WordPress project I developed, I decided it was time to DRY things up a
   little. The NerdyIsBack Plugin Framework aims to build on the WordPress
   Plugin API (not replace it) and bring better support for
   object-oriented architecture to the plugin development process.

Objectives

1. Ease object-oriented plugin development
2. Encourage separation of code
3. MVC implementation for WordPress administration panels

Writeup/Tutorial
The (limited) docs for the NIB Plugin Framework can be found at
http://www.nerdyisback.com/projects/nerdyisback-plugin-framework/

TODO for 0.6:
* Improve Documentation
* Create HTMLElement Composite class (and children) with Presenter
object for HTML representation.
* Refactor FormTablePresenter to utilize HTMLElement
