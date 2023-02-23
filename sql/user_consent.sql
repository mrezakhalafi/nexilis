CREATE TABLE `USER_CONSENT` (
  `KEY` varchar(64) DEFAULT NULL COMMENT 'consent name',
  `F_PIN` varchar(20) DEFAULT NULL,
  `VALUE` int(11) DEFAULT NULL COMMENT 'consent yes/no',
  UNIQUE KEY `UNIQ1` (`KEY`,`F_PIN`)
)