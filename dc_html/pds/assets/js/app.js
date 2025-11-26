
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

    // Use a global or a more robust way to pass this from PHP if it's dynamic
    // For now, assuming `allItems` is available globally if the script is in the footer
    // A better approach is to store it in a data attribute, e.g., data-items on the form
    const allItems = JSON.parse(document.body.getAttribute('data-all-items') || '[]');

    function createIngredientRow() {
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
            <label class="ingredient-consumable">
                <input type="checkbox" name="ingredients[${index}][is_consumable]" value="1">
                <span>Consumable</span>
            </label>
            <button type="button" class="remove-btn button-secondary">Remove</button>
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
