NLB Handbook
============

NLB is a PHP application framework that follows the MVC pattern and is built from the ground up for performance. This is the NLB Handbook which will guide you in your quest to become a master of all things NLB.

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
