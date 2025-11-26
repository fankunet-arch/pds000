<?php
// header_view.php
if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

// Set a default title if not provided
$page_title = isset($page_title) ? htmlspecialchars($page_title) : 'PDS Application';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo PDS_WEB_ROOT; ?>assets/css/style.css">
</head>
<body <?php echo isset($body_data_attributes) ? $body_data_attributes : ''; ?>>

<div class="container">
    <header class="main-header">
        <!-- Main navigation link back to the dashboard -->
        <a href="?action=dashboard" class="home-link"><h1>PDS - Preparation & Development System</h1></a>
    </header>
    <main>
