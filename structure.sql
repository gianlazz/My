SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE `codeday_blocks` (
  `blockID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `html` text NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '100',
  PRIMARY KEY (`blockID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `codeday_events` (
  `eventID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tagline` varchar(255) NOT NULL DEFAULT 'Pitch ideas, form teams, and build something amazing in 24 hours.',
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `location_address` varchar(255) NOT NULL,
  `hero_background_url` varchar(255) NOT NULL DEFAULT '/assets/img/hero.png',
  `hero_foreground_color` varchar(255) NOT NULL DEFAULT '#ffffff',
  `eventbrite_id` varchar(255) NOT NULL,
  PRIMARY KEY (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `codeday_faqs` (
  `faqID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`faqID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `codeday_schedules` (
  `scheduleID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('schedule','workshop') NOT NULL,
  `activity_lead` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`scheduleID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `codeday_sponsors` (
  `sponsorID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`sponsorID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `has_group_page` tinyint(1) NOT NULL,
  `has_profile_badge` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`groupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `plans` (
  `planID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `stripe_id` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `period` enum('week','month','year') NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`planID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `rfids` (
  `rfID` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`rfID`),
  UNIQUE KEY `userID` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `teams` (
  `teamID` int(11) NOT NULL AUTO_INCREMENT,
  `eventID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `presentation_link` varchar(255) NOT NULL,
  `product_link` varchar(255) NOT NULL,
  PRIMARY KEY (`teamID`),
  KEY `eventID` (`eventID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(161) NOT NULL,
  `password_reset_required` tinyint(1) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `studentrnd_email_enabled` tinyint(1) NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `users_groups` (
  `userID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  PRIMARY KEY (`userID`,`groupID`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users_plans` (
  `userID` int(11) NOT NULL,
  `planID` int(11) NOT NULL,
  `stripe_customer_id` varchar(255) NOT NULL,
  `is_cancelled` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  PRIMARY KEY (`userID`,`planID`),
  UNIQUE KEY `stripe_customer_id` (`stripe_customer_id`),
  KEY `planID` (`planID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `codeday_blocks`
  ADD CONSTRAINT `codeday_blocks_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `codeday_events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `codeday_faqs`
  ADD CONSTRAINT `codeday_faqs_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `codeday_events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `codeday_sponsors`
  ADD CONSTRAINT `codeday_sponsors_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `codeday_events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `rfids`
  ADD CONSTRAINT `rfids_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`eventID`) REFERENCES `codeday_events` (`eventID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users_groups`
  ADD CONSTRAINT `users_groups_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_groups_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

ALTER TABLE `users_plans`
  ADD CONSTRAINT `users_plans_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_plans_ibfk_2` FOREIGN KEY (`planID`) REFERENCES `plans` (`planID`) ON DELETE CASCADE;
