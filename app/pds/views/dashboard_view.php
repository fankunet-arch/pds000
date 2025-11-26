<?php
// dashboard_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDS Dashboard</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        h1 { border-bottom: 2px solid #eee; padding-bottom: 10px; }
        nav ul { list-style: none; padding: 0; }
        nav li { margin-bottom: 10px; }
        nav a { text-decoration: none; color: #007bff; font-size: 1.2em; }
        nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h1>PDS - Preparation & Development System</h1>
    <p>Welcome to the PDS dashboard. Please use the links below to manage the system.</p>

    <nav>
        <ul>
            <li><a href="?action=item_save">Create New Item</a></li>
            <li><a href="?action=recipe_save">Create New Recipe</a></li>
            <li><a href="?action=recipe_detail">Traceability & BOM Explorer</a></li>
        </ul>
    </nav>
</body>
</html>
