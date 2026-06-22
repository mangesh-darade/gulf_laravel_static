(function (global) {
    function getBase() {
        var path = location.pathname;
        if (path.slice(-1) !== '/') {
            path = path.substring(0, path.lastIndexOf('/') + 1);
        }
        return path;
    }

    var CATALOG_KEY = 'gulf_mobility_catalog';
    var LEADS_PENDING_KEY = 'gulf_mobility_leads_pending';
    var LEADS_SAVED_KEY = 'gulf_mobility_leads_saved';

    function normalizeCatalog(data) {
        if (Array.isArray(data)) {
            return { popup_settings: {}, categories: [], products: data };
        }
        return {
            popup_settings: data.popup_settings || {},
            categories: data.categories || [],
            products: data.products || []
        };
    }

    async function fetchCatalogFromServer() {
        var res = await fetch(getBase() + 'data/products.json');
        return normalizeCatalog(await res.json());
    }

    async function loadCatalog() {
        try {
            var stored = localStorage.getItem(CATALOG_KEY);
            if (stored) {
                return normalizeCatalog(JSON.parse(stored));
            }
        } catch (e) { /* use server */ }
        var catalog = await fetchCatalogFromServer();
        localStorage.setItem(CATALOG_KEY, JSON.stringify(catalog));
        return catalog;
    }

    function saveCatalog(catalog) {
        localStorage.setItem(CATALOG_KEY, JSON.stringify(normalizeCatalog(catalog)));
    }

    async function loadLeads() {
        var leads = [];
        try {
            var stored = localStorage.getItem(LEADS_SAVED_KEY);
            if (stored) {
                var parsed = JSON.parse(stored);
                leads = parsed.leads || (Array.isArray(parsed) ? parsed : []);
            }
        } catch (e) { /* ignore */ }

        if (!leads.length) {
            try {
                var res = await fetch(getBase() + 'data/leads.json');
                var data = await res.json();
                leads = data.leads || [];
            } catch (e) {
                leads = [];
            }
        }

        try {
            var pending = JSON.parse(localStorage.getItem(LEADS_PENDING_KEY) || '[]');
            var ids = new Set(leads.map(function (l) { return l.id; }));
            pending.forEach(function (l) {
                if (!ids.has(l.id)) leads.unshift(l);
            });
        } catch (e) { /* ignore */ }

        saveLeads(leads);
        return leads;
    }

    function saveLeads(leads) {
        localStorage.setItem(LEADS_SAVED_KEY, JSON.stringify({ leads: leads }));
        try {
            var ids = new Set(leads.map(function (l) { return l.id; }));
            var pending = JSON.parse(localStorage.getItem(LEADS_PENDING_KEY) || '[]');
            localStorage.setItem(LEADS_PENDING_KEY, JSON.stringify(
                pending.filter(function (l) { return !ids.has(l.id); })
            ));
        } catch (e) { /* ignore */ }
    }

    global.MobilityConfig = {
        getBase: getBase,
        dataUrl: function () {
            return getBase() + 'data/products.json';
        },
        leadsUrl: function () {
            return getBase() + 'data/leads.json';
        },
        LEADS_STORAGE_KEY: LEADS_PENDING_KEY,
        loadCatalog: loadCatalog,
        saveCatalog: saveCatalog,
        loadLeads: loadLeads,
        saveLeads: saveLeads,
        normalizeCatalog: normalizeCatalog
    };
})(window);
