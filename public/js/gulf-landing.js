(function () {
    const grid = document.getElementById('products-grid');
    const catGrid = document.getElementById('category-grid');
    const searchInput = document.getElementById('product-search');
    const boxes = Array.from(document.querySelectorAll('input[data-filter-group]'));
    const countEl = document.querySelector('[data-result-count]');
    const sortSelect = document.getElementById('sort-select');
    const toggleBtn = document.getElementById('filter-toggle-btn');
    const filtersPanel = document.getElementById('filters-panel');

    let allProducts = [];
    let allCategories = [];

    async function init() {
        try {
            const response = await fetch('data/products.json');
            const data = await response.json();
            
            if (Array.isArray(data)) {
                allProducts = data;
            } else {
                allProducts = data.products || [];
                allCategories = data.categories || [];
            }

            renderCategories();
            render();
            sortProducts();
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }

    function renderCategories() {
        if (!catGrid || !allCategories.length) return;
        catGrid.innerHTML = '';
        allCategories.forEach(c => {
            const card = document.createElement('article');
            card.className = 'cat-card';
            card.innerHTML = `
                <a class="cat-card-hit" href="#catalog" aria-label="${c.aria || ''}"></a>
                <div class="cat-card-media">
                    <img class="cat-card-img" src="${c.image}" alt="">
                </div>
                <div class="cat-card-body"><h3>${c.title}</h3></div>
            `;
            
            // Link to main catalog filter logic
            const hit = card.querySelector('.cat-card-hit');
            hit.onclick = (e) => {
                e.preventDefault();
                
                // 1. Uncheck all other category filters
                const catCheckboxes = boxes.filter(b => b.dataset.filterGroup === 'category');
                catCheckboxes.forEach(cb => cb.checked = false);
                
                // 2. Check the one matching this category ID
                const targetBox = catCheckboxes.find(cb => cb.value === c.id);
                if (targetBox) targetBox.checked = true;
                
                // 3. Scroll to catalog
                document.getElementById('catalog').scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // 4. Re-render the grid
                render();
            };

            catGrid.appendChild(card);
        });
    }

    function getPriceGroup(price) {
        if (price < 50) return 'under50';
        if (price < 100) return '50to100';
        if (price < 200) return '100to200';
        return 'over200';
    }

    function render() {
        const search = (searchInput.value || "").toLowerCase();
        const selectedBrands = boxes.filter(b => b.dataset.filterGroup === 'brand' && b.checked).map(b => b.value);
        const selectedCats = boxes.filter(b => b.dataset.filterGroup === 'category' && b.checked).map(b => b.value);
        const selectedPrices = boxes.filter(b => b.dataset.filterGroup === 'price' && b.checked).map(b => b.value);
        const selectedRatings = boxes.filter(b => b.dataset.filterGroup === 'rating' && b.checked).map(b => parseInt(b.value));
        const minRating = selectedRatings.length ? Math.min(...selectedRatings) : 0;

        grid.innerHTML = '';
        let visibleCount = 0;

        allProducts.forEach(p => {
            const searchOk = !search || p.search.includes(search);
            const brandOk = !selectedBrands.length || selectedBrands.includes(p.brand);
            
            // Check mkt_category since we mapped it to our new sidebar filters
            const catOk = !selectedCats.length || selectedCats.includes(p.mkt_category);
            
            const priceOk = !selectedPrices.length || selectedPrices.includes(getPriceGroup(p.price));
            const ratingOk = p.rating >= minRating;

            if (searchOk && brandOk && catOk && priceOk && ratingOk) {
                const card = createProductCard(p);
                grid.appendChild(card);
                visibleCount++;
            }
        });

        if (countEl) countEl.textContent = visibleCount;
        const noResults = document.getElementById('no-results');
        if (noResults) noResults.classList.toggle('is-hidden', visibleCount > 0);
    }

    function createProductCard(p) {
        const card = document.createElement('article');
        card.className = 'product-card';
        card.onclick = () => p.detail_url && window.location.assign(p.detail_url);

        const whole = Math.floor(p.price);
        const dec = (p.price % 1).toFixed(2).substring(2);

        let starsHtml = '';
        for (let i = 0; i < 5; i++) {
            starsHtml += `<span style="letter-spacing:1px;">${i < p.rating ? '&#9733;' : '&#9734;'}</span>`;
        }

        card.innerHTML = `
            <div class="pimg" style="background-image:url('${p.image}')"></div>
            <div style="color:#e6a800; font-size:12px; margin-bottom:8px;">
                ${starsHtml}
                <span style="color:#647481; margin-left:4px;">(42)</span>
            </div>
            <h5>${p.title}</h5>
            <p class="desc">${p.desc}</p>
            <div class="product-footer">
                <div class="price">${whole}<small>.${dec} AED</small></div>
                <button type="button" class="cart-fab"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg></button>
            </div>
        `;

        const cartFab = card.querySelector('.cart-fab');
        if (cartFab) {
            cartFab.onclick = (e) => {
                e.stopPropagation();
                if (p.detail_url) {
                    window.location.assign(p.detail_url);
                }
            };
        }

        return card;
    }

    function sortProducts() {
        const val = sortSelect.value;
        allProducts.sort((a, b) => {
            if (val === 'pop') return b.pop - a.pop;
            if (val === 'lh') return a.price - b.price;
            if (val === 'hl') return b.price - a.price;
            return 0;
        });
        render();
    }

    // Refresh the boxes list to include the newly possible dynamic filters
    function refreshBindings() {
        const newBoxes = Array.from(document.querySelectorAll('input[data-filter-group]'));
        newBoxes.forEach(b => {
             b.removeEventListener('change', render);
             b.addEventListener('change', render);
        });
    }

    boxes.forEach(b => b.addEventListener('change', render));
    searchInput.addEventListener('input', render);
    sortSelect.addEventListener('change', sortProducts);

    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            const isOpen = filtersPanel.style.display === 'block';
            filtersPanel.style.display = isOpen ? '' : 'block';
            toggleBtn.setAttribute('aria-expanded', !isOpen);
        });
    }

    const trigger = document.getElementById('top-search-trigger');
    if (trigger) {
        trigger.onclick = () => {
            document.getElementById('catalog').scrollIntoView({ behavior: 'smooth' });
            setTimeout(() => searchInput.focus(), 600);
        };
    }

    document.querySelectorAll('[data-filter-accordion]').forEach(group => {
        const trigger = group.querySelector('.filters__trigger');
        if (trigger) {
            trigger.onclick = () => group.classList.toggle('is-expanded');
        }
    });

    init();
})();
