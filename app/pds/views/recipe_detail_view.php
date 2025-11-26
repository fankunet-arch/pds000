<?php
// recipe_detail_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

render_header(['page_title' => t('traceability_demo')]);
?>

<div class="traceability-container">
    <div class="traceability-column">
        <div class="card">
            <h3><?php echo t('bom_explosion'); ?></h3>
            <form action="?action=recipe_detail" method="get">
                <input type="hidden" name="action" value="recipe_detail">
                <div class="form-group">
                    <label for="product_id"><?php echo t('select_product'); ?></label>
                    <select name="product_id" id="product_id" onchange="this.form.submit()">
                        <option value=""><?php echo t('select_one'); ?></option>
                        <?php foreach ($all_items as $item): ?>
                            <option value="<?php echo $item['item_id']; ?>" <?php if (isset($selected_product_id) && $selected_product_id == $item['item_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($item['item_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (isset($bom_results)): ?>
                <h4><?php printf(t('raw_materials_for'), htmlspecialchars($selected_product_name)); ?></h4>
                <?php if (empty($bom_results)): ?>
                    <p><?php echo t('no_raw_materials_found'); ?></p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th><?php echo t('item_name'); ?></th>
                                <th><?php echo t('total_quantity'); ?></th>
                                <th><?php echo t('unit'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bom_results as $material): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($material['item_name']); ?></td>
                                    <td><?php echo round($material['total_qty'], 4); ?></td>
                                    <td><?php echo htmlspecialchars($material['unit']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="traceability-column">
        <div class="card">
            <h3><?php echo t('impact_analysis'); ?></h3>
            <form action="?action=recipe_detail" method="get">
                <input type="hidden" name="action" value="recipe_detail">
                <div class="form-group">
                    <label for="material_id"><?php echo t('select_material_to_trace'); ?></label>
                    <select name="material_id" id="material_id" onchange="this.form.submit()">
                        <option value=""><?php echo t('select_one'); ?></option>
                        <?php foreach ($all_items as $item): ?>
                            <option value="<?php echo $item['item_id']; ?>" <?php if (isset($selected_material_id) && $selected_material_id == $item['item_id']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($item['item_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (isset($usage_results)): ?>
                <h4><?php printf(t('affected_final_products'), htmlspecialchars($selected_material_name)); ?></h4>
                <?php if (empty($usage_results)): ?>
                    <p><?php echo t('material_does_not_affect_products'); ?></p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th><?php echo t('item_code'); ?></th>
                                <th><?php echo t('product_name'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usage_results as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['item_code']); ?></td>
                                    <td><?php echo htmlspecialchars($product['item_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
render_footer();
