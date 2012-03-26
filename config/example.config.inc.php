<?php

// FILE SYSTEM
define('NLB_SITE_ROOT', realpath(dirname(__FILE__).'/..').'/');
define('NLB_LIB_ROOT', NLB_SITE_ROOT.'lib/');

// LOGGING
define('NLB_LOG_DEST_EMAIL', 'test@example.com'); // The email address destination for log messages. Leave blank to disable
define('NLB_LOG_DEST_FILE', NLB_SITE_ROOT.'logs/site.log'); // The file destination for log messages. Leave blank to disable

// DATABASE
define('NLB_MYSQL_HOST', 'localhost');
define('NLB_MYSQL_USER', 'nlb');
define('NLB_MYSQL_PASS', 'password');
define('NLB_MYSQL_DB', 'nlb');

// SMARTY
define('NLB_SMARTY_CLASS_LOC', NLB_LIB_ROOT.'Smarty-3.1.8/libs/Smarty.class.php');
define('NLB_SMARTY_DIR', NLB_SITE_ROOT.'smarty/'); // The directory that holds the smarty configs, templates, templates_c, plugins, and cache directories

// TESTING & DEBUGGING
define('SHOW_ERRORS', TRUE); // makes sure php errors are show on the screen

// PASSWORD HASH SALT
define('PASSWORD_HASH_SALT', 'exampleSaltString');
