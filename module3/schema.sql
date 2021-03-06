DROP DATABASE IF EXISTS `taskforce`;

CREATE DATABASE IF NOT EXISTS `taskforce`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `taskforce`.`locations`
(
    `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `city`      VARCHAR(100) NOT NULL,
    `latitude`  DOUBLE       NULL,
    `longitude` DOUBLE       NULL,
#`district` VARCHAR(45)  NULL,
#`street`   VARCHAR(100) NULL,
#`zip_code` VARCHAR(45)  NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_categories`
(
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(45)  NOT NULL,
    `slug`       VARCHAR(45)  NOT NULL,
    `created_at` DATETIME     NOT NULL,
    `updated_at` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `uidx_category_slug` (`slug` ASC)
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`users`
(
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email`           VARCHAR(320) NOT NULL,
    `password`        VARCHAR(100) NOT NULL,
    `first_name`      VARCHAR(100) NOT NULL,
    `last_name`       VARCHAR(100) NOT NULL,
    `bio`             TEXT(1000)   NULL,
    `date_of_birth`   DATETIME     NOT NULL,
    `phone`           VARCHAR(45)  NULL,
    `skype`           VARCHAR(45)  NULL,
    `other_messenger` VARCHAR(45)  NULL,
    `location_id`     INT UNSIGNED NOT NULL,
    `profile_views`   INT UNSIGNED NOT NULL DEFAULT 0,
    `last_active_at`  DATETIME     NOT NULL,
    `created_at`      DATETIME     NOT NULL,
    `updated_at`      DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_users_locations1_idx` (`location_id` ASC),
    UNIQUE INDEX `uidx_user_email` (`email` ASC),
    CONSTRAINT `fk_users_locations1`
        FOREIGN KEY (`location_id`)
            REFERENCES `taskforce`.`locations` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`tasks`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `owner_id`    INT UNSIGNED NOT NULL,
    `status`      ENUM ('new','cancelled','failed','in progress','completed','expired') NOT NULL,
    `agent_id`    INT UNSIGNED NULL,
    `name`        VARCHAR(100) NOT NULL,
    `description` TEXT(1000)   NOT NULL,
    `price`       INT UNSIGNED NOT NULL,
    `expired_at`  DATETIME     NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `location_id` INT UNSIGNED NULL,
    `created_at`  DATETIME     NOT NULL,
    `updated_at`  DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_tasks_locations1_idx` (`location_id` ASC),
    INDEX `fk_tasks_categories1_idx` (`category_id` ASC),
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
    CONSTRAINT `fk_tasks_users1`
        FOREIGN KEY (`owner_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE,
    CONSTRAINT `fk_tasks_users2`
        FOREIGN KEY (`agent_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_applications`
(
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `task_id`     INT UNSIGNED     NOT NULL,
    `user_id`     INT UNSIGNED     NOT NULL,
    `price`       INT UNSIGNED     NOT NULL,
    `comment`     VARCHAR(500)     NOT NULL,
    `is_rejected` TINYINT UNSIGNED NOT NULL,
    `created_at`  DATETIME         NOT NULL,
    `updated_at`  DATETIME         NOT NULL,
    INDEX `fk_task_applications_users1_idx` (`user_id` ASC),
    INDEX `fk_task_applications_tasks1_idx` (`task_id` ASC),
    UNIQUE INDEX `uidx_application` (`task_id` ASC, `user_id` ASC),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_task_applications_users1`
        FOREIGN KEY (`user_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION,
    CONSTRAINT `fk_task_applications_tasks1`
        FOREIGN KEY (`task_id`)
            REFERENCES `taskforce`.`tasks` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_reviews`
(
    `id`           INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `task_id`      INT UNSIGNED     NOT NULL,
    `is_completed` TINYINT UNSIGNED NOT NULL,
    `rating`       TINYINT UNSIGNED NULL,
    `comment`      VARCHAR(500)     NULL,
    `created_at`   DATETIME         NOT NULL,
    `updated_at`   DATETIME         NOT NULL,
    INDEX `fk_task_reviews_tasks1_idx` (`task_id` ASC),
    UNIQUE INDEX `uidx_review` (`task_id` ASC),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_task_reviews_tasks1`
        FOREIGN KEY (`task_id`)
            REFERENCES `taskforce`.`tasks` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_settings`
(
    `user_id`             INT UNSIGNED NOT NULL,
    `notify_task_updates` TINYINT      NOT NULL,
    `notify_reviews`      TINYINT      NOT NULL,
    `notify_messages`     TINYINT      NOT NULL,
    `show_contacts`       TINYINT      NOT NULL,
    `show_profile`        TINYINT      NOT NULL,
    `created_at`          DATETIME     NOT NULL,
    `updated_at`          DATETIME     NOT NULL,
    INDEX `fk_user_settings_users1_idx` (`user_id` ASC),
    UNIQUE INDEX `uidx_user_settings` (`user_id` ASC),
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_user_settings_users1`
        FOREIGN KEY (`user_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`notifications`
(
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `type`       VARCHAR(45)  NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `message`    VARCHAR(500) NOT NULL,
    `read_at`    DATETIME     NULL,
    `created_at` DATETIME     NOT NULL,
    `updated_at` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_notifications_users1_idx` (`user_id` ASC),
    CONSTRAINT `fk_notifications_users1`
        FOREIGN KEY (`user_id`)
            REFERENCES `TaskForce`.`users` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`chats`
(
    `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id`       INT UNSIGNED NOT NULL,
    `created_at`    DATETIME     NOT NULL,
    `updated_at`    DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_chats_tasks1_idx` (`task_id` ASC),
    CONSTRAINT `fk_chats_tasks1`
        FOREIGN KEY (`task_id`)
            REFERENCES `taskforce`.`tasks` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`chat_messages`
(
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `message`    VARCHAR(500) NOT NULL,
    `chat_id`    INT UNSIGNED NOT NULL,
    `author_id`  INT UNSIGNED NOT NULL,
    `created_at` DATETIME     NOT NULL,
    `updated_at` DATETIME     NOT NULL,
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
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_qualifications`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `created_at`  DATETIME     NOT NULL,
    `updated_at`  DATETIME     NOT NULL,
    INDEX `fk_qualifications_users1_idx` (`user_id` ASC),
    INDEX `fk_qualifications_task_categories1_idx` (`category_id` ASC),
    UNIQUE INDEX `uidx_qualification` (`user_id` ASC, `category_id` ASC),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_qualifications_users1`
        FOREIGN KEY (`user_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `fk_qualifications_task_categories1`
        FOREIGN KEY (`category_id`)
            REFERENCES `taskforce`.`task_categories` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_favorites`
(
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `favorite_id` INT UNSIGNED NOT NULL,
    `created_at`  DATETIME     NOT NULL,
    `updated_at`  DATETIME     NOT NULL,
    INDEX `fk_user_favorites_users1_idx` (`user_id` ASC),
    INDEX `fk_user_favorites_users2_idx` (`favorite_id` ASC),
    UNIQUE INDEX `uidx_favorite` (`user_id` ASC, `favorite_id` ASC),
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_user_favorites_users1`
        FOREIGN KEY (`user_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
    CONSTRAINT `fk_user_favorites_users2`
        FOREIGN KEY (`favorite_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`task_attachments`
(
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `task_id`    INT UNSIGNED NOT NULL,
    `name`       VARCHAR(100) NOT NULL,
    `extension`  VARCHAR(45)  NOT NULL,
    `mime`       VARCHAR(45)  NOT NULL,
    `size`       INT UNSIGNED NOT NULL,
    `path`       VARCHAR(100) NOT NULL,
    `hash`       VARCHAR(100) NOT NULL,
    `created_at` DATETIME     NOT NULL,
    `updated_at` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_task_attachments_tasks1_idx` (`task_id` ASC),
    CONSTRAINT `fk_task_attachments_tasks1`
        FOREIGN KEY (`task_id`)
            REFERENCES `taskforce`.`tasks` (`id`)
            ON DELETE NO ACTION
            ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `taskforce`.`user_attachments`
(
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `author_id`  INT UNSIGNED NOT NULL,
    `name`       VARCHAR(100) NOT NULL,
    `extension`  VARCHAR(45)  NOT NULL,
    `mime`       VARCHAR(45)  NOT NULL,
    `size`       INT UNSIGNED NOT NULL,
    `path`       VARCHAR(100) NOT NULL,
    `hash`       VARCHAR(100) NOT NULL,
    `created_at` DATETIME     NOT NULL,
    `updated_at` DATETIME     NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `fk_attachments_users1_idx` (`author_id` ASC),
    CONSTRAINT `fk_attachments_users10`
        FOREIGN KEY (`author_id`)
            REFERENCES `taskforce`.`users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
)
    ENGINE = InnoDB;

### DROP TABLE EXAMPLE ###
#SET FOREIGN_KEY_CHECKS=0;
#DROP TABLE `taskforce`.`tasks`, `taskforce`.`users`;
#SET FOREIGN_KEY_CHECKS=1;
