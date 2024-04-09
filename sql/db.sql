CREATE TABLE IF NOT EXISTS `mc_homebrands` (
    `id_slide` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    `img_slide` varchar(25) DEFAULT NULL,
    `order_slide` smallint(5) UNSIGNED NOT NULL,
    PRIMARY KEY (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_homebrands_content` (
  `id_slide_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_slide` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_slide` varchar(125),
  `blank_slide` smallint(1) unsigned NOT NULL default 0,
  `title_slide` varchar(125),
  `desc_slide` text,
  `published_slide` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_slide_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_slide` (`id_slide`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_homebrands_content`
  ADD CONSTRAINT `mc_homebrands_content_ibfk_1` FOREIGN KEY (`id_slide`) REFERENCES `mc_homebrands` (`id_slide`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_homebrands_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `prefix_img`, `resize_img`) VALUES
  (null, 'homebrands', 'homebrands', '250', '122', 'small', 's', 'adaptive'),
  (null, 'homebrands', 'homebrands', '750', '465', 'medium','m', 'adaptive'),
  (null, 'homebrands', 'homebrands', '1000', '1000', 'large','l', 'basic');