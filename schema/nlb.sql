/* entities table */
CREATE TABLE IF NOT EXISTS `entities` (
  `eid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `created_date` datetime DEFAULT NULL,
  `modified_date` datetime DEFAULT NULL,
  `uid` int(11) unsigned NOT NULL,
  `type` varchar(32) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`eid`),
  KEY `uid` (`uid`,`type`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* users table */
CREATE TABLE IF NOT EXISTS `users` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `eid` int(11) unsigned NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `last_login_date` datetime DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* user_roles table */
CREATE TABLE IF NOT EXISTS `user_roles` (
  `urid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `rid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`urid`),
  UNIQUE KEY `uid_rid_unique` (`uid`,`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* page_stats table */
CREATE TABLE IF NOT EXISTS `page_stats` (
  `ptid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `gentime` double DEFAULT NULL,
  `referrer` text NULL,
  `user_agent` text NULL,
  `peak_mem_usage` int(11) unsigned DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ptid`),
  KEY `path` (`path`(255),`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* roles table */
CREATE TABLE IF NOT EXISTS `roles` (
  `rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(32) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `role_name_unique` (`role_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* roles table default contents */
REPLACE INTO `roles` (`rid`, `role_name`) VALUES
(1, 'anonymous user'),
(2, 'authenticated user'),
(3, 'admin user');

/* nodes table */
CREATE TABLE IF NOT EXISTS `nodes` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `eid` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  PRIMARY KEY (`nid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* url_aliases table */
CREATE TABLE IF NOT EXISTS `url_aliases` (
  `path` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  UNIQUE KEY `unique_path_index` (`path`),
  KEY `path` (`path`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* vars table */
CREATE TABLE IF NOT EXISTS `vars` (
  `vid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text,
  PRIMARY KEY (`vid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;