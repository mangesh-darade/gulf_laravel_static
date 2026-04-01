(function () {
  function initCatalog() {
  var productsGrid = document.getElementById("products-grid");
  if (!productsGrid) return;

  var cards = Array.prototype.slice.call(productsGrid.querySelectorAll(".product-card"));
  var checkboxes = Array.prototype.slice.call(document.querySelectorAll("input[data-filter-group]"));
  checkboxes.forEach(function (box) {
    box.checked = false;
  });
  applyCatalogQueryFilters();
  var searchInput = document.getElementById("product-search");
  var sortSelect = document.getElementById("sort-select");
  var resultCount = document.querySelector("[data-result-count]");
  var noResults = document.getElementById("no-results");
  var filtersPanel = document.getElementById("filters-panel");
  var filterToggleBtn = document.getElementById("filter-toggle-btn");

  function syncFiltersDrawer() {
    if (!filterToggleBtn || !filtersPanel) return;
    var open = filtersPanel.classList.contains("is-open");
    filterToggleBtn.setAttribute("aria-expanded", open ? "true" : "false");
  }
  var cartCountEls = Array.prototype.slice.call(document.querySelectorAll("[data-cart-count]"));
  var modal = document.getElementById("product-modal");
  var modalTitle = document.getElementById("modal-title");
  var modalDesc = document.getElementById("modal-desc");
  var modalStars = document.getElementById("modal-stars");
  var modalImg = document.getElementById("modal-img");
  var modalImgPlaceholder = document.getElementById("modal-img-placeholder");
  var modalPriceWrap = document.getElementById("modal-price-wrap");
  var modalAddCart = document.getElementById("modal-add-cart");
  var closeModalEls = Array.prototype.slice.call(document.querySelectorAll("[data-close-modal]"));

  var cartCount = 0;
  var modalPreviousFocus = null;

  function applyCatalogQueryFilters() {
    try {
      var params = new URLSearchParams(window.location.search);
      var raw = params.get("cats");
      if (!raw) return;
      raw.split(",").forEach(function (slug) {
        slug = (slug || "").trim();
        if (!slug || !/^[a-z0-9_]+$/i.test(slug)) return;
        var box = document.querySelector(
          'input[data-filter-group="category"][value="' + slug + '"]'
        );
        if (box) box.checked = true;
      });
    } catch (e) {}
  }

  function scrollToCatalogIfNeeded() {
    if (window.location.hash !== "#catalog") return;
    var el = document.getElementById("catalog");
    if (!el) return;
    window.requestAnimationFrame(function () {
      el.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }

  function activeValues(group) {
    return checkboxes
      .filter(function (box) { return box.dataset.filterGroup === group && box.checked; })
      .map(function (box) { return box.value; });
  }

  function priceBucket(price) {
    if (price < 50) return "under50";
    if (price <= 100) return "50to100";
    if (price <= 200) return "100to200";
    return "over200";
  }

  function matchesFilters(card) {
    var name = (card.dataset.name || "").toLowerCase();
    var brand = card.dataset.brand;
    var category = card.dataset.category;
    var price = parseFloat(card.dataset.price || "0");
    var rating = parseFloat(card.dataset.rating || "0");

    var selectedBrand = activeValues("brand");
    var selectedCategory = activeValues("category");
    var selectedPrice = activeValues("price");
    var selectedRating = activeValues("rating");
    var search = (searchInput && searchInput.value || "").trim().toLowerCase();

    var brandOk = !selectedBrand.length || selectedBrand.indexOf(brand) !== -1;
    var categoryOk = !selectedCategory.length || selectedCategory.indexOf(category) !== -1;
    var priceOk = !selectedPrice.length || selectedPrice.indexOf(priceBucket(price)) !== -1;
    var ratingOk = !selectedRating.length || selectedRating.some(function (r) {
      return rating >= parseFloat(r);
    });
    var searchOk = !search || name.indexOf(search) !== -1;

    return brandOk && categoryOk && priceOk && ratingOk && searchOk;
  }

  function sortCards(cardsToSort) {
    var mode = sortSelect ? sortSelect.value : "Most Popular";
    var sorted = cardsToSort.slice();
    sorted.sort(function (a, b) {
      var aPrice = parseFloat(a.dataset.price || "0");
      var bPrice = parseFloat(b.dataset.price || "0");
      var aPop = parseFloat(a.dataset.popularity || "0");
      var bPop = parseFloat(b.dataset.popularity || "0");
      if (mode === "Price: Low to High") return aPrice - bPrice;
      if (mode === "Price: High to Low") return bPrice - aPrice;
      return bPop - aPop;
    });
    return sorted;
  }

  function updateURL() {
    try {
      var selectedCats = activeValues("category");
      var url = new URL(window.location);
      if (selectedCats.length > 0) {
        url.searchParams.set("cats", selectedCats.join(","));
      } else {
        url.searchParams.delete("cats");
      }
      window.history.replaceState({}, "", url);
    } catch (e) {}
  }

  function render() {
    var visible = cards.filter(matchesFilters);
    var sorted = sortCards(visible);

    updateURL();

    cards.forEach(function (c) { c.classList.add("is-hidden"); });
    sorted.forEach(function (c) {
      c.classList.remove("is-hidden");
      productsGrid.appendChild(c);
    });

    if (resultCount) resultCount.textContent = String(sorted.length);
    if (noResults) noResults.classList.toggle("is-hidden", sorted.length > 0);
  }

  function updateCartCount() {
    cartCountEls.forEach(function (el) { el.textContent = String(cartCount); });
  }

  function openModalForCard(card) {
    if (!modal) return;
    var title = card.querySelector("h5");
    var desc = card.querySelector(".desc");
    var stars = card.querySelector(".stars");
    modalTitle.textContent = title ? title.textContent : "Product";
    modalDesc.textContent = desc ? desc.textContent : "";
    if (modalStars && stars) {
      modalStars.innerHTML = stars.innerHTML;
    } else if (modalStars) {
      modalStars.innerHTML = "";
    }

    var whole = card.dataset.whole != null ? String(card.dataset.whole) : "0";
    var dec = card.dataset.dec != null ? String(card.dataset.dec) : "00";
    if (modalPriceWrap) {
      modalPriceWrap.innerHTML =
        '<span class="price__value">' +
        whole +
        '<small class="price-dec">.' +
        dec +
        "</small><small>AED</small></span>";
    }

    var imgUrl = (card.dataset.image || "").trim();
    if (modalImg && modalImgPlaceholder) {
      modalImg.onerror = function () {
        modalImg.classList.add("is-hidden");
        modalImg.removeAttribute("src");
        modalImgPlaceholder.classList.remove("is-hidden");
      };
      if (imgUrl) {
        modalImg.alt = title ? title.textContent : "Product";
        modalImg.src = imgUrl;
        modalImg.classList.remove("is-hidden");
        modalImgPlaceholder.classList.add("is-hidden");
      } else {
        modalImg.onload = null;
        modalImg.onerror = null;
        modalImg.removeAttribute("src");
        modalImg.classList.add("is-hidden");
        modalImg.alt = "";
        modalImgPlaceholder.classList.remove("is-hidden");
      }
    }

    modalPreviousFocus = document.activeElement;
    modal.classList.remove("is-hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
    window.requestAnimationFrame(function () {
      if (modalAddCart) modalAddCart.focus();
    });
  }

  function closeModal() {
    if (!modal) return;
    var toFocus = modalPreviousFocus;
    modalPreviousFocus = null;
    modal.classList.add("is-hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
    if (modalImg) {
      modalImg.onload = null;
      modalImg.onerror = null;
      modalImg.removeAttribute("src");
      modalImg.classList.add("is-hidden");
    }
    if (modalImgPlaceholder) modalImgPlaceholder.classList.remove("is-hidden");
    window.requestAnimationFrame(function () {
      if (toFocus && typeof toFocus.focus === "function") {
        try {
          toFocus.focus();
        } catch (err) {}
      }
    });
  }

  var filterAccordions = Array.prototype.slice.call(
    document.querySelectorAll("[data-filter-accordion]")
  );
  function syncFilterAccordion(group) {
    var trig = group.querySelector(".filters__trigger");
    var panel = group.querySelector(".filters__panel");
    if (!trig || !panel) return;
    var open = group.classList.contains("is-expanded");
    trig.setAttribute("aria-expanded", open ? "true" : "false");
    panel.hidden = !open;
  }
  filterAccordions.forEach(function (group) {
    syncFilterAccordion(group);
    var trig = group.querySelector(".filters__trigger");
    if (trig) {
      trig.addEventListener("click", function () {
        group.classList.toggle("is-expanded");
        syncFilterAccordion(group);
      });
    }
  });

  checkboxes.forEach(function (box) { box.addEventListener("change", render); });
  if (searchInput) searchInput.addEventListener("input", render);
  if (sortSelect) sortSelect.addEventListener("change", render);
  if (filterToggleBtn && filtersPanel) {
    filterToggleBtn.addEventListener("click", function () {
      filtersPanel.classList.toggle("is-open");
      syncFiltersDrawer();
    });
  }
  syncFiltersDrawer();
  checkboxes.forEach(function (box) {
    box.addEventListener("change", function () {
      if (window.innerWidth <= 900 && filtersPanel) {
        filtersPanel.classList.remove("is-open");
        syncFiltersDrawer();
      }
    });
  });

  cards.forEach(function (card) {
    var addBtn = card.querySelector(".cart-fab");
    if (addBtn) {
      addBtn.addEventListener("click", function (e) {
        e.stopPropagation();
        cartCount += 1;
        updateCartCount();
      });
    }
    card.addEventListener("click", function (e) {
      if (e.target.classList.contains("cart-fab")) return;
      openModalForCard(card);
    });
  });

  if (modalAddCart) {
    modalAddCart.addEventListener("click", function () {
      cartCount += 1;
      updateCartCount();
    });
  }

  closeModalEls.forEach(function (el) { el.addEventListener("click", closeModal); });
  document.addEventListener("click", function (e) {
    if (!filtersPanel || !filterToggleBtn) return;
    if (window.innerWidth > 900) return;
    var insidePanel = filtersPanel.contains(e.target);
    var isToggle = filterToggleBtn.contains(e.target);
    if (!insidePanel && !isToggle) {
      filtersPanel.classList.remove("is-open");
      syncFiltersDrawer();
    }
  });
  window.addEventListener("resize", function () {
    if (window.innerWidth > 900 && filtersPanel) {
      filtersPanel.classList.remove("is-open");
      syncFiltersDrawer();
    }
  });
  document.addEventListener("keydown", function (e) {
    if (e.key !== "Escape") return;
    if (modal && !modal.classList.contains("is-hidden")) {
      closeModal();
      return;
    }
    if (filtersPanel && filtersPanel.classList.contains("is-open")) {
      filtersPanel.classList.remove("is-open");
      syncFiltersDrawer();
      if (filterToggleBtn) filterToggleBtn.focus();
    }
  });

  updateCartCount();
  render();
  scrollToCatalogIfNeeded();
  }

  function initGlobalUi() {
    var topSearchBtn = document.querySelector("[data-open-catalog-search]");
    if (topSearchBtn) {
      topSearchBtn.addEventListener("click", function () {
        var catalogEl = document.getElementById("catalog");
        var productSearch = document.getElementById("product-search");
        if (catalogEl) {
          catalogEl.scrollIntoView({ behavior: "smooth", block: "start" });
        }
        window.setTimeout(function () {
          if (productSearch) {
            productSearch.focus({ preventScroll: true });
          }
        }, 400);
      });
    }

    var subForm = document.getElementById("subscribe-form");
    var subStatus = document.getElementById("subscribe-form-status");
    if (subForm) {
      subForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var emailInput = document.getElementById("newsletter-email");
        if (!emailInput) return;
        if (!emailInput.value.trim() || !emailInput.checkValidity()) {
          if (subStatus) subStatus.textContent = "Please enter a valid email address.";
          emailInput.focus();
          return;
        }
        if (subStatus) {
          subStatus.textContent = "Thanks — you're on the list. (Demo: no email sent.)";
        }
        emailInput.value = "";
      });
    }
  }

  initCatalog();
  initGlobalUi();
})();
