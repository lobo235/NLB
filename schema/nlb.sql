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
  `rid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `right` varchar(64) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `uid` (`uid`,`right`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* pagetimes table */
CREATE TABLE IF NOT EXISTS `pagetimes` (
  `ptid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `gentime` double NOT NULL,
  `referrer` text NOT NULL,
  `user_agent` text NOT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`ptid`),
  KEY `path` (`path`(255),`datetime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;