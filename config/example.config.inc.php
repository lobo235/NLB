<?php

// SITE INFORMATION
define('NLB_SITE_NAME', 'NLB Site');

// FILE SYSTEM
define('NLB_SITE_ROOT', realpath(dirname(__FILE__).'/..').'/');
define('NLB_LIB_ROOT', NLB_SITE_ROOT.'lib/');
define('NLB_URL_ROOT', ''); // Only set this value if your app doesn't reside at the domain root. If set, a leading and trailing slash are required.

// LOGGING
define('NLB_LOG_DEST_EMAIL', 'test@example.com'); // The email address destination for log messages. Leave blank to disable
define('NLB_LOG_DEST_FILE', NLB_SITE_ROOT.'logs/site.log'); // The file destination for log messages. Leave blank to disable
define('NLB_LOG_PAGETIMES', TRUE); // Logs the amount of time it takes to generate each page on the site
define('NLB_LOG_MEMORY_USAGE', TRUE); // Logs the amount of memory (peak) used to generate each page on the site

// DATABASE
define('NLB_MYSQL_HOST', 'localhost');
define('NLB_MYSQL_USER', 'nlb');
define('NLB_MYSQL_PASS', 'password');
define('NLB_MYSQL_DB', 'nlb');

// SMARTY
define('NLB_SMARTY_CLASS_LOC', NLB_LIB_ROOT.'Smarty-3.1.8/libs/Smarty.class.php');
define('NLB_SMARTY_DIR', NLB_SITE_ROOT.'smarty/'); // The directory that holds the smarty cache, configs, nlb_templates, plugins, templates, and templates_c directories

// TESTING & DEBUGGING
define('NLB_SHOW_ERRORS', FALSE); // makes sure php errors are show on the screen
define('NLB_DEBUG', FALSE); // Turns debugging mode on (Enables NLB_LOG_PAGETIMES, NLB_LOG_MEMORY_USAGE, and shows some HTML comments with debug info in the page source)

// PASSWORD HASH SALT
define('NLB_PASSWORD_HASH_SALT', 'exampleSaltString');

// SESSIONS
define('NLB_SESSION_LENGTH', 43200); // The length of sessions in seconds. Each time the user visits a page their session will be extended by this amount as well. Default is 43200 (12 hours).

// PATHS TO 3RD PARTY TOOLS/PROGRAMS
define('NLB_BASH_PATH', '/bin/bash');
define('NLB_JAVA_BIN', '/usr/bin/java');
define('NLB_YUI_COMPRESSOR_PATH', NLB_JAVA_BIN.' -jar /Users/lobo235/yuicompressor-2.4.7.jar');
