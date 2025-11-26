<?php
// recipe_form_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

// Prepare data for the header and JavaScript
$page_title = 'Recipe Management';
$all_items_json = json_encode($all_items);
$body_data_attributes = "data-all-items='" . htmlspecialchars($all_items_json, ENT_QUOTES, 'UTF-8') . "'";

render_header([
    'page_title' => $page_title,
    'body_data_attributes' => $body_data_attributes
]);
?>

<h2>Create New Recipe</h2>

<?php if (isset($error_message)): ?>
    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<?php if (isset($success_message)): ?>
    <p class="success"><?php echo htmlspecialchars($success_message); ?></p>
<?php endif; ?>

<form action="?action=recipe_save" method="post">
    <fieldset>
        <legend>Recipe Header</legend>
        <div class="form-group">
            <label for="target_item_id">Product (Target Item)</label>
            <select id="target_item_id" name="target_item_id" required>
                <option value="">-- Select a Product --</option>
                <?php foreach ($all_items as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['item_id']); ?>">
                        <?php echo htmlspecialchars($item['item_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="process_tag_id">Process Location</label>
            <select id="process_tag_id" name="process_tag_id" required>
                <option value="">-- Select a Location --</option>
                <?php foreach ($process_location_tags as $tag): ?>
                    <option value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                        <?php echo htmlspecialchars($tag['tag_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="yield_qty">Yield Quantity</label>
            <input type="number" id="yield_qty" name="yield_qty" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="yield_unit">Yield Unit</label>
            <input type="text" id="yield_unit" name="yield_unit" required>
        </div>
        <div class="form-group">
            <label for="instructions">Instructions</label>
            <textarea id="instructions" name="instructions"></textarea>
        </div>
    </fieldset>

    <fieldset>
        <legend>Ingredients (BOM)</legend>
        <div id="ingredients_container">
            <!-- Ingredient rows will be added by app.js -->
        </div>
        <button type="button" id="add_ingredient" class="button-secondary">Add Ingredient</button>
    </fieldset>

    <button type="submit">Save Recipe</button>
</form>

<?php
render_footer();
