<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gulf Pharmacy Landing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/gulf-landing.css') }}">
</head>

<body>
    <main class="page">
        <div class="hero-stack">
            <header class="topbar">
                <div class="container topbar-inner">
                    <a class="brand" href="{{ url('/') }}"><img class="brand-mark"
                            src="{{ asset('images/gulf-landing-logo.png') }}" width="200" height="44"
                            alt="Gulf Pharmacy"></a>
                    <div class="top-icons" aria-label="Search and cart">
                        <button type="button" class="icon-btn" aria-label="Search catalog" data-open-catalog-search>
                            <svg class="icon-btn__svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </button>
                        <a href="#catalog" class="icon-btn" aria-label="View Catalog">
                            <svg class="icon-btn__svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </a>
                    </div>
                </div>
            </header>

            <section class="hero" aria-labelledby="hero-heading">
                <div class="container hero-inner">
                    <div class="hero-copy">
                        <h1 id="hero-heading">Recover Safely.<br>Move Freely.</h1>
                        <p>Expert-curated mobility aids and post-surgery care kits, recommended by DHA-licensed
                            pharmacists.</p>
                        <div class="hero-actions">
                            <a href="#catalog" class="btn btn-hero-solid">Shop Mobility</a>
                            @php $gulfChatUrl = 'https://wa.me/971564185247'; @endphp
                            <a href="{{ $gulfChatUrl }}" class="btn btn-hero-outline hero-chat-link"@if (strpos($gulfChatUrl, 'http') === 0) target="_blank" rel="noopener noreferrer"@endif><img class="hero-chat-logo" src="{{ asset('images/gulf-hero-chat-pharmacist-logo.png') }}" width="22" height="22" alt="" aria-hidden="true"> Chat with a Pharmacist</a>
                        </div>
                    </div>
                    <img class="hero-people" src="{{ asset('images/gulf-landing-hero-subject1.png') }}" width="520"
                        height="600" alt="Caregiver helping senior with walker">
                </div>
                <div class="hero-separator">
                    <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                        <path d="M0,0 C300,120 900,120 1200,0 L1200,0 L0,0 Z" fill="currentColor"></path>
                    </svg>
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
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'home_health_care']).'#catalog' }}" aria-label="Shop post-surgery recovery and crutches in the catalog"></a>
                        <div class="cat-card-media">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-recovery.png') }}"
                                alt="Post-surgery recovery and mobility in the home">
                        </div>
                        <div class="cat-card-body">
                            <h3>Post-Surgery Recovery &amp; Crutches</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'home_health_care']).'#catalog' }}" aria-label="Shop everyday wellness supports in the catalog"></a>
                        <div class="cat-card-media cat-card-media--wide-photo">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-mobility.png') }}"
                                alt="Everyday wellness and compression supports">
                        </div>
                        <div class="cat-card-body">
                            <h3>Support for Everyday Wellness</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'home_health_care']).'#catalog' }}" aria-label="Shop senior protection and bath safety in the catalog"></a>
                        <div class="cat-card-media">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-elder-safety.png') }}"
                                alt="Senior care and bath safety">
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
                @php
                    $mobilityBrands = [
                        ['file' => 'gulf-landing-brand-caremax.png', 'alt' => 'Caremax'],
                        ['file' => 'gulf-landing-brand-jmc.png', 'alt' => 'JMC'],
                        ['file' => 'gulf-landing-brand-apex.png', 'alt' => 'APEX'],
                        ['file' => 'gulf-landing-brand-futuro.png', 'alt' => '3M Futuro'],
                        ['file' => 'gulf-landing-brand-jobri.png', 'alt' => 'Jobst'],
                    ];
                @endphp
                <div class="brand-marquee" role="region" aria-label="Mobility brand partners">
                    <div class="brand-marquee-viewport">
                        <div class="brand-marquee-track">
                            <div class="brand-marquee-group" role="list">
                                @foreach ($mobilityBrands as $b)
                                <div class="brand-logo" role="listitem"><img src="{{ asset('images/'.$b['file']) }}" alt="{{ $b['alt'] }}"></div>
                                @endforeach
                            </div>
                            <!-- <div class="brand-marquee-group brand-marquee-group--clone" aria-hidden="true">
                                @foreach ($mobilityBrands as $b)
                                <div class="brand-logo"><img src="{{ asset('images/'.$b['file']) }}" alt="" loading="lazy" decoding="async"></div>
                                @endforeach
                            </div> -->
                        </div>
                    </div>
                <!-- <div class="brand-row" role="list">
                    <div class="brand-logo" role="listitem"><img
                            src="{{ asset('images/gulf-landing-brand-caremax.png') }}" alt="Caremax"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-jmc.png') }}"
                            alt="JMC"></div>
                    <div class="brand-logo" role="listitem"><img src="{{ asset('images/gulf-landing-brand-apex.png') }}"
                            alt="APEX"></div>
                    <div class="brand-logo" role="listitem"><img
                            src="{{ asset('images/gulf-landing-brand-futuro.png') }}" alt="3M Futuro"></div>
                    <div class="brand-logo" role="listitem"><img
                            src="{{ asset('images/gulf-landing-brand-jobri.png') }}" alt="Jobst"></div>
                </div> -->
            </div>
        </section>

        <section class="support" aria-labelledby="support-heading">
            <img src="{{ asset('images/support-decor-short.png') }}" class="support-decor support-decor--left" alt="">
            <img src="{{ asset('images/support-decor.png') }}" class="support-decor support-decor--right" alt="">
            <div class="container support-inner">
                <h2 id="support-heading">Reliable Orthopedic Support</h2>
                <p class="support-tagline">Stability and comfort for everyday movement.</p>
                <div class="support-visual-wrap">
                    <a href="https://gulfpharmacy.com/c/home-health-care" class="support-visual-link">
                        <img src="{{ asset('images/orthopedic-products.png') }}" alt="Reliable Orthopedic Support" class="support-composite-img">
                    </a>
                </div>
            </div>
        </section>

        @php
            $landingProducts = config('gulf_catalog.landing_products', config('gulf_catalog.products', []));
            $catalogBrandLabels = [
                'beurer' => 'Beurer',
                'caremax' => 'Caremax',
                'dermaplast' => 'Dermaplast',
                'ezycare' => 'Ezy Care',
                'futuro' => 'Futuro',
                'jmc' => 'JMC',
                'jobri' => 'Jobri',
                'medisana' => 'Medisana',
                'meyra' => 'Meyra',
                'pic' => 'PIC Solution',
                'roche' => 'Roche Diagnostics',
                'stax' => 'Flamingo Stax',
                'vantelin' => 'Vantelin',
                'other' => 'Other brands',
            ];
            $catalogCategoryLabels = [
                'home_health_care' => 'Home Health Care',
            ];
            $catalogBrandsUsed = [];
            $catalogCategoriesUsed = [];
            foreach ($landingProducts as $row) {
                $catalogBrandsUsed[$row['brand']] = true;
                $catalogCategoriesUsed[$row['category']] = true;
            }
            $catalogBrandSlugs = array_keys($catalogBrandsUsed);
            sort($catalogBrandSlugs);
            if (($__o = array_search('other', $catalogBrandSlugs, true)) !== false) {
                array_splice($catalogBrandSlugs, $__o, 1);
                $catalogBrandSlugs[] = 'other';
            }
            $catalogCategoryOrder = ['home_health_care'];
            $catalogCategorySlugs = [];
            foreach ($catalogCategoryOrder as $c) {
                if (! empty($catalogCategoriesUsed[$c])) {
                    $catalogCategorySlugs[] = $c;
                }
            }
            foreach (array_keys($catalogCategoriesUsed) as $c) {
                if (! in_array($c, $catalogCategorySlugs, true)) {
                    $catalogCategorySlugs[] = $c;
                }
            }
        @endphp
        <section class="catalog" id="catalog" aria-labelledby="catalog-heading">
            <div class="container">
                <h2 id="catalog-heading" class="catalog-heading">Shop mobility &amp; home health</h2>
                <p class="catalog-lead">Crutches, supports, diagnostics, and recovery essentials — same range as our in-store home health aisle.</p>
                <div class="catalog-grid">
                    <aside class="filters" id="filters-panel" aria-label="Product filters">
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-brands" aria-expanded="true" aria-controls="filters-panel-brands">
                                Brand
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-brands" role="region" aria-labelledby="filters-trigger-brands">
                                @foreach ($catalogBrandSlugs as $brandSlug)
                                <label><input type="checkbox" data-filter-group="brand" value="{{ $brandSlug }}"> {{ $catalogBrandLabels[$brandSlug] ?? ucfirst(str_replace('-', ' ', $brandSlug)) }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-categories" aria-expanded="true" aria-controls="filters-panel-categories">
                                Category
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-categories" role="region" aria-labelledby="filters-trigger-categories">
                                @foreach ($catalogCategorySlugs as $catSlug)
                                <label><input type="checkbox" data-filter-group="category" value="{{ $catSlug }}"@if ($catSlug === 'home_health_care') checked @endif> {{ $catalogCategoryLabels[$catSlug] ?? ucfirst(str_replace('_', ' ', $catSlug)) }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-price" aria-expanded="true" aria-controls="filters-panel-price">
                                Price Range
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-price" role="region" aria-labelledby="filters-trigger-price">
                                <label><input type="checkbox" data-filter-group="price" value="under50"> Under 50 AED</label>
                                <label><input type="checkbox" data-filter-group="price" value="50to100"> 50 - 100 AED</label>
                                <label><input type="checkbox" data-filter-group="price" value="100to200"> 100 - 200 AED</label>
                                <label><input type="checkbox" data-filter-group="price" value="over200"> 200+ AED</label>
                            </div>
                        </div>
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-rating" aria-expanded="true" aria-controls="filters-panel-rating">
                                Customer Rating
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-rating" role="region" aria-labelledby="filters-trigger-rating">
                                <label><input type="checkbox" data-filter-group="rating" value="4"> <span style="letter-spacing: 2px; font-size: 20px;">&#9733;&#9733;&#9733;&#9733;</span>&amp; Up</label>
                                <label><input type="checkbox" data-filter-group="rating" value="3"> <span style="letter-spacing: 2px; font-size: 20px;">&#9733;&#9733;&#9733;</span>&amp; Up</label>
                            </div>
                        </div>
                    </aside>

                    <div class="catalog-content">
                        <div class="catalog-toolbar">
                            <span class="toolbar-results">Showing <span data-result-count>0</span> products</span>
                            <label class="search-wrap">Search:
                                <input type="search" id="product-search" placeholder="Search mobility & home health…" autocomplete="off">
                            </label>
                            <div class="toolbar-actions">
                                <button type="button" class="filter-toggle-btn" id="filter-toggle-btn" aria-expanded="false" aria-controls="filters-panel">Filters</button>
                                <label class="sort-wrap">
                                    <span class="sort-label">Sort by:</span>
                                    <select id="sort-select" class="sort-select-ui">
                                        <option>Most Popular</option>
                                        <option>Price: Low to High</option>
                                        <option>Price: High to Low</option>
                                    </select>
                                </label>
                            </div>
                        </div>

                        <div class="products" id="products-grid">
                            @foreach ($landingProducts as $i => $p)
                            <article class="product-card" data-name="{{ $p['search'] }}" data-brand="{{ $p['brand'] }}" data-category="{{ $p['category'] }}" data-rating="{{ (int) $p['rating'] }}" data-price="{{ $p['price'] }}" data-popularity="{{ $p['pop'] }}" data-whole="{{ $p['whole'] }}" data-dec="{{ $p['dec'] }}" @if (!empty($p['image'])) data-image="{{ e($p['image']) }}" @endif @if (!empty($p['detail_url'])) data-detail-url="{{ e($p['detail_url']) }}" @endif>
                                <div class="pimg{{ !empty($p['image']) ? ' p'.(($i % 3) + 1).' has-custom-img' : ' pimg--no-photo' }}" role="img" @if (!empty($p['image'])) aria-hidden="true" @else aria-label="No product image" @endif @if (!empty($p['image'])) style="background-image:url('{{ e($p['image']) }}')"@endif></div>
                                <div class="stars">{!! (int) $p['rating'] >= 5 ? '&#9733;&#9733;&#9733;&#9733;&#9733;' : '&#9733;&#9733;&#9733;&#9733;&#9734;' !!}<span>({{ $p['pop'] }})</span></div>
                                <h5>{{ $p['title'] }}</h5>
                                <p class="desc">{{ $p['desc'] }}</p>
                                <div class="product-footer">
                                    <div class="price price--rsp">
                                        <span class="price__value">{{ $p['whole'] }}<small class="price-dec">.{{ $p['dec'] }}</small><small>AED</small></span>
                                    </div>
                                    <button type="button" class="cart-fab" aria-label="Add to cart"><img src="{{ asset('images/Shop_Arrow.svg') }}" width="20" height="20" alt="Cart" style="filter: brightness(0) invert(1); opacity: 0.95;"></button>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                <p class="no-results is-hidden" id="no-results">No products match the selected filters.</p>
            </div>
        </section>

        <section class="promo" aria-labelledby="promo-heading">
            <div class="container promo-copy">
                <h2 id="promo-heading">Mobility &amp; Recovery Essentials</h2>
                <p>Expert-curated mobility aids and post-surgery care kits, designed for safe, comfortable recovery at home.</p>
                <a href="{{ url('/').'?'.http_build_query(['cats' => 'home_health_care']).'#catalog' }}" class="btn btn-ghost promo-catalog-btn">Browse Care Kits</a>
            </div>
        </section>

        <section class="newsletter" id="newsletter" aria-label="Newsletter and apps">
            <div class="container news-inner">
                <div class="news-text">
                    <h3>Be the first to know</h3>
                    <p class="sub">Get newsletters and exclusive offers</p>
                </div>
                <form class="subscribe-form" id="subscribe-form" action="{{ url('/') }}#newsletter" method="post" novalidate>
                    <div class="subscribe-form__field">
                        <label class="visually-hidden" for="newsletter-email">Email address</label>
                        <input type="email" id="newsletter-email" name="email" placeholder="Email" autocomplete="email" required>
                    </div>
                    <button type="submit" class="btn-notify">Notify Me &rarr;</button>
                    <p class="subscribe-form__status" id="subscribe-form-status" role="status" aria-live="polite"></p>
                </form>
                <div class="app-links">
                    <span>Download App</span>
                    <div class="store-badges">
                        <a href="#" aria-label="Google Play">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Get it on Google Play" height="38">
                        </a>
                        <a href="#" aria-label="App Store">
                            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="Download on the App Store" height="38">
                        </a>
                    </div>
                </div>
            </div>

            <div class="container"><hr class="newsletter-divider"></div>

            <div class="features-bar">
                <div class="container features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4-4l4 4-4 4"/></svg>
                        </div>
                        <strong>Free Shipping</strong><span>For all orders over AED 50*</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        </div>
                        <strong>24/7 Support</strong><span>Easy returns and refund</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><rect x="8" y="11" width="8" height="6" rx="1"/><path d="M10 11V9a2 2 0 0 1 4 0v2"/></svg>
                        </div>
                        <strong>Secure Payments</strong><span>Powered by Network</span>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9 10l-3 3 3 3"/><path d="M6 13h8a4 4 0 0 0 4-4"/></svg>
                        </div>
                        <strong>Easy Returns</strong><span>Dedicated Support</span>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container footer-inner">
                <span class="footer-copy">&copy; 2025 Gulf Pharmacy, All rights reserved. <a href="#">Shipping Policy</a> | <a href="#">Privacy Policy</a> | <a href="#">Terms &amp; Conditions</a> | <a href="#">Return &amp; Refund Policy</a></span>
            </div>
        </footer>

    </main>
    <script src="{{ asset('js/gulf-landing.js') }}"></script>
</body>

</html>