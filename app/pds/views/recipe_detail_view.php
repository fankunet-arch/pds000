<?php
// recipe_detail_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

render_header(['page_title' => 'Traceability Demo']);
?>

<div class="traceability-container">
    <div class="traceability-column">
        <h2>BOM Explosion (Forward Trace)</h2>
        <form action="?action=recipe_detail" method="get">
            <input type="hidden" name="action" value="recipe_detail">
            <div class="form-group">
                <label for="product_id">Select a Product:</label>
                <select name="product_id" id="product_id" onchange="this.form.submit()">
                    <option value="">-- Choose Item --</option>
                    <?php foreach ($all_items as $item): ?>
                        <option value="<?php echo $item['item_id']; ?>" <?php if (isset($selected_product_id) && $selected_product_id == $item['item_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($item['item_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (isset($bom_results)): ?>
            <h3>Raw Materials for 1 unit of "<?php echo htmlspecialchars($selected_product_name); ?>"</h3>
            <?php if (empty($bom_results)): ?>
                <p>No raw materials found. This might be a raw material itself or a product without a defined recipe.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Total Quantity</th>
                            <th>Unit</th>
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

    <div class="traceability-column">
        <h2>Impact Analysis (Reverse Trace)</h2>
        <form action="?action=recipe_detail" method="get">
            <input type="hidden" name="action" value="recipe_detail">
            <div class="form-group">
                <label for="material_id">Select a Material:</label>
                <select name="material_id" id="material_id" onchange="this.form.submit()">
                    <option value="">-- Choose Item --</option>
                    <?php foreach ($all_items as $item): ?>
                        <option value="<?php echo $item['item_id']; ?>" <?php if (isset($selected_material_id) && $selected_material_id == $item['item_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($item['item_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if (isset($usage_results)): ?>
            <h3>Affected Final Products by "<?php echo htmlspecialchars($selected_material_name); ?>"</h3>
            <?php if (empty($usage_results)): ?>
                <p>This material does not affect any final products.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Item Code</th>
                            <th>Product Name</th>
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

<?php
render_footer();
