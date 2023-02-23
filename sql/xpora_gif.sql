CREATE TABLE `XPORA_GIF` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FILENAME` varchar(255) NOT NULL,
  `URL` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4


insert into `XPORA_GIF` (`ID`, `FILENAME`, `URL`) values('1','pulsa-1.gif','https://www.tokopedia.com/pulsa');
insert into `XPORA_GIF` (`ID`, `FILENAME`, `URL`) values('2','pulsa-2.gif','https://www.tokopedia.com/pulsa');
insert into `XPORA_GIF` (`ID`, `FILENAME`, `URL`) values('3','bni-ad.gif','https://bni.co.id');