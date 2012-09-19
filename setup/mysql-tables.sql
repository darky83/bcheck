SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `backupattempt`
-- ----------------------------
DROP TABLE IF EXISTS `backupattempt`;
CREATE TABLE `backupattempt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `mailMessageId` bigint(20) NOT NULL,
  `result` char(1) NOT NULL,
  `backupDate` datetime NOT NULL,
  `created` datetime NOT NULL,
  `serverId` bigint(20) DEFAULT NULL,
  `backupvalidatorId` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `backupserver`
-- ----------------------------
DROP TABLE IF EXISTS `backupserver`;
CREATE TABLE `backupserver` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `serverName` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `altServerName` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for `backupvalidator`
-- ----------------------------
DROP TABLE IF EXISTS `backupvalidator`;
CREATE TABLE `backupvalidator` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `backupType` varchar(200) NOT NULL,
  `successRegex` varchar(1000) NOT NULL,
  `failureRegex` varchar(1000) NOT NULL,
  `backupServerId` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `mailmessage`
-- ----------------------------
DROP TABLE IF EXISTS `mailmessage`;
CREATE TABLE `mailmessage` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `message` longtext,
  `subject` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;