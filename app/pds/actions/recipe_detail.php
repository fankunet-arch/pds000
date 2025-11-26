<?php
// recipe_detail.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

global $pdo;

$view_data = [];

// Fetch all items for the dropdowns
$view_data['all_items'] = [];
if ($pdo) {
    try {
        $view_data['all_items'] = get_all_items($pdo);
    } catch (Exception $e) {
        $view_data['error'] = "Error fetching items: " . $e->getMessage();
    }
} else {
    $view_data['error'] = "Database connection not available.";
}

// --- Handle BOM Explosion ---
if ($pdo && isset($_GET['product_id']) && !empty($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];
    $view_data['selected_product_id'] = $product_id;

    $stmt = $pdo->prepare("SELECT item_name FROM pds_items WHERE item_id = :id");
    $stmt->execute([':id' => $product_id]);
    $view_data['selected_product_name'] = $stmt->fetchColumn();

    $raw_materials = [];
    explode_bom_recursive($pdo, $product_id, 1, $raw_materials);
    $view_data['bom_results'] = $raw_materials;
}

// --- Handle Impact Analysis ---
if ($pdo && isset($_GET['material_id']) && !empty($_GET['material_id'])) {
    $material_id = (int)$_GET['material_id'];
    $view_data['selected_material_id'] = $material_id;

    $stmt = $pdo->prepare("SELECT item_name FROM pds_items WHERE item_id = :id");
    $stmt->execute([':id' => $material_id]);
    $view_data['selected_material_name'] = $stmt->fetchColumn();

    $affected_products = [];
    trace_usage_recursive($pdo, $material_id, $affected_products);
    $view_data['usage_results'] = $affected_products;
}

render_view('recipe_detail_view', $view_data);
