<?php
// pds_lib.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

// This file will contain data models and generic functions.
// We will add functions here as we build out the features.

/**
 * Fetches tags from the database, optionally filtered by group.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param string|null $group The tag group to filter by (e.g., 'ItemType').
 * @return array The list of tags.
 */
function get_tags(PDO $pdo, $group = null) {
    $sql = "SELECT * FROM pds_tags";
    if ($group !== null) {
        $sql .= " WHERE tag_group = :tag_group";
    }
    $stmt = $pdo->prepare($sql);
    if ($group !== null) {
        $stmt->bindParam(':tag_group', $group, PDO::PARAM_STR);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Fetches all items from the database.
 *
 * @param PDO $pdo The PDO database connection object.
 * @return array The list of all items.
 */
function get_all_items(PDO $pdo) {
    $stmt = $pdo->query("SELECT item_id, item_name, item_code FROM pds_items WHERE status = 1 ORDER BY item_name ASC");
    return $stmt->fetchAll();
}

/**
 * Saves a recipe and its ingredients in a transaction.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param array $recipe_data The data for the recipe header.
 * @param array $ingredients_data The list of ingredients for the recipe.
 * @return int The ID of the newly created recipe.
 */
function save_recipe(PDO $pdo, array $recipe_data, array $ingredients_data) {
    try {
        $pdo->beginTransaction();

        // 1. Insert into pds_recipes
        $stmt_recipe = $pdo->prepare(
            "INSERT INTO pds_recipes (target_item_id, process_tag_id, yield_qty, yield_unit, instructions) VALUES (:target_item_id, :process_tag_id, :yield_qty, :yield_unit, :instructions)"
        );
        $stmt_recipe->execute([
            ':target_item_id' => $recipe_data['target_item_id'],
            ':process_tag_id' => $recipe_data['process_tag_id'],
            ':yield_qty'      => $recipe_data['yield_qty'],
            ':yield_unit'     => $recipe_data['yield_unit'],
            ':instructions'   => $recipe_data['instructions'],
        ]);
        $recipe_id = $pdo->lastInsertId();

        // 2. Insert into pds_recipe_ingredients
        $stmt_ingredient = $pdo->prepare(
            "INSERT INTO pds_recipe_ingredients (recipe_id, material_item_id, usage_qty, is_consumable) VALUES (:recipe_id, :material_item_id, :usage_qty, :is_consumable)"
        );
        foreach ($ingredients_data as $ingredient) {
            $stmt_ingredient->execute([
                ':recipe_id'        => $recipe_id,
                ':material_item_id' => $ingredient['material_item_id'],
                ':usage_qty'        => $ingredient['usage_qty'],
                ':is_consumable'    => isset($ingredient['is_consumable']) ? $ingredient['is_consumable'] : 0,
            ]);
        }

        $pdo->commit();
        return (int)$recipe_id;

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Saves an item and its associated tags in a transaction.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param array $item_data The data for the item.
 * @param array $tag_ids The IDs of the tags to associate with the item.
 * @return int The ID of the newly created item.
 */
function save_item(PDO $pdo, array $item_data, array $tag_ids) {
    try {
        $pdo->beginTransaction();

        // 1. Insert into pds_items
        $stmt = $pdo->prepare(
            "INSERT INTO pds_items (item_code, item_name, base_unit, status) VALUES (:item_code, :item_name, :base_unit, :status)"
        );
        $stmt->execute([
            ':item_code' => $item_data['item_code'],
            ':item_name' => $item_data['item_name'],
            ':base_unit' => $item_data['base_unit'],
            ':status'    => isset($item_data['status']) ? $item_data['status'] : 1,
        ]);
        $item_id = $pdo->lastInsertId();

        // 2. Insert into pds_item_tag_map
        if (!empty($tag_ids)) {
            $stmt_map = $pdo->prepare("INSERT INTO pds_item_tag_map (item_id, tag_id) VALUES (:item_id, :tag_id)");
            foreach ($tag_ids as $tag_id) {
                $stmt_map->execute([
                    ':item_id' => $item_id,
                    ':tag_id'  => $tag_id,
                ]);
            }
        }

        $pdo->commit();
        return (int)$item_id;

    } catch (Exception $e) {
        $pdo->rollBack();
        // In a real app, log this error
        throw $e;
    }
}

// --- Traceability Functions ---

/**
 * Helper to get the recipe and ingredients for a single item.
 * @internal
 */
function _get_recipe_for_item(PDO $pdo, int $item_id) {
    $stmt = $pdo->prepare("SELECT * FROM pds_recipes WHERE target_item_id = :item_id LIMIT 1");
    $stmt->execute([':item_id' => $item_id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        return null;
    }

    $stmt = $pdo->prepare("
        SELECT ri.material_item_id, ri.usage_qty, i.item_name, i.base_unit
        FROM pds_recipe_ingredients ri
        JOIN pds_items i ON ri.material_item_id = i.item_id
        WHERE ri.recipe_id = :recipe_id
    ");
    $stmt->execute([':recipe_id' => $recipe['recipe_id']]);
    $recipe['ingredients'] = $stmt->fetchAll();
    return $recipe;
}

/**
 * Recursively expands a product's BOM to find all raw materials.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param int $item_id The ID of the item to explode.
 * @param float $quantity The quantity of the parent item needed.
 * @param array &$raw_materials The accumulating list of raw materials.
 */
function explode_bom_recursive(PDO $pdo, int $item_id, float $quantity, array &$raw_materials) {
    $recipe = _get_recipe_for_item($pdo, $item_id);

    if ($recipe === null || empty($recipe['ingredients'])) {
        // This is a base material (raw or a semi-good without a recipe)
        if (!isset($raw_materials[$item_id])) {
            $stmt = $pdo->prepare("SELECT item_name, base_unit FROM pds_items WHERE item_id = :item_id");
            $stmt->execute([':item_id' => $item_id]);
            $item_info = $stmt->fetch();
            $raw_materials[$item_id] = [
                'item_id'   => $item_id,
                'item_name' => $item_info['item_name'],
                'total_qty' => 0,
                'unit'      => $item_info['base_unit'],
            ];
        }
        $raw_materials[$item_id]['total_qty'] += $quantity;
        return;
    }

    $yield_qty = (float) $recipe['yield_qty'];
    if ($yield_qty == 0) $yield_qty = 1; // Avoid division by zero

    foreach ($recipe['ingredients'] as $ingredient) {
        $needed_qty = ((float) $ingredient['usage_qty'] / $yield_qty) * $quantity;
        explode_bom_recursive($pdo, (int) $ingredient['material_item_id'], $needed_qty, $raw_materials);
    }
}


/**
 * Helper to find the direct parent items of a given material.
 * @internal
 */
function _get_parent_items(PDO $pdo, int $material_item_id) {
    $sql = "SELECT DISTINCT r.target_item_id
            FROM pds_recipe_ingredients ri
            JOIN pds_recipes r ON ri.recipe_id = r.recipe_id
            WHERE ri.material_item_id = :material_item_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':material_item_id' => $material_item_id]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Helper to check if an item is a final product via its tags.
 * @internal
 */
function _is_final_product(PDO $pdo, int $item_id) {
    $sql = "SELECT 1
            FROM pds_item_tag_map m
            JOIN pds_tags t ON m.tag_id = t.tag_id
            WHERE m.item_id = :item_id AND t.tag_code = 'sys_type_product'
            LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':item_id' => $item_id]);
    return $stmt->fetchColumn() !== false;
}

/**
 * Recursively traces the usage of a material to find all affected final products.
 *
 * @param PDO $pdo The PDO database connection object.
 * @param int $material_item_id The ID of the material to trace.
 * @param array &$affected_products The accumulating list of affected products.
 */
function trace_usage_recursive(PDO $pdo, int $material_item_id, array &$affected_products) {
    $parent_item_ids = _get_parent_items($pdo, $material_item_id);

    if (empty($parent_item_ids)) {
        return; // End of the line
    }

    foreach ($parent_item_ids as $parent_id) {
        $parent_id = (int) $parent_id;
        if (_is_final_product($pdo, $parent_id)) {
            if (!isset($affected_products[$parent_id])) {
                $stmt = $pdo->prepare("SELECT item_id, item_name, item_code FROM pds_items WHERE item_id = :item_id");
                $stmt->execute([':item_id' => $parent_id]);
                $affected_products[$parent_id] = $stmt->fetch();
            }
        }
        // Continue tracing upwards from this parent
        trace_usage_recursive($pdo, $parent_id, $affected_products);
    }
}


/**
 * Renders a view template.
 *
 * @param string $view_name The name of the view file (without .php extension).
 * @param array $data Data to be extracted and made available to the view.
 */
function render_header($data = []) {
    render_view('header_view', $data);
}

/**
 * Renders the footer template.
 *
 * @param array $data Data to be extracted and made available to the view.
 */
function render_footer($data = []) {
    render_view('footer_view', $data);
}

/**
 * Renders a view template.
 *
 * @param string $view_name The name of the view file (without .php extension).
 * @param array $data Data to be extracted and made available to the view.
 */
function render_view($view_name, $data = []) {
    $view_file = PDS_APP_PATH . '/views/' . $view_name . '.php';

    if (file_exists($view_file)) {
        // Extract the data array into individual variables
        extract($data);

        // Include the view file
        require $view_file;
    } else {
        // Handle view file not found
        echo "Error: View file not found for '$view_name'.";
    }
}
