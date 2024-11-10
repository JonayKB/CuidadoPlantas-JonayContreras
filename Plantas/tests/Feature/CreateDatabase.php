<?php
$pdo->exec("DROP TABLE IF EXISTS `comments`;");
$pdo->exec("DROP TABLE IF EXISTS `plants`;");
$pdo->exec("DROP TABLE IF EXISTS `posts`;");
$pdo->exec("DROP TABLE IF EXISTS `roles`;");
$pdo->exec("DROP TABLE IF EXISTS `users`;");
$pdo->exec("DROP TABLE IF EXISTS `user_rol`;");

$pdo->exec("
CREATE TABLE `comments` (
  `comment_id` bigint NOT NULL,
  `content` text NOT NULL,
  `parent_comment_id` bigint DEFAULT NULL,
  `post_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_comment_id`) REFERENCES `comments`(`comment_id`),
  FOREIGN KEY (`post_id`) REFERENCES `posts`(`post_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
)");

$pdo->exec("
CREATE TRIGGER update_comments_updated_at
AFTER UPDATE ON `comments`
FOR EACH ROW
BEGIN
  UPDATE `comments` SET `updated_at` = CURRENT_TIMESTAMP WHERE `comment_id` = OLD.`comment_id`;
END;
");


$pdo->exec("
CREATE TABLE `plants` (
  `plant_id` bigint NOT NULL PRIMARY KEY,
  `name` varchar(100) NOT NULL
)");


$pdo->exec("
CREATE TABLE `posts` (
  `post_id` bigint NOT NULL PRIMARY KEY,
  `title` varchar(255) NOT NULL,
  `description` text,
  `plant_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `image` blob,
  `imageMimeType` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `reports` int DEFAULT '0',
  FOREIGN KEY (`plant_id`) REFERENCES `plants`(`plant_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
)");


$pdo->exec("
CREATE TRIGGER update_posts_updated_at
AFTER UPDATE ON `posts`
FOR EACH ROW
BEGIN
  UPDATE `posts` SET `updated_at` = CURRENT_TIMESTAMP WHERE `post_id` = OLD.`post_id`;
END;
");


$pdo->exec("
CREATE TABLE `roles` (
  `role_id` bigint NOT NULL PRIMARY KEY,
  `name` varchar(50) NOT NULL
)");

$pdo->exec("
INSERT INTO `roles` (`role_id`, `name`) VALUES
(1, 'user'),
(2, 'admin')
");

$pdo->exec("
CREATE TABLE `users` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL UNIQUE
)");


$pdo->exec("
CREATE TRIGGER update_users_updated_at
AFTER UPDATE ON `users`
FOR EACH ROW
BEGIN
  UPDATE `users` SET `updated_at` = CURRENT_TIMESTAMP WHERE `id` = OLD.`id`;
END;
");


$pdo->exec("
INSERT INTO `users` (`id`, `name`, `password`, `verified`, `created_at`, `email`) VALUES
(1, 'Jonay', '\$2y\$12\$grWVsrbcpM.PDKYNYaT.ueA7Y/cEPlxStdrc2jU8raPAZW9hMCAYi', 0, '2024-11-09 18:50:10', 'jonaykb@gmail.com'),
(2, 'test', '\$2y\$12\$4l2kXM3BQngdLc9OlaDPCef/ACyt1vnBgj26Lqhf8KhIu7vKIb0H6', 0, '2024-11-09 22:36:40', 'test@gmail.com')
");

$pdo->exec("
CREATE TABLE `user_rol` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `user_id` bigint NOT NULL,
  `rol_id` bigint NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`rol_id`) REFERENCES `roles`(`role_id`)
)");



$pdo->exec("
INSERT INTO `user_rol` (`user_id`, `rol_id`, `id`) VALUES
(2, 1, 1)
");

