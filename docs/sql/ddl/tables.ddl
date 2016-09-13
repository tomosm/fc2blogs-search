DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userName` VARCHAR(255) NOT NULL,
  `serverNo` INT UNSIGNED NOT NULL,
  `entryNo` INT UNSIGNED NOT NULL,
  `title` TEXT NOT NULL,
  `description` TEXT NOT NULL,
  `link` VARCHAR(2083) NOT NULL,
  `postedAt` DATETIME NOT NULL,
  `createdAt` DATETIME NOT NULL,
  `updatedAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniUserNameServerNoEntryNo` (`userName`,`serverNo`,`entryNo`),
  INDEX `idxServerNo`(`serverNo`),
  INDEX `idxEntryNo`(`entryNo`),
  INDEX `idxPostedAt`(`postedAt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
