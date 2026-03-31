<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gulf Pharmacy Landing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gulf-landing.css') }}">
</head>
<body>
    <main class="page">
        <div class="hero-stack">
            <header class="topbar">
                <div class="container topbar-inner">
                    <a class="brand" href="{{ url('/') }}"><img class="brand-mark" src="{{ asset('images/gulf-landing-logo.png') }}" width="200" height="44" alt="Gulf Pharmacy"></a>
                    <div class="top-icons" aria-label="Search and cart">
                        <button type="button" class="icon-btn" aria-label="Search">⌕</button>
                        <button type="button" class="icon-btn cart-toggle" aria-label="Cart">🛒<span class="cart-count" data-cart-count>0</span></button>
                    </div>
                </div>
            </header>

            <section class="hero" aria-labelledby="hero-heading">
                <div class="container hero-inner">
                    <div class="hero-copy">
                        <h1 id="hero-heading">Recover Safely.<br>Move Freely.</h1>
                        <p>Expert-curated mobility aids and post-surgery care kits, recommended by DHA-licensed pharmacists.</p>
                        <div class="hero-actions">
                            <a href="#catalog" class="btn btn-hero-solid">Shop Mobility</a>
                            <a href="#" class="btn btn-hero-outline hero-chat-link"><img class="hero-chat-logo" src="{{ asset('images/gulf-hero-chat-pharmacist-logo.png') }}" width="22" height="22" alt="" aria-hidden="true"> Chat with a Pharmacist</a>
                        </div>
                    </div>
                    <img class="hero-people" src="{{ asset('images/gulf-landing-hero-subject.png') }}" width="520" height="600" alt="Caregiver helping senior with walker">
                </div>
            </section>
           
        </div>

        <section class="stats" aria-label="Key statistics">
            <div class="container stats-grid">
                <div><strong>1M+</strong><span>Happy Customers</span></div>
                <div><strong>4K+</strong><span>Product Catalog</span></div>
                <div><strong>24/7</strong><span>Pharmacist Support</span></div>
            </div>
        </section>

        <section class="categories" aria-labelledby="cat-heading">
            <div class="container">
                <h2 id="cat-heading">Explore by Category</h2>
                <div class="card-grid">
                    <article class="cat-card">
                        <div class="cat-card-media">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-recovery.png') }}" alt="Post-surgery recovery and mobility in the home">
                        </div>
                        <div class="cat-card-body">
                            <h3>Post-Surgery Recovery &amp; Crutches</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <div class="cat-card-media cat-card-media--wide-photo">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-mobility.png') }}" alt="Everyday wellness and compression supports">
                        </div>
                        <div class="cat-card-body">
                            <h3>Support for Everyday Wellness</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <div class="cat-card-media">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-elder-safety.png') }}" alt="Senior care and bath safety">
                        </div>
                        <div class="cat-card-body">
                            <h3>Senior Protection and bath safety</h3>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="brands" aria-labelledby="brands-heading">
            <div class="container">
                <h2 id="brands-heading">Top Mobility Brands on Gulf Pharmacy</h2>
                <div class="brand-row" role="list">
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-caremax.png') }}" alt="Caremax"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-jmc.png') }}" alt="JMC"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-apex.png') }}" alt="APEX"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-futuro.png') }}" alt="3M Futuro"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-jobri.png') }}" alt="Jobst"></div>
                </div>
                <div class="brand-dots" aria-hidden="true"><span></span><span></span></div>
            </div>
        </section>

        <section class="support" aria-labelledby="support-heading">
            <div class="container support-inner">
                <h2 id="support-heading">Reliable Orthopedic Support</h2>
                <p class="support-tagline">Stability and comfort for everyday movement.</p>
                <div class="support-products-row" role="list" aria-label="Orthopedic product packages">
                    <div class="support-product" role="listitem">
                        <div class="support-product__visual support-product__visual--1" role="img" aria-label="Medicare Finger and Toe Gel Tube Protector"></div>
                    </div>
                    <div class="support-product" role="listitem">
                        <div class="support-product__visual support-product__visual--2" role="img" aria-label="3M Futuro Ankle Performance Comfort Support"></div>
                    </div>
                    <div class="support-product" role="listitem">
                        <div class="support-product__visual support-product__visual--3" role="img" aria-label="Diabetic socks with anti-skid"></div>
                    </div>
                    <div class="support-product" role="listitem">
                        <div class="support-product__visual support-product__visual--4" role="img" aria-label="Variteks rib belt corset"></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="catalog" id="catalog" aria-label="Product catalog">
            <div class="container">
                <div class="catalog-toolbar">
                    <span><strong data-result-count>0</strong> products found</span>
                    <button type="button" class="filter-toggle-btn" id="filter-toggle-btn">Filters</button>
                    <label class="search-wrap">Search:
                        <input type="search" id="product-search" placeholder="Search for products..." autocomplete="off">
                    </label>
                    <label>Sort by:
                        <select id="sort-select">
                            <option>Most Popular</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                        </select>
                    </label>
                </div>
                <div class="catalog-grid">
                    <aside class="filters" id="filters-panel">
                        <h4>Brands</h4>
                        <label><input type="checkbox" data-filter-group="brand" value="futuro"> Futuro</label>
                        <label><input type="checkbox" data-filter-group="brand" value="caremax"> Caremax</label>
                        <label><input type="checkbox" data-filter-group="brand" value="nexcare"> Nexcare</label>
                        <h4>Categories</h4>
                        <label><input type="checkbox" data-filter-group="category" value="recovery"> Post-Surgery &amp; Recovery</label>
                        <label><input type="checkbox" data-filter-group="category" value="mobility"> Everyday Mobility</label>
                        <label><input type="checkbox" data-filter-group="category" value="safety"> Fall Protection &amp; Bathroom Safety</label>
                        <h4>Price Range</h4>
                        <label><input type="checkbox" data-filter-group="price" value="under50"> Under 50 AED</label>
                        <label><input type="checkbox" data-filter-group="price" value="50to100"> 50 - 100 AED</label>
                        <label><input type="checkbox" data-filter-group="price" value="100to200"> 100 - 200 AED</label>
                        <h4>Customer Rating</h4>
                        <label><input type="checkbox" data-filter-group="rating" value="4"> 4 Stars &amp; Up</label>
                        <label><input type="checkbox" data-filter-group="rating" value="3"> 3 Stars &amp; Up</label>
                    </aside>
                    <div class="products" id="products-grid">
                        <article class="product-card" data-name="Futuro Comfort Lift Knee Support Medium" data-brand="futuro" data-category="recovery" data-rating="5" data-price="58" data-popularity="124">
                            <div class="pimg p1"></div>
                            <div class="stars">★★★★★<span>(124)</span></div>
                            <h5>Futuro Comfort Lift Knee Support – Medium</h5>
                            <p class="desc">Adjustable support for everyday stability and comfortable movement.</p>
                            <div class="product-footer">
                                <div class="price">58<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="Nitrile Large Powder-Free Gloves 100 Pack" data-brand="caremax" data-category="mobility" data-rating="5" data-price="52" data-popularity="89">
                            <div class="pimg p2"></div>
                            <div class="stars">★★★★★<span>(89)</span></div>
                            <h5>Nitrile Large Powder-Free Gloves — 100 Pack</h5>
                            <p class="desc">Powder-free exam gloves for safe handling and hygiene.</p>
                            <div class="product-footer">
                                <div class="price">52<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="3M Nexcare Active Bandages 30 Pack Blue" data-brand="nexcare" data-category="safety" data-rating="4" data-price="31" data-popularity="42">
                            <div class="pimg p3"></div>
                            <div class="stars">★★★★☆<span>(42)</span></div>
                            <h5>3M Nexcare Active Bandages — 30 Pack — Blue</h5>
                            <p class="desc">Flexible bandages that stay on through daily activity.</p>
                            <div class="product-footer">
                                <div class="price">31<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="Futuro Comfort Lift Knee Support Large" data-brand="futuro" data-category="recovery" data-rating="5" data-price="58" data-popularity="112">
                            <div class="pimg p1"></div>
                            <div class="stars">★★★★★<span>(112)</span></div>
                            <h5>Futuro Comfort Lift Knee Support – Large</h5>
                            <p class="desc">Adjustable support for everyday stability and comfortable movement.</p>
                            <div class="product-footer">
                                <div class="price">58<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="Nitrile Large Powder-Free Gloves 100 Pack Box 2" data-brand="caremax" data-category="mobility" data-rating="5" data-price="52" data-popularity="89">
                            <div class="pimg p2"></div>
                            <div class="stars">★★★★★<span>(89)</span></div>
                            <h5>Nitrile Large Powder-Free Gloves — 100 Pack</h5>
                            <p class="desc">Powder-free exam gloves for safe handling and hygiene.</p>
                            <div class="product-footer">
                                <div class="price">52<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="3M Nexcare Active Bandages 30 Pack Blue Box 2" data-brand="nexcare" data-category="safety" data-rating="4" data-price="31" data-popularity="38">
                            <div class="pimg p3"></div>
                            <div class="stars">★★★★☆<span>(38)</span></div>
                            <h5>3M Nexcare Active Bandages — 30 Pack — Blue</h5>
                            <p class="desc">Flexible bandages that stay on through daily activity.</p>
                            <div class="product-footer">
                                <div class="price">31<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="Futuro Comfort Lift Knee Support Small" data-brand="futuro" data-category="recovery" data-rating="5" data-price="58" data-popularity="98">
                            <div class="pimg p1"></div>
                            <div class="stars">★★★★★<span>(98)</span></div>
                            <h5>Futuro Comfort Lift Knee Support – Small</h5>
                            <p class="desc">Adjustable support for everyday stability and comfortable movement.</p>
                            <div class="product-footer">
                                <div class="price">58<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="Nitrile Large Powder-Free Gloves 100 Pack Box 3" data-brand="caremax" data-category="mobility" data-rating="5" data-price="52" data-popularity="76">
                            <div class="pimg p2"></div>
                            <div class="stars">★★★★★<span>(76)</span></div>
                            <h5>Nitrile Large Powder-Free Gloves — 100 Pack</h5>
                            <p class="desc">Powder-free exam gloves for safe handling and hygiene.</p>
                            <div class="product-footer">
                                <div class="price">52<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                        <article class="product-card" data-name="3M Nexcare Active Bandages 30 Pack Blue Box 3" data-brand="nexcare" data-category="safety" data-rating="4" data-price="31" data-popularity="41">
                            <div class="pimg p3"></div>
                            <div class="stars">★★★★☆<span>(41)</span></div>
                            <h5>3M Nexcare Active Bandages — 30 Pack — Blue</h5>
                            <p class="desc">Flexible bandages that stay on through daily activity.</p>
                            <div class="product-footer">
                                <div class="price">31<small class="price-dec">.00</small><small>AED</small></div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">🛒</button>
                        </article>
                    </div>
                </div>
                <p class="no-results is-hidden" id="no-results">No products match the selected filters.</p>
            </div>
        </section>

        <section class="promo" aria-labelledby="promo-heading">
            <div class="container promo-copy">
                <h2 id="promo-heading">Mobility &amp; Recovery Essentials</h2>
                <p>Expert-curated mobility aids and post-surgery care kits, designed for safe, comfortable recovery at home.</p>
                <a href="#catalog" class="btn btn-ghost promo-catalog-btn">Browse Care Kits</a>
            </div>
        </section>

        <section class="newsletter" aria-label="Newsletter and apps">
            <div class="container news-inner">
                <div>
                    <h3>Be the first to know</h3>
                    <p class="sub">Get newsletters and exclusive offers</p>
                </div>
                <form class="subscribe-form" action="#" method="post">
                    <input type="email" name="email" placeholder="Email" autocomplete="email">
                    <button type="button">Subscribe</button>
                </form>
                <div class="app-links">
                    <span>Download our app</span>
                    <div class="store-badges">
                        <a href="#" aria-label="Google Play">Google Play</a>
                        <a href="#" aria-label="App Store">App Store</a>
                    </div>
                </div>
            </div>
            <div class="features-bar">
                <div class="container features-grid">
                    <div class="feature-item"><strong>Free Shipping</strong><span>For orders over 200 AED</span></div>
                    <div class="feature-item"><strong>24/7 Support</strong><span>Expert assistance</span></div>
                    <div class="feature-item"><strong>Secure Payments</strong><span>100% protected</span></div>
                    <div class="feature-item"><strong>Easy Returns</strong><span>Within 30 days</span></div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container footer-inner">
                <span>© {{ date('Y') }} Gulf Pharmacy, All rights reserved.</span>
                <span><a href="#">Shipping Policy</a> | <a href="#">Privacy Policy</a> | <a href="#">Terms &amp; Conditions</a> | <a href="#">Return &amp; Refund Policy</a></span>
            </div>
        </footer>

    </main>
    <div class="product-modal is-hidden" id="product-modal" aria-hidden="true">
        <div class="product-modal__backdrop" data-close-modal></div>
        <div class="product-modal__card" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <button class="product-modal__close" data-close-modal type="button" aria-label="Close">×</button>
            <h3 id="modal-title">Product</h3>
            <p id="modal-desc"></p>
            <p><strong id="modal-price"></strong></p>
        </div>
    </div>
    <script src="{{ asset('js/gulf-landing.js') }}"></script>
</body>
</html>
