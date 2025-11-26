<?php
// index.php - Central Controller

// Define a constant to prevent direct file access
define('PDS_ENTRY', true);

// 1. Bootstrap the application
require_once __DIR__ . '/bootstrap.php';

// 2. Define a whitelist of allowed actions
$allowed_actions = [
    'dashboard',
    'item_save',
    'recipe_save',
    'recipe_detail',
    // Add more actions here as they are created
];

// 3. Get the requested action from the URL, default to 'dashboard'
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// 4. Validate the action against the whitelist
if (in_array($action, $allowed_actions)) {
    // Construct the file path for the action
    $action_file = PDS_APP_PATH . '/actions/' . $action . '.php';

    // If the action file exists, include it.
    if (file_exists($action_file)) {
        require_once $action_file;
    } else {
        // Handle cases where the action file is missing
        header("HTTP/1.0 404 Not Found");
        echo "Error: Action file not found for '$action'.";
        exit;
    }
} else {
    // Handle invalid or disallowed actions
    header("HTTP/1.0 403 Forbidden");
    echo "Error: Action '$action' is not allowed.";
    exit;
}
