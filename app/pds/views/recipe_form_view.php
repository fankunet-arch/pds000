<?php
// recipe_form_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

// Prepare data for the header and JavaScript
$page_title = t('create_new_recipe');
$all_items_json = json_encode($all_items);

$js_data = [
    'data-all-items' => htmlspecialchars($all_items_json, ENT_QUOTES, 'UTF-8'),
    'data-remove-text' => t('remove'),
    'data-select-material-text' => t('select_material'),
    'data-usage-qty-placeholder' => t('usage_qty'),
    'data-consumable-text' => t('is_consumable'),
];
$body_data_attributes = '';
foreach ($js_data as $key => $value) {
    $body_data_attributes .= "{$key}='" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "' ";
}

render_header([
    'page_title' => $page_title,
    'body_data_attributes' => trim($body_data_attributes)
]);
?>

<div class="card">
    <?php if (isset($error_message)): ?>
        <p class="message error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="message success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form action="?action=recipe_save" method="post">
        <fieldset>
            <legend><?php echo t('recipe_header'); ?></legend>
            <div class="form-group">
                <label for="target_item_id"><?php echo t('product_target_item'); ?></label>
                <select id="target_item_id" name="target_item_id" required>
                    <option value=""><?php echo t('select_one'); ?></option>
                    <?php foreach ($all_items as $item): ?>
                        <option value="<?php echo htmlspecialchars($item['item_id']); ?>">
                            <?php echo htmlspecialchars($item['item_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="process_tag_id"><?php echo t('process_location'); ?></label>
                <select id="process_tag_id" name="process_tag_id" required>
                    <option value=""><?php echo t('select_one'); ?></option>
                    <?php foreach ($process_location_tags as $tag): ?>
                        <option value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                            <?php echo htmlspecialchars($tag['tag_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="yield_qty"><?php echo t('yield_quantity'); ?></label>
                <input type="number" id="yield_qty" name="yield_qty" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="yield_unit"><?php echo t('yield_unit'); ?></label>
                <input type="text" id="yield_unit" name="yield_unit" required>
            </div>
            <div class="form-group">
                <label for="instructions"><?php echo t('instructions'); ?></label>
                <textarea id="instructions" name="instructions"></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend><?php echo t('ingredients_bom'); ?></legend>
            <div id="ingredients_container">
                <!-- Ingredient rows will be added by app.js -->
            </div>
            <button type="button" id="add_ingredient" class="button-secondary"><?php echo t('add_ingredient'); ?></button>
        </fieldset>

        <button type="submit" class="button-primary"><?php echo t('save'); ?></button>
    </form>
</div>

<?php
render_footer();
