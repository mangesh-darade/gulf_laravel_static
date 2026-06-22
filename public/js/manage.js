(function () {
    'use strict';

    const ADMIN_PASSWORD = 'gulf2026';
    const SESSION_KEY = 'gulf_admin_logged';

    const loginScreen = document.getElementById('login-screen');
    const dashboardScreen = document.getElementById('dashboard-screen');
    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const pgrid = document.getElementById('pgrid');
    const leadsTbody = document.getElementById('leads-tbody');
    const toastArea = document.getElementById('toast-area');
    const modal = document.getElementById('add-modal');
    const addForm = document.getElementById('add-form');
    const modalErr = document.getElementById('modal-error');
    const addSubmit = document.getElementById('add-submit');
    const imgPreview = document.getElementById('img-preview');
    const addActiveEl = document.getElementById('add-active');
    const addActiveLbl = document.getElementById('add-active-label');
    const searchEl = document.getElementById('search');
    const pills = document.querySelectorAll('.pill');

    let activeFilter = 'all';
    let catalog = { popup_settings: {}, categories: [], products: [] };
    let leads = [];

    function esc(s) {
        if (s == null) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function toast(msg, type) {
        const t = document.createElement('div');
        t.className = 'toast ' + (type || 'ok');
        t.innerHTML = '<span>' + esc(msg) + '</span>';
        toastArea.appendChild(t);
        setTimeout(function () { t.remove(); }, 3000);
    }

    function persistCatalog() {
        MobilityConfig.saveCatalog(catalog);
    }

    function persistLeads() {
        MobilityConfig.saveLeads(leads);
    }

    function downloadJson(filename, obj) {
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob(
            [JSON.stringify(obj, null, 2)],
            { type: 'application/json' }
        ));
        a.download = filename;
        a.click();
        toast('Downloaded ' + filename);
    }

    function showLogin() {
        loginScreen.classList.remove('hidden');
        dashboardScreen.classList.add('hidden');
    }

    function showDashboard() {
        loginScreen.classList.add('hidden');
        dashboardScreen.classList.remove('hidden');
    }

    function formatLeadDate(dateStr) {
        try {
            return new Date(dateStr.replace(/-/g, '/')).toLocaleString('en-US', {
                month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit'
            });
        } catch (e) {
            return dateStr;
        }
    }

    async function loadDashboard() {
        catalog = await MobilityConfig.loadCatalog();
        leads = await MobilityConfig.loadLeads();
        fillPopup(catalog.popup_settings);
        renderProducts();
        renderLeads();
        showDashboard();
    }

    function buildProduct(payload, oldCode) {
        const title = String(payload.title).trim();
        const code = String(payload.code).trim();
        if (!code || !title) throw new Error('Code and title are required.');
        if (catalog.products.some(function (p) {
            return p.code === code && p.code !== oldCode;
        })) {
            throw new Error('Product code already exists.');
        }
        return {
            code: code,
            title: title,
            price: parseFloat(payload.price),
            brand: String(payload.brand).trim().toLowerCase(),
            category: 'home_health_care',
            mkt_category: String(payload.mkt_category).trim(),
            rating: Math.max(1, Math.min(5, parseInt(payload.rating, 10) || 5)),
            image: String(payload.image || '').trim(),
            desc: String(payload.desc || '').trim(),
            detail_url: String(payload.detail_url).trim(),
            pop: parseInt(payload.pop, 10) || 100,
            active: payload.active !== false,
            search: title.toLowerCase()
        };
    }

    function renderProducts() {
        const products = catalog.products;
        if (!products.length) {
            pgrid.innerHTML = '<div class="empty"><h3>No products yet</h3><p>Click Add Product to get started.</p></div>';
            refreshCounters();
            return;
        }
        pgrid.innerHTML = products.map(function (p) {
            const active = p.active !== false;
            const brand = p.brand ? p.brand.charAt(0).toUpperCase() + p.brand.slice(1) : '';
            return '<div class="pcard' + (active ? '' : ' inactive-card') + '" data-code="' + esc(p.code) + '" data-title="' + esc(p.title) + '" data-price="' + esc(p.price) + '" data-brand="' + esc(p.brand || '') + '" data-mkt_category="' + esc(p.mkt_category || '') + '" data-rating="' + esc(p.rating || 5) + '" data-pop="' + esc(p.pop || 100) + '" data-image="' + esc(p.image || '') + '" data-desc="' + esc(p.desc || '') + '" data-detail_url="' + esc(p.detail_url || '') + '" data-active="' + (active ? 'true' : 'false') + '">' +
                '<div class="pthumb" style="background-image:url(\'' + esc(p.image || '') + '\')"></div>' +
                '<div class="pinfo"><div><h3 class="ptitle">' + esc(p.title) + '</h3><div class="pmeta">Code: ' + esc(p.code) + ' | ' + esc(brand) + '</div><div class="pprice">' + esc(p.price) + ' AED</div></div>' +
                '<div class="pactions"><label class="switch"><input type="checkbox" class="toggle" data-code="' + esc(p.code) + '"' + (active ? ' checked' : '') + '><span class="slider"></span></label>' +
                '<span class="badge ' + (active ? 'badge-on' : 'badge-off') + '">' + (active ? 'Active' : 'Inactive') + '</span>' +
                '<button class="btn btn-danger btn-sm delete-btn" data-code="' + esc(p.code) + '" data-title="' + esc(p.title) + '" type="button" style="margin-left:auto">Delete</button></div></div></div>';
        }).join('');
        bindProductEvents();
        refreshCounters();
        applyFilter();
    }

    function renderLeads() {
        if (!leads.length) {
            leadsTbody.innerHTML = '<tr><td colspan="6" style="padding:50px;text-align:center;color:var(--muted);"><h3>No leads yet</h3><p>Leads from the popup will appear here automatically.</p></td></tr>';
            refreshLeadsCounters();
            return;
        }
        leadsTbody.innerHTML = leads.map(function (l) {
            const searchText = (l.name + ' ' + l.email + ' ' + l.phone + ' ' + (l.country || '')).toLowerCase();
            return '<tr class="lead-row" data-id="' + esc(l.id) + '" data-date-raw="' + esc(l.date) + '" data-search-text="' + esc(searchText) + '">' +
                '<td style="font-weight:600;">' + esc(l.name) + '</td>' +
                '<td><a href="mailto:' + esc(l.email) + '">' + esc(l.email) + '</a></td>' +
                '<td>' + esc(l.phone) + '</td>' +
                '<td>' + esc(l.country || 'N/A') + '</td>' +
                '<td>' + esc(formatLeadDate(l.date)) + '</td>' +
                '<td style="text-align:right;"><button class="btn btn-danger btn-sm delete-lead-btn" data-id="' + esc(l.id) + '" data-name="' + esc(l.name) + '" type="button">Delete</button></td></tr>';
        }).join('');
        bindLeadEvents();
        applyLeadsFilterAndSort();
        refreshLeadsCounters();
    }

    function fillPopup(ps) {
        document.getElementById('ps-title').value = ps.title || '';
        document.getElementById('ps-subtitle').value = ps.subtitle || '';
        document.getElementById('ps-button-text').value = ps.button_text || 'Join Now';
        document.getElementById('ps-redirect').value = ps.redirect_url || '';
    }

    function refreshCounters() {
        const cards = pgrid.querySelectorAll('.pcard');
        let active = 0, inactive = 0;
        cards.forEach(function (c) { c.dataset.active === 'true' ? active++ : inactive++; });
        document.getElementById('cnt-total').textContent = cards.length;
        document.getElementById('cnt-active').textContent = active;
        document.getElementById('cnt-inactive').textContent = inactive;
    }

    function refreshLeadsCounters() {
        const rows = document.querySelectorAll('.lead-row');
        const today = new Date().toISOString().slice(0, 10);
        let todayCount = 0;
        const countries = new Set();
        rows.forEach(function (r) {
            if ((r.dataset.dateRaw || '').indexOf(today) === 0) todayCount++;
            const c = r.cells[3] && r.cells[3].textContent.trim();
            if (c && c !== 'N/A') countries.add(c);
        });
        document.getElementById('cnt-leads-total').textContent = rows.length;
        document.getElementById('cnt-leads-today').textContent = todayCount;
        document.getElementById('cnt-leads-countries').textContent = countries.size;
    }

    function applyFilter() {
        const q = searchEl.value.trim().toLowerCase();
        pgrid.querySelectorAll('.pcard').forEach(function (c) {
            const matchQ = !q || c.dataset.title.toLowerCase().includes(q) || c.dataset.code.includes(q) || c.dataset.brand.toLowerCase().includes(q);
            const isActive = c.dataset.active === 'true';
            const matchF = activeFilter === 'all' || (activeFilter === 'active' && isActive) || (activeFilter === 'inactive' && !isActive);
            c.style.display = (matchQ && matchF) ? 'flex' : 'none';
        });
    }

    function applyLeadsFilterAndSort() {
        const q = (document.getElementById('search-leads').value || '').trim().toLowerCase();
        const targetDate = document.getElementById('filter-lead-date').value;
        const rows = Array.from(leadsTbody.querySelectorAll('.lead-row'));
        rows.forEach(function (r) {
            const show = (!q || r.dataset.searchText.includes(q)) && (!targetDate || (r.dataset.dateRaw || '').indexOf(targetDate) === 0);
            r.style.display = show ? '' : 'none';
        });
        const sortVal = document.getElementById('sort-leads').value;
        rows.sort(function (a, b) {
            if (sortVal === 'date_desc') return new Date(b.dataset.dateRaw) - new Date(a.dataset.dateRaw);
            if (sortVal === 'date_asc') return new Date(a.dataset.dateRaw) - new Date(b.dataset.dateRaw);
            if (sortVal === 'name_asc') return a.cells[0].textContent.localeCompare(b.cells[0].textContent);
            if (sortVal === 'name_desc') return b.cells[0].textContent.localeCompare(a.cells[0].textContent);
            return 0;
        });
        rows.forEach(function (r) { leadsTbody.appendChild(r); });
    }

    function bindProductEvents() {
        pgrid.querySelectorAll('.toggle').forEach(function (toggle) {
            toggle.onchange = function (e) {
                e.stopPropagation();
                const code = toggle.dataset.code;
                const state = toggle.checked;
                const p = catalog.products.find(function (x) { return x.code === code; });
                if (p) p.active = state;
                persistCatalog();
                renderProducts();
                toast(state ? 'Product activated' : 'Product deactivated');
            };
        });
        pgrid.querySelectorAll('.delete-btn').forEach(function (btn) {
            btn.onclick = function (e) {
                e.stopPropagation();
                if (!confirm('Delete "' + btn.dataset.title + '"?')) return;
                catalog.products = catalog.products.filter(function (p) { return p.code !== btn.dataset.code; });
                persistCatalog();
                renderProducts();
                toast('Product deleted');
            };
        });
        pgrid.querySelectorAll('.pcard').forEach(function (card) {
            card.onclick = function (e) {
                if (e.target.closest('.switch, .delete-btn, button, input')) return;
                openEditModal(card);
            };
        });
    }

    function bindLeadEvents() {
        document.querySelectorAll('.delete-lead-btn').forEach(function (btn) {
            btn.onclick = function () {
                if (!confirm('Delete lead from "' + btn.dataset.name + '"?')) return;
                leads = leads.filter(function (l) { return l.id !== btn.dataset.id; });
                persistLeads();
                renderLeads();
                toast('Lead deleted');
            };
        });
    }

    function openEditModal(card) {
        modalErr.classList.add('hidden');
        document.getElementById('modal-title').textContent = 'Edit Product';
        document.getElementById('old-code').value = card.dataset.code;
        addForm.code.value = card.dataset.code;
        addForm.price.value = card.dataset.price;
        addForm.title.value = card.dataset.title;
        addForm.brand.value = card.dataset.brand;
        addForm.mkt_category.value = card.dataset.mkt_category;
        addForm.rating.value = card.dataset.rating;
        addForm.pop.value = card.dataset.pop;
        addForm.image.value = card.dataset.image;
        addForm.desc.value = card.dataset.desc;
        addForm.detail_url.value = card.dataset.detail_url;
        addActiveEl.checked = card.dataset.active === 'true';
        addActiveLbl.textContent = addActiveEl.checked ? 'Active' : 'Inactive';
        if (card.dataset.image) { imgPreview.src = card.dataset.image; imgPreview.classList.add('show'); }
        else imgPreview.classList.remove('show');
        addSubmit.textContent = 'Save Changes';
        modal.classList.add('open');
    }

    function closeModal() { modal.classList.remove('open'); }

    document.getElementById('tab-products').onclick = function () {
        document.getElementById('tab-products').classList.add('active');
        document.getElementById('tab-leads').classList.remove('active');
        document.getElementById('tab-popup').classList.remove('active');
        document.getElementById('products-section').classList.remove('hidden');
        document.getElementById('leads-section').classList.add('hidden');
        document.getElementById('popup-section').classList.add('hidden');
        document.getElementById('open-add-modal').style.display = '';
    };
    document.getElementById('tab-leads').onclick = function () {
        document.getElementById('tab-leads').classList.add('active');
        document.getElementById('tab-products').classList.remove('active');
        document.getElementById('tab-popup').classList.remove('active');
        document.getElementById('products-section').classList.add('hidden');
        document.getElementById('leads-section').classList.remove('hidden');
        document.getElementById('popup-section').classList.add('hidden');
        document.getElementById('open-add-modal').style.display = 'none';
        refreshLeadsCounters();
    };
    document.getElementById('tab-popup').onclick = function () {
        document.getElementById('tab-popup').classList.add('active');
        document.getElementById('tab-products').classList.remove('active');
        document.getElementById('tab-leads').classList.remove('active');
        document.getElementById('products-section').classList.add('hidden');
        document.getElementById('leads-section').classList.add('hidden');
        document.getElementById('popup-section').classList.remove('hidden');
        document.getElementById('open-add-modal').style.display = 'none';
    };

    loginForm.onsubmit = function (e) {
        e.preventDefault();
        loginError.classList.add('hidden');
        if (document.getElementById('login-password').value !== ADMIN_PASSWORD) {
            loginError.textContent = 'Incorrect password.';
            loginError.classList.remove('hidden');
            return;
        }
        sessionStorage.setItem(SESSION_KEY, '1');
        loadDashboard().catch(function (err) {
            loginError.textContent = err.message;
            loginError.classList.remove('hidden');
        });
    };

    document.getElementById('logout-btn').onclick = function () {
        sessionStorage.removeItem(SESSION_KEY);
        showLogin();
    };

    document.getElementById('export-products-json').onclick = function () {
        downloadJson('products.json', catalog);
    };

    document.getElementById('export-leads-json').onclick = function () {
        downloadJson('leads.json', { leads: leads });
    };

    document.getElementById('import-products-json').onchange = function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function () {
            try {
                catalog = MobilityConfig.normalizeCatalog(JSON.parse(reader.result));
                persistCatalog();
                fillPopup(catalog.popup_settings);
                renderProducts();
                toast('Products imported');
            } catch (err) {
                toast('Invalid JSON file', 'err');
            }
        };
        reader.readAsText(file);
        e.target.value = '';
    };

    document.getElementById('open-add-modal').onclick = function () {
        addForm.reset();
        document.getElementById('old-code').value = '';
        document.getElementById('modal-title').textContent = 'Add New Product';
        addActiveEl.checked = true;
        imgPreview.classList.remove('show');
        modalErr.classList.add('hidden');
        addSubmit.textContent = 'Add Product';
        modal.classList.add('open');
    };

    document.getElementById('close-modal').onclick = closeModal;
    modal.onclick = function (e) { if (e.target === modal) closeModal(); };
    document.getElementById('image-url').onblur = function () {
        const url = document.getElementById('image-url').value.trim();
        if (url) { imgPreview.src = url; imgPreview.classList.add('show'); }
        else imgPreview.classList.remove('show');
    };
    addActiveEl.onchange = function () {
        addActiveLbl.textContent = addActiveEl.checked ? 'Active' : 'Inactive';
    };

    searchEl.oninput = applyFilter;
    pills.forEach(function (p) {
        p.onclick = function () {
            pills.forEach(function (x) { x.classList.remove('on'); });
            p.classList.add('on');
            activeFilter = p.dataset.f;
            applyFilter();
        };
    });

    document.getElementById('search-leads').oninput = applyLeadsFilterAndSort;
    document.getElementById('filter-lead-date').onchange = applyLeadsFilterAndSort;
    document.getElementById('clear-lead-date').onclick = function () {
        document.getElementById('filter-lead-date').value = '';
        applyLeadsFilterAndSort();
    };
    document.getElementById('sort-leads').onchange = applyLeadsFilterAndSort;

    document.getElementById('export-leads-csv').onclick = function () {
        const rows = Array.from(document.querySelectorAll('.lead-row')).filter(function (r) { return r.style.display !== 'none'; });
        if (!rows.length) { toast('No leads to export', 'err'); return; }
        let csv = 'Name,Email,Phone,Country,Date\n';
        rows.forEach(function (row) {
            const c = row.querySelectorAll('td');
            csv += '"' + c[0].textContent + '","' + c[1].textContent + '","' + c[2].textContent + '","' + c[3].textContent + '","' + c[4].textContent + '"\n';
        });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
        a.download = 'gulf_leads_' + new Date().toISOString().slice(0, 10) + '.csv';
        a.click();
    };

    document.getElementById('save-popup-btn').onclick = function () {
        const errEl = document.getElementById('popup-settings-error');
        errEl.classList.add('hidden');
        catalog.popup_settings = {
            title: document.getElementById('ps-title').value.trim(),
            subtitle: document.getElementById('ps-subtitle').value.trim(),
            button_text: document.getElementById('ps-button-text').value.trim(),
            redirect_url: document.getElementById('ps-redirect').value.trim()
        };
        if (!catalog.popup_settings.title || !catalog.popup_settings.subtitle) {
            errEl.textContent = 'Title and subtitle are required.';
            errEl.classList.remove('hidden');
            return;
        }
        persistCatalog();
        toast('Popup settings saved');
    };

    addForm.onsubmit = function (e) {
        e.preventDefault();
        modalErr.classList.add('hidden');
        const oldCode = document.getElementById('old-code').value;
        const fd = new FormData(addForm);
        try {
            const product = buildProduct({
                code: fd.get('code'),
                title: fd.get('title'),
                price: fd.get('price'),
                brand: fd.get('brand'),
                mkt_category: fd.get('mkt_category'),
                rating: fd.get('rating'),
                pop: fd.get('pop'),
                image: fd.get('image'),
                desc: fd.get('desc'),
                detail_url: fd.get('detail_url'),
                active: addActiveEl.checked
            }, oldCode);
            if (oldCode) {
                catalog.products = catalog.products.map(function (p) {
                    return p.code === oldCode ? product : p;
                });
            } else {
                catalog.products.push(product);
            }
            persistCatalog();
            renderProducts();
            closeModal();
            toast(oldCode ? 'Product updated' : 'Product added');
        } catch (err) {
            modalErr.textContent = err.message;
            modalErr.classList.remove('hidden');
        }
    };

    if (sessionStorage.getItem(SESSION_KEY) === '1') {
        loadDashboard().catch(showLogin);
    } else {
        showLogin();
    }
})();
