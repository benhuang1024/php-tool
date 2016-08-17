
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";




--
-- Database: `sperad`
--

-- --------------------------------------------------------

--
-- 表的结构 `mobile_baidu`
--

CREATE TABLE IF NOT EXISTS `mobile_baidu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(400) CHARACTER SET utf8 NOT NULL,
  `url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `rank` varchar(11) CHARACTER SET utf8 NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `mobile_baidu`
--

--
-- 表的结构 `monitor_baidu`
--

CREATE TABLE IF NOT EXISTS `monitor_baidu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(400) CHARACTER SET utf8 NOT NULL,
  `url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `rank` varchar(11) CHARACTER SET utf8 NOT NULL,
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2793 ;

--
-- 转存表中的数据 `monitor_baidu`
--



