<?php
// recipe_form_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Management</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="number"], select, textarea { width: 300px; padding: 8px; box-sizing: border-box; }
        textarea { height: 80px; }
        button { padding: 10px 15px; cursor: pointer; }
        #ingredients_container .ingredient-row { display: flex; align-items: center; margin-bottom: 10px; }
        #ingredients_container .ingredient-row > * { margin-right: 10px; }
        .error { color: red; }
        .success { color: green; }
        fieldset { border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; }
        legend { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Create New Recipe</h1>

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
                <!-- Ingredient rows will be added here -->
            </div>
            <button type="button" id="add_ingredient">Add Ingredient</button>
        </fieldset>

        <button type="submit">Save Recipe</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('ingredients_container');
            const addButton = document.getElementById('add_ingredient');
            let ingredientIndex = 0;

            const allItems = <?php echo json_encode($all_items); ?>;

            function addIngredientRow() {
                const index = ingredientIndex++;
                const row = document.createElement('div');
                row.className = 'ingredient-row';

                let options = '<option value="">-- Select Material --</option>';
                allItems.forEach(item => {
                    options += `<option value="${item.item_id}">${item.item_name}</option>`;
                });

                row.innerHTML = `
                    <select name="ingredients[${index}][material_item_id]" required>${options}</select>
                    <input type="number" name="ingredients[${index}][usage_qty]" placeholder="Usage Qty" step="0.01" required>
                    <label style="display:inline-flex; align-items:center;"><input type="checkbox" name="ingredients[${index}][is_consumable]" value="1"> Consumable</label>
                    <button type="button" class="remove-btn">Remove</button>
                `;

                container.appendChild(row);
            }

            container.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-btn')) {
                    e.target.closest('.ingredient-row').remove();
                }
            });

            addButton.addEventListener('click', addIngredientRow);

            // Add one ingredient row by default
            addIngredientRow();
        });
    </script>
</body>
</html>
