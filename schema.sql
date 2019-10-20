CREATE DATABASE IF NOT EXISTS `taskforce`
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `taskforce`.`locations` (
  `id` INT NOT NULL,
  `city_name` VARCHAR(45) NOT NULL,
  `district` VARCHAR(45) NULL,
  `street` VARCHAR(45) NULL,
  `zip_code` VARCHAR(45) NULL,
  `latitude` VARCHAR(45) NULL,
  `longitude` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_categories` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `slug` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uidx_category_slug` (`slug` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_statuses` (
  `id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `slug` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `uidx_status_slug` (`slug` ASC))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`tasks` (
  `id` INT NOT NULL,
  `owner_id` INT NOT NULL,
  `status_id` INT NOT NULL,
  `agent_id` INT NULL,
  `name` VARCHAR(45) NOT NULL,
  `description` TEXT(1000) NOT NULL,
  `price` INT UNSIGNED NOT NULL,
  `expired_at` DATETIME NOT NULL,
  `category_id` INT NOT NULL,
  `location_id` INT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tasks_locations1_idx` (`location_id` ASC),
  INDEX `fk_tasks_categories1_idx` (`category_id` ASC),
  INDEX `fk_tasks_task_statuses1_idx` (`status_id` ASC),
  CONSTRAINT `fk_tasks_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `taskforce`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_categories1`
    FOREIGN KEY (`category_id`)
    REFERENCES `taskforce`.`task_categories` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_task_statuses1`
    FOREIGN KEY (`status_id`)
    REFERENCES `taskforce`.`task_statuses` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`users` (
  `id` INT NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `bio` TEXT(1000) NULL,
  `date_of_birth` DATETIME NOT NULL,
  `telephone` VARCHAR(45) NULL,
  `skype` VARCHAR(45) NULL,
  `other_messenger` VARCHAR(45) NULL,
  `location_id` INT NOT NULL,
  `profile_views` INT NOT NULL DEFAULT 0,
  `failed_tasks` INT NOT NULL DEFAULT 0,
  `last_active_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_locations1_idx` (`location_id` ASC),
  UNIQUE INDEX `uidx_user_email` (`email` ASC)
  CONSTRAINT `fk_users_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `taskforce`.`locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_applications` (
  `task_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `price` INT UNSIGNED NOT NULL,
  `comment` VARCHAR(45) NOT NULL,
  `is_rejected` TINYINT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `fk_task_applications_users1_idx` (`user_id` ASC),
  INDEX `fk_task_applications_tasks1_idx` (`task_id` ASC),
  UNIQUE INDEX `uidx_application` (`task_id` ASC, `user_id` ASC),
  PRIMARY KEY (`user_id`, `task_id`),
  CONSTRAINT `fk_task_applications_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_applications_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_reviews` (
  `task_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `is_completed` TINYINT NOT NULL,
  `rating` TINYINT NULL,
  `comment` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `fk_task_reviews_users1_idx` (`user_id` ASC),
  INDEX `fk_task_reviews_tasks1_idx` (`task_id` ASC),
  UNIQUE INDEX `uidx_review` (`task_id` ASC, `user_id` ASC),
  PRIMARY KEY (`user_id`, `task_id`),
  CONSTRAINT `fk_task_reviews_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_task_reviews_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_settings` (
  `user_id` INT NOT NULL,
  `notify_task_updates` TINYINT NOT NULL,
  `notify_reviews` TINYINT NOT NULL,
  `notify_messages` TINYINT NOT NULL,
  `show_contacts` TINYINT NOT NULL,
  `show_profile` TINYINT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `fk_user_settings_users1_idx` (`user_id` ASC),
  UNIQUE INDEX `uidx_user_settings` (`user_id` ASC),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_settings_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`notifications` (
  `id` INT NOT NULL,
  `type` VARCHAR(45) NOT NULL,
  `user_id` INT NOT NULL,
  `message` VARCHAR(45) NOT NULL,
  `read_at` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_notifications_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_notifications_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `TaskForce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`chats` (
  `id` INT NOT NULL,
  `task_id` INT NOT NULL,
  `task_owner_id` INT NOT NULL,
  `task_agent_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_chats_tasks1_idx` (`task_id` ASC),
  INDEX `fk_chats_users1_idx` (`task_owner_id` ASC),
  INDEX `fk_chats_users2_idx` (`task_agent_id` ASC),
  UNIQUE INDEX `uidx_chat_task_authors` (`task_id` ASC, `task_owner_id` ASC, `task_agent_id` ASC),
  CONSTRAINT `fk_chats_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chats_users1`
    FOREIGN KEY (`task_owner_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_chats_users2`
    FOREIGN KEY (`task_agent_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`chat_messages` (
  `id` INT NOT NULL,
  `message` VARCHAR(100) NOT NULL,
  `chat_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_chat_messages_chats1_idx` (`chat_id` ASC),
  INDEX `fk_chat_messages_users1_idx` (`author_id` ASC),
  CONSTRAINT `fk_chat_messages_chats1`
    FOREIGN KEY (`chat_id`)
    REFERENCES `taskforce`.`chats` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_chat_messages_users1`
    FOREIGN KEY (`author_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_qualifications` (
  `user_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `fk_qualifications_users1_idx` (`user_id` ASC),
  INDEX `fk_qualifications_task_categories1_idx` (`category_id` ASC),
  UNIQUE INDEX `uidx_qualification` (`user_id` ASC, `category_id` ASC),
  PRIMARY KEY (`user_id`, `category_id`),
  CONSTRAINT `fk_qualifications_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_qualifications_task_categories1`
    FOREIGN KEY (`category_id`)
    REFERENCES `taskforce`.`task_categories` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_favorites` (
  `user_id` INT NOT NULL,
  `favourite_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  INDEX `fk_user_favorites_users1_idx` (`user_id` ASC),
  INDEX `fk_user_favorites_users2_idx` (`favourite_id` ASC),
  UNIQUE INDEX `uidx_favorite` (`user_id` ASC, `favourite_id` ASC),
  PRIMARY KEY (`user_id`, `favourite_id`),
  CONSTRAINT `fk_user_favorites_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_favorites_users2`
    FOREIGN KEY (`favourite_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_attachments` (
  `id` INT NOT NULL,
  `tasks_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `extension` VARCHAR(45) NOT NULL,
  `mime` VARCHAR(45) NOT NULL,
  `size` INT NOT NULL,
  `path` VARCHAR(45) NOT NULL,
  `hash` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_task_attachments_tasks1_idx` (`tasks_id` ASC),
  CONSTRAINT `fk_task_attachments_tasks1`
    FOREIGN KEY (`tasks_id`)
    REFERENCES `taskforce`.`tasks` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_attachments` (
  `id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `extension` VARCHAR(45) NOT NULL,
  `mime` VARCHAR(45) NOT NULL,
  `size` INT NOT NULL,
  `path` VARCHAR(45) NOT NULL,
  `hash` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_attachments_users1_idx` (`author_id` ASC),
  CONSTRAINT `fk_attachments_users10`
    FOREIGN KEY (`author_id`)
    REFERENCES `taskforce`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;
