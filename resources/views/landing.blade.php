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
                        <button type="button" class="icon-btn" aria-label="Search catalog" data-open-catalog-search>
                            <svg class="icon-btn__svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        </button>
                        <button type="button" class="icon-btn cart-toggle" aria-label="Cart">
                            <svg class="icon-btn__svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            <span class="cart-count" data-cart-count>0</span>
                        </button>
                    </div>
                </div>
            </header>

            <section class="hero" aria-labelledby="hero-heading">
                <div class="container hero-inner">
                    <div class="hero-copy">
                        <h1 id="hero-heading">Recover Safely.<br>Move Freely.</h1>
                        <p>Expert-curated mobility aids and post-surgery care kits, recommended by DHA-licensed pharmacists.</p>
                        <div class="hero-actions">
                            <a href="{{ url('/').'?'.http_build_query(['cats' => 'mobility,orthopedic']).'#catalog' }}" class="btn btn-hero-solid">Shop Mobility</a>
                            @php $gulfChatUrl = config('gulf_landing.pharmacist_chat_url'); @endphp
                            <a href="{{ $gulfChatUrl }}" class="btn btn-hero-outline hero-chat-link"@if (strpos($gulfChatUrl, 'http') === 0) target="_blank" rel="noopener noreferrer"@endif><img class="hero-chat-logo" src="{{ asset('images/gulf-hero-chat-pharmacist-logo.png') }}" width="22" height="22" alt="" aria-hidden="true"> Chat with a Pharmacist</a>
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
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'mobility,orthopedic,first_aid']).'#catalog' }}" aria-label="Shop post-surgery recovery and crutches in the catalog"></a>
                        <div class="cat-card-media">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-recovery.png') }}" alt="Post-surgery recovery and mobility in the home">
                        </div>
                        <div class="cat-card-body">
                            <h3>Post-Surgery Recovery &amp; Crutches</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'orthopedic,home_care']).'#catalog' }}" aria-label="Shop everyday wellness supports in the catalog"></a>
                        <div class="cat-card-media cat-card-media--wide-photo">
                            <img class="cat-card-img" src="{{ asset('images/gulf-landing-category-mobility.png') }}" alt="Everyday wellness and compression supports">
                        </div>
                        <div class="cat-card-body">
                            <h3>Support for Everyday Wellness</h3>
                        </div>
                    </article>
                    <article class="cat-card">
                        <a class="cat-card-hit" href="{{ url('/').'?'.http_build_query(['cats' => 'home_care']).'#catalog' }}" aria-label="Shop senior protection and bath safety in the catalog"></a>
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
                            <div class="brand-marquee-group brand-marquee-group--clone" aria-hidden="true">
                                @foreach ($mobilityBrands as $b)
                                <div class="brand-logo"><img src="{{ asset('images/'.$b['file']) }}" alt="" loading="lazy" decoding="async"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
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

        @php
            $landingProducts = config('gulf_catalog.landing_products', config('gulf_catalog.products', []));
            $catalogBrandLabels = [
                'abbott' => 'Abbott Nutrition',
                'aloe-pura' => 'Aloe Pura',
                'banana-boat' => 'Banana Boat',
                'beauty-formulas' => 'Beauty Formulas',
                'bio-oil' => 'Bio-Oil',
                'beurer' => 'Beurer',
                'british-life-sciences' => 'British Life Sciences',
                'caremax' => 'Caremax',
                'cellucare' => 'Cellucare',
                'cerave' => 'CeraVe',
                'dabur' => 'Dabur',
                'dermaplast' => 'Dermaplast',
                'dream-water' => 'Dream Water',
                'ezycare' => 'Ezy Care',
                'fresenius-kabi' => 'Fresenius Kabi',
                'futuro' => 'Futuro',
                'garnier' => 'Garnier',
                'himalaya' => 'Himalaya',
                'isdin' => 'Isdin',
                'jmc' => 'JMC',
                'jobri' => 'Jobri',
                'la-roche-posay' => 'La Roche-Posay',
                'maybelline' => 'Maybelline',
                'medisana' => 'Medisana',
                'meyra' => 'Meyra',
                'natures-bounty' => 'Nature\'s Bounty',
                'neutrogena' => 'Neutrogena',
                'now' => 'NOW',
                'nutrend' => 'Nutrend',
                'pic' => 'PIC Solution',
                'quest' => 'Quest',
                'roche' => 'Roche Diagnostics',
                'stax' => 'Flamingo Stax',
                'sukin' => 'Sukin',
                'sunshine-nutrition' => 'Sunshine Nutrition',
                'ultimate' => 'Ultimate',
                'urgaid' => 'Urgaid',
                'vantelin' => 'Vantelin',
                'vichy' => 'Vichy',
                'vita-vigor' => 'Vita-Vigor',
                'myra' => 'Myra',
                'other' => 'Other brands',
            ];
            $catalogCategoryLabels = [
                'mobility' => 'Mobility & wheelchairs',
                'orthopedic' => 'Orthopedic & compression',
                'diagnostics' => 'Diagnostics & monitoring',
                'first_aid' => 'First aid',
                'respiratory' => 'Respiratory care',
                'home_care' => 'Everyday home health',
                'beauty' => 'Beauty & dermacosmetics',
                'nutrition' => 'Wellness & clinical nutrition',
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
            $catalogCategoryOrder = ['mobility', 'orthopedic', 'diagnostics', 'first_aid', 'respiratory', 'home_care', 'beauty', 'nutrition'];
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
                <div class="catalog-toolbar">
                    <span><strong data-result-count>0</strong> products found</span>
                    <button type="button" class="filter-toggle-btn" id="filter-toggle-btn" aria-expanded="false" aria-controls="filters-panel">Filters</button>
                    <label class="search-wrap">Search:
                        <input type="search" id="product-search" placeholder="Search mobility and home health…" autocomplete="off">
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
                    <aside class="filters" id="filters-panel" aria-label="Product filters">
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-brands" aria-expanded="true" aria-controls="filters-panel-brands">
                                Brands
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
                                Categories
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-categories" role="region" aria-labelledby="filters-trigger-categories">
                                @foreach ($catalogCategorySlugs as $catSlug)
                                <label><input type="checkbox" data-filter-group="category" value="{{ $catSlug }}"> {{ $catalogCategoryLabels[$catSlug] ?? ucfirst(str_replace('_', ' ', $catSlug)) }}</label>
                                @endforeach
                            </div>
                        </div>
                        <div class="filters__group is-expanded" data-filter-accordion>
                            <button type="button" class="filters__trigger" id="filters-trigger-price" aria-expanded="true" aria-controls="filters-panel-price">
                                Price range
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
                                Customer rating
                                <span class="filters__chev" aria-hidden="true"></span>
                            </button>
                            <div class="filters__panel" id="filters-panel-rating" role="region" aria-labelledby="filters-trigger-rating">
                                <label><input type="checkbox" data-filter-group="rating" value="4"> 4 Stars &amp; Up</label>
                                <label><input type="checkbox" data-filter-group="rating" value="3"> 3 Stars &amp; Up</label>
                            </div>
                        </div>
                    </aside>
                    <div class="products" id="products-grid">
                        @foreach ($landingProducts as $i => $p)
                        <article class="product-card" data-name="{{ $p['search'] }}" data-brand="{{ $p['brand'] }}" data-category="{{ $p['category'] }}" data-rating="{{ (int) $p['rating'] }}" data-price="{{ $p['price'] }}" data-popularity="{{ $p['pop'] }}" data-whole="{{ $p['whole'] }}" data-dec="{{ $p['dec'] }}" @if (!empty($p['image'])) data-image="{{ e($p['image']) }}" @endif>
                            <div class="pimg{{ !empty($p['image']) ? ' p'.(($i % 3) + 1).' has-custom-img' : ' pimg--no-photo' }}" role="img" @if (!empty($p['image'])) aria-hidden="true" @else aria-label="No product image" @endif @if (!empty($p['image'])) style="background-image:url('{{ e($p['image']) }}')"@endif></div>
                            <div class="stars">{!! (int) $p['rating'] >= 5 ? '&#9733;&#9733;&#9733;&#9733;&#9733;' : '&#9733;&#9733;&#9733;&#9733;&#9734;' !!}<span>({{ $p['pop'] }})</span></div>
                            <h5>{{ $p['title'] }}</h5>
                            <p class="desc">{{ $p['desc'] }}</p>
                            <div class="product-footer">
                                <div class="price price--rsp">
                                    <span class="price__value">{{ $p['whole'] }}<small class="price-dec">.{{ $p['dec'] }}</small><small>AED</small></span>
                                </div>
                            </div>
                            <button type="button" class="cart-fab" aria-label="Add to cart">&#128722;</button>
                        </article>
                        @endforeach
                    </div>
                </div>
                <p class="no-results is-hidden" id="no-results">No products match the selected filters.</p>
            </div>
        </section>

        <section class="promo" aria-labelledby="promo-heading">
            <div class="container promo-copy">
                <h2 id="promo-heading">Mobility &amp; Recovery Essentials</h2>
                <p>Expert-curated mobility aids and post-surgery care kits, designed for safe, comfortable recovery at home.</p>
                <a href="{{ url('/').'?'.http_build_query(['cats' => 'orthopedic,mobility,first_aid']).'#catalog' }}" class="btn btn-ghost promo-catalog-btn">Browse Care Kits</a>
            </div>
        </section>

        <section class="newsletter" id="newsletter" aria-label="Newsletter and apps">
            <div class="container news-inner">
                <div>
                    <h3>Be the first to know</h3>
                    <p class="sub">Get newsletters and exclusive offers</p>
                </div>
                <form class="subscribe-form" id="subscribe-form" action="{{ url('/') }}#newsletter" method="post" novalidate>
                    <div class="subscribe-form__field">
                        <label class="visually-hidden" for="newsletter-email">Email address</label>
                        <input type="email" id="newsletter-email" name="email" placeholder="Email" autocomplete="email" required>
                        <button type="submit">Subscribe</button>
                    </div>
                    <p class="subscribe-form__status" id="subscribe-form-status" role="status" aria-live="polite"></p>
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
            <div class="product-modal__media">
                <img class="product-modal__img is-hidden" id="modal-img" src="" alt="">
                <div class="product-modal__img-placeholder" id="modal-img-placeholder" aria-hidden="true"></div>
            </div>
            <div class="product-modal__content">
                <h3 id="modal-title">Product</h3>
                <div class="stars" id="modal-stars"></div>
                <p class="product-modal__desc" id="modal-desc"></p>
                <div class="product-modal__footer">
                    <div class="price price--rsp product-modal__price" id="modal-price-wrap"></div>
                    <button type="button" class="product-modal__add-cart" id="modal-add-cart">Add to cart</button>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/gulf-landing.js') }}"></script>
</body>
</html>
