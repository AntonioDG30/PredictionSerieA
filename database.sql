CREATE DATABASE my_predictionseriea
;
USE my_predictionseriea
;

DROP TABLE IF EXISTS `predictions`;
CREATE TABLE IF NOT EXISTS `predictions` (
                                             `id` int NOT NULL AUTO_INCREMENT,
                                             `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                             `first_name` varchar(100) NOT NULL,
                                             `last_name` varchar(100) NOT NULL,
                                             `email` varchar(100) NOT NULL,
                                             `season` varchar(10) NOT NULL,
                                             `position1` varchar(100) DEFAULT NULL,
                                             `position2` varchar(100) DEFAULT NULL,
                                             `position3` varchar(100) DEFAULT NULL,
                                             `position4` varchar(100) DEFAULT NULL,
                                             `position5` varchar(100) DEFAULT NULL,
                                             `position6` varchar(100) DEFAULT NULL,
                                             `position7` varchar(100) DEFAULT NULL,
                                             `position8` varchar(100) DEFAULT NULL,
                                             `position9` varchar(100) DEFAULT NULL,
                                             `position10` varchar(100) DEFAULT NULL,
                                             `position11` varchar(100) DEFAULT NULL,
                                             `position12` varchar(100) DEFAULT NULL,
                                             `position13` varchar(100) DEFAULT NULL,
                                             `position14` varchar(100) DEFAULT NULL,
                                             `position15` varchar(100) DEFAULT NULL,
                                             `position16` varchar(100) DEFAULT NULL,
                                             `position17` varchar(100) DEFAULT NULL,
                                             `position18` varchar(100) DEFAULT NULL,
                                             `position19` varchar(100) DEFAULT NULL,
                                             `position20` varchar(100) DEFAULT NULL,
                                             PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `predictionsmarcatori`;
CREATE TABLE IF NOT EXISTS `predictionsmarcatori` (
                                                      `id` int NOT NULL AUTO_INCREMENT,
                                                      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                                                      `first_name` varchar(100) NOT NULL,
                                                      `last_name` varchar(100) NOT NULL,
                                                      `email` varchar(100) NOT NULL,
                                                      `season` varchar(10) NOT NULL,
                                                      `scorer1` varchar(100) NOT NULL,
                                                      `scorer2` varchar(100) NOT NULL,
                                                      `scorer3` varchar(100) NOT NULL,
                                                      `scorer4` varchar(100) NOT NULL,
                                                      `scorer5` varchar(100) NOT NULL,
                                                      PRIMARY KEY (`id`)
);
