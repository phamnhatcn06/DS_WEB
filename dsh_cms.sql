/*
 Navicat Premium Dump SQL

 Source Server         : Local_MySQL
 Source Server Type    : MySQL
 Source Server Version : 50724 (5.7.24)
 Source Host           : localhost:3306
 Source Schema         : dsh_cms

 Target Server Type    : MySQL
 Target Server Version : 50724 (5.7.24)
 File Encoding         : 65001

 Date: 03/08/2026 06:40:25
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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `migrations` VALUES ('m260801_010000_add_branding_seo_script_settings', 1785661175);
INSERT INTO `migrations` VALUES ('m260802_000000_fix_media_paths_to_theme', 1785661224);
INSERT INTO `migrations` VALUES ('m260803_000000_create_tags_and_taggables', 1785663467);

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
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_audit_logs_entity`(`entity_type`, `entity_id`) USING BTREE,
  INDEX `idx_audit_logs_user_id_created_at`(`user_id`, `created_at`) USING BTREE,
  INDEX `idx_audit_logs_created_at`(`created_at`) USING BTREE,
  CONSTRAINT `fk_audit_logs_users` FOREIGN KEY (`user_id`) REFERENCES `pvn_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `pvn_audit_logs` VALUES (28, 1, 'login', 'User', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-02 17:34:05');
INSERT INTO `pvn_audit_logs` VALUES (29, 1, 'create', 'MediaFile', 158, NULL, '{\"file_size\":2453978,\"file_name\":\"gemini-generated-image-p82qu2p82qu2p82q-20260802173508-15a1ca.png\",\"file_path\":\"uploads\\/202608\\/gemini-generated-image-p82qu2p82qu2p82q-20260802173508-15a1ca.png\",\"mime_type\":\"image\\/png\",\"width\":864,\"height\":1184,\"checksum\":\"e78ec02a69b8c5bf68775128d6d73e6906186342b527c36c4083f13f9c428876\",\"uploaded_by\":1,\"created_at\":{\"expression\":\"NOW()\",\"params\":[]},\"updated_at\":{\"expression\":\"NOW()\",\"params\":[]},\"id\":\"158\",\"folder_id\":null,\"file_url\":null,\"alt_text\":null,\"title\":null,\"caption\":null,\"variants\":null,\"deleted_at\":null}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-02 17:35:08');

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'tags.create');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'tags.create');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'tags.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'tags.delete');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'tags.update');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'tags.update');
INSERT INTO `pvn_auth_item_children` VALUES ('admin', 'tags.view');
INSERT INTO `pvn_auth_item_children` VALUES ('super_admin', 'tags.view');
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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `pvn_auth_items` VALUES ('tags.create', 0, 'Thêm — Thẻ (Tag)', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('tags.delete', 0, 'Xoá — Thẻ (Tag)', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('tags.update', 0, 'Sửa — Thẻ (Tag)', NULL, 'N;');
INSERT INTO `pvn_auth_items` VALUES ('tags.view', 0, 'Xem — Thẻ (Tag)', NULL, 'N;');
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
-- Table structure for pvn_business_sector_tags
-- ----------------------------
DROP TABLE IF EXISTS `pvn_business_sector_tags`;
CREATE TABLE `pvn_business_sector_tags`  (
  `sector_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`sector_id`, `tag_id`) USING BTREE,
  INDEX `idx_business_sector_tags_tag`(`tag_id`) USING BTREE,
  CONSTRAINT `fk_business_sector_tags_sector` FOREIGN KEY (`sector_id`) REFERENCES `pvn_business_sectors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_business_sector_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `pvn_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_business_sector_tags
-- ----------------------------
INSERT INTO `pvn_business_sector_tags` VALUES (5, 1);
INSERT INTO `pvn_business_sector_tags` VALUES (5, 2);
INSERT INTO `pvn_business_sector_tags` VALUES (5, 3);
INSERT INTO `pvn_business_sector_tags` VALUES (5, 4);
INSERT INTO `pvn_business_sector_tags` VALUES (6, 5);
INSERT INTO `pvn_business_sector_tags` VALUES (6, 6);
INSERT INTO `pvn_business_sector_tags` VALUES (6, 7);
INSERT INTO `pvn_business_sector_tags` VALUES (6, 8);
INSERT INTO `pvn_business_sector_tags` VALUES (7, 9);
INSERT INTO `pvn_business_sector_tags` VALUES (7, 10);
INSERT INTO `pvn_business_sector_tags` VALUES (7, 11);
INSERT INTO `pvn_business_sector_tags` VALUES (8, 12);
INSERT INTO `pvn_business_sector_tags` VALUES (8, 13);

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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pvn_business_sectors
-- ----------------------------
INSERT INTO `pvn_business_sectors` VALUES (5, 'thi-cong-xay-lap', '01', 'Thi công & Xây lắp', 'Thi công và xây lắp', 'Nền móng cho mọi công trình', 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.', 'Tổng thầu thi công các công trình giao thông, hạ tầng kỹ thuật, thủy lợi, dân dụng và công nghiệp trên toàn quốc.', 'Thi công & Xây lắp', 'Năng lực tổng thầu EPC cho các công trình trọng điểm, đảm bảo tiến độ, chất lượng và an toàn lao động.', 21, NULL, 'Khám phá dự án', '#du-an', 1, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (6, 'dau-tu-bot-ha-tang', '02', 'Đầu tư BOT & Hạ tầng', 'Đầu tư BOT & Hạ tầng', 'Kết nối hành lang kinh tế', 'Trở thành doanh nghiệp uy tín trong lĩnh vực năng lượng, bất động sản và xây lắp.', 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.', 'Đầu tư BOT & Hạ tầng', 'Đầu tư các dự án hạ tầng giao thông theo hình thức BOT. Dự án tiêu biểu: BOT Hà Nội – Bắc Giang với tổng mức đầu tư 4.213 tỷ đồng.', 6, NULL, 'Khám phá dự án', '#du-an', 2, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (7, 'nha-o-do-thi', '03', 'Nhà ở & Đô thị', 'Nhà ở & Đô thị', 'Kiến tạo không gian sống', 'Phát triển nhà ở xã hội và khu đô thị bền vững, nâng tầm chất lượng sống cho cộng đồng.', 'Phát triển nhà ở xã hội và khu đô thị bền vững. Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.', 'Nhà ở & Đô thị', 'Dự án Nhà ở xã hội Bãi Viên – Nam Định: 1.100 căn hộ, tổng vốn hơn 909 tỷ đồng.', 10, NULL, 'Khám phá dự án', '#du-an', 3, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_business_sectors` VALUES (8, 'nang-luong-kcn', '04', 'Năng lượng & KCN', 'Năng lượng & KCN', 'Động lực tăng trưởng xanh', 'Đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.', 'Định hướng chiến lược mới: đầu tư phát triển khu công nghiệp và năng lượng tái tạo, tạo nền tảng tăng trưởng dài hạn.', 'Năng lượng & KCN', 'Định hướng chiến lược mới: phát triển hạ tầng khu công nghiệp gắn với năng lượng tái tạo.', 21, NULL, 'Khám phá dự án', '#du-an', 4, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
  `variants` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL COMMENT 'Các size responsive đã convert',
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
) ENGINE = InnoDB AUTO_INCREMENT = 159 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pvn_media_files
-- ----------------------------
INSERT INTO `pvn_media_files` VALUES (1, 1, 'about-chevrons.svg', 'themes/dongson/assets/images/about-chevrons.svg', NULL, 'image/svg+xml', 1345, NULL, NULL, 'About chevrons', NULL, NULL, '039a747eb2173d390e4c5bfcc0f7214210eeb5aa5687e65dfffd6756ff914b63', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (2, 1, 'about-construction.webp', 'themes/dongson/assets/images/about-construction.webp', NULL, 'image/webp', 106056, 1200, 675, 'Công trình xây dựng của Đông Sơn Holdings', NULL, NULL, '7ab07154371576aa9156dcd4feba6ce6e8f4ef05d4e9092a2d69d0b1e0c9f01b', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (3, 1, 'about-energy.webp', 'themes/dongson/assets/images/about-energy.webp', NULL, 'image/webp', 42600, 1200, 795, 'Dự án năng lượng tái tạo của Đông Sơn Holdings', NULL, NULL, 'dd67d5b518a9b60d82ed641682841a9cc8b7754ece5c57acf62a2648fd18b1c0', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (4, 1, 'arrow-right-red.svg', 'themes/dongson/assets/images/arrow-right-red.svg', NULL, 'image/svg+xml', 516, NULL, NULL, 'Arrow right red', NULL, NULL, '7627fbf7b7cbde0105896e637b3d292b814473abfbd5c32e8e18835051cb9bcd', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (5, 1, 'arrow-right.svg', 'themes/dongson/assets/images/arrow-right.svg', NULL, 'image/svg+xml', 530, NULL, NULL, 'Arrow right', NULL, NULL, '52165cf2b1509a1698cf75366a04b4daff26b20f06880897f59c5da6802cecc0', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (6, 1, 'bot-interchange.webp', 'themes/dongson/assets/images/bot-interchange.webp', NULL, 'image/webp', 185534, 1400, 788, 'Nút giao thông hạ tầng BOT của Đông Sơn Holdings', NULL, NULL, '65780f35564f675706cb0a0c1b2c4ad5bac62a7504b907cb6b1c4d852dc02bab', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (7, 1, 'caret-down.svg', 'themes/dongson/assets/images/caret-down.svg', NULL, 'image/svg+xml', 372, NULL, NULL, 'Caret down', NULL, NULL, '75b70a56e756fd0ae24964fdbbf32ab8729c845125ab7120d0cd78086b249250', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (8, 1, 'chevron-left.svg', 'themes/dongson/assets/images/chevron-left.svg', NULL, 'image/svg+xml', 386, NULL, NULL, 'Chevron left', NULL, NULL, 'f60a8b23a70aa5c1ad2996bdf3893657246e3a642f83a4f2974308176a2d71ef', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (9, 1, 'chevron-right.svg', 'themes/dongson/assets/images/chevron-right.svg', NULL, 'image/svg+xml', 386, NULL, NULL, 'Chevron right', NULL, NULL, '885fd20b13bb85541d1769d211f8f40ec791d00616ed8f390a751b19b98a9ddb', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (10, 1, 'cta-bridge.webp', 'themes/dongson/assets/images/cta-bridge.webp', NULL, 'image/webp', 76316, 1024, 576, 'Cầu vượt do Đông Sơn Holdings thi công', NULL, NULL, '0ef9e40e59d9e81ad00fa5ca3da692546859de7c09ede73e72d99497742293ee', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (11, 1, 'doitac-bridge-night.webp', 'themes/dongson/assets/images/doitac-bridge-night.webp', NULL, 'image/webp', 234928, 2000, 1050, 'Cầu về đêm — dự án hạ tầng Đông Sơn Holdings', NULL, NULL, 'be7f10a6b513e65df374cbe905828ca4632f0609f926c4ec162aa5d0eed6e85e', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (12, 1, 'duan-01-bot.webp', 'themes/dongson/assets/images/duan-01-bot.webp', NULL, 'image/webp', 164656, 900, 562, 'BOT Hà Nội – Bắc Giang, Quốc lộ 1', NULL, NULL, 'd3b01fa9b229d42464f197bb97e4fdafd6a106869aaa167cc2464e16d867506a', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (13, 1, 'duan-02-dothi.webp', 'themes/dongson/assets/images/duan-02-dothi.webp', NULL, 'image/webp', 79454, 900, 617, 'Khu đô thị hiện đại do Đông Sơn Holdings phát triển', NULL, NULL, 'a676e6302d0c63b816334f2045895c0f3bbcc0d6df7453a5e7341def315a2bcc', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (14, 1, 'duan-03-nhao.webp', 'themes/dongson/assets/images/duan-03-nhao.webp', NULL, 'image/webp', 112868, 900, 675, 'Tổ hợp căn hộ đã bàn giao', NULL, NULL, '4e4615dfd8fd898f56a35da971797589fe96a41a8a391d3c564da3da45f40c65', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (15, 1, 'duan-04-thicong.webp', 'themes/dongson/assets/images/duan-04-thicong.webp', NULL, 'image/webp', 63120, 900, 506, 'Công trình đang thi công phần thân', NULL, NULL, 'c77d4ea66d7e963c52e0c0f31eef0e05f32da9f9e12a4fb735171752bac7413f', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (16, 1, 'giatri-bg.webp', 'themes/dongson/assets/images/giatri-bg.webp', NULL, 'image/webp', 85594, 1600, 900, 'Công trình tiêu biểu của Đông Sơn Holdings', NULL, NULL, '980fd454b54a007513afd9ab84fbaaf6dc60e68a9a830aa3492393bfa845d4c2', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (17, 1, 'giatri-icon-award.svg', 'themes/dongson/assets/images/giatri-icon-award.svg', NULL, 'image/svg+xml', 3355, NULL, NULL, 'Giatri icon award', NULL, NULL, '5dfa45a4fa1ee207a71de204c48657a15ffc5d126c600dd4e10a41ddc838a13a', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (18, 1, 'giatri-icon-innovation.svg', 'themes/dongson/assets/images/giatri-icon-innovation.svg', NULL, 'image/svg+xml', 490, NULL, NULL, 'Giatri icon innovation', NULL, NULL, '1e446eaae246430901eba907326c9cc9aace7cdb2b57f378a28e0eac2ae10ef2', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (19, 1, 'giatri-icon-person.svg', 'themes/dongson/assets/images/giatri-icon-person.svg', NULL, 'image/svg+xml', 1080, NULL, NULL, 'Giatri icon person', NULL, NULL, 'de50b0d28d4b581e67a19c4a3b34845b62b0851d11e41c4f3cf58428cf64e437', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (20, 1, 'giatri-icon-shield.svg', 'themes/dongson/assets/images/giatri-icon-shield.svg', NULL, 'image/svg+xml', 786, NULL, NULL, 'Giatri icon shield', NULL, NULL, 'fad619b7570f2d160d93d7e21c50751adb42cff88d1f0fdc49abbe61a3d4ad2e', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (21, 1, 'hero-bg.webp', 'themes/dongson/assets/images/hero-bg.webp', NULL, 'image/webp', 81312, 1024, 576, 'Công trình hạ tầng của Đông Sơn Holdings', NULL, NULL, '05c71794068bdec53ed9490f13ffad7427c3dbe9e6c66b61b1608fabb585f7d5', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (22, 1, 'icon-calendar.svg', 'themes/dongson/assets/images/icon-calendar.svg', NULL, 'image/svg+xml', 566, NULL, NULL, 'Icon calendar', NULL, NULL, '35c3003781c81701d748c31f741076edf6bd70ca13f9e8387edeb5c1b88bccec', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (23, 1, 'icon-email.svg', 'themes/dongson/assets/images/icon-email.svg', NULL, 'image/svg+xml', 924, NULL, NULL, 'Icon email', NULL, NULL, '4795b209a3a41f87436796cdfcdb11f278f6c357f0ac5a901f8832bb375d04ce', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (24, 1, 'icon-phone.svg', 'themes/dongson/assets/images/icon-phone.svg', NULL, 'image/svg+xml', 1559, NULL, NULL, 'Icon phone', NULL, NULL, '783aa8a156a9dc99507dca7591acfba7c54800a6739a57aa8ef7a1574488ac97', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (25, 1, 'icon-pin.svg', 'themes/dongson/assets/images/icon-pin.svg', NULL, 'image/svg+xml', 1062, NULL, NULL, 'Icon pin', NULL, NULL, '48517ec25c349fce856be4dfc0a51baad7933b853292ceccc3f2588c29e31c4a', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (26, 1, 'linhvuc-crane.webp', 'themes/dongson/assets/images/linhvuc-crane.webp', NULL, 'image/webp', 24944, 735, 490, 'Cần cẩu trên công trường Đông Sơn Holdings', NULL, NULL, 'fe53322f7800be7bcedd962761a43117f30104418c6ea911db23bcc1b6b027b3', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (27, 1, 'logo-red.webp', 'themes/dongson/assets/images/logo-red.webp', NULL, 'image/webp', 73720, 874, 890, 'Biểu tượng Đông Sơn Holdings', NULL, NULL, '7d7764e505ff2949bfab925cecebcf2f6beb2f46d48bd848ed7bff886c444581', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (28, 1, 'logo.webp', 'themes/dongson/assets/images/logo.webp', NULL, 'image/webp', 47842, 874, 890, 'Đông Sơn Holdings', NULL, NULL, 'ab734eec0abf894322545eab1e2ce6489c44c1c0d2dc65a72ed991d1d383c46c', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (29, 1, 'news-01.webp', 'themes/dongson/assets/images/news-01.webp', NULL, 'image/webp', 60020, 800, 550, 'Khu nhà ở xã hội Bãi Viên – Nam Định', NULL, NULL, 'be94a8102f8a7dae02159eb5fe87819f4e4a28fb9aaefcc97c27a2d2f2c14672', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (30, 1, 'news-02.webp', 'themes/dongson/assets/images/news-02.webp', NULL, 'image/webp', 175960, 1400, 960, 'Khu đô thị do Đông Sơn Holdings đầu tư', NULL, NULL, '2aa784cb0167fbdc4b98fb3467f070a7ce05fc82d06058ee19ecc68e9ab79886', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (31, 1, 'partner-1.webp', 'themes/dongson/assets/images/partner-1.webp', NULL, 'image/webp', 40008, 576, 414, 'Tổng công ty 319 — Bộ Quốc phòng', NULL, NULL, 'c47c54e8d859e35aa8b2232e49da2d1392ff48a3406adcad236253c0a177aee1', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (32, 1, 'partner-2.webp', 'themes/dongson/assets/images/partner-2.webp', NULL, 'image/webp', 14510, 594, 336, 'OGC Group', NULL, NULL, '484933d03df54d8e8a1905ef2498900e19018b85bfe906b93614385dd41f8476', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (33, 1, 'partner-3.webp', 'themes/dongson/assets/images/partner-3.webp', NULL, 'image/webp', 47816, 1257, 689, 'Vinaconex', NULL, NULL, '68fff9512b0d178e1cfc32f6e59cc39bc00ad47bafa87dc6c863246aceeec729', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (34, 1, 'partner-4.webp', 'themes/dongson/assets/images/partner-4.webp', NULL, 'image/webp', 15858, 960, 470, 'Văn Phú – Invest', NULL, NULL, '67b329f6e9c8d75ad4eca7b16ca2a9dac70bdeb8e4d8bac287d1fe1245c2ca1b', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (35, 1, 'partner-5.webp', 'themes/dongson/assets/images/partner-5.webp', NULL, 'image/webp', 25688, 512, 512, 'Tư Lập', NULL, NULL, 'e500daf8179322592fb8d7f6d7f2ab700098ed71a535e1cd416600b0be790ff8', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (36, 1, 'partner-6.webp', 'themes/dongson/assets/images/partner-6.webp', NULL, 'image/webp', 74680, 2250, 1250, 'Trung tâm Lưu ký & Bù trừ Chứng khoán Việt Nam (VSDC)', NULL, NULL, 'f4f55219afdd9e1feb3dfb969fb96df5759583da1c42572187dd2fb888688f96', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (37, 1, 'partner-7.webp', 'themes/dongson/assets/images/partner-7.webp', NULL, 'image/webp', 6582, 448, 446, 'Sở Giao dịch Chứng khoán Hà Nội (HNX)', NULL, NULL, 'd9f19c74f55fa32996fcd0ef3a9486ed7ab5b2369f77ccf4beb7b085b8f99502', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (38, 1, 'placeholder.svg', 'themes/dongson/assets/images/placeholder.svg', NULL, 'image/svg+xml', 678, NULL, NULL, 'Placeholder', NULL, NULL, '2c0ae7d1f1c7abd4abf69045b0c0983ab8992f64ab4e98dff8763ab64968fbb2', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (39, 1, 'social-facebook.svg', 'themes/dongson/assets/images/social-facebook.svg', NULL, 'image/svg+xml', 825, NULL, NULL, 'Social facebook', NULL, NULL, '4bcd9b812205af0fb9d74059a3b0e00c91bfa1d58f091d79b3a80f9c995f0147', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (40, 1, 'social-linkedin.svg', 'themes/dongson/assets/images/social-linkedin.svg', NULL, 'image/svg+xml', 1501, NULL, NULL, 'Social linkedin', NULL, NULL, 'c51a5a3795b76ee7737327d9d5276a19618bf7545627c58faff298f1f5c84d85', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (41, 1, 'social-youtube.svg', 'themes/dongson/assets/images/social-youtube.svg', NULL, 'image/svg+xml', 1297, NULL, NULL, 'Social youtube', NULL, NULL, '171e861bb80e31e3447997d2f8c54ed6551358614051ea03d18ba92d74e6969c', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (42, 1, 'timeline-cityscape.svg', 'themes/dongson/assets/images/timeline-cityscape.svg', NULL, 'image/svg+xml', 16788, NULL, NULL, 'Timeline cityscape', NULL, NULL, '9f782e0bada8f19f6d208d8ffad4b7c6cf9241d1237a857b57a780b12ebe97f6', NULL, NULL, '2026-07-24 16:34:47', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (43, 1, '05cb39dfc98db922116eff1af070ea3ac3a18ded.webp', 'themes/dongson/assets/images/05cb39dfc98db922116eff1af070ea3ac3a18ded.webp', NULL, 'image/webp', 7156, 448, 446, '05cb39dfc98db922116eff1af070ea3ac3a18ded', NULL, NULL, '70e6e980a4728bd554e5502d30f2ee7031899161f2f7166e31306ede6d28bff9', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (44, 1, '08ecd43343b9dec3525f5ab25e3a27d14c91567c.svg', 'themes/dongson/assets/images/08ecd43343b9dec3525f5ab25e3a27d14c91567c.svg', NULL, 'image/svg+xml', 1391, NULL, NULL, '08ecd43343b9dec3525f5ab25e3a27d14c91567c', NULL, NULL, '4f12795800502b27282dcc18ea47eac588580c8c65edec31b43196ed3639af29', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (45, 1, '09323f7dfdf4b36278505b34ef51dabfabe13fb4.webp', 'themes/dongson/assets/images/09323f7dfdf4b36278505b34ef51dabfabe13fb4.webp', NULL, 'image/webp', 27460, 512, 512, '09323f7dfdf4b36278505b34ef51dabfabe13fb4', NULL, NULL, '86c877670bff71fa248bf1de5aea7ca277854ed9a0417b1ef31bc10b2dceaaf2', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (46, 1, '0b349491c8d2119abce18459d5420a33cf7c9c7d.svg', 'themes/dongson/assets/images/0b349491c8d2119abce18459d5420a33cf7c9c7d.svg', NULL, 'image/svg+xml', 288, NULL, NULL, '0b349491c8d2119abce18459d5420a33cf7c9c7d', NULL, NULL, '371280b8b621893a9912f905103c273f71540860e03b585f9c7c63ca113354ef', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (47, 1, '0b918d5ba8e86b82ec86acb9bf75caed33279a52.webp', 'themes/dongson/assets/images/0b918d5ba8e86b82ec86acb9bf75caed33279a52.webp', NULL, 'image/webp', 42122, 576, 414, '0b918d5ba8e86b82ec86acb9bf75caed33279a52', NULL, NULL, 'de7e437521554f8babd26ca74514fe1655eb02bc5a98951af805ecf572916355', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (48, 1, '0d874f939969c246d6f6d705998084319da2775e.svg', 'themes/dongson/assets/images/0d874f939969c246d6f6d705998084319da2775e.svg', NULL, 'image/svg+xml', 1573, NULL, NULL, '0d874f939969c246d6f6d705998084319da2775e', NULL, NULL, '722f667e96b0efefc5c480a7350974e3f57c721e77bbc1492b95598c3fc8cda2', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (49, 1, '128dcd65aac5c28301725d3bc77cc2b35dfa23f8.svg', 'themes/dongson/assets/images/128dcd65aac5c28301725d3bc77cc2b35dfa23f8.svg', NULL, 'image/svg+xml', 3910, NULL, NULL, '128dcd65aac5c28301725d3bc77cc2b35dfa23f8', NULL, NULL, '03c59fc418c3623007efe311bfff9cf9680a590ea5fec0579c0e01ae31ef5a34', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (50, 1, '1436b58e389b0395d7e0dbf984f357a67f41f17c.svg', 'themes/dongson/assets/images/1436b58e389b0395d7e0dbf984f357a67f41f17c.svg', NULL, 'image/svg+xml', 957, NULL, NULL, '1436b58e389b0395d7e0dbf984f357a67f41f17c', NULL, NULL, '310c604a03849206d351992d7f9b1d04062821d0fc4332abdbb83e2f243f26e8', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (51, 1, '1924660ccfd8adc39407d8dd4988a2ee5d6f52fa.svg', 'themes/dongson/assets/images/1924660ccfd8adc39407d8dd4988a2ee5d6f52fa.svg', NULL, 'image/svg+xml', 628, NULL, NULL, '1924660ccfd8adc39407d8dd4988a2ee5d6f52fa', NULL, NULL, 'f84a7984acbcd7cea0ee909d866e52eb22cde73d56f2c02eb5748ad832bbdaf6', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (52, 1, '194a843ba249905d0ebb8bc4348ca0995e93526c.svg', 'themes/dongson/assets/images/194a843ba249905d0ebb8bc4348ca0995e93526c.svg', NULL, 'image/svg+xml', 2175, NULL, NULL, '194a843ba249905d0ebb8bc4348ca0995e93526c', NULL, NULL, 'c68111c42c98ee93ba87646f35acd9097c94b84419916892749b628d5ac08406', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (53, 1, '1bcad9de80434d17e7e7294da27cc4db1f5579f4.webp', 'themes/dongson/assets/images/1bcad9de80434d17e7e7294da27cc4db1f5579f4.webp', NULL, 'image/webp', 93924, 1024, 576, '1bcad9de80434d17e7e7294da27cc4db1f5579f4', NULL, NULL, 'eec3b0eebfb1ef372d79e0d8a96b352642e7663524f7ca202700e02049a259e6', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (54, 1, '1da65782184435756067b9def5757e0ae0304012.webp', 'themes/dongson/assets/images/1da65782184435756067b9def5757e0ae0304012.webp', NULL, 'image/webp', 1160290, 4096, 2304, '1da65782184435756067b9def5757e0ae0304012', NULL, NULL, '9fcb3f785a2d155649fb98f2be6549b2afa6507f27f7e88aed6be3fe86ba2e32', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (55, 1, '1dce60caf22bb962b33070ec913a0d8922fc0d90.svg', 'themes/dongson/assets/images/1dce60caf22bb962b33070ec913a0d8922fc0d90.svg', NULL, 'image/svg+xml', 613, NULL, NULL, '1dce60caf22bb962b33070ec913a0d8922fc0d90', NULL, NULL, '1e3bd3217b13dc67a02142c1a15fbb8d7f0037cac627d8709452a62161d1f11e', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (56, 1, '1e71b03bd8e84a97943b1a2b38f484d13829de8b.webp', 'themes/dongson/assets/images/1e71b03bd8e84a97943b1a2b38f484d13829de8b.webp', NULL, 'image/webp', 620942, 4096, 2713, '1e71b03bd8e84a97943b1a2b38f484d13829de8b', NULL, NULL, '78688fecb959e7dfa9dfcd271df13e7217b2b6f160cb0e93f6f41c5b3539bd9e', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (57, 1, '1eea5bbab148fab8813463c8155caf31ad41555e.svg', 'themes/dongson/assets/images/1eea5bbab148fab8813463c8155caf31ad41555e.svg', NULL, 'image/svg+xml', 611, NULL, NULL, '1eea5bbab148fab8813463c8155caf31ad41555e', NULL, NULL, 'd1bff80a49660ad589ff81ae15e350dd67c38122a4ad5ef9c6d0e28776e3c35f', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (58, 1, '2cb53b895748ee52f5a8166c1ee323d4c22eb48d.webp', 'themes/dongson/assets/images/2cb53b895748ee52f5a8166c1ee323d4c22eb48d.webp', NULL, 'image/webp', 1295264, 4096, 2809, '2cb53b895748ee52f5a8166c1ee323d4c22eb48d', NULL, NULL, 'aef1a40b99da40657cf16734b97a2eb7b27c79b8da18b3e90931dd25b31def96', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (59, 1, '304aee7c32c66deeae7decacfc30e478ef932186.svg', 'themes/dongson/assets/images/304aee7c32c66deeae7decacfc30e478ef932186.svg', NULL, 'image/svg+xml', 1086, NULL, NULL, '304aee7c32c66deeae7decacfc30e478ef932186', NULL, NULL, 'bcfe411e65d745b38b6beeff33328e2c5c6a14ac52bcf53ee3395253333206d8', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (60, 1, '347f1f1180a73707fd6dc9cd471802ae488406c0.webp', 'themes/dongson/assets/images/347f1f1180a73707fd6dc9cd471802ae488406c0.webp', NULL, 'image/webp', 50408, 874, 890, '347f1f1180a73707fd6dc9cd471802ae488406c0', NULL, NULL, 'a4e84088c60232f681d02ba946ead48ef23a721b62575b74d4257dcb63995b60', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (61, 1, '350c41fc57e511e0029d28d8742d946a4b5c137c.webp', 'themes/dongson/assets/images/350c41fc57e511e0029d28d8742d946a4b5c137c.webp', NULL, 'image/webp', 135278, 1024, 576, '350c41fc57e511e0029d28d8742d946a4b5c137c', NULL, NULL, 'a374ac33e0045226d1f735c36c5287bb27af93e11982d11c7be6a66574dca7df', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (62, 1, '359e3d9a71c294afc69d21216c1a3a8f9c8a2d45.svg', 'themes/dongson/assets/images/359e3d9a71c294afc69d21216c1a3a8f9c8a2d45.svg', NULL, 'image/svg+xml', 1569, NULL, NULL, '359e3d9a71c294afc69d21216c1a3a8f9c8a2d45', NULL, NULL, 'e54c84ef4e4c26a1cf22d65d6dd6366ecbf47fae526b41b71de534b21ea99148', NULL, NULL, '2026-08-02 15:59:35', '2026-08-02 15:59:35', NULL);
INSERT INTO `pvn_media_files` VALUES (64, 1, '38957d52f22e6339b92de6bc0c82b14c75473aa6.svg', 'themes/dongson/assets/images/38957d52f22e6339b92de6bc0c82b14c75473aa6.svg', NULL, 'image/svg+xml', 1392, NULL, NULL, '38957d52f22e6339b92de6bc0c82b14c75473aa6', NULL, NULL, 'a45c8928b814ed688efb5acaf35f058a090a489d91463236f1d6c29ff9e451dd', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (65, 1, '38a0056e899e283d7c05a0751f5b6262404919d4.svg', 'themes/dongson/assets/images/38a0056e899e283d7c05a0751f5b6262404919d4.svg', NULL, 'image/svg+xml', 3365, NULL, NULL, '38a0056e899e283d7c05a0751f5b6262404919d4', NULL, NULL, '5ea4fe3c01e892bfe5e43d872fda0a20f3e8ce155f551ccc53fe77d9966d167b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (66, 1, '38e33693074d5694a769662e45f06a93ffad093c.svg', 'themes/dongson/assets/images/38e33693074d5694a769662e45f06a93ffad093c.svg', NULL, 'image/svg+xml', 626, NULL, NULL, '38e33693074d5694a769662e45f06a93ffad093c', NULL, NULL, '547beb3e0b5d6319ffed02ca8ad68d02608f972831c5ed9d5fe2302f9d91adcf', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (67, 1, '3b1557cd0d980ce7abe00ecb0b8ed17f60eac0f7.svg', 'themes/dongson/assets/images/3b1557cd0d980ce7abe00ecb0b8ed17f60eac0f7.svg', NULL, 'image/svg+xml', 4153, NULL, NULL, '3b1557cd0d980ce7abe00ecb0b8ed17f60eac0f7', NULL, NULL, '38ac35fc2be83b250c4afe279a55e2d05c09ad3050965bd950a49931de86f63e', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (68, 1, '3bf45a2731a2e00ec7168928645fa31edda124f0.svg', 'themes/dongson/assets/images/3bf45a2731a2e00ec7168928645fa31edda124f0.svg', NULL, 'image/svg+xml', 1308, NULL, NULL, '3bf45a2731a2e00ec7168928645fa31edda124f0', NULL, NULL, '26d4bbe23c21b4e6dcf412790c402cbf865c7349f7b5baac0a1ef4d6ee58d51d', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (69, 1, '42cb0d26fd246350a254c607f13584598064ad4f.svg', 'themes/dongson/assets/images/42cb0d26fd246350a254c607f13584598064ad4f.svg', NULL, 'image/svg+xml', 406, NULL, NULL, '42cb0d26fd246350a254c607f13584598064ad4f', NULL, NULL, '98f659bbdab808b93ff609abfc7cdc55c6c5b3d4f6556f336733ec1c040175a5', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (70, 1, '44fabbc55ceb11493f32c6569297753d40a890f6.svg', 'themes/dongson/assets/images/44fabbc55ceb11493f32c6569297753d40a890f6.svg', NULL, 'image/svg+xml', 1574, NULL, NULL, '44fabbc55ceb11493f32c6569297753d40a890f6', NULL, NULL, 'f08ef84d5335fb82cc6858e228190592883e9be0b783f4e778c4b288bd44feca', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (71, 1, '456656839ccc76726a4569c6a3f3d27dc34a6c92.svg', 'themes/dongson/assets/images/456656839ccc76726a4569c6a3f3d27dc34a6c92.svg', NULL, 'image/svg+xml', 1570, NULL, NULL, '456656839ccc76726a4569c6a3f3d27dc34a6c92', NULL, NULL, '3a7ada97b46012f7e1bc396f21c025de2fc696a9cdb11402ac0d5956a242a010', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (72, 1, '46ada32ff852893491ddaff1c9ba4169ec60fe8f.svg', 'themes/dongson/assets/images/46ada32ff852893491ddaff1c9ba4169ec60fe8f.svg', NULL, 'image/svg+xml', 536, NULL, NULL, '46ada32ff852893491ddaff1c9ba4169ec60fe8f', NULL, NULL, 'b570c91f55238159a49e4339267a380fda36b250eca37149fc35143042e05810', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (73, 1, '46cbb9a46e2741401743dec71fb22c653e7136f2.svg', 'themes/dongson/assets/images/46cbb9a46e2741401743dec71fb22c653e7136f2.svg', NULL, 'image/svg+xml', 957, NULL, NULL, '46cbb9a46e2741401743dec71fb22c653e7136f2', NULL, NULL, '270816bce8ac24ffbbfd9b1b13715acb9d2aaa07d8e0b7391eee1cfd681fd4b5', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (74, 1, '474e6698aed9e0bed2725199beb025880b361109.webp', 'themes/dongson/assets/images/474e6698aed9e0bed2725199beb025880b361109.webp', NULL, 'image/webp', 18272, 960, 470, '474e6698aed9e0bed2725199beb025880b361109', NULL, NULL, '9459605853fe57eb1ebf9225e57a31ea257d1a1319e5a8cb612bac3e6641dd37', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (75, 1, '4d25b52d25fb6167a583c880013a548a81a06e60.webp', 'themes/dongson/assets/images/4d25b52d25fb6167a583c880013a548a81a06e60.webp', NULL, 'image/webp', 1999018, 4096, 3072, '4d25b52d25fb6167a583c880013a548a81a06e60', NULL, NULL, 'cabfb4b6c405cd7aabc901c114f5cbe7d388c4e9ae52ef0c60178208c0e3df28', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (76, 1, '4dee2a643306ca985ec28ee00617fe7ce213fdbe.svg', 'themes/dongson/assets/images/4dee2a643306ca985ec28ee00617fe7ce213fdbe.svg', NULL, 'image/svg+xml', 1197, NULL, NULL, '4dee2a643306ca985ec28ee00617fe7ce213fdbe', NULL, NULL, 'ecec58b2d2f503a6f0da92f3c00d5bc41166073503868f59c451d02be1680793', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (77, 1, '5516164db9493ad74318b40cde831c81cfd23928.svg', 'themes/dongson/assets/images/5516164db9493ad74318b40cde831c81cfd23928.svg', NULL, 'image/svg+xml', 958, NULL, NULL, '5516164db9493ad74318b40cde831c81cfd23928', NULL, NULL, 'e47eeda38211de42e68cfcb82aab6e48c877de6b2ce59be66ee01cfbb0853c59', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (78, 1, '55718099eeb04cf44a8a19e35e060581d044f560.svg', 'themes/dongson/assets/images/55718099eeb04cf44a8a19e35e060581d044f560.svg', NULL, 'image/svg+xml', 1394, NULL, NULL, '55718099eeb04cf44a8a19e35e060581d044f560', NULL, NULL, '75b7736dceac18259af00a09c40ea250ef2bf9a042cfb8f2d00f57bace96d74f', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (79, 1, '566e475c8d558d427379e59ed7a09c14b1f0c1bd.svg', 'themes/dongson/assets/images/566e475c8d558d427379e59ed7a09c14b1f0c1bd.svg', NULL, 'image/svg+xml', 2191, NULL, NULL, '566e475c8d558d427379e59ed7a09c14b1f0c1bd', NULL, NULL, 'd9f7a1acabab905a81445403161f5d8b888b08a825fa0021417337179c7e93f0', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (80, 1, '59d4e8985c38bfa0ee5dc866d86f2cc55638f07f.webp', 'themes/dongson/assets/images/59d4e8985c38bfa0ee5dc866d86f2cc55638f07f.webp', NULL, 'image/webp', 397592, 2000, 1050, '59d4e8985c38bfa0ee5dc866d86f2cc55638f07f', NULL, NULL, '0bfc564d2f736779790161c29404470ce644c452b2768c4edf29d14fa584fe92', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (81, 1, '5d0870231dae7440c15098b8ab88b64750318182.svg', 'themes/dongson/assets/images/5d0870231dae7440c15098b8ab88b64750318182.svg', NULL, 'image/svg+xml', 377, NULL, NULL, '5d0870231dae7440c15098b8ab88b64750318182', NULL, NULL, '74a7a59ef45fc0a3a0f75fe2524e22e79ce87c6c8ea75487076a38fbca17bd0d', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (82, 1, '5ff4eaa78da301324a0aa30393f19bf0083eaad9.svg', 'themes/dongson/assets/images/5ff4eaa78da301324a0aa30393f19bf0083eaad9.svg', NULL, 'image/svg+xml', 1573, NULL, NULL, '5ff4eaa78da301324a0aa30393f19bf0083eaad9', NULL, NULL, 'e811713248bf901022665f8c689dda4fafc0f79fa7fbc1ef5c3701cecacc0a01', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (83, 1, '60e159a06c0507f3c585bf56202e4e90631af4fe.svg', 'themes/dongson/assets/images/60e159a06c0507f3c585bf56202e4e90631af4fe.svg', NULL, 'image/svg+xml', 1573, NULL, NULL, '60e159a06c0507f3c585bf56202e4e90631af4fe', NULL, NULL, 'cae866606d2aa03986a95fef2ced48ff9ec0ee61a4eed12fad8bbe6797a7c018', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (84, 1, '6230d21557ad68c36a5fd23578d1f448c9df80bd.svg', 'themes/dongson/assets/images/6230d21557ad68c36a5fd23578d1f448c9df80bd.svg', NULL, 'image/svg+xml', 2175, NULL, NULL, '6230d21557ad68c36a5fd23578d1f448c9df80bd', NULL, NULL, '38c6bce19cc8d24ef6af94f03c71ec135e166f67a08aa69b766fde79641f5c13', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (85, 1, '6862225d55440cb6fc7968be0cc71e171a588b7a.svg', 'themes/dongson/assets/images/6862225d55440cb6fc7968be0cc71e171a588b7a.svg', NULL, 'image/svg+xml', 2186, NULL, NULL, '6862225d55440cb6fc7968be0cc71e171a588b7a', NULL, NULL, 'be7559331818275f4d1fba0ed60e82b2228f3d82bc17d77204cfbe21f01c5798', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (86, 1, '6c3bd0771d83ee62050aef9df3bf7f5b58d7f722.webp', 'themes/dongson/assets/images/6c3bd0771d83ee62050aef9df3bf7f5b58d7f722.webp', NULL, 'image/webp', 52922, 874, 890, '6c3bd0771d83ee62050aef9df3bf7f5b58d7f722', NULL, NULL, '6f71a4708375020221d2bfb41795d0867c96cd33e0c93cac9b488ed448147023', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (87, 1, '74ac779dad63bf2c394133a9b4adb97947b7cce2.svg', 'themes/dongson/assets/images/74ac779dad63bf2c394133a9b4adb97947b7cce2.svg', NULL, 'image/svg+xml', 4147, NULL, NULL, '74ac779dad63bf2c394133a9b4adb97947b7cce2', NULL, NULL, '1a9948d96dc17d6128bac02461721abf20f09f22f5baffe49afcac96da22dd80', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (88, 1, '768c2683e04fc7ed2bbf7f2c7f917a2a81c68d33.svg', 'themes/dongson/assets/images/768c2683e04fc7ed2bbf7f2c7f917a2a81c68d33.svg', NULL, 'image/svg+xml', 1573, NULL, NULL, '768c2683e04fc7ed2bbf7f2c7f917a2a81c68d33', NULL, NULL, '9a344b2c9b2cf1493223dc6e7920e64056169121f886b4d085311a4b45672518', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (89, 1, '76ab8608b4c514b62c5fc38a323bbac115e5761f.svg', 'themes/dongson/assets/images/76ab8608b4c514b62c5fc38a323bbac115e5761f.svg', NULL, 'image/svg+xml', 538, NULL, NULL, '76ab8608b4c514b62c5fc38a323bbac115e5761f', NULL, NULL, '763da61a1d537d85b998344d26ab6988ba709e5e9fb0128b4bdc951eedb4924b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (90, 1, '7722c2ba95624068458d348853fe1df0e0cf0290.svg', 'themes/dongson/assets/images/7722c2ba95624068458d348853fe1df0e0cf0290.svg', NULL, 'image/svg+xml', 1569, NULL, NULL, '7722c2ba95624068458d348853fe1df0e0cf0290', NULL, NULL, 'c832335f0c52f0091bf85f90355594a8a4ac32271e2392da9d18d3d959384833', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (91, 1, '7ad1f50b79fc68c20a452a1f3a0e86bcc44cc19e.webp', 'themes/dongson/assets/images/7ad1f50b79fc68c20a452a1f3a0e86bcc44cc19e.webp', NULL, 'image/webp', 51656, 1257, 689, '7ad1f50b79fc68c20a452a1f3a0e86bcc44cc19e', NULL, NULL, '5e414bb0af77204e7046226f63d4edc4c248cc7cb157e4bb20fc2773fdc28db8', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (92, 1, '7b3e2bff37be28c1e5d996dcd6f0f6c6414cb9f3.svg', 'themes/dongson/assets/images/7b3e2bff37be28c1e5d996dcd6f0f6c6414cb9f3.svg', NULL, 'image/svg+xml', 958, NULL, NULL, '7b3e2bff37be28c1e5d996dcd6f0f6c6414cb9f3', NULL, NULL, '2dfd7a003792f77177867f2d9de36ccf93c409156ea8d2585dc902383aa6f1f6', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (93, 1, '7d27878724430e73b89c9f03ab5c7fa06d4108aa.svg', 'themes/dongson/assets/images/7d27878724430e73b89c9f03ab5c7fa06d4108aa.svg', NULL, 'image/svg+xml', 3910, NULL, NULL, '7d27878724430e73b89c9f03ab5c7fa06d4108aa', NULL, NULL, '9dc1ab61d2ae266d66e005809be0db4f32f431a2fde0a39ac5bbc6d1752ed83b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (94, 1, '7d6eb2db70af7bc803e5d75d90a4a5aded750224.svg', 'themes/dongson/assets/images/7d6eb2db70af7bc803e5d75d90a4a5aded750224.svg', NULL, 'image/svg+xml', 1513, NULL, NULL, '7d6eb2db70af7bc803e5d75d90a4a5aded750224', NULL, NULL, '1dc735da005e563061d11821fe90ecdd87865b1d75b9472e39ac3e2c10350f40', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (95, 1, '7d82727361594abad0fb938a9b8eda0e76dc121b.svg', 'themes/dongson/assets/images/7d82727361594abad0fb938a9b8eda0e76dc121b.svg', NULL, 'image/svg+xml', 3932, NULL, NULL, '7d82727361594abad0fb938a9b8eda0e76dc121b', NULL, NULL, '523027fb74e1af1084694ba994195420815143d9df400367004e744fb5246f76', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (96, 1, '7dbb77f911df78562e9a44b76b369f9c3e3351dc.webp', 'themes/dongson/assets/images/7dbb77f911df78562e9a44b76b369f9c3e3351dc.webp', NULL, 'image/webp', 83780, 2250, 1250, '7dbb77f911df78562e9a44b76b369f9c3e3351dc', NULL, NULL, 'cd3f221a80572c4268aa9bd75fc6a712487c1e07d34c928c41909efd9eb5fa5b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (97, 1, '7ececd12f1bf31c69c7fcdca1aade623da0f4d7a.svg', 'themes/dongson/assets/images/7ececd12f1bf31c69c7fcdca1aade623da0f4d7a.svg', NULL, 'image/svg+xml', 3920, NULL, NULL, '7ececd12f1bf31c69c7fcdca1aade623da0f4d7a', NULL, NULL, 'ea4d5d4de300cf35b4fc3e8c2784ddc4c153b08ed5c8a91d57e496d94d6e84dc', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (98, 1, '8182b62d4dbe3e1049dd9769f2d49f5fcc89e571.svg', 'themes/dongson/assets/images/8182b62d4dbe3e1049dd9769f2d49f5fcc89e571.svg', NULL, 'image/svg+xml', 1068, NULL, NULL, '8182b62d4dbe3e1049dd9769f2d49f5fcc89e571', NULL, NULL, '8797e9350ad72cd8548d8a9e05f7baf898b34141263b35e670034fc83a28bc91', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (99, 1, '84d30bb21b9c3ec8e686fee075d2df50c8c69923.webp', 'themes/dongson/assets/images/84d30bb21b9c3ec8e686fee075d2df50c8c69923.webp', NULL, 'image/webp', 34204, 735, 490, '84d30bb21b9c3ec8e686fee075d2df50c8c69923', NULL, NULL, '28eedb0ed8114acb4ee608b5d48ed601a37e6b02e2e6b2c10436d852b224ac98', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (100, 1, '858aedab1436e91a958f72e00d49ae9173caadc0.svg', 'themes/dongson/assets/images/858aedab1436e91a958f72e00d49ae9173caadc0.svg', NULL, 'image/svg+xml', 284, NULL, NULL, '858aedab1436e91a958f72e00d49ae9173caadc0', NULL, NULL, '2fac2fd436672ce7a275c053be2d9e41ef4b74b078a2f7b3f14da175de0098de', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (101, 1, '87725c18d1b9a95944d176f886ddae2f2b1d198a.svg', 'themes/dongson/assets/images/87725c18d1b9a95944d176f886ddae2f2b1d198a.svg', NULL, 'image/svg+xml', 406, NULL, NULL, '87725c18d1b9a95944d176f886ddae2f2b1d198a', NULL, NULL, '28c08c95f7b853214240383889fcc933110ce7f8d1dd0d1c54d5b3132c5f0554', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (102, 1, '8e139e7d94d689c4d5d972a4948557aadd20642b.svg', 'themes/dongson/assets/images/8e139e7d94d689c4d5d972a4948557aadd20642b.svg', NULL, 'image/svg+xml', 3924, NULL, NULL, '8e139e7d94d689c4d5d972a4948557aadd20642b', NULL, NULL, '37eba47a61549fc72d86f16fc62b8dec90777fbd2bc4abe6a42369ba796bca0b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (103, 1, '9fdd267151031afa502b7b9c8adeedcc6568c496.svg', 'themes/dongson/assets/images/9fdd267151031afa502b7b9c8adeedcc6568c496.svg', NULL, 'image/svg+xml', 1391, NULL, NULL, '9fdd267151031afa502b7b9c8adeedcc6568c496', NULL, NULL, 'e72afe31d480201c4deadada11d2433608038819c27217959c009c45211b5e07', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (104, 1, 'a17dc48f448ca3f0d8a28583a8e79b5ebe51b645.webp', 'themes/dongson/assets/images/a17dc48f448ca3f0d8a28583a8e79b5ebe51b645.webp', NULL, 'image/webp', 75462, 874, 890, 'A17dc48f448ca3f0d8a28583a8e79b5ebe51b645', NULL, NULL, '5b77555117fd09f11c61e8e3de2d4c59f942de31af4e771ae889574add839c07', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (105, 1, 'a6b1afcb3bac1424bd3b43bdf8d2dd59e27da99a.svg', 'themes/dongson/assets/images/a6b1afcb3bac1424bd3b43bdf8d2dd59e27da99a.svg', NULL, 'image/svg+xml', 835, NULL, NULL, 'A6b1afcb3bac1424bd3b43bdf8d2dd59e27da99a', NULL, NULL, '559121f661a98edf69fbe84dfe4d5a286956faa8b5555e97c5310c1a539249e7', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (106, 1, 'a954e32fe3d5ab194e7b5429d0fdb74cf2309751.svg', 'themes/dongson/assets/images/a954e32fe3d5ab194e7b5429d0fdb74cf2309751.svg', NULL, 'image/svg+xml', 1390, NULL, NULL, 'A954e32fe3d5ab194e7b5429d0fdb74cf2309751', NULL, NULL, 'fb2d7a6d6e38999eedc38664e541fc3beb15d049527ec3b454cea60c1aa4c013', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (107, 1, 'ab73ffce8bef318298b18aaf221ce23808f031a5.svg', 'themes/dongson/assets/images/ab73ffce8bef318298b18aaf221ce23808f031a5.svg', NULL, 'image/svg+xml', 291, NULL, NULL, 'Ab73ffce8bef318298b18aaf221ce23808f031a5', NULL, NULL, 'e5abd470e4ff772d94fe63b421eefdb7f92d0c159fd2d55c8817fb085f8c822d', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (108, 1, 'about-bg.jpg', 'themes/dongson/assets/images/about-bg.jpg', NULL, 'image/jpeg', 909826, 1024, 1024, 'About bg', NULL, NULL, '105d8d0abd8e2b61fdf03f8381d1a497e555bb75f0d91cd1aaf3444da743b0f3', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (109, 1, 'ae589fc7c9b857b7c3f1308cd328cada6955e7ea.svg', 'themes/dongson/assets/images/ae589fc7c9b857b7c3f1308cd328cada6955e7ea.svg', NULL, 'image/svg+xml', 284, NULL, NULL, 'Ae589fc7c9b857b7c3f1308cd328cada6955e7ea', NULL, NULL, 'c38179fd218fcee7289c1c849f6654ed070d6f7bd667a1a38ffdf560d84c73dd', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (110, 1, 'aed186015dac0e590f6c6674f13873a21afc34fb.webp', 'themes/dongson/assets/images/aed186015dac0e590f6c6674f13873a21afc34fb.webp', NULL, 'image/webp', 134526, 1600, 900, 'Aed186015dac0e590f6c6674f13873a21afc34fb', NULL, NULL, 'c3a50dee05db03003d46cc9d865d3bcd6e404635681ebfe9cc9e922e47ee22e8', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (111, 1, 'b06b70fd8882d755c9ffff77034249a5ad61a3bf.svg', 'themes/dongson/assets/images/b06b70fd8882d755c9ffff77034249a5ad61a3bf.svg', NULL, 'image/svg+xml', 935, NULL, NULL, 'B06b70fd8882d755c9ffff77034249a5ad61a3bf', NULL, NULL, '74fde6d69fd80174b62f017448de2c1b2fa2af827a94d2a77ec2c9bea37a413e', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (112, 1, 'b0def76398fb9199d6d536da86d94ef599603f94.svg', 'themes/dongson/assets/images/b0def76398fb9199d6d536da86d94ef599603f94.svg', NULL, 'image/svg+xml', 966, NULL, NULL, 'B0def76398fb9199d6d536da86d94ef599603f94', NULL, NULL, 'b6c4d360b3537791a15cbbdafacfe9fbc39372612dec6ffe5c9c7f537300eced', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (113, 1, 'b0fc87f1c9d273d042aeb7c31c086b04e1f4d972.svg', 'themes/dongson/assets/images/b0fc87f1c9d273d042aeb7c31c086b04e1f4d972.svg', NULL, 'image/svg+xml', 958, NULL, NULL, 'B0fc87f1c9d273d042aeb7c31c086b04e1f4d972', NULL, NULL, '687acbcbacc6abb7b5c8b4af2b08de9916517367d0c1fcdac29fe2b947ecadb5', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (114, 1, 'b6e2fcf65c5e5d32d712de00a543cb7088e92558.svg', 'themes/dongson/assets/images/b6e2fcf65c5e5d32d712de00a543cb7088e92558.svg', NULL, 'image/svg+xml', 1390, NULL, NULL, 'B6e2fcf65c5e5d32d712de00a543cb7088e92558', NULL, NULL, '5db81fe2c9c1ffac35fd0af3abcf22eacdf02b616685bbc7242f024c8861bdb3', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (115, 1, 'ba49d4ef846e7d836cf8e62bfc0f1c56f26b7fa9.webp', 'themes/dongson/assets/images/ba49d4ef846e7d836cf8e62bfc0f1c56f26b7fa9.webp', NULL, 'image/webp', 1956768, 4000, 2250, 'Ba49d4ef846e7d836cf8e62bfc0f1c56f26b7fa9', NULL, NULL, '305b6bbe84e16cd22853e136f1d5c8b706c661ff4a47353195a3053ca760c65a', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (116, 1, 'bff08fd8b2e1cd2afb1ab974eb579f181bf447db.svg', 'themes/dongson/assets/images/bff08fd8b2e1cd2afb1ab974eb579f181bf447db.svg', NULL, 'image/svg+xml', 1009, NULL, NULL, 'Bff08fd8b2e1cd2afb1ab974eb579f181bf447db', NULL, NULL, 'dfed657e75c2392f16aa37e9fdc4f8ed4b30d6506bcec4b1631c67ea26de0099', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (117, 1, 'bot-banner.jpg', 'themes/dongson/assets/images/bot-banner.jpg', NULL, 'image/jpeg', 920596, 1024, 1024, 'Bot banner', NULL, NULL, 'b9ec224811ed358b993ce881a22dffd064bd564566797048c9f25596842a6549', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (118, 1, 'c53a923ad58d8f756075911b95f546af53a0876f.svg', 'themes/dongson/assets/images/c53a923ad58d8f756075911b95f546af53a0876f.svg', NULL, 'image/svg+xml', 613, NULL, NULL, 'C53a923ad58d8f756075911b95f546af53a0876f', NULL, NULL, 'b4334de642374692bebbcaed9c4c2618144526dbbc67e745e966ef28c7f3bdd7', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (119, 1, 'c592fc719c60f72cc2a2970d534dfac6cd99557f.svg', 'themes/dongson/assets/images/c592fc719c60f72cc2a2970d534dfac6cd99557f.svg', NULL, 'image/svg+xml', 384, NULL, NULL, 'C592fc719c60f72cc2a2970d534dfac6cd99557f', NULL, NULL, '1df417c9707577084ac9a1e235c49bd1ce92aaa781a1089ac9ef22cb59b54c44', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (120, 1, 'c876dcb76377a5de9749a61e6b8fd3f009170cce.svg', 'themes/dongson/assets/images/c876dcb76377a5de9749a61e6b8fd3f009170cce.svg', NULL, 'image/svg+xml', 1391, NULL, NULL, 'C876dcb76377a5de9749a61e6b8fd3f009170cce', NULL, NULL, '3b695b54bc1a6106a2b15276a9767ec8ba418690e046f96059930ff90370b935', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (121, 1, 'cb54c98f6394bcb0907031b932987fa97b8c2574.webp', 'themes/dongson/assets/images/cb54c98f6394bcb0907031b932987fa97b8c2574.webp', NULL, 'image/webp', 16794, 594, 336, 'Cb54c98f6394bcb0907031b932987fa97b8c2574', NULL, NULL, 'a0ea2a9cd41c9e16b64c9be310c414b89e3ae05b4626bdd769ab8ffec03bd4c0', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (122, 1, 'd438a58e5e37a4cc22d93ff442e0be39e01dc962.webp', 'themes/dongson/assets/images/d438a58e5e37a4cc22d93ff442e0be39e01dc962.webp', NULL, 'image/webp', 1073784, 2048, 1280, 'D438a58e5e37a4cc22d93ff442e0be39e01dc962', NULL, NULL, 'dc6a404f102c714be1bcfb9fa46cf4baeaa3956228407035ce3d5abb357e4bf7', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (123, 1, 'd6894188a28a106bf7699e605dea57da1270b790.svg', 'themes/dongson/assets/images/d6894188a28a106bf7699e605dea57da1270b790.svg', NULL, 'image/svg+xml', 496, NULL, NULL, 'D6894188a28a106bf7699e605dea57da1270b790', NULL, NULL, '1a3161d62f11f064a61a2076a8bf3f0435809ed80da7dfc35bdcb9c9a771388c', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (124, 1, 'd70111c58bf430c7b8699d95cc46d27a54f94540.svg', 'themes/dongson/assets/images/d70111c58bf430c7b8699d95cc46d27a54f94540.svg', NULL, 'image/svg+xml', 958, NULL, NULL, 'D70111c58bf430c7b8699d95cc46d27a54f94540', NULL, NULL, '0ab71148b608f906057b4b5e31c2f4542720b85cbd50a308c9e7b56adabcbbfd', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (125, 1, 'd7f8c19070f545b87ab90357e4912654888b1c9a.svg', 'themes/dongson/assets/images/d7f8c19070f545b87ab90357e4912654888b1c9a.svg', NULL, 'image/svg+xml', 3910, NULL, NULL, 'D7f8c19070f545b87ab90357e4912654888b1c9a', NULL, NULL, 'd5650a7ccb55ee16432a10c1ab52800f3baaf8a5e47dfca98eae439392d6c21c', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (126, 1, 'd85091370c11c53cf8e6b4fc562fbfc3cb3f7c88.svg', 'themes/dongson/assets/images/d85091370c11c53cf8e6b4fc562fbfc3cb3f7c88.svg', NULL, 'image/svg+xml', 1567, NULL, NULL, 'D85091370c11c53cf8e6b4fc562fbfc3cb3f7c88', NULL, NULL, 'b84422afe6d0dfe09d8102a3750434ba361b22e6d506eb730441850f166a4f57', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (127, 1, 'd90e52c11abfdde97f9e90b4b7c8195e8e84ca93.svg', 'themes/dongson/assets/images/d90e52c11abfdde97f9e90b4b7c8195e8e84ca93.svg', NULL, 'image/svg+xml', 4139, NULL, NULL, 'D90e52c11abfdde97f9e90b4b7c8195e8e84ca93', NULL, NULL, '276ef1272a987120bd95f3ce0682fa7e7f51e886698a0a3c1b05bb8b998accc5', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (128, 1, 'dcc6d09e889d3722d3194459b58ebe2e13295513.svg', 'themes/dongson/assets/images/dcc6d09e889d3722d3194459b58ebe2e13295513.svg', NULL, 'image/svg+xml', 966, NULL, NULL, 'Dcc6d09e889d3722d3194459b58ebe2e13295513', NULL, NULL, 'a889a6222cad75c816ad86e2b5d0a4252dce90738610b7c896d9c9c37c21335c', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (129, 1, 'de552b65b322f4ca6a95cc11379bcc6d2ce034e3.svg', 'themes/dongson/assets/images/de552b65b322f4ca6a95cc11379bcc6d2ce034e3.svg', NULL, 'image/svg+xml', 406, NULL, NULL, 'De552b65b322f4ca6a95cc11379bcc6d2ce034e3', NULL, NULL, '8f2c0a4c0249d98c1f7500048f15f043c0fd65d17238411c085824a36ba09bfe', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (130, 1, 'e2227fd51820cbd4998d36bf4ff02e1d8273e238.svg', 'themes/dongson/assets/images/e2227fd51820cbd4998d36bf4ff02e1d8273e238.svg', NULL, 'image/svg+xml', 628, NULL, NULL, 'E2227fd51820cbd4998d36bf4ff02e1d8273e238', NULL, NULL, '2d3d2a4ac5b4f10b95bf54e10982ef800051de235e0ce69f750212794ab28b66', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (131, 1, 'e293b40ee1891a55cf3b81fca82acda2af879855.svg', 'themes/dongson/assets/images/e293b40ee1891a55cf3b81fca82acda2af879855.svg', NULL, 'image/svg+xml', 2186, NULL, NULL, 'E293b40ee1891a55cf3b81fca82acda2af879855', NULL, NULL, '3329b03e846a3509e07eedeb1e005914f0df9e4cdd7a90321382e96385184adb', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (132, 1, 'e3da8240955520fcb9ca648bca8687398b521a14.svg', 'themes/dongson/assets/images/e3da8240955520fcb9ca648bca8687398b521a14.svg', NULL, 'image/svg+xml', 4147, NULL, NULL, 'E3da8240955520fcb9ca648bca8687398b521a14', NULL, NULL, '14aeb035b37789fcaefda038e6ade90f216a0376302188625df1ecaa60ed5d95', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (133, 1, 'e6720dea436c9c8d63efe4b2a48deb0e1ec42459.webp', 'themes/dongson/assets/images/e6720dea436c9c8d63efe4b2a48deb0e1ec42459.webp', NULL, 'image/webp', 6926, 1024, 43, 'E6720dea436c9c8d63efe4b2a48deb0e1ec42459', NULL, NULL, 'fb16792a83bcd84e346310ec630e09cd27e56d3ca8d3a1581922a8a5248ebf8b', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (134, 1, 'e90eaafb9e65e4156683affe07edd0ff091df179.svg', 'themes/dongson/assets/images/e90eaafb9e65e4156683affe07edd0ff091df179.svg', NULL, 'image/svg+xml', 522, NULL, NULL, 'E90eaafb9e65e4156683affe07edd0ff091df179', NULL, NULL, '307ec3cce341b648083aea28e6384360d333eba1979a9ca6f16799485ecbd4be', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (135, 1, 'ec51c17b0fa4403b91e117f57dbd121d3199b5e8.svg', 'themes/dongson/assets/images/ec51c17b0fa4403b91e117f57dbd121d3199b5e8.svg', NULL, 'image/svg+xml', 792, NULL, NULL, 'Ec51c17b0fa4403b91e117f57dbd121d3199b5e8', NULL, NULL, '5fdc2576946467f50a573c7afcb72012b48cc5fcbf85f43e331ea245b2e30636', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (136, 1, 'f0872eceac3a675dc61abf8a177dca83b1198041.svg', 'themes/dongson/assets/images/f0872eceac3a675dc61abf8a177dca83b1198041.svg', NULL, 'image/svg+xml', 384, NULL, NULL, 'F0872eceac3a675dc61abf8a177dca83b1198041', NULL, NULL, 'dc40e3c5668373a2fa2f3af875bca2fbd91d80779a65450432aa803623da7a61', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (137, 1, 'f2d1e83e8ec37c18d8f4c9b02fa99a286324f3b2.webp', 'themes/dongson/assets/images/f2d1e83e8ec37c18d8f4c9b02fa99a286324f3b2.webp', NULL, 'image/webp', 94920, 800, 550, 'F2d1e83e8ec37c18d8f4c9b02fa99a286324f3b2', NULL, NULL, '157ce9b5894a68a4c2d7109a6951a90e7bfb77a20238cbb7ca63bfde0aa9dca9', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (138, 1, 'f4f34a9817727c4dd0fd8224d6d6184be0a038a3.svg', 'themes/dongson/assets/images/f4f34a9817727c4dd0fd8224d6d6184be0a038a3.svg', NULL, 'image/svg+xml', 13353, NULL, NULL, 'F4f34a9817727c4dd0fd8224d6d6184be0a038a3', NULL, NULL, 'e9dc31fc8d1e04e1c8d49a9f1d72438f870d2e01950d4a8f7db9f2d618fe1a70', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (139, 1, 'f5820c6dac9d62a437ff4964f7104eae299921cf.svg', 'themes/dongson/assets/images/f5820c6dac9d62a437ff4964f7104eae299921cf.svg', NULL, 'image/svg+xml', 388, NULL, NULL, 'F5820c6dac9d62a437ff4964f7104eae299921cf', NULL, NULL, 'f6fdec1b00d4d34d8afba3a801c9e4809322434974fc97928571a0fde5ec4262', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (140, 1, 'f726a6b56c0b0c09538b7885db8a3e3b4f658aca.svg', 'themes/dongson/assets/images/f726a6b56c0b0c09538b7885db8a3e3b4f658aca.svg', NULL, 'image/svg+xml', 1573, NULL, NULL, 'F726a6b56c0b0c09538b7885db8a3e3b4f658aca', NULL, NULL, '982ea454276dcf9f8f527b9133211181d70f4aa95e3a8160594fd85157d771a9', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (141, 1, 'f7dbcc477a3e841511481a1dda35cc601739ef48.svg', 'themes/dongson/assets/images/f7dbcc477a3e841511481a1dda35cc601739ef48.svg', NULL, 'image/svg+xml', 406, NULL, NULL, 'F7dbcc477a3e841511481a1dda35cc601739ef48', NULL, NULL, 'c195a1115b0f0798717a7139a2ab05a017980db34cf0d61273119dd359b959db', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (142, 1, 'f8f417c11cc9ea331576a07c99d3688d7f736b92.svg', 'themes/dongson/assets/images/f8f417c11cc9ea331576a07c99d3688d7f736b92.svg', NULL, 'image/svg+xml', 4147, NULL, NULL, 'F8f417c11cc9ea331576a07c99d3688d7f736b92', NULL, NULL, 'c2e8ffdcc418cabfd42cffd48eadf62a7da1a28026fe5959b2b40354d64d6bd6', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (143, 1, 'ff1950c2d83ef48c9b5afc6f33d409245e745644.svg', 'themes/dongson/assets/images/ff1950c2d83ef48c9b5afc6f33d409245e745644.svg', NULL, 'image/svg+xml', 1569, NULL, NULL, 'Ff1950c2d83ef48c9b5afc6f33d409245e745644', NULL, NULL, '3bc414acfb7c399aaca911d8bdbece32bb82750a1be5e0f987623659da366fd0', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (144, 1, 'hero-bg.jpg', 'themes/dongson/assets/images/hero-bg.jpg', NULL, 'image/jpeg', 985985, 1024, 1024, 'Hero bg', NULL, NULL, 'be725f71f051d697da17080bfaca82317567db0aa2c2c14df3cea6052f2afe0c', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (145, 1, 'news-baivien.webp', 'themes/dongson/assets/images/news-baivien.webp', NULL, 'image/webp', 29624, 512, 288, 'News baivien', NULL, NULL, '9252cfddcf3e8146da370f92b4182f82fbe3586f1a2284371c0641afb2318e0d', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (146, 1, 'news-cauhongha.webp', 'themes/dongson/assets/images/news-cauhongha.webp', NULL, 'image/webp', 12054, 338, 224, 'News cauhongha', NULL, NULL, '9439802cff00a8c177009a7729884c2396d96205f4b8cbce6e0bd1e37ded0923', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (147, 1, 'news-dhdcd.webp', 'themes/dongson/assets/images/news-dhdcd.webp', NULL, 'image/webp', 15142, 512, 288, 'News dhdcd', NULL, NULL, '5f8d2a5a359a12feee2a0fa2ea8a802b939965a106790b4e50626238e7ceee20', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (148, 1, 'news-hero.webp', 'themes/dongson/assets/images/news-hero.webp', NULL, 'image/webp', 81228, 1024, 576, 'News hero', NULL, NULL, '803c0d7f0ffc83bb36d28ecc4bbc101ae0dcfcc5d4d51ee630e35e86477316f3', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (149, 1, 'news-pickleball.webp', 'themes/dongson/assets/images/news-pickleball.webp', NULL, 'image/webp', 41094, 512, 279, 'News pickleball', NULL, NULL, '4bf9ed09925002a7183c7d14183f6203efbc2026d550975fad971c7fc7286eff', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (150, 1, 'sumenh-hero.webp', 'themes/dongson/assets/images/sumenh-hero.webp', NULL, 'image/webp', 85228, 1024, 576, 'Sumenh hero', NULL, NULL, '4306cf23b8b0defe33bf31de0fdef0b913bf3c1393472267c1ea515d0f6c5a26', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (151, 1, 'sumenh-mission-badge.svg', 'themes/dongson/assets/images/sumenh-mission-badge.svg', NULL, 'image/svg+xml', 362, NULL, NULL, 'Sumenh mission badge', NULL, NULL, '77342cceb1100db4c8ce7dd5784fb1cb2f7242fb544231d99f51e8d41ae8b94e', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (152, 1, 'sumenh-mission.webp', 'themes/dongson/assets/images/sumenh-mission.webp', NULL, 'image/webp', 319620, 2560, 1435, 'Sumenh mission', NULL, NULL, '3493c8ad22d0291bcf0d564903dfe084e28ffe48963d86b4500ebf62ec5fc859', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (153, 1, 'sumenh-tag-check.svg', 'themes/dongson/assets/images/sumenh-tag-check.svg', NULL, 'image/svg+xml', 1128, NULL, NULL, 'Sumenh tag check', NULL, NULL, '204ee053c98bdf6ff559aaf3a8ab3f5a4f0c9edf3dd6c3d3cd6dd4ea13d4ff85', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (154, 1, 'sumenh-value-chuyennghiep.svg', 'themes/dongson/assets/images/sumenh-value-chuyennghiep.svg', NULL, 'image/svg+xml', 1243, NULL, NULL, 'Sumenh value chuyennghiep', NULL, NULL, '7d5530dba0a95caa2bdcbb143d402e99a4e0b7d3454f69b6990d795b8351fa5f', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (155, 1, 'sumenh-value-doimoi.svg', 'themes/dongson/assets/images/sumenh-value-doimoi.svg', NULL, 'image/svg+xml', 1089, NULL, NULL, 'Sumenh value doimoi', NULL, NULL, 'c3b06bebe3b09c8693eeab272e17cfd9a2c33aa8d61fd214e4acad0df3bb8947', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (156, 1, 'sumenh-value-tincay.svg', 'themes/dongson/assets/images/sumenh-value-tincay.svg', NULL, 'image/svg+xml', 651, NULL, NULL, 'Sumenh value tincay', NULL, NULL, 'eb594b483dbf7c1a801546023da7392af74762d6bf5033b9e198234f9dfd0440', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (157, 1, 'sumenh-value-trachnhiem.svg', 'themes/dongson/assets/images/sumenh-value-trachnhiem.svg', NULL, 'image/svg+xml', 4000, NULL, NULL, 'Sumenh value trachnhiem', NULL, NULL, 'b6d9fb3be3c0ac7a4156860bf6b09b1d3dfce0af558406a201fa9d4906fb10e5', NULL, NULL, '2026-08-02 16:00:22', '2026-08-02 16:00:22', NULL);
INSERT INTO `pvn_media_files` VALUES (158, NULL, 'gemini-generated-image-p82qu2p82qu2p82q-20260802173508-15a1ca.png', 'uploads/202608/gemini-generated-image-p82qu2p82qu2p82q-20260802173508-15a1ca.png', NULL, 'image/png', 2453978, 864, 1184, NULL, NULL, NULL, 'e78ec02a69b8c5bf68775128d6d73e6906186342b527c36c4083f13f9c428876', NULL, 1, '2026-08-02 17:35:08', '2026-08-02 17:35:08', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 63 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `pvn_menu_items` VALUES (62, 7, NULL, 'Thẻ (Tag)', 'route', '/admin/tag/index', NULL, '_self', 'fa-tags', 'tags.view', 20, 0, 1, NULL, 1, '2026-08-02 16:37:47', '2026-08-02 16:37:47', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pvn_news_categories
-- ----------------------------
INSERT INTO `pvn_news_categories` VALUES (7, 'du-an', 'Dự án', NULL, NULL, 1, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (8, 'thi-cong', 'Thi công', NULL, NULL, 2, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (9, 'dau-tu', 'Đầu tư', NULL, NULL, 3, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);
INSERT INTO `pvn_news_categories` VALUES (10, 'co-dong', 'Cổ đông', NULL, NULL, 4, 1, 1, '2026-07-24 16:45:10', '2026-07-24 16:45:10', NULL);

-- ----------------------------
-- Table structure for pvn_news_post_tags
-- ----------------------------
DROP TABLE IF EXISTS `pvn_news_post_tags`;
CREATE TABLE `pvn_news_post_tags`  (
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`) USING BTREE,
  INDEX `idx_news_post_tags_tag`(`tag_id`) USING BTREE,
  CONSTRAINT `fk_news_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `pvn_news_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_news_post_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `pvn_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_news_post_tags
-- ----------------------------

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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 45 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
INSERT INTO `pvn_site_settings` VALUES (31, 'site_logo', '28', 'media', 'branding', 'Logo header', 'Logo hiển thị trên thanh điều hướng.', 1, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (32, 'site_logo_footer', '28', 'media', 'branding', 'Logo footer', 'Logo hiển thị ở chân trang.', 2, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (33, 'favicon', '', 'media', 'branding', 'Favicon', 'Biểu tượng trên tab trình duyệt (nên là .ico hoặc .png vuông).', 3, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (34, 'header_script', '', 'string', 'scripts', 'Script trong <head>', 'Chèn ngay trước </head> (ví dụ: verify domain, thẻ meta bổ sung).', 1, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (35, 'body_start_script', '', 'string', 'scripts', 'Script đầu <body>', 'Chèn ngay sau <body> (ví dụ: Google Tag Manager noscript).', 2, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (36, 'footer_script', '', 'string', 'scripts', 'Script cuối <body>', 'Chèn ngay trước </body> (ví dụ: Google Analytics, chat widget).', 3, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (37, 'meta_keywords', 'Đông Sơn Holdings, hạ tầng BOT, bất động sản, năng lượng, DSH', 'string', 'seo', 'Từ khoá SEO', 'Ngăn cách bằng dấu phẩy.', 10, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (38, 'meta_author', 'Đông Sơn Holdings', 'string', 'seo', 'Tác giả (author)', '', 11, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (39, 'meta_robots', 'index, follow', 'string', 'seo', 'Chỉ thị robots', 'Ví dụ: index, follow hoặc noindex, nofollow.', 12, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (40, 'canonical_base_url', '', 'string', 'seo', 'URL gốc (canonical)', 'Ví dụ: https://dongsonholdings.vn (không có dấu / cuối).', 13, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (41, 'og_image', '21', 'media', 'seo', 'Ảnh chia sẻ (OG image)', 'Ảnh hiển thị khi chia sẻ lên mạng xã hội (khuyến nghị 1200×630).', 14, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (42, 'og_type', 'website', 'string', 'seo', 'Loại Open Graph', 'Thường là website.', 15, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (43, 'twitter_card', 'summary_large_image', 'string', 'seo', 'Kiểu Twitter card', 'summary hoặc summary_large_image.', 16, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');
INSERT INTO `pvn_site_settings` VALUES (44, 'google_site_verification', '', 'string', 'seo', 'Mã Google Search Console', 'Chỉ nhập phần content của thẻ verify, để trống nếu không dùng.', 17, 1, '2026-08-02 15:59:35', '2026-08-02 15:59:35');

-- ----------------------------
-- Table structure for pvn_tags
-- ----------------------------
DROP TABLE IF EXISTS `pvn_tags`;
CREATE TABLE `pvn_tags`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(160) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_tags_slug`(`slug`) USING BTREE,
  INDEX `idx_tags_sort_order`(`sort_order`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pvn_tags
-- ----------------------------
INSERT INTO `pvn_tags` VALUES (1, 'epc', 'EPC', NULL, 1, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (2, 'ha-tang', 'Hạ tầng', NULL, 2, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (3, 'dan-dung', 'Dân dụng', NULL, 3, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (4, 'cong-nghiep', 'Công nghiệp', NULL, 4, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (5, 'bot', 'BOT', NULL, 5, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (6, 'cao-toc', 'Cao tốc', NULL, 6, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (7, 'cau-duong', 'Cầu đường', NULL, 7, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (8, 'vanh-dai', 'Vành đai', NULL, 8, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (9, 'nha-o-xa-hoi', 'Nhà ở xã hội', NULL, 9, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (10, 'do-thi', 'Đô thị', NULL, 10, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (11, 'bds', 'BĐS', NULL, 11, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (12, 'nang-luong-tai-tao', 'Năng lượng tái tạo', NULL, 12, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);
INSERT INTO `pvn_tags` VALUES (13, 'khu-cong-nghiep', 'Khu công nghiệp', NULL, 13, 1, '2026-08-02 16:37:46', '2026-08-02 16:37:46', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of pvn_users
-- ----------------------------
INSERT INTO `pvn_users` VALUES (1, 'admin@htds.vn', '$2y$12$Ir1BnogCBiXXsY0uc3KYoOqHHws6CybhpXpmIcqlGYqkDQxYNUJEu', 'Quản trị hệ thống', NULL, NULL, 1, NULL, '2026-08-02 17:34:05', '127.0.0.1', 0, NULL, NULL, NULL, NULL, '2026-07-24 16:34:47', '2026-07-30 16:34:12', NULL);

SET FOREIGN_KEY_CHECKS = 1;
