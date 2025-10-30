-- ============================================================================
-- Module      : imenu.sql
-- Version     : 3.0R0.0
-- PHP version : MySQL 5+/10.4.27-MariaDB
--
-- Author      : Denis Patrice <denispatrice@yahoo.com>
-- Copyright   : Copyright (c) Denis Patrice Dipl.-Ing. 2024
--               All rights reserved
--
-- Application : imenu
-- Description : Database
--
-- Date+Time of change   By     Description
-- --------------------- ------ ----------------------------------------------
-- 12-May-24 00:00 WIT   Denis  Deployment V. 2024 "LEO MALET"
--
-- ============================================================================

DROP TABLE IF EXISTS `imenu_services`;
CREATE TABLE IF NOT EXISTS `imenu_services` (

	`recno`   INTEGER NOT NULL AUTO_INCREMENT,
	`updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
	          ON UPDATE CURRENT_TIMESTAMP,

	`service_id`   VARCHAR(16)  NOT NULL DEFAULT '',
	`intentURL`    VARCHAR(200) NOT NULL DEFAULT '',
	`service_name` VARCHAR(32)  NOT NULL DEFAULT '',
	`description`  TEXT NOT NULL,

  PRIMARY KEY (`recno`),
	
	KEY ximenu_services1 (`updated`),
	KEY ximenu_services2 (`service_id`),
	KEY ximenu_services3 (`service_name`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `imenu_sessions`;
CREATE TABLE IF NOT EXISTS `imenu_sessions` (

	`recno`   INTEGER NOT NULL AUTO_INCREMENT,
	`updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
	          ON UPDATE CURRENT_TIMESTAMP,

	`service_id` VARCHAR(8)  NOT NULL DEFAULT '',
	`user_id`    VARCHAR(20) NOT NULL DEFAULT '',
	`node_id`    VARCHAR(16) NOT NULL DEFAULT '',

  PRIMARY KEY (`recno`),
	
	KEY ximenu_sessions1 (`updated`),
	KEY ximenu_sessions2 (`service_id`),
	KEY ximenu_sessions3 (`user_id`),
	KEY ximenu_sessions4 (`node_id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS `imenu_session_variables`;
CREATE TABLE IF NOT EXISTS `imenu_session_variables` (

	`recno`   INTEGER NOT NULL AUTO_INCREMENT,
	`updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
	          ON UPDATE CURRENT_TIMESTAMP,

	`service_id` VARCHAR(8)   NOT NULL DEFAULT '',
	`user_id`    VARCHAR(20)  NOT NULL DEFAULT '',
	`node_id`    VARCHAR(16)  NOT NULL DEFAULT '',
	`variable`   VARCHAR(20)  NOT NULL DEFAULT '',
	`value`      VARCHAR(100) NOT NULL DEFAULT '',

 PRIMARY KEY (`recno`),
	
	KEY ximenu_session_variables1 (`updated`),
	KEY ximenu_session_variables2 (`service_id`),
	KEY ximenu_session_variables3 (`user_id`),
	KEY ximenu_session_variables4 (`node_id`),
	KEY ximenu_session_variables5 (`variable`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- End of file: imenu.sql
-- ============================================================================
