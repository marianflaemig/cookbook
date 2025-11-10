document.addEventListener('DOMContentLoaded', () => {
    const categoryInput = document.getElementById('recipe_category');
    const categoryDatalist = document.getElementById('category-names-list');

    if (!categoryInput || !categoryDatalist) {
        console.warn('Category autofill not initialized');
        console.log(categoryInput);
        console.log(categoryDatalist);
        return;
    }

    const fetchCategoryNames = async () => {
      try {
          const response = await fetch('/api/categories/names');

          if (!(response).ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
          }

          const categoryNames = await response.json();

          categoryDatalist.innerHTML = '';

          categoryNames.forEach(name => {
              const option = document.createElement('option');
              option.value = name;
              categoryDatalist.appendChild(option);
          });

          console.log(`Category autofill loaded ${categoryNames.length} names`);

      } catch (error) {
          console.error('Failed to fetch category names for autofill');
      }
    };

    fetchCategoryNames();
});
