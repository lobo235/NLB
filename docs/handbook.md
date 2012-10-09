NLB Handbook
============

NLB is a PHP web application framework that follows the MVC pattern and is built from the ground up for performance. This is the NLB Handbook which will guide you in your quest to become a master of all things NLB.

Installation
------------

### Requirements

-   Linux/FreeBSD (no Windows support at this time)
-   PHP 5.3 or higher with PDO mysql driver working
-   Apache2 with mod\_rewrite enabled
-   MySQL 5.0+
-   YUI Compressor 2.4.7+ installed on your server [download link](http://yuilibrary.com/download/yuicompressor/)

Installing NLB is not too difficult. Here are the steps to install:

-   Download the NLB code and put it on your server somewhere.
-   Create a new VirtualHost that points to the `www` folder.
-   Copy the `sites/default/config/example.config.inc.php` file to
    `sites/default/config/config.inc.php`.
-   Edit the new `config.inc.php` file to have the necessary settings
    for your application.
-   Visit `http://<yoursite.com>/install.php` changing `<yoursite.com>`
    to your actual domain name.
-   Follow the on-screen instructions to install NLB.
-   Make sure the following directories exist (relative to the NLB root
    folder) and that they are writable by apache:
    -   `logs`
    -   `www/combined-assets`
    -   `smarty/templates_c`
    -   `smarty/cache`

Routes
------

In NLB, routes are used to create a mapping between a particular path
and a controller that should handle that path. Access restrictions can
also be added to a route preventing certain users from accessing certain
areas of your application. NLB has a list of it's own routes in the
`config/nlb_routes.inc.php` file. To define your own routes you must use
the `sites/default/config/routes.inc.php` file. The routes in
`sites/default/config/routes.inc.php` will override routes defined in
`config/nlb_routes.inc.php`. This allows you to change NLB's default
behavior if desired. Below is a simple `routes.inc.php` file defining a
route that would be used for a simple blog post listing:

    <?php
    $routes = array(
        'blog' => array(
            'handler' => 'blog.php?action=list',
            'access' => array('anonymous user'),
        ),
    );

Given that defined route, NLB will route requests to `/blog` to the
`blog.php` controller and will set `$_GET['action']` to `'list'`. It's
important to note that any query string parameters in your handler
definition will be automatically merged with the `$_GET` array,
overwriting any existing keys in the `$_GET` array. Also, in keeping
with PHP's standards, the query string parameters in your handler
definition will also be automatically merged with the `$_REQUEST` array
for consistency. Controllers in the `handlers` directory can be
organized into subdirectories if desired. This helps to keep the code
for larger sites more manageable.

### Dynamic Routes

When defining a route, it is possible to use a wildcard in your path
definition. Using a wildcard allows you to map multiple similar paths to
a single controller. The value of the wildcard can then be passed to the
controller. **Wildcards can be alphanumeric and may also contain
underscores**. There can be multiple wildcards in a path definition.
Take a look at this route definition which would be used for viewing
specific blog posts:

    <?php
    $routes = array(
        'blog/view-%post_id' => array(
            'handler' => 'blog.php?action=viewpost&id=%post_id',
            'access' => array('anonymous user'),
        ),
    );

Using this defined route, NLB will route requests to paths like
`/blog/view-3` and `/blog/view-7` to the `blog.php` controller and will
set `$_GET['action']` to `'viewpost'` and `$_GET['id']` to `'3'` or
`'7'` respectively.

Templates
---------

NLB uses the [Smarty](http://www.smarty.net/) template system. Currently, a bundled version of Smarty 3.x
is included with NLB for your convenience.

When rendering templates, NLB has been configured to first search for
templates in the `sites/default/themes/<your_theme>/` directory. If the
template does not exist there, the `smarty/nlb_templates` directory is
searched. With this configuration, you can override any default template
that comes with NLB by providing your own version of the template. Just
make sure the file has the exact same name as the NLB default template.

Multi-Site Configuration
------------------------

NLB has a feature that allows you to run multiple websites from the same
NLB core. The way this is done is by copying the `sites/default`
directory to `sites/<your_site>` where <your\_site\> is the hostname for
the site you are configuring as a multi-site. NLB will try to match the
hostname in the request to the name of one of the directories in the
`sites` directory. If a match is found, the config file for that
directory will be used instead of the one in the `sites/default/config`
directory. Also, the themes and request handlers in that directory will
be used instead of the ones in `sites/default`. If no match is found,
NLB defaults to using the `sites/default` directory. NLB will also try
to do partial matches against the hostname to try and find a matching
multi-site directory. Below are some examples of how NLB matches
hostnames to directories in `sites`. Given the hostname
`www.example.com`, NLB will look for the following directories in
`sites` (in this order):

1.   www.example.com
1.   example.com
1.   www.example
1.   example
1.   default

Given the hostname `example.com`, NLB will look for the following
directories in `sites` (in this order):

1.   example.com
1.   example
1.   com
1.   default