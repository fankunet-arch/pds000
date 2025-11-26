<?php
// recipe_save.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

global $pdo;

$error_message = null;
$success_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    if (
        empty($_POST['target_item_id']) ||
        empty($_POST['process_tag_id']) ||
        empty($_POST['yield_qty']) ||
        empty($_POST['yield_unit']) ||
        empty($_POST['ingredients']) ||
        !is_array($_POST['ingredients'])
    ) {
        $error_message = "Recipe header fields and at least one ingredient are required.";
    } else {
        $recipe_data = [
            'target_item_id' => (int)$_POST['target_item_id'],
            'process_tag_id' => (int)$_POST['process_tag_id'],
            'yield_qty'      => (float)$_POST['yield_qty'],
            'yield_unit'     => $_POST['yield_unit'],
            'instructions'   => $_POST['instructions'],
        ];

        $ingredients_data = [];
        foreach ($_POST['ingredients'] as $ing) {
            if (!empty($ing['material_item_id']) && !empty($ing['usage_qty'])) {
                $ingredients_data[] = [
                    'material_item_id' => (int)$ing['material_item_id'],
                    'usage_qty'        => (float)$ing['usage_qty'],
                    'is_consumable'    => isset($ing['is_consumable']) ? 1 : 0,
                ];
            }
        }

        if (empty($ingredients_data)) {
            $error_message = "At least one valid ingredient is required.";
        } else {
            try {
                $new_recipe_id = save_recipe($pdo, $recipe_data, $ingredients_data);
                $success_message = "Recipe created successfully with ID: {$new_recipe_id}.";
            } catch (Exception $e) {
                $error_message = "Error saving recipe: " . $e->getMessage();
            }
        }
    }
}

// Fetch data for the view
try {
    $all_items = get_all_items($pdo);
    $process_location_tags = get_tags($pdo, 'ProcessLocation');
} catch (Exception $e) {
    $error_message = "Error fetching data for the form: " . $e->getMessage();
    $all_items = [];
    $process_location_tags = [];
}

render_view('recipe_form_view', [
    'all_items' => $all_items,
    'process_location_tags' => $process_location_tags,
    'error_message' => $error_message,
    'success_message' => $success_message,
]);
