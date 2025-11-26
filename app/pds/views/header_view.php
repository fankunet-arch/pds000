<?php
// header_view.php
if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}
global $lang; // Make lang array available
$page_title = isset($page_title) ? htmlspecialchars($page_title) : t('pds_title');
$current_action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo PDS_WEB_ROOT; ?>assets/css/style.css">
</head>
<body <?php echo isset($body_data_attributes) ? $body_data_attributes : ''; ?>>

<div class="wrapper">
    <aside class="sidebar">
        <h1><a href="?action=dashboard"><?php echo t('pds_title'); ?></a></h1>
        <nav class="main-nav">
            <ul>
                <li><a href="?action=dashboard" class="<?php echo $current_action === 'dashboard' ? 'active' : ''; ?>"><?php echo t('dashboard'); ?></a></li>
                <li><a href="?action=item_save" class="<?php echo $current_action === 'item_save' ? 'active' : ''; ?>"><?php echo t('nav_create_item'); ?></a></li>
                <li><a href="?action=recipe_save" class="<?php echo $current_action === 'recipe_save' ? 'active' : ''; ?>"><?php echo t('nav_create_recipe'); ?></a></li>
                <li><a href="?action=recipe_detail" class="<?php echo $current_action === 'recipe_detail' ? 'active' : ''; ?>"><?php echo t('nav_traceability'); ?></a></li>
            </ul>
        </nav>
    </aside>
    <main class="main-content">
        <header class="page-header">
            <h2><?php echo htmlspecialchars($page_title); ?></h2>
        </header>
        <div class="content-body">
