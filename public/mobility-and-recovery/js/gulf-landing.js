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

    function dataUrl() {
        return window.MobilityConfig ? MobilityConfig.dataUrl() : 'data/products.json';
    }

    function saveLeadLocally(payload) {
        const key = (window.MobilityConfig && MobilityConfig.LEADS_STORAGE_KEY) || 'gulf_mobility_leads_pending';
        let list = [];
        try { list = JSON.parse(localStorage.getItem(key) || '[]'); } catch (e) { list = []; }
        list.unshift({
            id: 'lead_' + Date.now(),
            name: payload.name,
            email: payload.email,
            phone: payload.phone,
            country: payload.country || 'N/A',
            date: new Date().toISOString().replace('T', ' ').slice(0, 19)
        });
        localStorage.setItem(key, JSON.stringify(list));
    }

    async function init() {
        try {
            const data = window.MobilityConfig
                ? await MobilityConfig.loadCatalog()
                : await (await fetch(dataUrl())).json();

            // ── Default popup settings (fallback if JSON has nothing) ──
            var popupSettings = {
                title:        'Claim Your Free Wellness Consultation & 10% Off!',
                subtitle:     'Subscribe to Gulf Pharmacy Wellness Club today and enjoy exclusive member offers, same-day delivery updates & professional advice.',
                redirect_url: '',
                button_text:  'Join Now'
            };

            if (Array.isArray(data)) {
                allProducts = data;
            } else {
                allProducts    = data.products    || [];
                allCategories  = data.categories  || [];
                // ── Merge popup_settings from JSON ──
                if (data.popup_settings) {
                    if (data.popup_settings.title)        popupSettings.title        = data.popup_settings.title;
                    if (data.popup_settings.subtitle)     popupSettings.subtitle     = data.popup_settings.subtitle;
                    if (data.popup_settings.redirect_url) popupSettings.redirect_url = data.popup_settings.redirect_url;
                    if (data.popup_settings.button_text)  popupSettings.button_text  = data.popup_settings.button_text;
                }
            }

            renderCategories();
            render();
            sortProducts();
            setupLeadPopup(popupSettings);  // called after JSON loads so settings are ready
        } catch (error) {
            console.error('Error fetching products:', error);
            setupLeadPopup(null); // still show popup with defaults on error
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

            const hit = card.querySelector('.cat-card-hit');
            hit.onclick = (e) => {
                e.preventDefault();
                const catCheckboxes = boxes.filter(b => b.dataset.filterGroup === 'category');
                catCheckboxes.forEach(cb => cb.checked = false);
                const targetBox = catCheckboxes.find(cb => cb.value === c.id);
                if (targetBox) targetBox.checked = true;
                document.getElementById('catalog').scrollIntoView({ behavior: 'smooth', block: 'start' });
                render();
            };

            catGrid.appendChild(card);
        });
    }

    function getPriceGroup(price) {
        if (price < 50)  return 'under50';
        if (price < 100) return '50to100';
        if (price < 200) return '100to200';
        return 'over200';
    }

    function render() {
        const search = (searchInput.value || "").toLowerCase();
        const selectedBrands  = boxes.filter(b => b.dataset.filterGroup === 'brand'    && b.checked).map(b => b.value);
        const selectedCats    = boxes.filter(b => b.dataset.filterGroup === 'category' && b.checked).map(b => b.value);
        const selectedPrices  = boxes.filter(b => b.dataset.filterGroup === 'price'    && b.checked).map(b => b.value);
        const selectedRatings = boxes.filter(b => b.dataset.filterGroup === 'rating'   && b.checked).map(b => parseInt(b.value));
        const minRating = selectedRatings.length ? Math.min(...selectedRatings) : 0;

        grid.innerHTML = '';
        let visibleCount = 0;

        allProducts.forEach(p => {
            const searchOk = !search || p.search.includes(search);
            const brandOk  = !selectedBrands.length  || selectedBrands.includes(p.brand);
            const catOk    = !selectedCats.length    || selectedCats.includes(p.mkt_category);
            const priceOk  = !selectedPrices.length  || selectedPrices.includes(getPriceGroup(p.price));
            const ratingOk = p.rating >= minRating;

            if (p.active !== false && searchOk && brandOk && catOk && priceOk && ratingOk) {
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
        const dec   = (p.price % 1).toFixed(2).substring(2);

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
                if (p.detail_url) window.location.assign(p.detail_url);
            };
        }

        return card;
    }

    function sortProducts() {
        const val = sortSelect.value;
        allProducts.sort((a, b) => {
            if (val === 'pop') return b.pop - a.pop;
            if (val === 'lh')  return a.price - b.price;
            if (val === 'hl')  return b.price - a.price;
            return 0;
        });
        render();
    }

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
        const t = group.querySelector('.filters__trigger');
        if (t) t.onclick = () => group.classList.toggle('is-expanded');
    });

    // ── Lead Generator Popup ────────────────────────────────────────────────────
    // settings object: { title, subtitle, redirect_url }
    // All fields are read from products.json > popup_settings
    // Change them there — no code editing needed!
    function setupLeadPopup(settings) {
        if (localStorage.getItem('gp_lead_popup_done')) return;

        // Merge with defaults
        var cfg = {
            title:        'Claim Your Free Wellness Consultation & 10% Off!',
            subtitle:     'Subscribe to Gulf Pharmacy Wellness Club today and enjoy exclusive member offers, same-day delivery updates & professional advice.',
            redirect_url: '',
            button_text:  'Join Now'
        };
        if (settings) {
            if (settings.title)        cfg.title        = settings.title;
            if (settings.subtitle)     cfg.subtitle     = settings.subtitle;
            if (settings.redirect_url) cfg.redirect_url = settings.redirect_url;
            if (settings.button_text)  cfg.button_text  = settings.button_text;
        }

        // Safe HTML escaping for title/subtitle
        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        // Build popup overlay
        const overlay = document.createElement('div');
        overlay.className = 'gp-popup-overlay';
        overlay.id = 'gp-lead-popup';
        overlay.innerHTML = `
            <div class="gp-popup-box">
                <button type="button" class="gp-popup-close" id="gp-close-popup" aria-label="Close">&times;</button>
                <h2 class="gp-popup-title">${escHtml(cfg.title)}</h2>
                <p class="gp-popup-subtitle">${escHtml(cfg.subtitle)}</p>
                <form id="gp-popup-form" class="gp-popup-form">
                    <input type="text"  id="gp-lead-name"  class="gp-popup-input" placeholder="First name" required>
                    <input type="email" id="gp-lead-email" class="gp-popup-input" placeholder="Email" required>
                    <div class="gp-popup-phone-row">
                        <select id="gp-lead-country" class="gp-popup-country-select" style="pointer-events: none; -webkit-appearance: none; -moz-appearance: none; appearance: none; padding-right: 6px; text-align: center;">
                            <option value="AE" data-code="+971" selected>🇦🇪</option>
                        </select>
                        <div style="position: relative; flex: 1;">
                            <span id="gp-popup-code-prefix" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 14px; font-weight: 600; color: var(--gp-text);">+971</span>
                            <input type="tel" id="gp-lead-phone" class="gp-popup-input" style="padding-left: 58px;" placeholder="50 123 4567" required>
                        </div>
                    </div>
                    <button type="submit" class="gp-popup-submit" id="gp-lead-submit">${escHtml(cfg.button_text)}</button>
                </form>
                <p class="gp-popup-privacy">By signing up, you agree to receive our best offers before anyone else. We don't spam. We hate spam just as you do.</p>
            </div>
        `;
        document.body.appendChild(overlay);

        const closeBtn  = document.getElementById('gp-close-popup');
        const form      = document.getElementById('gp-popup-form');
        const select    = document.getElementById('gp-lead-country');
        const prefix    = document.getElementById('gp-popup-code-prefix');
        const phone     = document.getElementById('gp-lead-phone');
        const submitBtn = document.getElementById('gp-lead-submit');

        // Close popup
        function closePopup() {
            overlay.classList.remove('show');
            localStorage.setItem('gp_lead_popup_done', 'true');
            setTimeout(() => { overlay.style.display = 'none'; overlay.remove(); }, 300);
        }

        closeBtn.addEventListener('click', closePopup);
        overlay.addEventListener('click', (e) => { if (e.target === overlay) closePopup(); });

        // Form submit → save lead → redirect or thank-you
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Normalize and Validate UAE phone number
            let phoneVal = phone.value.trim().replace(/\D/g, ''); // keep only digits
            if (phoneVal.startsWith('0')) {
                phoneVal = phoneVal.substring(1);
            }
            
            const uaeMobileRegex = /^(50|52|54|55|56|58|59)\d{7}$/;
            if (!uaeMobileRegex.test(phoneVal)) {
                alert('Please enter a valid UAE mobile number (9 digits starting with 5, e.g., 501234567).');
                phone.focus();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Joining\u2026';

            const fullPhone = '+971 ' + phoneVal;

            const payload = {
                name:    document.getElementById('gp-lead-name').value.trim(),
                email:   document.getElementById('gp-lead-email').value.trim(),
                phone:   fullPhone,
                country: 'United Arab Emirates'
            };

            try {
                saveLeadLocally(payload);
                localStorage.setItem('gp_lead_popup_done', 'true');

                if (cfg.redirect_url) {
                    window.location.assign(cfg.redirect_url);
                } else {
                    const box = overlay.querySelector('.gp-popup-box');
                    box.innerHTML = `
                        <h2 class="gp-popup-title" style="color:var(--gp-teal); margin-bottom:12px;">Welcome to the Club! 🎉</h2>
                        <p class="gp-popup-subtitle" style="margin-bottom:0;">Thank you for subscribing. We have registered your details and your welcome discount will be sent shortly.</p>
                    `;
                    setTimeout(() => {
                        overlay.classList.remove('show');
                        setTimeout(() => { overlay.style.display = 'none'; overlay.remove(); }, 300);
                    }, 3500);
                }

            } catch (err) {
                alert(err.message || 'Failed to submit. Please try again.');
                submitBtn.disabled = false;
                submitBtn.textContent = cfg.button_text;
            }
        });

        // ── Scroll trigger: show popup when user scrolls 30% ──
        window.addEventListener('scroll', function scrollTrigger() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            if (docHeight <= 0) return;
            const pct = (scrollTop / docHeight) * 100;
            if (pct >= 30) {
                overlay.style.display = 'flex';
                setTimeout(() => overlay.classList.add('show'), 10);
                window.removeEventListener('scroll', scrollTrigger);
            }
        });
    }

    // setupLeadPopup is called inside init() once products.json is loaded
    init();
})();
