CREATE TABLE `pages` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `content` longtext NOT NULL,
  `title` varchar(255) NOT NULL
) 
  FULLTEXT KEY `ft_pages` (`content`, `title`)
  COMMENT='Where the spider will save a log of pages it browses when using Firefox';

ALTER TABLE `pages`
ADD `url` varchar(255) COLLATE 'utf8mb4_unicode_ci' NOT NULL;

CREATE TABLE `tf_cache` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `pageid` int NOT NULL,
  `term` varchar(255) NOT NULL,
  `tf` DOUBLE PRECISION NOT NULL
);

CREATE TABLE `bangs` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bang` varchar(255) NOT NULL,
  `weburl` varchar(255) NOT NULL
);