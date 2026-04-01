<?php

/*
|--------------------------------------------------------------------------
| Landing catalog
|--------------------------------------------------------------------------
| Feed rows: [ code, name, RSP, FINAL+VAT (reference), promo, category_name, sub_category_name, brand_name, image_url, rating, product_details_url ]
| UI shows RSP only. FINAL+VAT is kept for reference, not displayed.
| Catalog limited to Home Health Care products supplied for the landing page.
*/

$catalogFeedRows = [
    ['10055405', 'Beurer 11 Frog Instant Thermometer', 50.00, 50.00, '', 'Home Health Care', 'Diagnostics-Thermometer', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10055405/10055405-1.JPEG', 5, 'https://gulfpharmacy.com/search-listing?search=Beurer+11+Frog+Instant+Thermometer'],
    ['10055404', 'Beurer 27 Blood Pressure Monitor Limited Edition', 180.00, 180.00, '', 'Home Health Care', 'Diagnostics-Blood Pressure', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10055404/10055404-1.JPEG', 5, 'https://gulfpharmacy.com/search-listing?search=Beurer+27+Blood+Pressure+Monitor+Limited+Edition'],
    ['10680528', 'Beurer Br 60 Insect Bite Healer', 122.86, 122.86, '', 'Home Health Care', 'Insect Repellent', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10680528/10680528-1.jpeg', 5, 'https://gulfpharmacy.com/search-listing?search=Beurer+Br+60+Insect+Bite+Healer'],
    ['22700319', 'Beurer Hearing Aid -Ha50', 160.95, 160.95, '', 'Home Health Care', 'Equipment-Ent', 'BEURER', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/22700319/22700319-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Beurer+Hearing+Aid+-Ha50'],
    ['10234544', 'Caremax Elbow Crutches-Ca856Lm', 85.00, 85.00, '', 'Home Health Care', 'Mobility Aids', 'Caremax', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10234544/10234544-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Caremax+Elbow+Crutches-Ca856Lm'],
    ['12336395', 'Dermaplast Water Resistant Plaster 20\'S', 12.00, 12.00, '', 'Home Health Care', 'First Aid-Bandages', 'DERMAPLAST', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12336395/12336395-1.jpg', 5, 'https://gulfpharmacy.com/c/home-health-care/p/dermaplast-water-resistant-plaster-20-rsquo-s'],
    ['14724526', 'Ezycare Plus Menstrual Cup', 30.00, 30.00, '', 'Home Health Care', 'Intimate Hygiene', 'EZY CARE', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14724526/14724526-1.JPEG', 5, 'https://gulfpharmacy.com/search-listing?search=Menstrual+Cup+'],
    ['19555896', 'Flamingo Stax Mallet Finger Support', 18.00, 18.00, '', 'Home Health Care', 'Orthopedic Supports-Finger', 'Stax Mallet', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/19555896/19555896-1.JPG', 5, 'https://gulfpharmacy.com/c/home-health-care/p/flamingo-stax-mallet'],
    ['16330130', 'Futuro Energizing Wrist Support Left Hand- L-Xl', 167.00, 167.00, '', 'Home Health Care', 'Orthopedic Supports-Wrist', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/16330130/16330130-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Futuro+Energizing+Wrist+Support+Left+Hand-+L-Xl'],
    ['12141363', 'Futuro Firm Beige Pantyhose- M', 251.00, 251.00, '', 'Home Health Care', 'Compression Stockings', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12141363/12141363-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Futuro+Firm+Beige+Pantyhose-+M'],
    ['10255227', 'Futuro Infinity Precision Fit Ankle Support- Large', 105.00, 105.00, '', 'Home Health Care', 'Orthopedic Supports-Ankle', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10255227/10255227-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Futuro+Infinity+Precision+Fit+Ankle+Support-+Large'],
    ['12552361', 'Futuro Thumb Stabiliser S-M', 121.00, 121.00, '', 'Home Health Care', 'Orthopedic Supports-Thumb', 'Futuro', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12552361/12552361-1.jpg', 5, 'https://gulfpharmacy.com/c/home-health-care/p/futuro-thumb-stabiliser-small-medium-size'],
    ['12471015', 'Jmc Folding Walker With Small-Wheel -3016W', 210.00, 210.00, '', 'Home Health Care', 'Mobility Aids', 'Jmc', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12471015/12471015-1.jpg', 5, 'https://gulfpharmacy.com/c/home-health-care/p/jmc-folding-walker-with-4-wheels-model-media6-3016w'],
    ['12254557', 'Jmc Wheel Chair Standard', 500.00, 500.00, '', 'Home Health Care', 'Mobility Aids', 'Jmc', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/12254557/12254557-1.JPEG', 5, 'https://gulfpharmacy.com/search-listing?search=Jmc+Wheel+Chair+Standard'],
    ['14295108', 'Jobri Deluxe Visco Lumbar Support- Black- Bb6006', 295.00, 295.00, '', 'Home Health Care', 'Orthopedic Pillows', 'Jobri', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14295108/14295108-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Jobri+Deluxe+Visco+Lumbar+Support-+Black-+Bb6006'],
    ['15451460', 'Jobri Ring Cushion 20"-White-Bh1020', 170.00, 170.00, '', 'Home Health Care', 'Orthopedic Supports-Seat', 'Jobri', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15451460/15451460-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Jobri+Ring+Cushion+20%22-White-Bh1020'],
    ['14223422', 'MEDISANA MC828 PREMIUM MASSAGESEAT (99930)', 1500.00, 1500.00, '', 'Home Health Care', 'Equipment-Ent', 'Medisana', '', 5, ''],
    ['12191602', 'Meyra 9.500 Clou Power Wheelchair', 14000.00, 14000.00, '', 'Home Health Care', 'Mobility Aids', 'Meyra', '', 5, ''],
    ['14455007', 'Pic Enema Set Bag 2L', 40.00, 40.00, '', 'Home Health Care', 'Consumables And Accessories', 'PIC (Pic Solution)', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/14455007/14455007-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Pic+Enema+Set+Bag+2L'],
    ['17896509', 'Vantelin Back Support, Black, Size L', 245.00, 245.00, '', 'Home Health Care', 'Orthopedic Supports-Back', 'VANTELIN', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/17896509/17896509-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Vantelin+Back+Support%2C+Black%2C+Size+L'],
    ['15150000', 'Accu-Chek Instant Mg/Di Sc Kit (09221794078)', 199.00, 199.00, '', 'Home Health Care', 'Diagnostics - Blood Glucose Monitors', 'ROCHE DIAGNOSTICS', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/15150000/15150000-1.jpg', 5, 'https://gulfpharmacy.com/search-listing?search=Accu-Chek+Instant+Mg%2FDi+Sc+Kit+%2809221794078%29'],
    ['10055123', 'Pic Air Family Evolution NEBULIZER', 320.00, 320.00, '', 'Home Health Care', 'Respiratory Care-Nebulizer', 'PIC (Pic Solution)', 'https://uae-gulfpharmacys3b.s3.me-central-1.amazonaws.com/images/products/10055123/10055123-1.jpeg', 5, 'https://gulfpharmacy.com/search-listing?search=pic%20air%20family'],
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
    ];

    foreach ($needles as $needle => $slug) {
        if (strpos($n, $needle) !== false) {
            return $slug;
        }
    }

    return 'other';
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
    $detailUrl = isset($row[10]) ? (string) $row[10] : '';
    list($code, $name, $rsp, $final, $promo, $catName, $subCat, $brandName, $imageUrl, $rating) = array_slice($row, 0, 10);
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
    $landingCat = 'home_health_care';

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
        'detail_url' => $detailUrl,
    ];

    $products[] = array_merge($base, ['category' => $landingCat]);
    $landingProducts[] = array_merge($base, ['category' => $landingCat]);
}

return [
    'products' => $products,
    'landing_products' => $landingProducts,
];
