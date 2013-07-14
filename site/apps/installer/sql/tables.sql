SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(10) NOT NULL,
  `user` varchar(250) CHARACTER SET latin1 NOT NULL,
  `pwd` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  `info` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=135 ;

INSERT INTO `blog` (`id`, `title`, `message`, `info`) VALUES
(1, 'Â¡FELICIDADES!', 'Balero CMS se ha instalado correctamente, si vez ï¿½ste mensaje, la instalaciÃ³n ha sido un Ã©xito.\r\nÃ‰ste mensaje proviene de tu base de datos, puedes editarlo desde tu [panel de administraciÃ³n](?app=admin).\r\n\r\nGracias por utilizar Balero CMS, no olvides visitar la pÃ¡gina oficial. No olvides leer la documentaciÃ³n.\r\n', 'admin @ 2013-07-06 04:57:51'),
(2, 'MARKDOWN', 'Esto es un H1\r\n=============\r\n\r\nEsto es un H2\r\n-------------\r\n\r\n# Esto es un H1\r\n\r\n## Esto es un H2\r\n\r\n###### Esto es un H6\r\n\r\n> Primer nivel.\r\n>\r\n> > Nivel anidado.\r\n>\r\n> Regresando al primer nivel.\r\n\r\nÂ¿MÃ¡s? Revisa la sintaxis de markdown.', 'admin @ 2013-07-06 05:03:48');

CREATE TABLE IF NOT EXISTS `box_content` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `message` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `custom_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) CHARACTER SET latin1 NOT NULL,
  `url_friendly` int(10) NOT NULL,
  `pagination` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `custom_settings` (`id`, `theme`, `url_friendly`, `pagination`) VALUES
(1, 'universe', 1, 5);

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(10) NOT NULL,
  `text` varchar(100) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) CHARACTER SET latin1 NOT NULL,
  `password` int(250) NOT NULL,
  `genre` varchar(250) CHARACTER SET latin1 NOT NULL,
  `avatar` mediumblob NOT NULL,
  `avatar_type` varchar(30) CHARACTER SET latin1 NOT NULL,
  `country` varchar(250) CHARACTER SET latin1 NOT NULL,
  `newsletter` varchar(10) CHARACTER SET latin1 NOT NULL,
  `url` varchar(250) CHARACTER SET latin1 NOT NULL,
  `250` varchar(250) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `virtual_page` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `virtual_title` varchar(250) CHARACTER SET latin1 NOT NULL,
  `virtual_content` text CHARACTER SET latin1 NOT NULL,
  `date` varchar(250) CHARACTER SET latin1 NOT NULL,
  `active` int(10) NOT NULL,
  `visible` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

INSERT INTO `virtual_page` (`id`, `virtual_title`, `virtual_content`, `date`, `active`, `visible`) VALUES
(1, 'Licencia', '> La Licencia de documentaciÃ³n libre de GNU o GFDL (GNU Free \r\nDocumentation License) es una licencia copyleft para > contenido \r\nlibre, diseÃ±ada por la FundaciÃ³n para el software libre (FSF) para el \r\nproyecto GNU. \r\n> Esta licencia, a diferencia de otras, asegura que el material \r\n> licenciado bajo la misma estÃ© disponible de forma completamente libre, \r\n> pudiendo ser copiado, redistribuido, modificado e incluso vendido \r\n> siempre y cuando el material se mantenga bajo los tÃ©rminos de esta misma\r\n> licencia (GNU GFDL). En caso de venderse en una cantidad superior a 100\r\n> ejemplares, deberÃ¡ distribuirse en un formato que garantice futuras \r\n> ediciones (debiendo incluir para ello el texto o cÃ³digo fuente \r\n> original).\r\n> Dicha licencia fue diseÃ±ada principalmente para manuales, libros de \r\n> texto y otros materiales de referencia e institucionales que acompaÃ±aran\r\n> al software GNU. Sin embargo puede ser usada en cualquier trabajo \r\n> basado en texto, sin que importe cuÃ¡l sea su contenido.\r\n', '2013-07-04', 1, 1);

