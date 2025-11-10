/**
 * Handles the addition of a Category form row.
 * This function should be initialized on the DOMContentLoaded event.
 */
document.addEventListener('DOMContentLoaded', function () {

    const addButton = document.getElementById('add-category-button');
    const categorySelect = document.getElementById('recipe_category');

    let newCategoryCounter = -1;

    if (!addButton || !categorySelect) {
        console.log('Category handler not initialized: missing add button or select');
    }

    addButton.addEventListener('click', function() {

        const newCategoryName = prompt("Enter the name of the new category:");

        if (!newCategoryName || newCategoryName.trim() === "") {
            return; // User cancelled or entered nothing
        }

        const trimmedName = newCategoryName.trim();

        const newOption = document.createElement('option');
        newOption.value = trimmedName;
        newOption.textContent = trimmedName;

        categorySelect.appendChild(newOption);
        categorySelect.value = newOption.value;

        newCategoryCounter--;

        alert(`Category "${trimmedName}" added to list and selected! Note: This category is currently unsaved.`);
    });
});
