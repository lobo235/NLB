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

/* user_rights table */
CREATE TABLE IF NOT EXISTS `user_rights` (
  `urid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `rid` int(11) unsigned NOT NULL,
  PRIMARY KEY (`urid`),
  UNIQUE KEY `uid_rid_unique` (`uid`,`rid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* page_stats table */
CREATE TABLE IF NOT EXISTS `page_stats` (
  `ptid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `gentime` double DEFAULT NULL,
  `referrer` text NOT NULL,
  `user_agent` text NOT NULL,
  `peak_mem_usage` int(11) unsigned DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ptid`),
  KEY `path` (`path`(255),`datetime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* rights table */
CREATE TABLE IF NOT EXISTS `rights` (
  `rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `right_name` varchar(32) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `right_name_unique` (`right_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* rights table default contents */
INSERT INTO `rights` (`rid`, `right_name`) VALUES
(1, 'anonymous user'),
(2, 'authenticated user'),
(3, 'admin user');