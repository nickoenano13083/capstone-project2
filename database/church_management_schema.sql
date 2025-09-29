-- MySQL schema for church-management-laravel
-- Generated from Laravel migrations

CREATE DATABASE IF NOT EXISTS `church_management`;
USE `church_management`;

-- users table
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(255) NULL DEFAULT 'Member',
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

-- password_reset_tokens table
CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL PRIMARY KEY,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL
);

-- sessions table
CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
  INDEX (`user_id`)
);

-- cache table
CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `value` MEDIUMTEXT NOT NULL,
  `expiration` INT NOT NULL
);

-- cache_locks table
CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL PRIMARY KEY,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
);

-- jobs table
CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` TINYINT UNSIGNED NOT NULL,
  `reserved_at` INT UNSIGNED NULL,
  `available_at` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  INDEX (`queue`)
);

-- job_batches table
CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` LONGTEXT NOT NULL,
  `options` MEDIUMTEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL
);

-- failed_jobs table
CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(255) NOT NULL UNIQUE,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- chapters table
CREATE TABLE `chapters` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `leader_id` BIGINT UNSIGNED NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  CONSTRAINT `chapters_leader_id_foreign` FOREIGN KEY (`leader_id`) REFERENCES `members`(`id`) ON DELETE SET NULL
);

-- members table
CREATE TABLE `members` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `join_date` DATE NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'Active',
  `role` VARCHAR(255) NOT NULL DEFAULT 'Member',
  `gender` VARCHAR(255) NULL,
  `birthday` DATE NULL,
  `chapter_id` BIGINT UNSIGNED NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

-- events table
CREATE TABLE `events` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `event_date` DATETIME NOT NULL,
  `location` VARCHAR(255) NOT NULL,
  `organizer` VARCHAR(100) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'upcoming',
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL
);

-- prayer_requests table
CREATE TABLE `prayer_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `member_id` BIGINT UNSIGNED NOT NULL,
  `request` TEXT NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `response` TEXT NULL,
  `prayer_date` DATE NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  CONSTRAINT `prayer_requests_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members`(`id`) ON DELETE CASCADE
);

-- attendance table
CREATE TABLE `attendance` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `member_id` BIGINT UNSIGNED NOT NULL,
  `event_id` BIGINT UNSIGNED NULL,
  `attendance_date` DATE NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'present',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  CONSTRAINT `attendance_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members`(`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE SET NULL
); 