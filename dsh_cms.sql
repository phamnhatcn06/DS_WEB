/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : dsh_cms

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 31/07/2026 17:21:12
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `version` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apply_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('m000000_000000_base', 1784885683);
INSERT INTO `migrations` VALUES ('m260724_010000_create_media_tables', 1784885684);
INSERT INTO `migrations` VALUES ('m260724_020000_create_user_and_auth_tables', 1784885684);
INSERT INTO `migrations` VALUES ('m260724_030000_create_settings_and_audit', 1784885685);
INSERT INTO `migrations` VALUES ('m260724_040000_create_content_tables', 1784885686);
INSERT INTO `migrations` VALUES ('m260724_050000_create_projects_and_news', 1784885687);
INSERT INTO `migrations` VALUES ('m260724_060000_seed_rbac_and_admin', 1784885687);
INSERT INTO `migrations` VALUES ('m260724_070000_seed_media_library', 1784885688);
INSERT INTO `migrations` VALUES ('m260724_080000_seed_homepage_content', 1784886310);
INSERT INTO `migrations` VALUES ('m260730_000000_add_password_reset_to_users', 1785404042);
INSERT INTO `migrations` VALUES ('m260731_000000_create_menu_tables', 1785463590);
INSERT INTO `migrations` VALUES ('m260731_010000_convert_menu_icons_to_fontawesome', 1785467847);
INSERT INTO `migrations` VALUES ('m260731_020000_seed_frontend_menus', 1785468663);
INSERT INTO `migrations` VALUES ('m260731_030000_seed_features_admin', 1785481426);
INSERT INTO `migrations` VALUES ('m260801_000000_seed_roles_module_and_sidebar', 1785469928);

-- ----------------------------
-- Table structure for pvn_audit_logs
-- ----------------------------
DROP TABLE IF EXISTS `pvn_audit_logs`;
CREATE TABLE `pvn_audit_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `old_values` json NULL,
  `new_values` json NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_audit_logs_entity`(`entity_type`, `entity_id`) USING BTREE,
  INDEX `idx_audit_logs_user_id_created_at`(`user_id`, `created_at`) USING BTREE,
  INDEX `idx_audit_logs_created_at`(`created_at`) USING BTREE,
  CONSTRAINT `fk_audit_logs_users` FOREIGN KEY (`user_id`) REFERENCES `pvn_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_audit_logs
-- ----------------------------
INSERT INTO `pvn_audit_logs` VALUES (1, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:36:10');
INSERT INTO `pvn_audit_logs` VALUES (2, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:37:42');
INSERT INTO `pvn_audit_logs` VALUES (3, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:38:23');
INSERT INTO `pvn_audit_logs` VALUES (4, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:39:18');
INSERT INTO `pvn_audit_logs` VALUES (5, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:39:27');
INSERT INTO `pvn_audit_logs` VALUES (6, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:39:54');
INSERT INTO `pvn_audit_logs` VALUES (7, 1, 'update', 'HeroSlide', 1, '{\"title\": \"Đông Sơn Holding\", \"subtitle\": \"Trở thành doanh nghiệp uy tín trong lĩnh vực\\nnăng lượng, bất động sản và xây lắp\", \"updated_at\": \"2026-07-24 16:34:48\", \"logo_media_id\": 28, \"primary_cta_label\": \"Khám phá dự án\", \"background_media_id\": 21, \"secondary_cta_label\": \"Lĩnh vực hoạt động\"}', '{\"title\": \"Kiem thu 37956\", \"subtitle\": \"Phu de kiem thu\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"logo_media_id\": null, \"primary_cta_label\": \"CTA chinh\", \"background_media_id\": \"1\", \"secondary_cta_label\": \"CTA phu\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:39:54');
INSERT INTO `pvn_audit_logs` VALUES (8, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:40:46');
INSERT INTO `pvn_audit_logs` VALUES (9, 1, 'create', 'CoreValue', 5, NULL, '{\"id\": \"5\", \"title\": \"Gia tri 5517\", \"is_active\": \"1\", \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"sort_order\": \"99\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"description\": \"Mo ta kiem thu\", \"icon_variant\": \"default\", \"icon_media_id\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:40:47');
INSERT INTO `pvn_audit_logs` VALUES (10, 1, 'create', 'NewsCategory', 5, NULL, '{\"id\": \"5\", \"name\": \"Đau tu ha tầng\", \"slug\": \"dau-tu-ha-tang\", \"is_active\": \"1\", \"parent_id\": null, \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"sort_order\": \"9\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"description\": null, \"show_in_filter\": \"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:40:47');
INSERT INTO `pvn_audit_logs` VALUES (11, 1, 'update', 'BusinessSector', 1, '{\"name\": \"Thi công và xây lắp\", \"tags\": \"[\\\"EPC\\\", \\\"Hạ tầng\\\", \\\"Dân dụng\\\", \\\"Công nghiệp\\\"]\", \"eyebrow\": \"Thi công & Xây lắp\", \"headline\": \"Nền móng cho mọi công trình\", \"cta_label\": \"Khám phá dự án\", \"lead_text\": \"Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.\", \"card_title\": \"Thi công & Xây lắp\", \"updated_at\": \"2026-07-24 16:34:48\", \"description\": \"Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.\", \"image_media_id\": 21, \"card_description\": \"Năng lực tổng thầu EPC cho các công trình trọng điểm, đảm bảo tiến độ, chất lượng và an toàn lao động.\"}', '{\"name\": \"Thi cong va xay lap\", \"tags\": \"[\\\"EPC\\\",\\\"Hạ tầng\\\",\\\"Dân dụng\\\",\\\"Công nghiệp\\\"]\", \"eyebrow\": \"Thi cong\", \"headline\": \"Nen mong\", \"cta_label\": \"CTA\", \"lead_text\": \"Lead\", \"card_title\": \"Card\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"description\": \"Mo ta\", \"image_media_id\": null, \"card_description\": \"Card mo ta\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:40:47');
INSERT INTO `pvn_audit_logs` VALUES (12, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:41:45');
INSERT INTO `pvn_audit_logs` VALUES (13, 1, 'create', 'CoreValue', 6, NULL, '{\"id\": \"6\", \"title\": \"Gia tri 73214\", \"is_active\": \"1\", \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"sort_order\": \"99\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"description\": \"Mo ta kiem thu\", \"icon_variant\": \"default\", \"icon_media_id\": null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:41:45');
INSERT INTO `pvn_audit_logs` VALUES (14, 1, 'create', 'NewsCategory', 6, NULL, '{\"id\": \"6\", \"name\": \"Đau tu ha tầng\", \"slug\": \"dau-tu-ha-tang-2\", \"is_active\": \"1\", \"parent_id\": null, \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"sort_order\": \"9\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"description\": null, \"show_in_filter\": \"1\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:41:46');
INSERT INTO `pvn_audit_logs` VALUES (15, 1, 'update', 'BusinessSector', 1, '{\"tags\": \"[\\\"EPC\\\", \\\"Hạ tầng\\\", \\\"Dân dụng\\\", \\\"Công nghiệp\\\"]\", \"updated_at\": \"2026-07-24 16:40:47\"}', '{\"tags\": \"[\\\"ALPHA\\\",\\\"BETA\\\",\\\"GAMMA\\\"]\", \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}}', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:41:46');
INSERT INTO `pvn_audit_logs` VALUES (16, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:42:42');
INSERT INTO `pvn_audit_logs` VALUES (17, 1, 'create', 'MediaFile', 43, NULL, '{\"id\": \"43\", \"title\": null, \"width\": 320, \"height\": 200, \"caption\": null, \"alt_text\": null, \"checksum\": \"09820720d39049b2635b439a9e06e7d44223a828d1b0d9231d2ab0eefdf87125\", \"file_url\": null, \"variants\": null, \"file_name\": \"test-upload-20260724164242-2579dc.png\", \"file_path\": \"uploads/202607/test-upload-20260724164242-2579dc.png\", \"file_size\": 687, \"folder_id\": null, \"mime_type\": \"image/png\", \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"uploaded_by\": 1}', '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:42:42');
INSERT INTO `pvn_audit_logs` VALUES (18, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:43:40');
INSERT INTO `pvn_audit_logs` VALUES (19, 1, 'create', 'MediaFile', 44, NULL, '{\"id\": \"44\", \"title\": null, \"width\": 320, \"height\": 200, \"caption\": null, \"alt_text\": null, \"checksum\": \"09820720d39049b2635b439a9e06e7d44223a828d1b0d9231d2ab0eefdf87125\", \"file_url\": null, \"variants\": null, \"file_name\": \"test-upload-20260724164340-fb1bf9.png\", \"file_path\": \"uploads/202607/test-upload-20260724164340-fb1bf9.png\", \"file_size\": 687, \"folder_id\": null, \"mime_type\": \"image/png\", \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"uploaded_by\": 1}', '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:43:40');
INSERT INTO `pvn_audit_logs` VALUES (20, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:44:32');
INSERT INTO `pvn_audit_logs` VALUES (21, 1, 'create', 'MediaFile', 45, NULL, '{\"id\": \"45\", \"title\": null, \"width\": 320, \"height\": 200, \"caption\": null, \"alt_text\": null, \"checksum\": \"09820720d39049b2635b439a9e06e7d44223a828d1b0d9231d2ab0eefdf87125\", \"file_url\": null, \"variants\": null, \"file_name\": \"test-upload-20260724164433-a1377d.png\", \"file_path\": \"uploads/202607/test-upload-20260724164433-a1377d.png\", \"file_size\": 687, \"folder_id\": null, \"mime_type\": \"image/png\", \"created_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"deleted_at\": null, \"updated_at\": {\"params\": [], \"expression\": \"NOW()\"}, \"uploaded_by\": 1}', '127.0.0.1', 'curl/8.13.0', '2026-07-24 16:44:33');
INSERT INTO `pvn_audit_logs` VALUES (22, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:45:52');
INSERT INTO `pvn_audit_logs` VALUES (23, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.19041.6456', '2026-07-24 16:46:26');
INSERT INTO `pvn_audit_logs` VALUES (24, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-30 16:40:12');
INSERT INTO `pvn_audit_logs` VALUES (25, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'curl/8.18.0', '2026-07-30 16:44:35');
INSERT INTO `pvn_audit_logs` VALUES (26, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'curl/8.18.0', '2026-07-30 16:52:34');
INSERT INTO `pvn_audit_logs` VALUES (27, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 10:05:52');

-- ----------------------------
-- Table structure for pvn_auth_assignments
-- ----------------------------
DROP TABLE IF EXISTS `pvn_auth_assignments`;
CREATE TABLE `pvn_auth_assignments`  (
  `itemname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `userid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bizrule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`itemname`, `userid`) USING BTREE,
  CONSTRAINT `fk_auth_assignments_auth_items` FOREIGN KEY (`itemname`) REFERENCES `pvn_auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_auth_assignments
-- ----------------------------
INSERT INTO `pvn_auth_assignments` VALUES ('super_admin', '1', NULL, 'N;');

-- ----------------------------
-- Table structure for pvn_auth_item_children
-- ----------------------------
DROP TABLE IF EXISTS `pvn_auth_item_children`;
CREATE TABLE `pvn_auth_item_children`  (
  `parent` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`, `child`) USING BTREE,
  INDEX `fk_auth_item_children_child`(`child`) USING BTREE,
  CONSTRAINT `fk_auth_item_children_child` FOREIGN KEY (`child`) REFERENCES `pvn_auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_children_parent` FOREIGN KEY (`parent`) REFERENCES `pvn_auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_auth_item_children
-- ----------------------------
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'admin');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'audit.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'business_sectors.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'business_sectors.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'business_sectors.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'business_sectors.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'core_values.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'core_values.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'core_values.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'core_values.view');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'editor');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'features.create');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'features.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'features.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'features.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'features.update');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'features.update');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'features.view');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'features.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'hero_slides.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'hero_slides.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'hero_slides.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'hero_slides.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'media.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'media.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'media.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'media.view');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'menus.create');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'menus.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'menus.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'menus.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'menus.reorder');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'menus.reorder');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'menus.update');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'menus.update');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'menus.view');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'menus.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'news_categories.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'news_categories.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'news_categories.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'news_categories.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'news_posts.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'news_posts.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'news_posts.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'news_posts.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'partners.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'partners.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'partners.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'partners.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'projects.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'projects.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'projects.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'projects.view');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'roles.create');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'roles.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'roles.update');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'roles.view');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'settings.update');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'settings.view');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'settings.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'timeline_milestones.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'timeline_milestones.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'timeline_milestones.update');
INSERT INTO `pvn_auth_item_children` VALUES ('viewer', 'timeline_milestones.view');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'users.create');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'users.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'users.update');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'users.view');
INSERT INTO `pvn_auth_item_children` VALUES ('editor', 'viewer');

-- ----------------------------
-- Table structure for pvn_auth_items
-- ----------------------------
DROP TABLE IF EXISTS `pvn_auth_items`;
CREATE TABLE `pvn_auth_items`  (
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(11) NOT NULL COMMENT '0=operation, 1=task, 2=role',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `bizrule` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'KHÔNG SỬ DỤNG — eval() là rủi ro bảo mật',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_auth_items
-- ----------------------------
INSERT INTO `pvn_auth_items` VALUES ('admin', 2, 'Quản trị viên', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('audit.create', 0, 'Thêm — Nhật ký hệ thống', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('audit.delete', 0, 'Xoá — Nhật ký hệ thống', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('audit.update', 0, 'Sửa — Nhật ký hệ thống', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('audit.view', 0, 'Xem — Nhật ký hệ thống', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('business_sectors.create', 0, 'Thêm — Lĩnh vực kinh doanh', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('business_sectors.delete', 0, 'Xoá — Lĩnh vực kinh doanh', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('business_sectors.update', 0, 'Sửa — Lĩnh vực kinh doanh', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('business_sectors.view', 0, 'Xem — Lĩnh vực kinh doanh', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('core_values.create', 0, 'Thêm — Giá trị cốt lõi', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('core_values.delete', 0, 'Xoá — Giá trị cốt lõi', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('core_values.update', 0, 'Sửa — Giá trị cốt lõi', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('core_values.view', 0, 'Xem — Giá trị cốt lõi', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('editor', 2, 'Biên tập viên', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('features.create', 0, 'Thêm — Cấu hình chức năng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('features.delete', 0, 'Xoá — Cấu hình chức năng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('features.update', 0, 'Sửa — Cấu hình chức năng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('features.view', 0, 'Xem — Cấu hình chức năng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('guest', 2, 'Khách', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('hero_slides.create', 0, 'Thêm — Hero slider', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('hero_slides.delete', 0, 'Xoá — Hero slider', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('hero_slides.update', 0, 'Sửa — Hero slider', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('hero_slides.view', 0, 'Xem — Hero slider', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('media.create', 0, 'Thêm — Thư viện media', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('media.delete', 0, 'Xoá — Thư viện media', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('media.update', 0, 'Sửa — Thư viện media', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('media.view', 0, 'Xem — Thư viện media', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('menus.create', 0, 'Thêm — Quản lý menu', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('menus.delete', 0, 'Xoá — Quản lý menu', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('menus.reorder', 0, 'Sắp xếp — Quản lý menu', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('menus.update', 0, 'Sửa — Quản lý menu', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('menus.view', 0, 'Xem — Quản lý menu', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_categories.create', 0, 'Thêm — Danh mục tin', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_categories.delete', 0, 'Xoá — Danh mục tin', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_categories.update', 0, 'Sửa — Danh mục tin', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_categories.view', 0, 'Xem — Danh mục tin', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_posts.create', 0, 'Thêm — Bài viết', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_posts.delete', 0, 'Xoá — Bài viết', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_posts.update', 0, 'Sửa — Bài viết', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('news_posts.view', 0, 'Xem — Bài viết', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('partners.create', 0, 'Thêm — Đối tác & cổ đông', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('partners.delete', 0, 'Xoá — Đối tác & cổ đông', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('partners.update', 0, 'Sửa — Đối tác & cổ đông', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('partners.view', 0, 'Xem — Đối tác & cổ đông', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('projects.create', 0, 'Thêm — Dự án', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('projects.delete', 0, 'Xoá — Dự án', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('projects.update', 0, 'Sửa — Dự án', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('projects.view', 0, 'Xem — Dự án', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('roles.create', 0, 'Thêm — Nhóm quyền', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('roles.delete', 0, 'Xoá — Nhóm quyền', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('roles.update', 0, 'Sửa — Nhóm quyền', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('roles.view', 0, 'Xem — Nhóm quyền', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('settings.create', 0, 'Thêm — Cấu hình website', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('settings.delete', 0, 'Xoá — Cấu hình website', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('settings.update', 0, 'Sửa — Cấu hình website', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('settings.view', 0, 'Xem — Cấu hình website', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('super_admin', 2, 'Quản trị tối cao', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('timeline_milestones.create', 0, 'Thêm — Mốc hành trình', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('timeline_milestones.delete', 0, 'Xoá — Mốc hành trình', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('timeline_milestones.update', 0, 'Sửa — Mốc hành trình', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('timeline_milestones.view', 0, 'Xem — Mốc hành trình', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('users.create', 0, 'Thêm — Người dùng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('users.delete', 0, 'Xoá — Người dùng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('users.update', 0, 'Sửa — Người dùng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('users.view', 0, 'Xem — Người dùng', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('viewer', 2, 'Người xem', NULL, 'N;');

-- ----------------------------
-- Table structure for pvn_business_sectors
-- ----------------------------
DROP TABLE IF EXISTS `pvn_business_sectors`;
CREATE TABLE `pvn_business_sectors`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_label` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '01..04 hiển thị ở S4',
  `eyebrow` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headline` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Tiêu đề lớn ở S2',
  `lead_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `card_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `card_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tags` json NULL COMMENT 'Chip/tag; tách bảng riêng ở phase 2',
  `image_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `icon_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `cta_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `cta_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `show_in_slider` tinyint(1) NOT NULL DEFAULT 1,
  `show_in_grid` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_business_sectors_slug`(`slug`) USING BTREE,
  INDEX `idx_business_sectors_sort_order`(`sort_order`) USING BTREE,
  INDEX `fk_business_sectors_image_media`(`image_media_id`) USING BTREE,
  INDEX `fk_business_sectors_icon_media`(`icon_media_id`) USING BTREE,
  CONSTRAINT `fk_business_sectors_icon_media` FOREIGN KEY (`icon_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_business_sectors_image_media` FOREIGN KEY (`image_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_business_sectors
-- ----------------------------
INSERT INTO `pvn_business_sectors` VALUES (5, 'thi-cong-xay-lap', '01', 'Thi công & Xây lắp', 'Thi công và xây lắp', 'Nền móng cho mọi công trình', 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.', 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.', 'Thi công & Xây lắp', 'Năng lực tổng thầu EPC cho các công trình trọng điểm, đảm bảo tiến độ, chất lượng và an toàn lao động.', '[\"EPC\", \"Hạ tầng\", \"Dân dụng\", \"Công nghiệp\"]', 21, NULL, 'Khám phá dự án', '#du-an', 1, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (6, 'dau-tu-bot-ha-tang', '02', 'Đầu tư BOT & Hạ tầng', 'Đầu tư BOT & Hạ tầng', 'Kết nối hành lang kinh tế', 'Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, bất động sản và xây lắp.', 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.', 'Đầu tư BOT & Hạ tầng', 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.', '[\"BOT\", \"Cao tốc\", \"Cầu đường\", \"Vành đai\"]', 6, NULL, 'Khám phá dự án', '#du-an', 2, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (7, 'nha-o-do-thi', '03', 'Nhà ở & Đô thị', 'Nhà ở & Đô thị', 'Kiến tạo không gian sống', 'Phát triển nhà ở xã hội và khu đô thị bền vững, nâng tầm chất lượng sống cho cộng đồng.', 'Phát triển nhà ở xã hội và khu đô thị bền vững. Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.', 'Nhà ở & Đô thị', 'Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.', '[\"Nhà ở xã hội\", \"Đô thị\", \"BĐS\"]', 10, NULL, 'Khám phá dự án', '#du-an', 3, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (8, 'nang-luong-kcn', '04', 'Năng lượng & KCN', 'Năng lượng & KCN', 'Động lực tăng trưởng xanh', 'Đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.', 'Định hướng chiến lược mới: đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.', 'Năng lượng & KCN', 'Định hướng chiến lược mới: phát triển hạ tầng khu công nghiệp gắn với năng lượng tái tạo.', '[\"Năng lượng tái tạo\", \"Khu công nghiệp\"]', 21, NULL, 'Khám phá dự án', '#du-an', 4, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_core_values
-- ----------------------------
DROP TABLE IF EXISTS `pvn_core_values`;
CREATE TABLE `pvn_core_values`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `icon_variant` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'default|award|inner',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_core_values_sort_order`(`sort_order`) USING BTREE,
  INDEX `fk_core_values_icon_media`(`icon_media_id`) USING BTREE,
  CONSTRAINT `fk_core_values_icon_media` FOREIGN KEY (`icon_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_core_values
-- ----------------------------
INSERT INTO `pvn_core_values` VALUES (7, 'Trách nhiệm', 'Cam kết thực hiện đúng tiến độ, chất lượng và an toàn lao động trong mọi dự án thi công.', 20, 'default', 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_core_values` VALUES (8, 'Chuyên nghiệp', 'Đội ngũ kỹ sư, cán bộ kỹ thuật nhiều năm kinh nghiệm trên các công trình trọng điểm quốc gia.', 17, 'award', 2, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_core_values` VALUES (9, 'Đổi mới', 'Không ngừng ứng dụng công nghệ thi công tiên tiến, nâng cao năng lực quản trị và triển khai dự án.', 18, 'inner', 3, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_core_values` VALUES (10, 'Tin cậy', 'Đối tác tin cậy của các tổng công ty nhà nước, chủ đầu tư lớn và ban quản lý dự án quốc gia.', 19, 'default', 4, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_hero_slides
-- ----------------------------
DROP TABLE IF EXISTS `pvn_hero_slides`;
CREATE TABLE `pvn_hero_slides`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `background_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `logo_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `primary_cta_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `primary_cta_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `secondary_cta_label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `secondary_cta_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `overlay_opacity` smallint(6) NOT NULL DEFAULT 50 COMMENT '0-100',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_hero_slides_sort_order`(`sort_order`) USING BTREE,
  INDEX `idx_hero_slides_is_active`(`is_active`) USING BTREE,
  INDEX `fk_hero_slides_bg_media`(`background_media_id`) USING BTREE,
  INDEX `fk_hero_slides_logo_media`(`logo_media_id`) USING BTREE,
  CONSTRAINT `fk_hero_slides_bg_media` FOREIGN KEY (`background_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_hero_slides_logo_media` FOREIGN KEY (`logo_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_hero_slides
-- ----------------------------
INSERT INTO `pvn_hero_slides` VALUES (5, 'Đông Sơn Holding', 'Trở thành doanh nghiệp uy tín trong lĩnh vực\nnăng lượng, bất động sản và xây lắp', 21, 28, 'Khám phá dự án', '#du-an', 'Lĩnh vực hoạt động', '#linh-vuc', 50, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_hero_slides` VALUES (6, 'Hạ tầng & BOT', 'Kết nối hành lang kinh tế, kiến tạo những\ntuyến giao thông trọng điểm quốc gia', 21, 28, 'Khám phá dự án', '#du-an', 'Lĩnh vực hoạt động', '#linh-vuc', 50, 2, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_hero_slides` VALUES (7, 'Bất động sản', 'Phát triển các khu đô thị và nhà ở bền vững,\nnâng tầm chất lượng sống cho cộng đồng', 21, 28, 'Khám phá dự án', '#du-an', 'Lĩnh vực hoạt động', '#linh-vuc', 50, 3, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_hero_slides` VALUES (8, 'Năng lượng', 'Đầu tư năng lượng tái tạo và khu công nghiệp,\nhướng tới một tương lai xanh và bền vững', 21, 28, 'Khám phá dự án', '#du-an', 'Lĩnh vực hoạt động', '#linh-vuc', 50, 4, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_media_files
-- ----------------------------
DROP TABLE IF EXISTS `pvn_media_files`;
CREATE TABLE `pvn_media_files`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn tương đối từ webroot',
  `file_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'URL CDN nếu có',
  `mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `width` int(11) NULL DEFAULT NULL,
  `height` int(11) NULL DEFAULT NULL,
  `alt_text` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Bắt buộc ở tầng validate — SEO/a11y',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `checksum` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SHA-256 chống upload trùng',
  `variants` json NULL COMMENT 'Các size responsive đã convert',
  `uploaded_by` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_media_files_checksum`(`checksum`) USING BTREE,
  INDEX `idx_media_files_folder_id`(`folder_id`) USING BTREE,
  INDEX `idx_media_files_mime_type`(`mime_type`) USING BTREE,
  INDEX `fk_media_files_users`(`uploaded_by`) USING BTREE,
  CONSTRAINT `fk_media_files_media_folders` FOREIGN KEY (`folder_id`) REFERENCES `pvn_media_folders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_media_files_users` FOREIGN KEY (`uploaded_by`) REFERENCES `pvn_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_media_files
-- ----------------------------
INSERT INTO `pvn_media_files` VALUES (1, 1, 'about-chevrons.svg', '../assets/images/about-chevrons.svg', NULL, 'image/svg+xml', 1345, NULL, NULL, 'About chevrons', NULL, NULL, '039a747eb2173d390e4c5bfcc0f7214210eeb5aa5687e65dfffd6756ff914b63', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (2, 1, 'about-construction.webp', '../assets/images/about-construction.webp', NULL, 'image/webp', 106056, 1200, 675, 'Công trình xây dựng của Đông Sơn Holdings', NULL, NULL, '7ab07154371576aa9156dcd4feba6ce6e8f4ef05d4e9092a2d69d0b1e0c9f01b', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (3, 1, 'about-energy.webp', '../assets/images/about-energy.webp', NULL, 'image/webp', 42600, 1200, 795, 'Dự án năng lượng tái tạo của Đông Sơn Holdings', NULL, NULL, 'dd67d5b518a9b60d82ed641682841a9cc8b7754ece5c57acf62a2648fd18b1c0', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (4, 1, 'arrow-right-red.svg', '../assets/images/arrow-right-red.svg', NULL, 'image/svg+xml', 516, NULL, NULL, 'Arrow right red', NULL, NULL, '7627fbf7b7cbde0105896e637b3d292b814473abfbd5c32e8e18835051cb9bcd', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (5, 1, 'arrow-right.svg', '../assets/images/arrow-right.svg', NULL, 'image/svg+xml', 530, NULL, NULL, 'Arrow right', NULL, NULL, '52165cf2b1509a1698cf75366a04b4daff26b20f06880897f59c5da6802cecc0', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (6, 1, 'bot-interchange.webp', '../assets/images/bot-interchange.webp', NULL, 'image/webp', 185534, 1400, 788, 'Nút giao thông hạ tầng BOT của Đông Sơn Holdings', NULL, NULL, '65780f35564f675706cb0a0c1b2c4ad5bac62a7504b907cb6b1c4d852dc02bab', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (7, 1, 'caret-down.svg', '../assets/images/caret-down.svg', NULL, 'image/svg+xml', 372, NULL, NULL, 'Caret down', NULL, NULL, '75b70a56e756fd0ae24964fdbbf32ab8729c845125ab7120d0cd78086b249250', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (8, 1, 'chevron-left.svg', '../assets/images/chevron-left.svg', NULL, 'image/svg+xml', 386, NULL, NULL, 'Chevron left', NULL, NULL, 'f60a8b23a70aa5c1ad2996bdf3893657246e3a642f83a4f2974308176a2d71ef', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (9, 1, 'chevron-right.svg', '../assets/images/chevron-right.svg', NULL, 'image/svg+xml', 386, NULL, NULL, 'Chevron right', NULL, NULL, '885fd20b13bb85541d1769d211f8f40ec791d00616ed8f390a751b19b98a9ddb', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (10, 1, 'cta-bridge.webp', '../assets/images/cta-bridge.webp', NULL, 'image/webp', 76316, 1024, 576, 'Cầu vượt do Đông Sơn Holdings thi công', NULL, NULL, '0ef9e40e59d9e81ad00fa5ca3da692546859de7c09ede73e72d99497742293ee', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (11, 1, 'doitac-bridge-night.webp', '../assets/images/doitac-bridge-night.webp', NULL, 'image/webp', 234928, 2000, 1050, 'Cầu về đêm — dự án hạ tầng Đông Sơn Holdings', NULL, NULL, 'be7f10a6b513e65df374cbe905828ca4632f0609f926c4ec162aa5d0eed6e85e', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (12, 1, 'duan-01-bot.webp', '../assets/images/duan-01-bot.webp', NULL, 'image/webp', 164656, 900, 562, 'BOT Hà Nội – Bắc Giang, Quốc lộ 1', NULL, NULL, 'd3b01fa9b229d42464f197bb97e4fdafd6a106869aaa167cc2464e16d867506a', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (13, 1, 'duan-02-dothi.webp', '../assets/images/duan-02-dothi.webp', NULL, 'image/webp', 79454, 900, 617, 'Khu đô thị hiện đại do Đông Sơn Holdings phát triển', NULL, NULL, 'a676e6302d0c63b816334f2045895c0f3bbcc0d6df7453a5e7341def315a2bcc', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (14, 1, 'duan-03-nhao.webp', '../assets/images/duan-03-nhao.webp', NULL, 'image/webp', 112868, 900, 675, 'Tổ hợp căn hộ đã bàn giao', NULL, NULL, '4e4615dfd8fd898f56a35da971797589fe96a41a8a391d3c564da3da45f40c65', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (15, 1, 'duan-04-thicong.webp', '../assets/images/duan-04-thicong.webp', NULL, 'image/webp', 63120, 900, 506, 'Công trình đang thi công phần thân', NULL, NULL, 'c77d4ea66d7e963c52e0c0f31eef0e05f32da9f9e12a4fb735171752bac7413f', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (16, 1, 'giatri-bg.webp', '../assets/images/giatri-bg.webp', NULL, 'image/webp', 85594, 1600, 900, 'Công trình tiêu biểu của Đông Sơn Holdings', NULL, NULL, '980fd454b54a007513afd9ab84fbaaf6dc60e68a9a830aa3492393bfa845d4c2', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (17, 1, 'giatri-icon-award.svg', '../assets/images/giatri-icon-award.svg', NULL, 'image/svg+xml', 3355, NULL, NULL, 'Giatri icon award', NULL, NULL, '5dfa45a4fa1ee207a71de204c48657a15ffc5d126c600dd4e10a41ddc838a13a', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (18, 1, 'giatri-icon-innovation.svg', '../assets/images/giatri-icon-innovation.svg', NULL, 'image/svg+xml', 490, NULL, NULL, 'Giatri icon innovation', NULL, NULL, '1e446eaae246430901eba907326c9cc9aace7cdb2b57f378a28e0eac2ae10ef2', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (19, 1, 'giatri-icon-person.svg', '../assets/images/giatri-icon-person.svg', NULL, 'image/svg+xml', 1080, NULL, NULL, 'Giatri icon person', NULL, NULL, 'de50b0d28d4b581e67a19c4a3b34845b62b0851d11e41c4f3cf58428cf64e437', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (20, 1, 'giatri-icon-shield.svg', '../assets/images/giatri-icon-shield.svg', NULL, 'image/svg+xml', 786, NULL, NULL, 'Giatri icon shield', NULL, NULL, 'fad619b7570f2d160d93d7e21c50751adb42cff88d1f0fdc49abbe61a3d4ad2e', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (21, 1, 'hero-bg.webp', '../assets/images/hero-bg.webp', NULL, 'image/webp', 81312, 1024, 576, 'Công trình hạ tầng của Đông Sơn Holdings', NULL, NULL, '05c71794068bdec53ed9490f13ffad7427c3dbe9e6c66b61b1608fabb585f7d5', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (22, 1, 'icon-calendar.svg', '../assets/images/icon-calendar.svg', NULL, 'image/svg+xml', 566, NULL, NULL, 'Icon calendar', NULL, NULL, '35c3003781c81701d748c31f741076edf6bd70ca13f9e8387edeb5c1b88bccec', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (23, 1, 'icon-email.svg', '../assets/images/icon-email.svg', NULL, 'image/svg+xml', 924, NULL, NULL, 'Icon email', NULL, NULL, '4795b209a3a41f87436796cdfcdb11f278f6c357f0ac5a901f8832bb375d04ce', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (24, 1, 'icon-phone.svg', '../assets/images/icon-phone.svg', NULL, 'image/svg+xml', 1559, NULL, NULL, 'Icon phone', NULL, NULL, '783aa8a156a9dc99507dca7591acfba7c54800a6739a57aa8ef7a1574488ac97', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (25, 1, 'icon-pin.svg', '../assets/images/icon-pin.svg', NULL, 'image/svg+xml', 1062, NULL, NULL, 'Icon pin', NULL, NULL, '48517ec25c349fce856be4dfc0a51baad7933b853292ceccc3f2588c29e31c4a', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (26, 1, 'linhvuc-crane.webp', '../assets/images/linhvuc-crane.webp', NULL, 'image/webp', 24944, 735, 490, 'Cần cẩu trên công trường Đông Sơn Holdings', NULL, NULL, 'fe53322f7800be7bcedd962761a43117f30104418c6ea911db23bcc1b6b027b3', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (27, 1, 'logo-red.webp', '../assets/images/logo-red.webp', NULL, 'image/webp', 73720, 874, 890, 'Biểu tượng Đông Sơn Holdings', NULL, NULL, '7d7764e505ff2949bfab925cecebcf2f6beb2f46d48bd848ed7bff886c444581', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (28, 1, 'logo.webp', '../assets/images/logo.webp', NULL, 'image/webp', 47842, 874, 890, 'Đông Sơn Holdings', NULL, NULL, 'ab734eec0abf894322545eab1e2ce6489c44c1c0d2dc65a72ed991d1d383c46c', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (29, 1, 'news-01.webp', '../assets/images/news-01.webp', NULL, 'image/webp', 60020, 800, 550, 'Khu nhà ở xã hội Bãi Viên – Nam Định', NULL, NULL, 'be94a8102f8a7dae02159eb5fe87819f4e4a28fb9aaefcc97c27a2d2f2c14672', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (30, 1, 'news-02.webp', '../assets/images/news-02.webp', NULL, 'image/webp', 175960, 1400, 960, 'Khu đô thị do Đông Sơn Holdings đầu tư', NULL, NULL, '2aa784cb0167fbdc4b98fb3467f070a7ce05fc82d06058ee19ecc68e9ab79886', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (31, 1, 'partner-1.webp', '../assets/images/partner-1.webp', NULL, 'image/webp', 40008, 576, 414, 'Tổng công ty 319 — Bộ Quốc phòng', NULL, NULL, 'c47c54e8d859e35aa8b2232e49da2d1392ff48a3406adcad236253c0a177aee1', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (32, 1, 'partner-2.webp', '../assets/images/partner-2.webp', NULL, 'image/webp', 14510, 594, 336, 'OGC Group', NULL, NULL, '484933d03df54d8e8a1905ef2498900e19018b85bfe906b93614385dd41f8476', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (33, 1, 'partner-3.webp', '../assets/images/partner-3.webp', NULL, 'image/webp', 47816, 1257, 689, 'Vinaconex', NULL, NULL, '68fff9512b0d178e1cfc32f6e59cc39bc00ad47bafa87dc6c863246aceeec729', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (34, 1, 'partner-4.webp', '../assets/images/partner-4.webp', NULL, 'image/webp', 15858, 960, 470, 'Văn Phú – Invest', NULL, NULL, '67b329f6e9c8d75ad4eca7b16ca2a9dac70bdeb8e4d8bac287d1fe1245c2ca1b', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (35, 1, 'partner-5.webp', '../assets/images/partner-5.webp', NULL, 'image/webp', 25688, 512, 512, 'Tư Lập', NULL, NULL, 'e500daf8179322592fb8d7f6d7f2ab700098ed71a535e1cd416600b0be790ff8', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (36, 1, 'partner-6.webp', '../assets/images/partner-6.webp', NULL, 'image/webp', 74680, 2250, 1250, 'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)', NULL, NULL, 'f4f55219afdd9e1feb3dfb969fb96df5759583da1c42572187dd2fb888688f96', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (37, 1, 'partner-7.webp', '../assets/images/partner-7.webp', NULL, 'image/webp', 6582, 448, 446, 'Sở Giao dịch Chứng khoán Hà Nội (HNX)', NULL, NULL, 'd9f19c74f55fa32996fcd0ef3a9486ed7ab5b2369f77ccf4beb7b085b8f99502', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (38, 1, 'placeholder.svg', '../assets/images/placeholder.svg', NULL, 'image/svg+xml', 678, NULL, NULL, 'Placeholder', NULL, NULL, '2c0ae7d1f1c7abd4abf69045b0c0983ab8992f64ab4e98dff8763ab64968fbb2', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (39, 1, 'social-facebook.svg', '../assets/images/social-facebook.svg', NULL, 'image/svg+xml', 825, NULL, NULL, 'Social facebook', NULL, NULL, '4bcd9b812205af0fb9d74059a3b0e00c91bfa1d58f091d79b3a80f9c995f0147', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (40, 1, 'social-linkedin.svg', '../assets/images/social-linkedin.svg', NULL, 'image/svg+xml', 1501, NULL, NULL, 'Social linkedin', NULL, NULL, 'c51a5a3795b76ee7737327d9d5276a19618bf7545627c58faff298f1f5c84d85', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (41, 1, 'social-youtube.svg', '../assets/images/social-youtube.svg', NULL, 'image/svg+xml', 1297, NULL, NULL, 'Social youtube', NULL, NULL, '171e861bb80e31e3447997d2f8c54ed6551358614051ea03d18ba92d74e6969c', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);
INSERT INTO `pvn_media_files` VALUES (42, 1, 'timeline-cityscape.svg', '../assets/images/timeline-cityscape.svg', NULL, 'image/svg+xml', 16788, NULL, NULL, 'Timeline cityscape', NULL, NULL, '9f782e0bada8f19f6d208d8ffad4b7c6cf9241d1237a857b57a780b12ebe97f6', NULL, NULL, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);

-- ----------------------------
-- Table structure for pvn_media_folders
-- ----------------------------
DROP TABLE IF EXISTS `pvn_media_folders`;
CREATE TABLE `pvn_media_folders`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_media_folders_slug`(`slug`) USING BTREE,
  INDEX `idx_media_folders_parent_id`(`parent_id`) USING BTREE,
  CONSTRAINT `fk_media_folders_media_folders` FOREIGN KEY (`parent_id`) REFERENCES `pvn_media_folders` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_media_folders
-- ----------------------------
INSERT INTO `pvn_media_folders` VALUES (1, NULL, 'Ảnh website', 'anh-website', 1, '2026-07-24 16:34:47', '2026-07-24 16:34:47', NULL);

-- ----------------------------
-- Table structure for pvn_menu_items
-- ----------------------------
DROP TABLE IF EXISTS `pvn_menu_items`;
CREATE TABLE `pvn_menu_items`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `location_id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NULL DEFAULT NULL COMMENT 'NULL = mục gốc',
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'route' COMMENT 'route|url|divider',
  `route` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Route Yii nội bộ khi item_type=route',
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Link khi item_type=url',
  `target` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self' COMMENT '_self|_blank',
  `icon` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Class Bootstrap Icons bi-*',
  `perm` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Khoá RBAC; NULL = ai cũng thấy',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `depth` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Cache độ sâu (0 = gốc)',
  `is_protected` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = không cho xoá/ẩn',
  `css_class` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_menu_items_location`(`location_id`) USING BTREE,
  INDEX `idx_menu_items_parent`(`parent_id`) USING BTREE,
  INDEX `idx_menu_items_sort`(`location_id`, `parent_id`, `sort_order`) USING BTREE,
  CONSTRAINT `fk_menu_items_location` FOREIGN KEY (`location_id`) REFERENCES `pvn_menu_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_items_parent` FOREIGN KEY (`parent_id`) REFERENCES `pvn_menu_items` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_menu_items
-- ----------------------------
INSERT INTO `pvn_menu_items` VALUES (18, 2, NULL, 'Về chúng tôi', 'url', NULL, 'about.html', '_self', NULL, NULL, 1, 0, 0, 'nav-caret', 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (19, 2, 18, 'Giới thiệu', 'url', NULL, 'about.html', '_self', NULL, NULL, 1, 1, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (20, 2, 18, 'Sứ mệnh - Tầm nhìn', 'url', NULL, 'sumenh.html', '_self', NULL, NULL, 2, 1, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (21, 2, 18, 'Sơ đồ tổ chức', 'url', NULL, 'sodo-to-chuc.html', '_self', NULL, NULL, 3, 1, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (22, 2, NULL, 'Lĩnh vực', 'url', NULL, '#linh-vuc', '_self', NULL, NULL, 2, 0, 0, 'nav-caret', 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (23, 2, NULL, 'Dự án', 'url', NULL, '#du-an', '_self', NULL, NULL, 3, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (24, 2, NULL, 'Quan hệ cổ đông', 'url', NULL, '#co-dong', '_self', NULL, NULL, 4, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (25, 2, NULL, 'Tin tức', 'url', NULL, 'tintuc.html', '_self', NULL, NULL, 5, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (26, 3, NULL, 'Giới thiệu', 'url', NULL, '#gioi-thieu', '_self', NULL, NULL, 1, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (27, 3, NULL, 'Tầm nhìn & Sứ mệnh', 'url', NULL, '#gioi-thieu', '_self', NULL, NULL, 2, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (28, 3, NULL, 'Ban lãnh đạo', 'url', NULL, '#gioi-thieu', '_self', NULL, NULL, 3, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (29, 3, NULL, 'Giá trị cốt lõi', 'url', NULL, '#gioi-thieu', '_self', NULL, NULL, 4, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (30, 3, NULL, 'Trách nhiệm XH', 'url', NULL, '#gioi-thieu', '_self', NULL, NULL, 5, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (31, 4, NULL, 'Thi công & Xây lắp', 'url', NULL, '#linh-vuc', '_self', NULL, NULL, 1, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (32, 4, NULL, 'Đầu tư BOT', 'url', NULL, '#linh-vuc', '_self', NULL, NULL, 2, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (33, 4, NULL, 'Nhà ở & Đô thị', 'url', NULL, '#linh-vuc', '_self', NULL, NULL, 3, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (34, 4, NULL, 'Năng lượng & KCN', 'url', NULL, '#linh-vuc', '_self', NULL, NULL, 4, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (35, 5, NULL, 'BOT Hà Nội – Bắc Giang', 'url', NULL, '#du-an', '_self', NULL, NULL, 1, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (36, 5, NULL, 'Nhà ở XH Bãi Viên', 'url', NULL, '#du-an', '_self', NULL, NULL, 2, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (37, 5, NULL, 'Cao tốc TQ–HG', 'url', NULL, '#du-an', '_self', NULL, NULL, 3, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (38, 5, NULL, 'Mỹ Đình – Bái Đính', 'url', NULL, '#du-an', '_self', NULL, NULL, 4, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (39, 6, NULL, 'Báo cáo tài chính', 'url', NULL, '#co-dong', '_self', NULL, NULL, 1, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (40, 6, NULL, 'Công bố thông tin', 'url', NULL, '#co-dong', '_self', NULL, NULL, 2, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (41, 6, NULL, 'Báo cáo thường niên', 'url', NULL, '#co-dong', '_self', NULL, NULL, 3, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (42, 6, NULL, 'ĐHĐCĐ 2026', 'url', NULL, '#co-dong', '_self', NULL, NULL, 4, 0, 0, NULL, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_items` VALUES (43, 7, NULL, 'Tổng quan', 'route', '/admin/default/index', NULL, '_self', 'fa-tachometer', NULL, 1, 0, 1, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (44, 7, NULL, 'Nội dung trang chủ', 'divider', NULL, NULL, '_self', NULL, NULL, 2, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (45, 7, NULL, 'Hero slider', 'route', '/admin/heroSlide/index', NULL, '_self', 'fa-clone', 'hero_slides.view', 3, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (46, 7, NULL, 'Lĩnh vực kinh doanh', 'route', '/admin/sector/index', NULL, '_self', 'fa-sitemap', 'business_sectors.view', 4, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (47, 7, NULL, 'Dự án', 'route', '/admin/project/index', NULL, '_self', 'fa-building', 'projects.view', 5, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (48, 7, NULL, 'Giá trị cốt lõi', 'route', '/admin/coreValue/index', NULL, '_self', 'fa-trophy', 'core_values.view', 6, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (49, 7, NULL, 'Hành trình', 'route', '/admin/timeline/index', NULL, '_self', 'fa-history', 'timeline_milestones.view', 7, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (50, 7, NULL, 'Đối tác & cổ đông', 'route', '/admin/partner/index', NULL, '_self', 'fa-users', 'partners.view', 8, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (51, 7, NULL, 'Tin tức', 'divider', NULL, NULL, '_self', NULL, NULL, 9, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (52, 7, NULL, 'Bài viết', 'route', '/admin/newsPost/index', NULL, '_self', 'fa-newspaper-o', 'news_posts.view', 10, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (53, 7, NULL, 'Danh mục tin', 'route', '/admin/newsCategory/index', NULL, '_self', 'fa-tags', 'news_categories.view', 11, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (54, 7, NULL, 'Hệ thống', 'divider', NULL, NULL, '_self', NULL, NULL, 12, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (55, 7, NULL, 'Menu website', 'route', '/admin/menu/index', NULL, '_self', 'fa-list-ul', 'menus.view', 13, 0, 1, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (56, 7, NULL, 'Thư viện media', 'route', '/admin/media/index', NULL, '_self', 'fa-picture-o', 'media.view', 14, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (57, 7, NULL, 'Cấu hình website', 'route', '/admin/setting/index', NULL, '_self', 'fa-cog', 'settings.view', 15, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (58, 7, NULL, 'Người dùng', 'route', '/admin/user/index', NULL, '_self', 'fa-user', 'users.view', 16, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (59, 7, NULL, 'Nhóm quyền', 'route', '/admin/role/index', NULL, '_self', 'fa-shield', 'roles.view', 17, 0, 1, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (60, 7, NULL, 'Nhật ký', 'route', '/admin/audit/index', NULL, '_self', 'fa-file-text-o', 'audit.view', 18, 0, 0, NULL, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);
INSERT INTO `pvn_menu_items` VALUES (61, 7, NULL, 'Cấu hình chức năng', 'route', '/admin/feature/index', NULL, '_self', 'fa-sliders', 'features.view', 19, 0, 1, NULL, 1, '2026-07-31 14:03:46', '2026-07-31 14:03:46', NULL);

-- ----------------------------
-- Table structure for pvn_menu_locations
-- ----------------------------
DROP TABLE IF EXISTS `pvn_menu_locations`;
CREATE TABLE `pvn_menu_locations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug bất biến dùng trong code',
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `supports_nesting` tinyint(1) NOT NULL DEFAULT 1,
  `max_depth` tinyint(4) NOT NULL DEFAULT 2 COMMENT 'Số cấp tối đa',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_menu_locations_code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_menu_locations
-- ----------------------------
INSERT INTO `pvn_menu_locations` VALUES (2, 'public_header', 'Menu Header', 'Thanh điều hướng chính trên header website.', 1, 2, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_locations` VALUES (3, 'public_footer_about', 'Về Đông Sơn', 'Cột 2 của footer.', 0, 1, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_locations` VALUES (4, 'public_footer_sectors', 'Lĩnh vực', 'Cột 3 của footer.', 0, 1, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_locations` VALUES (5, 'public_footer_projects', 'Dự án', 'Cột 4 của footer.', 0, 1, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_locations` VALUES (6, 'public_footer_investors', 'Nhà đầu tư', 'Cột 5 của footer.', 0, 1, 1, '2026-07-31 10:31:03', '2026-07-31 10:31:03', NULL);
INSERT INTO `pvn_menu_locations` VALUES (7, 'admin_sidebar', 'Sidebar quản trị', 'Menu bên trái trong khu vực quản trị (main.php).', 1, 2, 1, '2026-07-31 10:52:08', '2026-07-31 10:52:08', NULL);

-- ----------------------------
-- Table structure for pvn_news_categories
-- ----------------------------
DROP TABLE IF EXISTS `pvn_news_categories`;
CREATE TABLE `pvn_news_categories`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `parent_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `show_in_filter` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_news_categories_slug`(`slug`) USING BTREE,
  INDEX `idx_news_categories_sort_order`(`sort_order`) USING BTREE,
  INDEX `fk_news_categories_parent`(`parent_id`) USING BTREE,
  CONSTRAINT `fk_news_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `pvn_news_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_news_categories
-- ----------------------------
INSERT INTO `pvn_news_categories` VALUES (7, 'du-an', 'Dự án', NULL, NULL, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (8, 'thi-cong', 'Thi công', NULL, NULL, 2, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (9, 'dau-tu', 'Đầu tư', NULL, NULL, 3, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (10, 'co-dong', 'Cổ đông', NULL, NULL, 4, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_news_posts
-- ----------------------------
DROP TABLE IF EXISTS `pvn_news_posts`;
CREATE TABLE `pvn_news_posts`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(220) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Chỉ card lớn hiển thị',
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `thumbnail_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `published_at` datetime NOT NULL,
  `date_display_format` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'd/m/Y',
  `author_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `card_size` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sm' COMMENT 'lg|tall|sm',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `view_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `source_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_news_posts_slug`(`slug`) USING BTREE,
  INDEX `idx_news_posts_category_published`(`category_id`, `published_at`) USING BTREE,
  INDEX `idx_news_posts_status_published`(`status`, `published_at`) USING BTREE,
  INDEX `idx_news_posts_is_featured`(`is_featured`) USING BTREE,
  INDEX `fk_news_posts_thumbnail_media`(`thumbnail_media_id`) USING BTREE,
  INDEX `fk_news_posts_users`(`author_id`) USING BTREE,
  CONSTRAINT `fk_news_posts_news_categories` FOREIGN KEY (`category_id`) REFERENCES `pvn_news_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_news_posts_thumbnail_media` FOREIGN KEY (`thumbnail_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_news_posts_users` FOREIGN KEY (`author_id`) REFERENCES `pvn_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_news_posts
-- ----------------------------
INSERT INTO `pvn_news_posts` VALUES (5, 'dau-tu-du-an-nha-o-xa-hoi-bai-vien-nam-dinh', 7, 'Đông Sơn Holdings đầu tư dự án nhà ở xã hội Bãi Viên – Nam Định', 'CTCP Đông Sơn Holdings chính thức công bố đầu tư Khu nhà ở xã hội Bãi Viên tại TP. Nam Định, tổng mức đầu tư hơn 909 tỷ đồng với 1.100 căn hộ, khởi công tháng 5/2025.', NULL, 29, '2026-03-09 08:00:00', 'd/m/Y', NULL, 'lg', 1, 1, 0, 'published', NULL, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_posts` VALUES (6, 'tang-von-dieu-le-len-350-ty-dong', 9, 'Tăng vốn điều lệ lên 350 tỷ đồng, mở rộng danh mục đầu tư', NULL, NULL, 30, '2025-11-01 08:00:00', 'm/Y', NULL, 'tall', 0, 1, 0, 'published', NULL, 2, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_posts` VALUES (7, 'khoi-cong-goi-thau-xay-lap-trong-diem', 8, 'Khởi công gói thầu xây lắp trọng điểm, bảo đảm tiến độ toàn tuyến', NULL, NULL, 15, '2025-05-01 08:00:00', 'm/Y', NULL, 'sm', 0, 1, 0, 'published', NULL, 3, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_posts` VALUES (8, 'co-phieu-dsh-giao-dich-tren-upcom', 10, 'Cổ phiếu DSH chính thức giao dịch trên UPCOM', NULL, NULL, 12, '2025-04-22 08:00:00', 'd/m/Y', NULL, 'sm', 0, 1, 0, 'published', NULL, 4, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_partners
-- ----------------------------
DROP TABLE IF EXISTS `pvn_partners`;
CREATE TABLE `pvn_partners`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dùng làm alt của logo',
  `logo_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `website_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `partner_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'partner' COMMENT 'partner|shareholder|regulator',
  `ownership_percent` decimal(5, 2) NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_partners_type_sort`(`partner_type`, `sort_order`) USING BTREE,
  INDEX `fk_partners_logo_media`(`logo_media_id`) USING BTREE,
  CONSTRAINT `fk_partners_logo_media` FOREIGN KEY (`logo_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_partners
-- ----------------------------
INSERT INTO `pvn_partners` VALUES (8, 'Tổng công ty 319 — Bộ Quốc phòng', 31, NULL, 'shareholder', 15.00, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (9, 'OGC Group', 32, NULL, 'shareholder', NULL, 2, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (10, 'Vinaconex', 33, NULL, 'partner', NULL, 3, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (11, 'Văn Phú – Invest', 34, NULL, 'partner', NULL, 4, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (12, 'Tư Lập', 35, NULL, 'partner', NULL, 5, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (13, 'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)', 36, NULL, 'regulator', NULL, 6, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_partners` VALUES (14, 'Sở Giao dịch Chứng khoán Hà Nội (HNX)', 37, NULL, 'regulator', NULL, 7, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_projects
-- ----------------------------
DROP TABLE IF EXISTS `pvn_projects`;
CREATE TABLE `pvn_projects`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sector_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `content` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `thumbnail_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `investment_amount` decimal(18, 2) NULL DEFAULT NULL COMMENT 'Không dùng FLOAT cho tiền',
  `investment_currency` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'VND',
  `investment_display` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Chuỗi hiển thị: 4.213 tỷ đồng',
  `scale_display` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '1.100 căn hộ',
  `start_date` date NULL DEFAULT NULL,
  `completion_date` date NULL DEFAULT NULL,
  `project_status` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'operating' COMMENT 'planning|construction|operating|completed',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft' COMMENT 'draft|published|archived',
  `published_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_projects_slug`(`slug`) USING BTREE,
  INDEX `idx_projects_featured_sort`(`is_featured`, `sort_order`) USING BTREE,
  INDEX `idx_projects_sector_id`(`sector_id`) USING BTREE,
  INDEX `idx_projects_status_published_at`(`status`, `published_at`) USING BTREE,
  INDEX `fk_projects_thumbnail_media`(`thumbnail_media_id`) USING BTREE,
  CONSTRAINT `fk_projects_business_sectors` FOREIGN KEY (`sector_id`) REFERENCES `pvn_business_sectors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_projects_thumbnail_media` FOREIGN KEY (`thumbnail_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_projects
-- ----------------------------
INSERT INTO `pvn_projects` VALUES (6, 'bot-ha-noi-bac-giang', 'BOT Hà Nội – Bắc Giang', 'Quốc lộ 1, Hà Nội – Bắc Giang', 'Bắc Giang', 6, NULL, NULL, 12, 4213000000000.00, 'VND', '4.213 tỷ đồng', 'Thời gian thu phí 21 năm', NULL, NULL, 'operating', 1, 1, 1, 'published', '2026-07-24 16:45:10', '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_projects` VALUES (7, 'khu-do-thi-dong-son', 'Khu đô thị Đông Sơn', 'Thành phố Thanh Hóa', 'Thanh Hóa', 7, NULL, NULL, 13, NULL, 'VND', NULL, NULL, NULL, NULL, 'construction', 1, 2, 1, 'published', '2026-07-24 16:45:10', '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_projects` VALUES (8, 'nha-o-xa-hoi-bai-vien', 'Nhà ở xã hội Bãi Viên', 'Thành phố Nam Định', 'Nam Định', 7, NULL, NULL, 12, 909000000000.00, 'VND', 'hơn 909 tỷ đồng', '1.100 căn hộ', NULL, NULL, 'construction', 1, 3, 1, 'published', '2026-07-24 16:45:10', '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_projects` VALUES (9, 'to-hop-can-ho-song-dao', 'Tổ hợp căn hộ Sông Đào', 'Thành phố Nam Định', 'Nam Định', 7, NULL, NULL, 14, NULL, 'VND', NULL, NULL, NULL, NULL, 'completed', 1, 4, 1, 'published', '2026-07-24 16:45:10', '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_projects` VALUES (10, 'du-an-dang-thi-cong-ha-noi', 'Dự án đang thi công', 'Hà Nội', 'Hà Nội', 5, NULL, NULL, 15, NULL, 'VND', NULL, NULL, NULL, NULL, 'construction', 1, 5, 1, 'published', '2026-07-24 16:45:10', '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_sessions
-- ----------------------------
DROP TABLE IF EXISTS `pvn_sessions`;
CREATE TABLE `pvn_sessions`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lưu hash, không lưu token thô',
  `refresh_token_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `revoked_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_sessions_token_hash`(`token_hash`) USING BTREE,
  INDEX `idx_sessions_user_id`(`user_id`) USING BTREE,
  INDEX `idx_sessions_expires_at`(`expires_at`) USING BTREE,
  CONSTRAINT `fk_sessions_users` FOREIGN KEY (`user_id`) REFERENCES `pvn_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for pvn_site_settings
-- ----------------------------
DROP TABLE IF EXISTS `pvn_site_settings`;
CREATE TABLE `pvn_site_settings`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `value_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string' COMMENT 'string|number|boolean|json|media',
  `group_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Nhãn hiển thị trong admin',
  `hint` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_site_settings_setting_key`(`setting_key`) USING BTREE,
  INDEX `idx_site_settings_group_name`(`group_name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_site_settings
-- ----------------------------
INSERT INTO `pvn_site_settings` VALUES (16, 'company_name', 'Công ty Cổ phần Đông Sơn Holdings', 'string', 'general', 'Tên công ty', NULL, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (17, 'company_short_name', 'Đông Sơn Holdings', 'string', 'general', 'Tên rút gọn', NULL, 2, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (18, 'stock_code', 'DSH', 'string', 'general', 'Mã chứng khoán', NULL, 3, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (19, 'hotline', '024 3933 5708', 'string', 'contact', 'Điện thoại', NULL, 4, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (20, 'contact_email', 'hatangdongson@htds.vn', 'string', 'contact', 'Email liên hệ', NULL, 5, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (21, 'head_office_address', '', 'string', 'contact', 'Địa chỉ trụ sở', NULL, 6, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (22, 'copyright_text', '© 2026 Công ty Cổ phần Đông Sơn Holdings (DSH). Bảo lưu mọi quyền.', 'string', 'general', 'Dòng bản quyền', NULL, 7, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (23, 'social_facebook', '#', 'string', 'social', 'Facebook', NULL, 8, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (24, 'social_linkedin', '#', 'string', 'social', 'LinkedIn', NULL, 9, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (25, 'social_youtube', '#', 'string', 'social', 'YouTube', NULL, 10, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (26, 'default_meta_title', 'Đông Sơn Holdings — Kiến tạo hạ tầng, vững bước tương lai', 'string', 'seo', 'Tiêu đề SEO mặc định', NULL, 11, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (27, 'default_meta_description', 'Đông Sơn Holdings (DSH) — tập đoàn đầu tư hạ tầng BOT, bất động sản và năng lượng, kiến tạo những công trình trọng điểm cho tương lai Việt Nam.', 'string', 'seo', 'Mô tả SEO mặc định', NULL, 12, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (28, 'cta_title', 'Khám phá tiềm năng\\nBắt đầu kết nối.', 'string', 'general', 'Tiêu đề banner CTA', NULL, 13, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (29, 'cta_button_label', 'Liên lạc ngay', 'string', 'general', 'Nhãn nút CTA', NULL, 14, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');
INSERT INTO `pvn_site_settings` VALUES (30, 'cta_button_url', 'mailto:hatangdongson@htds.vn', 'string', 'general', 'Link nút CTA', NULL, 15, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10');

-- ----------------------------
-- Table structure for pvn_timeline_milestones
-- ----------------------------
DROP TABLE IF EXISTS `pvn_timeline_milestones`;
CREATE TABLE `pvn_timeline_milestones`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year_label` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Chuỗi để hỗ trợ dạng 2024–2025',
  `year_value` smallint(6) NOT NULL COMMENT 'Dùng để ORDER BY',
  `event_date` date NULL DEFAULT NULL,
  `eyebrow` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `side` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto' COMMENT 'left|right|auto',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_timeline_milestones_year_value`(`year_value`) USING BTREE,
  INDEX `idx_timeline_milestones_sort_order`(`sort_order`) USING BTREE,
  INDEX `fk_timeline_milestones_media`(`image_media_id`) USING BTREE,
  CONSTRAINT `fk_timeline_milestones_media` FOREIGN KEY (`image_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_timeline_milestones
-- ----------------------------
INSERT INTO `pvn_timeline_milestones` VALUES (8, '2009', 2009, '2009-12-09', 'Khởi đầu', 'Thành lập công ty', '09/12/2009 – Thành lập Công ty CP Đầu tư & Thương mại 319 với sự tham gia của Tổng công ty 319 – Bộ Quốc phòng (51%) và các cổ đông sáng lập.', NULL, 'left', 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (9, '2014', 2014, NULL, 'Hạ tầng', 'Đầu tư BOT Hà Nội – Bắc Giang', 'Khởi công dự án cải tạo, nâng cấp Quốc lộ 1 đoạn Hà Nội – Bắc Giang. Tổng mức đầu tư 4.213 tỷ đồng, thời gian thu phí 21 năm.', NULL, 'right', 2, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (10, '2017', 2017, NULL, 'Cổ phần hóa', 'Thoái vốn Nhà nước', 'Tổng công ty 319 bán đấu giá 3,6 triệu cổ phần trên HNX, giảm tỷ lệ từ 51% xuống 15%. Giá trúng đấu giá: 11.900 đồng/CP.', NULL, 'left', 3, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (11, '2019', 2019, '2019-10-31', 'Tái cơ cấu', 'Đổi tên thành Đông Sơn', '31/10/2019 – Chính thức đổi tên thành Công ty CP Đầu tư Hạ tầng Đông Sơn theo Giấy ĐKKD số 10 của Sở KH&ĐT Hà Nội.', NULL, 'right', 4, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (12, '2024', 2024, '2024-11-25', 'Đại chúng', 'Trở thành công ty đại chúng', '25/11/2024 – UBCK xác nhận hoàn thành đăng ký công ty đại chúng. 09/12/2024 – Đăng ký lưu ký tập trung tại VSD với mã CK DSH.', NULL, 'left', 5, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (13, '2025', 2025, '2025-04-22', 'Niêm yết', 'Niêm yết UPCOM & tăng vốn', '22/04/2025 – DSH chính thức giao dịch trên UPCOM với giá tham chiếu 18.000 đồng/CP. Tháng 11/2025: tăng vốn điều lệ lên 350 tỷ đồng.', NULL, 'right', 6, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_timeline_milestones` VALUES (14, '2026', 2026, NULL, 'Hiện tại', 'Đông Sơn Holdings', 'Đại hội cổ đông thường niên 2026 thông qua đổi tên thành CTCP Đông Sơn Holdings (DSH). Mở rộng sang lĩnh vực khu công nghiệp và năng lượng.', NULL, 'left', 7, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_users
-- ----------------------------
DROP TABLE IF EXISTS `pvn_users`;
CREATE TABLE `pvn_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'bcrypt cost 12',
  `full_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_media_id` int(10) UNSIGNED NULL DEFAULT NULL,
  `phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` datetime NULL DEFAULT NULL,
  `last_login_at` datetime NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `failed_login_count` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime NULL DEFAULT NULL,
  `two_factor_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reset_token_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'SHA-256 của token đặt lại mật khẩu',
  `reset_token_expires_at` datetime NULL DEFAULT NULL COMMENT 'Hạn dùng token đặt lại mật khẩu',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_users_email`(`email`) USING BTREE,
  INDEX `idx_users_is_active`(`is_active`) USING BTREE,
  INDEX `fk_users_media_files`(`avatar_media_id`) USING BTREE,
  CONSTRAINT `fk_users_media_files` FOREIGN KEY (`avatar_media_id`) REFERENCES `pvn_media_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_users
-- ----------------------------
INSERT INTO `pvn_users` VALUES (1, 'admin@htds.vn', '$2y$12$Ir1BnogCBiXXsY0uc3KYoOqHHws6CybhpXpmIcqlGYqkDQxYNUJEu', 'Quản trị hệ thống', NULL, NULL, 1, NULL, '2026-07-31 10:05:52', '127.0.0.1', 0, NULL, NULL, NULL, NULL, '2026-07-24 16:34:47', '2026-07-30 16:34:12', NULL);

SET FOREIGN_KEY_CHECKS = 1;
