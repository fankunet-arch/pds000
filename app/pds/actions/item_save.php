<?php
// item_save.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

global $pdo; // Assuming $pdo is available globally from bootstrap.php

$error_message = null;
$success_message = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    if (empty($_POST['item_code']) || empty($_POST['item_name']) || empty($_POST['base_unit']) || empty($_POST['tags'])) {
        $error_message = "All fields are required, and at least one item type must be selected.";
    } else {
        $item_data = [
            'item_code' => $_POST['item_code'],
            'item_name' => $_POST['item_name'],
            'base_unit' => $_POST['base_unit'],
            'status'    => 1, // Default to enabled
        ];

        // Ensure tags are an array of integers
        $tag_ids = array_map('intval', $_POST['tags']);

        try {
            $new_item_id = save_item($pdo, $item_data, $tag_ids);
            $success_message = "Item '{$item_data['item_name']}' created successfully with ID: {$new_item_id}.";
        } catch (Exception $e) {
            // In a real app, you would log the detailed error.
            $error_message = "Error saving item: " . $e->getMessage();
        }
    }
}

// Fetch data for the view
try {
    $item_type_tags = get_tags($pdo, 'ItemType');
} catch (Exception $e) {
    $error_message = "Error fetching tags: " . $e->getMessage();
    $item_type_tags = [];
}

// Render the view
render_view('item_form_view', [
    'item_type_tags' => $item_type_tags,
    'error_message' => $error_message,
    'success_message' => $success_message,
]);
