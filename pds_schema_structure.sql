-- pds_schema_structure.sql

-- Create pds_items table
CREATE TABLE `pds_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_code` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `base_unit` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create pds_tags table
CREATE TABLE `pds_tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_code` varchar(255) NOT NULL,
  `tag_name` varchar(255) NOT NULL,
  `tag_group` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_code` (`tag_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default tags
INSERT INTO `pds_tags` (`tag_code`, `tag_name`, `tag_group`) VALUES
('sys_type_raw', 'Raw Material', 'ItemType'),
('sys_type_semi', 'Semi-finished Good', 'ItemType'),
('sys_type_product', 'Product', 'ItemType'),
('proc_loc_kitchen', 'Kitchen Process', 'ProcessLocation'),
('proc_loc_bar', 'Bar Process', 'ProcessLocation');

-- Create pds_item_tag_map table
CREATE TABLE `pds_item_tag_map` (
  `map_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`map_id`),
  KEY `item_id` (`item_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `pds_item_tag_map_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `pds_items` (`item_id`),
  CONSTRAINT `pds_item_tag_map_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `pds_tags` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create pds_recipes table
CREATE TABLE `pds_recipes` (
  `recipe_id` int(11) NOT NULL AUTO_INCREMENT,
  `target_item_id` int(11) NOT NULL,
  `process_tag_id` int(11) NOT NULL,
  `yield_qty` decimal(10,2) NOT NULL,
  `yield_unit` varchar(50) NOT NULL,
  `instructions` text,
  PRIMARY KEY (`recipe_id`),
  KEY `target_item_id` (`target_item_id`),
  KEY `process_tag_id` (`process_tag_id`),
  CONSTRAINT `pds_recipes_ibfk_1` FOREIGN KEY (`target_item_id`) REFERENCES `pds_items` (`item_id`),
  CONSTRAINT `pds_recipes_ibfk_2` FOREIGN KEY (`process_tag_id`) REFERENCES `pds_tags` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create pds_recipe_ingredients table
CREATE TABLE `pds_recipe_ingredients` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe_id` int(11) NOT NULL,
  `material_item_id` int(11) NOT NULL,
  `usage_qty` decimal(10,2) NOT NULL,
  `is_consumable` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`detail_id`),
  KEY `recipe_id` (`recipe_id`),
  KEY `material_item_id` (`material_item_id`),
  CONSTRAINT `pds_recipe_ingredients_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `pds_recipes` (`recipe_id`),
  CONSTRAINT `pds_recipe_ingredients_ibfk_2` FOREIGN KEY (`material_item_id`) REFERENCES `pds_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;