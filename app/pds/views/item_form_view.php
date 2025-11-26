<?php
// item_form_view.php

if (!defined('PDS_ENTRY')) {
    die('Access denied.');
}

render_header(['page_title' => t('create_new_item')]);
?>

<div class="card">
    <?php if (isset($error_message)): ?>
        <p class="message error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <?php if (isset($success_message)): ?>
        <p class="message success"><?php echo htmlspecialchars($success_message); ?></p>
    <?php endif; ?>

    <form action="?action=item_save" method="post">
        <div class="form-group">
            <label for="item_code"><?php echo t('item_code'); ?></label>
            <input type="text" id="item_code" name="item_code" required>
        </div>
        <div class="form-group">
            <label for="item_name"><?php echo t('item_name'); ?></label>
            <input type="text" id="item_name" name="item_name" required>
        </div>
        <div class="form-group">
            <label for="base_unit"><?php echo t('base_unit'); ?></label>
            <input type="text" id="base_unit" name="base_unit" required>
        </div>
        <div class="form-group">
            <label for="tags"><?php echo t('item_type'); ?></label>
            <select id="tags" name="tags[]" multiple required>
                <?php foreach ($item_type_tags as $tag): ?>
                    <option value="<?php echo htmlspecialchars($tag['tag_id']); ?>">
                        <?php echo htmlspecialchars($tag['tag_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="button-primary"><?php echo t('save'); ?></button>
    </form>
</div>

<?php
render_footer();
