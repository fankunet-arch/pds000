<?php
// item_form_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Management</title>
    <!-- Simple styling for demonstration -->
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], select { width: 300px; padding: 8px; }
        button { padding: 10px 15px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Create New Item</h1>

    <?php if (isset($error_message)): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form action="?action=item_save" method="post">
        <div class="form-group">
            <label for="item_code">Item Code</label>
            <input type="text" id="item_code" name="item_code" required>
        </div>
        <div class="form-group">
            <label for="item_name">Item Name</label>
            <input type="text" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="base_unit">Base Unit (e.g., g, ml, pcs)</label>
            <input type="text" id="base_unit" name="base_unit" required>
        </div>
        <div class="form-group">
            <label for="tags">Item Type (Select at least one)</label>
            <select id="tags" name="tags[]" multiple required size="3">
                <?php foreach ($item_type_tags as $tag): ?>
                    <option value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                        <?php echo htmlspecialchars($tag['tag_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Save Item</button>
    </form>
</body>
</html>
