<?php

/*
|--------------------------------------------------------------------------
| Landing catalog
|--------------------------------------------------------------------------
| Feed rows: [ code, name, RSP, FINAL+VAT (source only), promo, category_name, sub_category_name, brand_name, image_url, rating ]
| UI shows RSP only; FINAL+VAT is kept in data for reference, not displayed.
| Order: Home Health Care (22), Beauty Care (22), Wellness & Clinical Nutrition (22).
*/

$catalogFeedRows = [
    // Home Health Care
    ['10055405', 'Beurer 11 Frog Instant Thermometer', 50.00, 42.00, '20%', 'Home Health Care', 'Diagnostics-Thermometer', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10055405/10055405-1.JPEG', 5],
    ['10055404', 'Beurer 27 Blood Pressure Monitor Limited Edition', 180.00, 151.20, '20%', 'Home Health Care', 'Diagnostics-Blood Pressure', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10055404/10055404-1.JPEG', 5],
    ['10680528', 'Beurer Br 60 Insect Bite Healer', 122.86, 103.20, '20%', 'Home Health Care', 'Insect Repellent', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10680528/10680528-1.jpeg', 5],
    ['22700319', 'Beurer Hearing Aid -Ha50', 160.95, 169.00, '', 'Home Health Care', 'Equipment-Ent', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/22700319/22700319-1.jpg', 5],
    ['10234544', 'Caremax Elbow Crutches-Ca856Lm', 85.00, 71.40, '20%', 'Home Health Care', 'Mobility Aids', 'Caremax', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10234544/10234544-1.jpg', 5],
    ['12336395', 'Dermaplast Water Resistant Plaster 20\'S', 12.00, 9.45, '25%', 'Home Health Care', 'First Aid-Bandages', 'DERMAPLAST', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12336395/12336395-1.jpg', 5],
    ['12455030', 'Ezycare Plus Menstrual Cup', 30.00, 28.35, '10%', 'Home Health Care', 'Intimate Hygiene', 'EZY CARE', '', 5],
    ['19555896', 'Flamingo Stax Mallet Finger Support', 18.00, 18.90, '', 'Home Health Care', 'Orthopedic Supports-Finger', 'Stax Mallet', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/19555896/19555896-1.JPG', 5],
    ['16330130', 'Futuro Energizing Wrist Support Left Hand- L-Xl', 167.00, 149.05, '15%', 'Home Health Care', 'Orthopedic Supports-Knee', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/16330130/16330130-1.jpg', 5],
    ['12141363', 'Futuro Firm Beige Pantyhose- M', 251.00, 224.02, '15%', 'Home Health Care', 'Compression Stockings', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12141363/12141363-1.jpg', 5],
    ['10255227', 'Futuro Infinity Precision Fit Ankle Support- Large', 105.00, 93.71, '15%', 'Home Health Care', 'Orthopedic Supports-Knee', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10255227/10255227-1.jpg', 5],
    ['12552361', 'Futuro Thumb Stabiliser S-M', 121.00, 107.99, '15%', 'Home Health Care', 'Orthopedic Supports-Knee', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12552361/12552361-1.jpg', 5],
    ['25556306', 'Jmc Folding Walker With Small-Wheel -3016W', 210.00, 176.40, '20%', 'Home Health Care', 'Mobility Aids', 'Jmc', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12471015/12471015-1.jpg', 5],
    ['12254557', 'Jmc Wheel Chair Standard', 500.00, 420.00, '20%', 'Home Health Care', 'Mobility Aids', 'Jmc', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12254557/12254557-1.JPEG', 5],
    ['14295108', 'Jobri Deluxe Visco Lumbar Support- Black- Bb6006', 295.00, 247.80, '20%', 'Home Health Care', 'Orthopedic Pillows', 'Jobri', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14295108/14295108-1.jpg', 5],
    ['15451460', 'Jobri Ring Cushion 20"-White-Bh1020', 170.00, 142.80, '20%', 'Home Health Care', 'Orthopedic Supports-Knee', 'Jobri', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15451460/15451460-1.jpg', 5],
    ['14223422', 'MEDISANA MC828 PREMIUM MASSAGESEAT (99930)', 1500.00, 1260.00, '20%', 'Home Health Care', 'Equipment-Ent', 'Medisana', '', 5],
    ['12191602', 'Meyra 9.500 Clou Power Wheelchair', 14000.00, 11760.00, '20%', 'Home Health Care', 'Mobility Aids', 'Meyra', '', 5],
    ['14455007', 'Pic Enema Set Bag 2L', 40.00, 32.00, '20%', 'Home Health Care', 'Consumables And Accessories', 'PIC (Pic Solution)', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14455007/14455007-1.jpg', 5],
    ['17896509', 'Vantelin Back Support, Black, Size L', 245.00, 154.35, '40%', 'Home Health Care', 'Orthopedic Supports-Back', 'VANTELIN', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/17896509/17896509-1.jpg', 5],
    ['15150000', 'Accu-Chek Instant Mg/Di Sc Kit (09221794078)', 199.00, 208.95, '', 'Home Health Care', 'Diagnostics - Blood Glucose Monitors', 'ROCHE DIAGNOSTICS', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15150000/15150000-1.jpg', 5],
    ['10055123', 'Pic Air Family Evolution NEBULIZER', 320.00, 268.80, '20%', 'Home Health Care', 'Respiratory Care-Nebulizer', 'PIC (Pic Solution)', '', 5],

    // Beauty Care and Dermacosmetics
    ['12440054', 'Aloe Pura Organic Aloe Vera Gel 200Ml', 47.00, 49.35, '', 'Beauty Care', 'Soothing & Moisturizers', 'ALOE PURA', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/products/12440054/12440054-1.JPEG', 5],
    ['12671142', 'Banana Boat Sport Spray Spf100 170G', 87.00, 91.35, '', 'Beauty Care', 'Sun Care-Body', 'Banana Boat', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12671142/12671142-1.jpg', 5],
    ['16900547', 'Beauty Formulas Cool Moist Cucumber Facial Scrub', 19.00, 12.97, '35%', 'Beauty Care', 'Cleansers/Scrub & Toners', 'BEAUTY FORMULAS', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/16900547/16900547-1.jpg', 5],
    ['11300678', 'Bio-Oil Skincare Oil (Natural) - 125ml', 75.00, 59.06, '25%', 'Beauty Care', 'Scar /Stretch Mark Removal', 'Bio Oil', '', 5],
    ['14205738', 'Cerave Blemish Control Cleanser, 236Ml', 88.57, 83.70, '10%', 'Beauty Care', 'Cleansers & Toners', 'CERAVE', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14205738/14205738-1.jpg', 5],
    ['14205740', 'Cerave Hyaluronic Acid Hydrating Serum, 10Ml', 85.00, 80.33, '10%', 'Beauty Care', 'Face Serum', 'CERAVE', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14205740/14205740-1.jpg', 5],
    ['14205736', 'Cerave Pm Facial Moisturizing Lotion, 50Ml', 87.62, 82.80, '10%', 'Beauty Care', 'Moisturizers-Face', 'CERAVE', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14205736/14205736-1.jpg', 5],
    ['12191759', 'Garnier Skin Active Fast Bright Hyperpigmentation Ampoule Serum', 129.24, 135.70, '', 'Beauty Care', 'Whitening', 'Garnier', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12191759/12191759-1.jpg', 5],
    ['12191756', 'Garnier Skinactive Fast Bright Vitamin C Purifying Gel Wash 400 Ml', 58.07, 60.97, '', 'Beauty Care', 'Cleansers & Toners', 'Garnier', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12191756/12191756-1.jpg', 5],
    ['10251520', 'Isdin Fotoprotector Fusion Water Magic, Spf 50 – Promotional Pack ( Buy 1 Get 1)', 175.00, 183.75, '1+1 OFFER PACK', 'Beauty Care', 'Sun Care - Face', 'Isdin', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10251520/10251520-1.jpg', 5],
    ['10251525', 'Isdin Fotoprotector Wet Skin Transparent Spray, Spf 50+, 250 Ml – Pediatrics, Promotional Pack ( Buy 1 Get 1)', 195.00, 204.75, '1+1 OFFER PACK', 'Beauty Care', 'Sun Care - Face & Body', 'Isdin', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10251525/10251525-1.JPG', 5],
    ['28905747', 'La Roche-Posay Cicaplast Baume Lips 7.5Ml', 61.00, 64.05, '', 'Beauty Care', 'Lip Care', 'LA ROCHE-POSAY', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/28905747/28905747-1.jpg', 5],
    ['10002029', 'La Roche-Posay Thermal Spring Water 150Ml (Rp038)', 85.00, 89.25, '', 'Beauty Care', 'Soothing & Moisturizers', 'LA ROCHE-POSAY', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10002029/10002029-1.JPEG', 5],
    ['12512027', 'Maybelline Volum Express The Colossal Washable Mascara- Black', 50.75, 53.29, '', 'Beauty Care', 'Color Cosmetics & Make Up', 'Maybelline', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12512027/12512027-1.jpg', 5],
    ['15293675', 'Neutrogena Hydro Boost Gel-Cream 50Ml', 51.43, 54.00, '', 'Beauty Care', 'Moisturizers-Face', 'Neutrogena', '', 5],
    ['12691750', 'Sukin Men\'s Facial Cleansing Scrub 125Ml', 58.00, 42.63, '30%', 'Beauty Care', 'Cleansers/Scrub & Toners', 'Sukin', '', 5],
    ['12691749', 'Sukin Men\'s Facial Moisturiser 225Ml', 58.00, 42.63, '30%', 'Beauty Care', 'Moisturizers-Face', 'Sukin', '', 5],
    ['12045606', 'Urgaid Pimple Patch 36\'S', 24.29, 25.50, '', 'Beauty Care', 'Acne Care', 'URGAID', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12045606/12045606-1.jpg', 5],
    ['16114336', 'Vichy Normaderm Phyto Cleanser Gel, 200Ml', 111.00, 116.55, '', 'Beauty Care', 'Cleansers & Toners', 'VICHY', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/16114336/16114336-1.jpg', 5],
    ['12512026', 'Maybelline The Colossal Kajal With Argan Oil- Extra Black', 20.30, 21.32, '', 'Beauty Care', 'Color Cosmetics & Make Up', 'Maybelline', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12512026/12512026-1.jpg', 5],
    ['12512028', 'Maybelline The Falsies Lash Lift', 68.87, 72.31, '', 'Beauty Care', 'Color Cosmetics & Make Up', 'Maybelline', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12512028/12512028-1.jpg', 5],
    ['12512030', 'Maybelline Super Stay Matte Ink Liquid Lipstick- Lover', 79.74, 83.73, '', 'Beauty Care', 'Color Cosmetics & Make Up', 'Maybelline', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12512030/12512030-1.jpg', 5],

    // Wellness and Clinical Nutrition (+ sports / OTC where listed)
    ['10820530', 'Fresubin 2Kcal Drink Vanilla-200ml', 16.00, 16.00, '', 'Nutrition & Supplements', 'Vitality & Energizers', 'Fresenius Kabi', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10820530/10820530-1.jpg', 5],
    ['10820518', 'Fresubin Supportan Tropical Fruits 200ml', 18.00, 18.00, '', 'Nutrition & Supplements', 'Vitality & Energizers', 'Fresenius Kabi', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10820518/10820518-1.jpg', 5],
    ['10820526', 'Fresubin Supportan Cappuccino 200 ml', 18.00, 18.00, '', 'Nutrition & Supplements', 'Vitality & Energizers', 'Fresenius Kabi', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10820526/10820526-1.jpg', 5],
    ['24165823', 'Glucerna Sr Vanilla Powder 400G', 50.00, 52.50, '', 'Nutrition & Supplements', 'Meal Replacement', 'ABBOTT NUTRITION', '', 5],
    ['18120102', 'Cellucare Tablets 60s', 330.00, 330.00, '', 'Nutrition & Supplements', 'Vitality & Energizers', 'Cellucare', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/18120102/18120102-1.jpg', 5],
    ['10134212', 'Now Cod Liver Oil 1000Mg- 90 Softgels', 99.00, 103.95, '', 'Nutrition & Supplements', 'Fish Oils & Omegas', 'Now', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/24005702/24005702-1.jpg', 5],
    ['11798133', 'Nutrend Collagen Liquid 500ML Orange', 85.71, 81.00, '10%', 'Sports Nutrition', 'Anti-Aging Supplements', 'Nutrend', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/products/11798133/11798133-1.jpg', 5],
    ['11798108', 'Nutrend N1 Pre-workout 255G', 65.41, 61.81, '10%', 'Sports Nutrition', 'Pre Work Out', 'Nutrend', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/products/11798108/11798108-1.JPG', 5],
    ['11798107', 'Nutrend Creatine Monohydrate (Creapure)', 130.77, 123.58, '10%', 'Sports Nutrition', 'Creatine', 'Nutrend', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/products/11798107/11798107-1.jpg', 5],
    ['15933874', 'Ultimate Digestive Enzymes Tablets 90`S', 99.00, 62.37, '40%', 'Nutrition & Supplements', 'Vitality & Energizers', 'ULTIMATE', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15933874/15933874-1.jpg', 5],
    ['12345729', 'Nature\'s Bounty Melatonin 5Mg', 118.00, 105.32, '15%', 'Nutrition & Supplements', 'Stress & Sleep Support', 'Nature\'S Bounty', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12345729/12345729-1.jpg', 5],
    ['12691732', 'Sunshine Nutrition Good Gummies Beauty Dietary Supplement 45 Gummies', 75.00, 55.13, '30%', 'Nutrition & Supplements', 'Gummies/Skin - Hair - Nails', 'Sunshine Nutrition', '', 5],
    ['12987891', 'Himalaya PartySmart 12 Capsules', 60.00, 59.85, '5%', 'Nutrition & Supplements', 'Liver Support', 'Himalaya', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12987891/12987891-1.JPG', 5],
    ['12343307', 'Dabur Shilajit Capsules 100\'S', 90.00, 67.50, '25%', 'Nutrition & Supplements', 'Vitality & Energizers', 'DABUR', 'https://gulfpharmacy.com//public/storage/product-image/17739727505jpeg', 5],
    ['16283029', 'Happi Kidz Multivitamin Gummies  1+1 Offer Pack', 65.00, 68.25, '1+1 OFFER PACK', 'Nutrition & Supplements-Kids', 'Gummies/Multivitamins', 'British Life Sciences Pvt Ltd', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/16283028/16283028-1.jpg', 5],
    ['21844824', 'Myra E 400 IU Capsules 30s', 53.00, 55.65, '', 'Nutrition & Supplements', 'Vitamin E', 'MYRA', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/21844824/21844824-1.jpg', 5],
    ['15933887', 'Ultimate Phytoslim Apple Cider Vinegar', 166.00, 104.58, '40%', 'Nutrition & Supplements', 'Vitality & Energizers', 'ULTIMATE', '', 5],
    ['12691755', 'Sunshine Good Gummies Multivitamin Gummies - 60 Count', 82.00, 60.27, '30%', 'Nutrition & Supplements-Kids', 'Gummies/Multivitamins', 'SUNSHINE NUTRITION', '', 5],
    ['12590013', 'Quest Biomag 150Ml Mag+B6 5Mg Tab 30`S', 69.00, 69.00, '', 'Over The Counter Medicines', 'Minerals/Magnesium', 'Quest', '', 5],
    ['26429900', 'Nature\'s Bounty Advanced Magnesium Glycinate, 60 Capsules (NB-01255)', 160.00, 168.00, '', 'Nutrition & Supplements', 'Stress & Sleep Support', 'Nature\'S Bounty', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/26429900/26429900-1.jpg', 5],
    ['15101012', 'Vita-Vigor Super Stress B With Zinc Tablets- 60', 67.00, 49.25, '30%', 'Nutrition & Supplements', 'Stress & Sleep Support', 'Vita-Vigor', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15101012/15101012-1.jpg', 5],
    ['12346571', 'Dream Water Sleep And Relaxation Shot Snoozberry 74 Ml', 16.50, 17.33, '', 'Nutrition & Supplements', 'Stress & Sleep Support', 'Dream Water', '', 5],
];

/**
 * Map ERP brand name to sidebar filter slug (landing.blade.php).
 */
function gulf_catalog_brand_slug($brandName)
{
    $n = strtoupper(trim(str_replace('`', "'", $brandName)));

    $needles = [
        'ROCHE DIAGNOSTICS' => 'roche',
        'ROCHE' => 'roche',
        'PIC (' => 'pic',
        'PIC ' => 'pic',
        'BEURER' => 'beurer',
        'CAREMAX' => 'caremax',
        'FUTURO' => 'futuro',
        'JOBRI' => 'jobri',
        'JMC' => 'jmc',
        'MEYRA' => 'meyra',
        'MEDISANA' => 'medisana',
        'VANTELIN' => 'vantelin',
        'DERMAPLAST' => 'dermaplast',
        'EZY CARE' => 'ezycare',
        'STAX' => 'stax',
        'LA ROCHE-POSAY' => 'la-roche-posay',
        'LA ROCHE' => 'la-roche-posay',
        'BEAUTY FORMULAS' => 'beauty-formulas',
        'ALOE PURA' => 'aloe-pura',
        'BANANA BOAT' => 'banana-boat',
        'BIO OIL' => 'bio-oil',
        'CERAVE' => 'cerave',
        'GARNIER' => 'garnier',
        'ISDIN' => 'isdin',
        'MAYBELLINE' => 'maybelline',
        'NEUTROGENA' => 'neutrogena',
        'SUKIN' => 'sukin',
        'URGAID' => 'urgaid',
        'VICHY' => 'vichy',
        'FRESENIUS KABI' => 'fresenius-kabi',
        'ABBOTT NUTRITION' => 'abbott',
        'CELLUCARE' => 'cellucare',
        'NUTREND' => 'nutrend',
        'ULTIMATE' => 'ultimate',
        'NATURE' => 'natures-bounty',
        'SUNSHINE NUTRITION' => 'sunshine-nutrition',
        'SUNSHINE' => 'sunshine-nutrition',
        'HIMALAYA' => 'himalaya',
        'DABUR' => 'dabur',
        'BRITISH LIFE SCIENCES' => 'british-life-sciences',
        'MYRA' => 'myra',
        'QUEST' => 'quest',
        'VITA-VIGOR' => 'vita-vigor',
        'DREAM WATER' => 'dream-water',
        'NOW' => 'now',
    ];

    foreach ($needles as $needle => $slug) {
        if (strpos($n, $needle) !== false) {
            return $slug;
        }
    }

    return 'other';
}

/**
 * Landing catalog category (mobility story) from merchandising sub-category.
 */
function gulf_catalog_landing_category($subCat)
{
    $s = strtolower($subCat);
    if (strpos($s, 'mobility') !== false) {
        return 'mobility';
    }
    if (strpos($s, 'orthopedic') !== false || strpos($s, 'compression') !== false || strpos($s, 'pillow') !== false) {
        return 'orthopedic';
    }
    if (strpos($s, 'diagnostic') !== false || strpos($s, 'blood glucose') !== false || strpos($s, 'thermometer') !== false || strpos($s, 'blood pressure') !== false) {
        return 'diagnostics';
    }
    if (strpos($s, 'first aid') !== false || strpos($s, 'bandage') !== false) {
        return 'first_aid';
    }
    if (strpos($s, 'respiratory') !== false || strpos($s, 'nebulizer') !== false) {
        return 'respiratory';
    }

    return 'home_care';
}

/**
 * Blade / filters: beauty & nutrition vs home-health taxonomy.
 */
function gulf_catalog_row_landing_category($catName, $subCat)
{
    $c = strtolower($catName);
    if (strpos($c, 'beauty') !== false) {
        return 'beauty';
    }
    if (strpos($c, 'nutrition') !== false || strpos($c, 'sports nutrition') !== false || strpos($c, 'over the counter') !== false) {
        return 'nutrition';
    }

    return gulf_catalog_landing_category($subCat);
}

function gulf_catalog_price_parts($price)
{
    $price = (float) $price;
    $parts = explode('.', number_format($price, 2, '.', ''));

    return [$parts[0], isset($parts[1]) ? $parts[1] : '00'];
}

$products = [];
$landingProducts = [];

foreach ($catalogFeedRows as $mi => $row) {
    list($code, $name, $rsp, $final, $promo, $catName, $subCat, $brandName, $imageUrl, $rating) = $row;
    $final = (float) $final;
    $rsp = (float) $rsp;
    $rating = (int) $rating;
    if ($rating < 1) {
        $rating = 5;
    }
    list($whole, $dec) = gulf_catalog_price_parts($rsp);

    $title = $code.' - '.$name;
    $descLine = $catName.' · '.$subCat;
    $promoT = trim($promo);
    if ($promoT !== '') {
        $descLine .= ' · '.$promoT;
    }

    $slug = gulf_catalog_brand_slug($brandName);
    $landingCat = gulf_catalog_row_landing_category($catName, $subCat);

    $base = [
        'search' => strtolower($code.' '.$name),
        'brand' => $slug,
        'rating' => $rating,
        'price' => $rsp,
        'pop' => max(12, 210 - ($mi * 3)),
        'title' => $title,
        'desc' => $descLine,
        'whole' => $whole,
        'dec' => $dec,
        'image' => $imageUrl,
    ];

    $products[] = array_merge($base, ['category' => $landingCat]);
    $landingProducts[] = array_merge($base, ['category' => $landingCat]);
}

return [
    'products' => $products,
    'landing_products' => $landingProducts,
];
