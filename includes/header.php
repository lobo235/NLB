<?php

require_once('load_config.php');

// Load classes that will be needed on almost every page
class_exists('DatabaseService') || require_once(NLB_LIB_ROOT.'services/DatabaseService.class.php');
class_exists('UIService') || require_once(NLB_LIB_ROOT.'services/UIService.class.php');
class_exists('User') || require_once(NLB_LIB_ROOT.'dom/User.class.php');
class_exists('LogService') || require_once(NLB_LIB_ROOT.'services/LogService.class.php');
class_exists('StringUtils') || require_once(NLB_LIB_ROOT.'util/StringUtils.class.php');

// get an instance of the DatabaseService to communicate/use the database
$DB = DatabaseService::getInstance();

// get an instance of the UIService to be able to render UI components
$UI = UIService::getInstance();

// get an instance of the LogService class to be able to log/email messages
$Log = LogService::getInstance();

$su = StringUtils::getInstance();