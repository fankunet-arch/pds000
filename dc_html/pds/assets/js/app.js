
document.addEventListener('DOMContentLoaded', function () {
    const recipeForm = document.querySelector('form[action="?action=recipe_save"]');

    if (recipeForm) {
        initializeRecipeForm();
    }
});

function initializeRecipeForm() {
    const container = document.getElementById('ingredients_container');
    const addButton = document.getElementById('add_ingredient');

    // Check if the necessary elements exist before proceeding
    if (!container || !addButton) {
        console.error('Recipe form ingredient container or add button not found.');
        return;
    }

    let ingredientIndex = container.children.length;

    const allItems = JSON.parse(document.body.getAttribute('data-all-items') || '[]');
    const removeText = document.body.getAttribute('data-remove-text') || 'Remove';
    const selectMaterialText = document.body.getAttribute('data-select-material-text') || '-- Select Material --';
    const usageQtyPlaceholder = document.body.getAttribute('data-usage-qty-placeholder') || 'Usage Qty';
    const consumableText = document.body.getAttribute('data-consumable-text') || 'Consumable';

    function createIngredientRow() {
        const index = ingredientIndex++;
        const row = document.createElement('div');
        row.className = 'ingredient-row';

        let options = `<option value="">${selectMaterialText}</option>`;
        allItems.forEach(item => {
            options += `<option value="${item.item_id}">${item.item_name}</option>`;
        });

        row.innerHTML = `
            <select name="ingredients[${index}][material_item_id]" required>${options}</select>
            <input type="number" name="ingredients[${index}][usage_qty]" placeholder="${usageQtyPlaceholder}" step="0.01" required>
            <label class="ingredient-consumable">
                <input type="checkbox" name="ingredients[${index}][is_consumable]" value="1">
                <span>${consumableText}</span>
            </label>
            <button type="button" class="remove-btn">${removeText}</button>
        `;

        container.appendChild(row);
    }

    container.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-btn')) {
            e.target.closest('.ingredient-row').remove();
        }
    });

    addButton.addEventListener('click', createIngredientRow);

    // If there are no ingredients on page load, add one by default
    if (container.children.length === 0) {
        createIngredientRow();
    }
}
