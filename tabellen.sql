-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hingelogd`
-- 

CREATE TABLE IF NOT EXISTS `Hingelogd` (
  `nr` int(11) NOT NULL auto_increment,
  `stamnr` smallint(6) NOT NULL default '0',
  `tijd` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`nr`),
  UNIQUE KEY `nr` (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=824 DEFAULT CHARSET=latin1 AUTO_INCREMENT=824 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hleerlingen`
-- 

CREATE TABLE IF NOT EXISTS `Hleerlingen` (
  `stamnr` smallint(4) unsigned NOT NULL default '0',
  `voornaam` varchar(20) NOT NULL default '',
  `tussenvoegsel` varchar(7) default NULL,
  `achternaam` varchar(20) NOT NULL default '',
  `klas` varchar(5) default NULL,
  `locatie` varchar(20) NOT NULL default '',
  `wachtwoord` varchar(10) default NULL,
  `woonplaats` varchar(30) default NULL,
  `loopbaan` varchar(60) default NULL,
  PRIMARY KEY  (`stamnr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hllnomschr`
-- 

CREATE TABLE IF NOT EXISTS `Hllnomschr` (
  `nr` int(11) NOT NULL auto_increment,
  `van` int(11) NOT NULL default '0',
  `voor` int(11) NOT NULL default '0',
  `omschrijving` blob,
  PRIMARY KEY  (`nr`)
) ENGINE=MyISAM AUTO_INCREMENT=357 DEFAULT CHARSET=latin1 AUTO_INCREMENT=357 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hlog`
-- 

CREATE TABLE IF NOT EXISTS `Hlog` (
  `tijd` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ipnummer` varchar(15) default NULL,
  `postdata` longblob,
  `rest` longblob,
  `stamnr` smallint(6) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hpersoneel`
-- 

CREATE TABLE IF NOT EXISTS `Hpersoneel` (
  `nr` int(11) NOT NULL default '0',
  `naam` varchar(30) default NULL,
  `locatie` varchar(20) default NULL,
  `achternaam` varchar(30) default NULL,
  `voorvoegsel` varchar(4) default NULL,
  PRIMARY KEY  (`nr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hexboekdata`
-- 

CREATE TABLE IF NOT EXISTS `Hexboekdata` (
  `stamnr` smallint(4) unsigned NOT NULL,
  `fotoakkoord` boolean default FALSE,
  `tekst1` blob,
  `tekst2` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

-- 
-- Tabel structuur voor tabel `Hdocomschr`
-- 

CREATE TABLE IF NOT EXISTS `Hdocomschr` (
  `van` smallint(4) unsigned NOT NULL,
  `voor` int(11) NOT NULL,
  `omschrijving` blob
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;
