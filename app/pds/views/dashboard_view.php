<?php
// dashboard_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

render_header(['page_title' => 'PDS Dashboard']);
?>

<p>Welcome to the PDS dashboard. Please use the links below to manage the system.</p>

<nav>
    <ul>
        <li><a href="?action=item_save">Create New Item</a></li>
        <li><a href="?action=recipe_save">Create New Recipe</a></li>
        <li><a href="?action=recipe_detail">Traceability & BOM Explorer</a></li>
    </ul>
</nav>

<?php
render_footer();
