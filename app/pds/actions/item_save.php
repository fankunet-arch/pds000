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
        $error_message = t('error_all_fields_required');
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
            $success_message = sprintf(t('success_item_created'), $item_data['item_name'], $new_item_id);
        } catch (Exception $e) {
            $error_message = sprintf(t('error_item_creation_failed'), $e->getMessage());
        }
    }
}

// Fetch data for the view
$item_type_tags = [];
if ($pdo) {
    try {
        $item_type_tags = get_tags($pdo, 'ItemType');
    } catch (Exception $e) {
        $error_message = "Error fetching tags: " . $e->getMessage();
    }
} elseif (!isset($success_message)) {
    // Show DB error only if there isn't another message already.
    $error_message = "Database connection not available.";
}

// Render the view
render_view('item_form_view', [
    'item_type_tags' => $item_type_tags,
    'error_message' => $error_message,
    'success_message' => $success_message,
]);
