-- 10kt.com schema (MySQL 5.7+ / MariaDB 10.3+). Import via hPanel → phpMyAdmin.
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(30)  NOT NULL,
  email         VARCHAR(190) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  display_name  VARCHAR(80)  NOT NULL DEFAULT '',
  bio           TEXT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_username (username),
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS pages (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NOT NULL,
  slug        VARCHAR(60)  NOT NULL,
  title       VARCHAR(150) NOT NULL,
  summary     VARCHAR(255) NOT NULL DEFAULT '',
  mode        ENUM('template','html') NOT NULL DEFAULT 'template',
  data        MEDIUMTEXT NULL,          -- JSON for template mode
  html        MEDIUMTEXT NULL,          -- raw HTML for html mode
  sort_order  INT NOT NULL DEFAULT 0,
  published   TINYINT(1) NOT NULL DEFAULT 1,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_slug (user_id, slug),
  KEY idx_published (published, updated_at),
  CONSTRAINT fk_pages_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS login_attempts (
  ip           VARCHAR(45) NOT NULL,
  attempted_at DATETIME NOT NULL,
  KEY idx_ip_time (ip, attempted_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
