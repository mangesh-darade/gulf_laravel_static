(function () {
  var productsGrid = document.getElementById("products-grid");
  if (!productsGrid) return;

  var cards = Array.prototype.slice.call(productsGrid.querySelectorAll(".product-card"));
  var checkboxes = Array.prototype.slice.call(document.querySelectorAll("input[data-filter-group]"));
  checkboxes.forEach(function (box) { box.checked = false; });
  var searchInput = document.getElementById("product-search");
  var sortSelect = document.getElementById("sort-select");
  var resultCount = document.querySelector("[data-result-count]");
  var noResults = document.getElementById("no-results");
  var filtersPanel = document.getElementById("filters-panel");
  var filterToggleBtn = document.getElementById("filter-toggle-btn");
  var cartCountEls = Array.prototype.slice.call(document.querySelectorAll("[data-cart-count]"));
  var modal = document.getElementById("product-modal");
  var modalTitle = document.getElementById("modal-title");
  var modalDesc = document.getElementById("modal-desc");
  var modalPrice = document.getElementById("modal-price");
  var closeModalEls = Array.prototype.slice.call(document.querySelectorAll("[data-close-modal]"));

  var cartCount = 0;

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

  function render() {
    var visible = cards.filter(matchesFilters);
    var sorted = sortCards(visible);

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
    modalTitle.textContent = title ? title.textContent : "Product";
    modalDesc.textContent = desc ? desc.textContent : "";
    modalPrice.textContent = "AED " + (card.dataset.price || "0");
    modal.classList.remove("is-hidden");
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }

  function closeModal() {
    if (!modal) return;
    modal.classList.add("is-hidden");
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }

  checkboxes.forEach(function (box) { box.addEventListener("change", render); });
  if (searchInput) searchInput.addEventListener("input", render);
  if (sortSelect) sortSelect.addEventListener("change", render);
  if (filterToggleBtn && filtersPanel) {
    filterToggleBtn.addEventListener("click", function () {
      filtersPanel.classList.toggle("is-open");
    });
  }
  checkboxes.forEach(function (box) {
    box.addEventListener("change", function () {
      if (window.innerWidth <= 900 && filtersPanel) {
        filtersPanel.classList.remove("is-open");
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

  closeModalEls.forEach(function (el) { el.addEventListener("click", closeModal); });
  document.addEventListener("click", function (e) {
    if (!filtersPanel || !filterToggleBtn) return;
    if (window.innerWidth > 900) return;
    var insidePanel = filtersPanel.contains(e.target);
    var isToggle = filterToggleBtn.contains(e.target);
    if (!insidePanel && !isToggle) {
      filtersPanel.classList.remove("is-open");
    }
  });
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") closeModal();
  });

  updateCartCount();
  render();
})();
