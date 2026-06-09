<?php
session_start();

// ── Configuration ─────────────────────────────────────────────────────────────
$password          = 'gulf2026';
$json_path         = __DIR__ . '/data/products.json';
$backup_path       = __DIR__ . '/data/products_backup.json';
$leads_json_path   = __DIR__ . '/data/leads.json';
$leads_backup_path = __DIR__ . '/data/leads_backup.json';

// ── Helpers ───────────────────────────────────────────────────────────────────
function readData($path) {
    if (!file_exists($path)) return null;
    return json_decode(file_get_contents($path), true);
}

function saveData($path, $backup_path, $data) {
    copy($path, $backup_path); // backup first
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) !== false;
}

function jsonResponse($success, $message, $extra = array()) {
    header('Content-Type: application/json');
    echo json_encode(array_merge(array('success' => $success, 'message' => $message), $extra));
    exit;
}

// ── Auth check ────────────────────────────────────────────────────────────────
$is_logged_in = isset($_SESSION['gulf_admin_logged']) && $_SESSION['gulf_admin_logged'] === true;

// ── AJAX: Submit Lead (PUBLIC) ────────────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'submit_lead') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        jsonResponse(false, 'Method Not Allowed.');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    $name  = isset($input['name']) ? trim($input['name']) : '';
    $email = isset($input['email']) ? trim($input['email']) : '';
    $phone = isset($input['phone']) ? trim($input['phone']) : '';
    $country = isset($input['country']) ? trim($input['country']) : '';

    if (empty($name) || empty($email) || empty($phone)) {
        jsonResponse(false, 'First name, Email and Phone are required.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, 'Invalid email address.');
    }

    // Read or initialize leads
    $data = readData($leads_json_path);
    if (!$data) {
        $data = array('leads' => array());
    }

    $new_lead = array(
        'id'      => 'lead_' . time() . '_' . rand(100, 999),
        'name'    => $name,
        'email'   => $email,
        'phone'   => $phone,
        'country' => $country ? $country : 'N/A',
        'date'    => date('Y-m-d H:i:s')
    );

    array_unshift($data['leads'], $new_lead);

    if (saveData($leads_json_path, $leads_backup_path, $data)) {
        jsonResponse(true, 'Lead recorded successfully!', array('lead' => $new_lead));
    } else {
        jsonResponse(false, 'Failed to save lead database.');
    }
}

// ── Logout ────────────────────────────────────────────────────────────────────
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: manage.php');
    exit;
}

// ── Login ─────────────────────────────────────────────────────────────────────
$login_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_password'])) {
    if ($_POST['login_password'] === $password) {
        $_SESSION['gulf_admin_logged'] = true;
        header('Location: manage.php');
        exit;
    }
    $login_error = 'Incorrect password. Please try again.';
}

// ── AJAX: Toggle Active Status ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'toggle') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input = json_decode(file_get_contents('php://input'), true);
    $code  = isset($input['code']) ? trim($input['code']) : '';
    $state = isset($input['active']) ? (bool)$input['active'] : false;

    if (!$code) jsonResponse(false, 'Product code is required.');
    $data = readData($json_path);
    if (!$data) jsonResponse(false, 'Could not read products.json.');

    $found = false;
    foreach ($data['products'] as &$p) {
        if ($p['code'] === $code) { $p['active'] = $state; $found = true; break; }
    }
    if (!$found) { http_response_code(404); jsonResponse(false, 'Product not found.'); }
    saveData($json_path, $backup_path, $data) ? jsonResponse(true, 'Status updated!') : jsonResponse(false, 'Failed to save.');
}

// ── AJAX: Add Product ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required fields
    $required = array('code', 'title', 'price', 'brand', 'mkt_category', 'detail_url');
    foreach ($required as $field) {
        $val = isset($input[$field]) ? trim($input[$field]) : '';
        if (empty($val)) {
            jsonResponse(false, "Field '{$field}' is required.");
        }
    }

    $data = readData($json_path);
    if (!$data) jsonResponse(false, 'Could not read products.json.');

    // Check for duplicate code
    foreach ($data['products'] as $p) {
        if ($p['code'] === trim($input['code'])) {
            jsonResponse(false, "A product with code '{$input['code']}' already exists.");
        }
    }

    $title = trim($input['title']);
    $new = array(
        'code'         => trim($input['code']),
        'title'        => $title,
        'price'        => (float)$input['price'],
        'brand'        => strtolower(trim($input['brand'])),
        'category'     => 'home_health_care',
        'mkt_category' => trim($input['mkt_category']),
        'rating'       => max(1, min(5, (int)(isset($input['rating']) ? $input['rating'] : 5))),
        'image'        => trim(isset($input['image']) ? $input['image'] : ''),
        'desc'         => trim(isset($input['desc']) ? $input['desc'] : ''),
        'detail_url'   => trim($input['detail_url']),
        'pop'          => (int)(isset($input['pop']) ? $input['pop'] : 100),
        'active'       => isset($input['active']) ? (bool)$input['active'] : true,
        'search'       => strtolower($title),
    );

    $data['products'][] = $new;
    saveData($json_path, $backup_path, $data)
        ? jsonResponse(true, "Product '{$title}' added successfully!", array('product' => $new))
        : jsonResponse(false, 'Failed to save.');
}

// ── AJAX: Edit Product ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input = json_decode(file_get_contents('php://input'), true);

    $old_code = isset($input['old_code']) ? trim($input['old_code']) : '';
    $code     = isset($input['code']) ? trim($input['code']) : '';

    if (!$old_code || !$code) jsonResponse(false, 'Product code is required.');

    // Validate required fields
    $required = array('code', 'title', 'price', 'brand', 'mkt_category', 'detail_url');
    foreach ($required as $field) {
        $val = isset($input[$field]) ? trim($input[$field]) : '';
        if (empty($val)) {
            jsonResponse(false, "Field '{$field}' is required.");
        }
    }

    $data = readData($json_path);
    if (!$data) jsonResponse(false, 'Could not read products.json.');

    // Check duplicate code if code changed
    if ($code !== $old_code) {
        foreach ($data['products'] as $p) {
            if ($p['code'] === $code) {
                jsonResponse(false, "A product with code '{$code}' already exists.");
            }
        }
    }

    $title = trim($input['title']);
    $found = false;
    $updated_product = null;

    foreach ($data['products'] as &$p) {
        if ($p['code'] === $old_code) {
            $p['code']         = $code;
            $p['title']        = $title;
            $p['price']        = (float)$input['price'];
            $p['brand']        = strtolower(trim($input['brand']));
            $p['mkt_category'] = trim($input['mkt_category']);
            $p['rating']       = max(1, min(5, (int)(isset($input['rating']) ? $input['rating'] : 5)));
            $p['image']        = trim(isset($input['image']) ? $input['image'] : '');
            $p['desc']         = trim(isset($input['desc']) ? $input['desc'] : '');
            $p['detail_url']   = trim($input['detail_url']);
            $p['pop']          = (int)(isset($input['pop']) ? $input['pop'] : 100);
            $p['active']       = isset($input['active']) ? (bool)$input['active'] : true;
            $p['search']       = strtolower($title);
            $found = true;
            $updated_product = $p;
            break;
        }
    }

    if (!$found) { http_response_code(404); jsonResponse(false, 'Product not found.'); }
    saveData($json_path, $backup_path, $data)
        ? jsonResponse(true, "Product '{$title}' updated successfully!", array('product' => $updated_product))
        : jsonResponse(false, 'Failed to save.');
}

// ── AJAX: Delete Product ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input = json_decode(file_get_contents('php://input'), true);
    $code  = isset($input['code']) ? trim($input['code']) : '';
    if (!$code) jsonResponse(false, 'Product code is required.');
    $data = readData($json_path);
    if (!$data) jsonResponse(false, 'Could not read products.json.');

    $before = count($data['products']);
    $data['products'] = array_values(array_filter($data['products'], function($p) use ($code) {
        return $p['code'] !== $code;
    }));
    if (count($data['products']) === $before) { http_response_code(404); jsonResponse(false, 'Product not found.'); }
    saveData($json_path, $backup_path, $data) ? jsonResponse(true, 'Product deleted.') : jsonResponse(false, 'Failed to save.');
}

// ── AJAX: Save Popup Settings ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'save_popup') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input       = json_decode(file_get_contents('php://input'), true);
    $title       = isset($input['title'])        ? trim($input['title'])        : '';
    $subtitle    = isset($input['subtitle'])     ? trim($input['subtitle'])     : '';
    $redirect    = isset($input['redirect_url']) ? trim($input['redirect_url']) : '';
    $button_text = isset($input['button_text'])  ? trim($input['button_text'])  : 'Join Now';

    if (empty($title))    jsonResponse(false, 'Popup title cannot be empty.');
    if (empty($subtitle)) jsonResponse(false, 'Popup subtitle cannot be empty.');
    if (empty($button_text)) jsonResponse(false, 'Popup button text cannot be empty.');

    $data = readData($json_path);
    if (!$data) jsonResponse(false, 'Could not read products.json.');

    $data['popup_settings'] = array(
        'title'        => $title,
        'subtitle'     => $subtitle,
        'redirect_url' => $redirect,
        'button_text'  => $button_text
    );

    saveData($json_path, $backup_path, $data)
        ? jsonResponse(true, 'Popup settings saved!')
        : jsonResponse(false, 'Failed to save settings.');
}

// ── AJAX: Delete Lead ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete_lead') {
    if (!$is_logged_in) { http_response_code(403); jsonResponse(false, 'Unauthorized.'); }
    $input = json_decode(file_get_contents('php://input'), true);
    $id    = isset($input['id']) ? trim($input['id']) : '';
    if (!$id) jsonResponse(false, 'Lead ID is required.');
    
    $data = readData($leads_json_path);
    if (!$data) jsonResponse(false, 'Could not read leads.json.');

    $before = count($data['leads']);
    $data['leads'] = array_values(array_filter($data['leads'], function($l) use ($id) {
        return $l['id'] !== $id;
    }));
    if (count($data['leads']) === $before) { http_response_code(404); jsonResponse(false, 'Lead not found.'); }
    saveData($leads_json_path, $leads_backup_path, $data) ? jsonResponse(true, 'Lead deleted.') : jsonResponse(false, 'Failed to save.');
}

// ── Load products and leads for page render ───────────────────────────────────
$products = array();
$leads    = array();
$popup_settings = array('title' => '', 'subtitle' => '', 'redirect_url' => '', 'button_text' => 'Join Now');
if ($is_logged_in) {
    $d = readData($json_path);
    $products = isset($d['products']) ? $d['products'] : array();
    if (isset($d['popup_settings'])) {
        $popup_settings = $d['popup_settings'];
    }

    $ld = readData($leads_json_path);
    $leads = isset($ld['leads']) ? $ld['leads'] : array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Manager | Gulf Pharmacy</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --teal: #008b8b; --teal-d: #006666; --teal-l: #00b3b3;
            --mint: #e0f7fa; --text: #1f2a2d; --muted: #647481;
            --border: #e4ecee; --bg: #f7fafb;
            --red: #d32f2f; --green: #2e7d32;
            --r: 12px; --rl: 16px;
            --t: all 0.3s cubic-bezier(.4,0,.2,1);
            --sh: 0 2px 8px rgba(0,0,0,.05);
            --shm: 0 8px 30px rgba(0,0,0,.08);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Montserrat', system-ui, sans-serif; background: var(--bg); color: var(--text); line-height: 1.5; min-height: 100vh; }

        /* ── Login ── */
        .login-wrap { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 24px; background: linear-gradient(135deg, #e0f7fa 0%, #fff 100%); }
        .login-card { background: rgba(255,255,255,.9); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,.6); border-radius: var(--rl); width: 100%; max-width: 420px; padding: 44px 36px; box-shadow: var(--shm); text-align: center; animation: fadeUp .45s ease-out; }
        .login-logo { max-height: 52px; margin-bottom: 24px; }
        .login-card h2 { font-size: 22px; font-weight: 700; color: var(--teal-d); margin-bottom: 6px; }
        .login-card > p { color: var(--muted); font-size: 13px; margin-bottom: 28px; }

        /* ── Form Elements (shared) ── */
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; letter-spacing: .4px; text-transform: uppercase; color: var(--muted); margin-bottom: 6px; }
        .form-group label span.req { color: var(--red); }
        .fc { width: 100%; padding: 11px 14px; font-family: inherit; font-size: 14px; border: 1px solid var(--border); border-radius: 8px; outline: none; transition: var(--t); background: #fff; }
        .fc:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(0,139,139,.12); }
        select.fc { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23647481' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /* ── Buttons ── */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; font-family: inherit; font-weight: 600; font-size: 14px; padding: 11px 22px; border-radius: 8px; border: none; cursor: pointer; transition: var(--t); text-decoration: none; white-space: nowrap; }
        .btn-full { width: 100%; }
        .btn-primary { background: var(--teal); color: #fff; }
        .btn-primary:hover { background: var(--teal-d); transform: translateY(-1px); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); font-size: 13px; }
        .btn-outline:hover { background: #f0f4f5; }
        .btn-outline.active { background-color: var(--teal); border-color: var(--teal); color: #fff; }
        .btn-danger { background: #ffebee; color: var(--red); border: 1px solid #ffcdd2; font-size: 13px; padding: 7px 14px; }
        .btn-danger:hover { background: #ffcdd2; }
        .btn-sm { padding: 6px 14px; font-size: 12px; }

        /* ── Error Box ── */
        .alert-error { background: #ffebee; color: var(--red); font-size: 13px; font-weight: 600; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; border-left: 4px solid var(--red); text-align: left; }

        /* ── Header ── */
        .header { background: #fff; border-bottom: 1px solid var(--border); padding: 14px 24px; position: sticky; top: 0; z-index: 200; box-shadow: var(--sh); }
        .header-inner { display: flex; justify-content: space-between; align-items: center; max-width: 1280px; margin: 0 auto; gap: 16px; }
        .header-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .header-logo img { height: 36px; }
        .header-logo span { font-weight: 700; color: var(--teal-d); font-size: 15px; letter-spacing: .5px; }
        .header-nav { display: flex; gap: 8px; }
        .header-actions { display: flex; gap: 10px; align-items: center; }

        /* ── Main ── */
        .main { max-width: 1280px; margin: 36px auto; padding: 0 24px 60px; }

        /* ── Stats ── */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 28px; }
        .stat { background: #fff; border: 1px solid var(--border); border-radius: var(--r); padding: 22px 24px; box-shadow: var(--sh); }
        .stat .lbl { font-size: 12px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
        .stat .val { font-size: 34px; font-weight: 800; color: var(--text); }
        .stat.s-active { border-left: 4px solid var(--green); }
        .stat.s-inactive { border-left: 4px solid var(--red); }

        /* ── Controls ── */
        .controls { background: #fff; border: 1px solid var(--border); border-radius: var(--r); padding: 18px 20px; margin-bottom: 22px; box-shadow: var(--sh); display: flex; flex-wrap: wrap; gap: 14px; align-items: center; }
        .search-wrap { position: relative; flex: 1; min-width: 260px; }
        .search-wrap svg { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; }
        .search-wrap input { width: 100%; padding: 11px 14px 11px 40px; font-family: inherit; font-size: 14px; border: 1px solid var(--border); border-radius: 8px; outline: none; transition: var(--t); }
        .search-wrap input:focus { border-color: var(--teal); }
        .filter-pills { display: flex; gap: 8px; }
        .pill { background: none; border: 1px solid var(--border); color: var(--muted); font-family: inherit; font-weight: 600; font-size: 13px; padding: 8px 18px; border-radius: 20px; cursor: pointer; transition: var(--t); }
        .pill:hover { background: #f5f8f9; }
        .pill.on { background: var(--teal); border-color: var(--teal); color: #fff; }

        /* ── Product Grid ── */
        .pgrid { display: grid; grid-template-columns: repeat(auto-fill, minmax(310px, 1fr)); gap: 18px; }
        .pcard { background: #fff; border: 1px solid var(--border); border-radius: var(--r); padding: 15px; display: flex; gap: 14px; box-shadow: var(--sh); transition: var(--t); position: relative; cursor: pointer; }
        .pcard:hover { box-shadow: 0 6px 22px rgba(0,0,0,.06); transform: translateY(-2px); }
        .pcard.inactive-card { border-color: #ffcdd2; background: #fffafa; }
        .pthumb { width: 76px; height: 76px; border-radius: 8px; background-size: cover; background-position: center; background-color: var(--bg); flex-shrink: 0; border: 1px solid var(--border); }
        .pinfo { flex: 1; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; }
        .ptitle { font-size: 13px; font-weight: 700; color: var(--text); margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .pmeta { font-size: 11px; color: var(--muted); font-weight: 500; margin-bottom: 5px; }
        .pprice { font-size: 14px; font-weight: 800; color: var(--teal-d); }
        .pactions { display: flex; align-items: center; gap: 10px; margin-top: 8px; }

        /* ── Toggle ── */
        .switch { position: relative; display: inline-block; width: 42px; height: 23px; flex-shrink: 0; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; inset: 0; background: #ccc; transition: var(--t); border-radius: 23px; }
        .slider::before { content: ''; position: absolute; height: 17px; width: 17px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: var(--t); box-shadow: 0 1px 3px rgba(0,0,0,.2); }
        input:checked + .slider { background: var(--teal); }
        input:checked + .slider::before { transform: translateX(19px); }

        /* ── Badge ── */
        .badge { font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 12px; text-transform: uppercase; letter-spacing: .5px; }
        .badge-on { background: #e8f5e9; color: var(--green); }
        .badge-off { background: #ffebee; color: var(--red); }

        /* ── Empty state ── */
        .empty { grid-column: 1/-1; background: #fff; border: 1px solid var(--border); border-radius: var(--r); padding: 60px; text-align: center; color: var(--muted); }
        .empty svg { margin-bottom: 14px; color: #b0bec5; }
        .empty h3 { color: var(--text); margin-bottom: 6px; }

        /* ── Modal ── */
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); backdrop-filter: blur(4px); z-index: 1000; justify-content: center; align-items: center; padding: 24px; }
        .modal-overlay.open { display: flex; }
        .modal { background: #fff; border-radius: var(--rl); width: 100%; max-width: 580px; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.2); animation: fadeUp .3s ease-out; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; padding: 22px 28px 0; }
        .modal-header h2 { font-size: 18px; font-weight: 700; color: var(--text); }
        .modal-close { background: none; border: none; cursor: pointer; font-size: 22px; color: var(--muted); line-height: 1; padding: 4px; border-radius: 50%; transition: var(--t); }
        .modal-close:hover { background: var(--bg); color: var(--text); }
        .modal-body { padding: 20px 28px 28px; }

        /* Image preview */
        .img-preview-wrap { display: flex; align-items: center; gap: 12px; }
        .img-preview { width: 64px; height: 64px; border-radius: 8px; background: var(--bg); border: 1px solid var(--border); object-fit: cover; display: none; }
        .img-preview.show { display: block; }

        /* Active toggle in add form */
        .toggle-row { display: flex; align-items: center; gap: 12px; }
        .toggle-row label.switch { margin: 0; }
        .toggle-row span { font-size: 14px; font-weight: 600; }

        /* ── Leads Table Styles ── */
        .leads-tbl { width: 100%; border-collapse: collapse; text-align: left; background: #fff; }
        .leads-tbl th { background: #f0f4f5; padding: 14px 18px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: var(--muted); border-bottom: 2px solid var(--border); }
        .leads-tbl td { padding: 14px 18px; font-size: 14px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .leads-tbl tr:last-child td { border-bottom: none; }
        .leads-tbl tr:hover td { background: #fcfdfe; }

        /* ── Toasts ── */
        .toast-area { position: fixed; bottom: 24px; right: 24px; z-index: 2000; display: flex; flex-direction: column; gap: 10px; }
        .toast { background: #fff; border-radius: 8px; padding: 14px 18px; box-shadow: 0 10px 30px rgba(0,0,0,.15); display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 600; min-width: 260px; border-left: 5px solid var(--teal); opacity: 0; transform: translateY(20px); animation: slideUp .3s forwards; }
        .toast.ok  { border-left-color: var(--green); }
        .toast.err { border-left-color: var(--red); }

        /* ── Animations ── */
        @keyframes fadeUp  { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }
        @keyframes slideUp { to   { opacity:1; transform: translateY(0); } }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .header-inner { flex-direction: column; gap: 14px; }
            .controls { flex-direction: column; }
            .search-wrap { min-width: unset; width: 100%; }
            .form-row { grid-template-columns: 1fr; }
            .pcard { flex-direction: column; align-items: center; text-align: center; }
            .pactions { justify-content: center; }
            .leads-tbl-card { overflow-x: auto; }
        }
    </style>
</head>
<body>

<?php if (!$is_logged_in): ?>
<!-- ════════════ LOGIN ════════════ -->
<div class="login-wrap">
    <div class="login-card">
        <img class="login-logo" src="https://gulfpharmacy.com/images/gulf-landing-logo.png" alt="Gulf Pharmacy" onerror="this.src='images/gulf-landing-logo.png';this.onerror=null">
        <h2>Dashboard Manager</h2>
        <p>Enter the admin password to continue</p>
        <?php if ($login_error): ?>
            <div class="alert-error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form method="POST" action="manage.php">
            <div class="form-group">
                <label>Admin Password</label>
                <input type="password" name="login_password" class="fc" placeholder="••••••••" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Authenticate &rarr;</button>
        </form>
    </div>
</div>

<?php else: ?>
<!-- ════════════ DASHBOARD ════════════ -->
<header class="header">
    <div class="header-inner">
        <div style="display: flex; align-items: center; gap: 16px;">
            <a href="#" class="header-logo">
                <img src="https://gulfpharmacy.com/images/gulf-landing-logo.png" alt="Gulf Pharmacy" onerror="this.src='images/gulf-landing-logo.png';this.onerror=null">
                <span>PORTAL MANAGER</span>
            </a>
            
            <!-- Module Switcher Tabs -->
            <nav class="header-nav">
                <button class="btn btn-outline btn-sm active" id="tab-products">Products</button>
                <button class="btn btn-outline btn-sm" id="tab-leads">Leads Module</button>
                <button class="btn btn-outline btn-sm" id="tab-popup">⚙️ Popup Settings</button>
            </nav>
        </div>
        
        <div class="header-actions">
            <button class="btn btn-primary btn-sm" id="open-add-modal">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Product
            </button>
            <a href="manage.php?action=logout" class="btn btn-outline">Logout</a>
        </div>
    </div>
</header>

<main class="main">

    <!-- ════════════ MODULE 1: PRODUCTS SECTION ════════════ -->
    <div id="products-section">
        <!-- Stats -->
        <div class="stats">
            <div class="stat">
                <div class="lbl">Total Products</div>
                <div class="val" id="cnt-total"><?php echo count($products); ?></div>
            </div>
            <div class="stat s-active">
                <div class="lbl">Active</div>
                <div class="val" id="cnt-active">0</div>
            </div>
            <div class="stat s-inactive">
                <div class="lbl">Inactive</div>
                <div class="val" id="cnt-inactive">0</div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls">
            <div class="search-wrap">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="search" placeholder="Search by name, code or brand…">
            </div>
            <div class="filter-pills">
                <button class="pill on" data-f="all">All</button>
                <button class="pill" data-f="active">Active</button>
                <button class="pill" data-f="inactive">Inactive</button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="pgrid" id="pgrid">
            <?php if (empty($products)): ?>
                <div class="empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="2"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    <h3>No products yet</h3>
                    <p>Click <strong>Add Product</strong> to get started.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $p):
                    $isActive = !isset($p['active']) || $p['active'] !== false;
                ?>
                <div class="pcard <?php echo $isActive ? '' : 'inactive-card'; ?>"
                     data-code="<?php echo htmlspecialchars($p['code']); ?>"
                     data-title="<?php echo htmlspecialchars($p['title']); ?>"
                     data-price="<?php echo htmlspecialchars($p['price']); ?>"
                     data-brand="<?php echo htmlspecialchars(isset($p['brand']) ? $p['brand'] : ''); ?>"
                     data-mkt_category="<?php echo htmlspecialchars(isset($p['mkt_category']) ? $p['mkt_category'] : ''); ?>"
                     data-rating="<?php echo htmlspecialchars(isset($p['rating']) ? $p['rating'] : '5'); ?>"
                     data-pop="<?php echo htmlspecialchars(isset($p['pop']) ? $p['pop'] : '100'); ?>"
                     data-image="<?php echo htmlspecialchars(isset($p['image']) ? $p['image'] : ''); ?>"
                     data-desc="<?php echo htmlspecialchars(isset($p['desc']) ? $p['desc'] : ''); ?>"
                     data-detail_url="<?php echo htmlspecialchars(isset($p['detail_url']) ? $p['detail_url'] : ''); ?>"
                     data-active="<?php echo $isActive ? 'true' : 'false'; ?>">

                    <div class="pthumb" style="background-image:url('<?php echo htmlspecialchars(isset($p['image']) ? $p['image'] : ''); ?>')"></div>

                    <div class="pinfo">
                        <div>
                            <h3 class="ptitle"><?php echo htmlspecialchars($p['title']); ?></h3>
                            <div class="pmeta">
                                Code: <?php echo htmlspecialchars($p['code']); ?> &nbsp;|&nbsp;
                                <?php echo htmlspecialchars(ucfirst(isset($p['brand']) ? $p['brand'] : '')); ?>
                            </div>
                            <div class="pprice"><?php echo htmlspecialchars($p['price']); ?> AED</div>
                        </div>
                        <div class="pactions">
                            <label class="switch">
                                <input type="checkbox" class="toggle" data-code="<?php echo htmlspecialchars($p['code']); ?>" <?php echo $isActive ? 'checked' : ''; ?>>
                                <span class="slider"></span>
                            </label>
                            <span class="badge <?php echo $isActive ? 'badge-on' : 'badge-off'; ?>">
                                <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                            </span>
                            <button class="btn btn-danger btn-sm delete-btn" data-code="<?php echo htmlspecialchars($p['code']); ?>" data-title="<?php echo htmlspecialchars($p['title']); ?>" style="margin-left:auto">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ════════════ MODULE 2: LEADS SECTION ════════════ -->
    <div id="leads-section" style="display: none;">
        <!-- Stats -->
        <div class="stats">
            <div class="stat">
                <div class="lbl">Total Leads Collected</div>
                <div class="val" id="cnt-leads-total"><?php echo count($leads); ?></div>
            </div>
            <div class="stat s-active">
                <div class="lbl">Leads Registered Today</div>
                <div class="val" id="cnt-leads-today">0</div>
            </div>
            <div class="stat s-inactive">
                <div class="lbl">Active Countries</div>
                <div class="val" id="cnt-leads-countries">0</div>
            </div>
        </div>

        <!-- Controls -->
        <div class="controls" style="display: flex; gap: 14px; align-items: center; flex-wrap: wrap;">
            <div class="search-wrap" style="flex: 1; min-width: 200px;">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="search-leads" placeholder="Search leads by name, email, country or phone…">
            </div>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase;">Date:</span>
                    <input type="date" id="filter-lead-date" class="fc" style="padding: 7px 12px; font-size: 13px; width: auto; height: 38px;">
                    <button type="button" class="btn btn-outline btn-sm" id="clear-lead-date" style="padding: 8px 12px; height: 38px;">Clear</button>
                </div>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase;">Sort:</span>
                    <select id="sort-leads" class="fc" style="padding: 7px 32px 7px 12px; font-size: 13px; width: auto; min-width: 140px; height: 38px;">
                        <option value="date_desc">Newest First</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="name_asc">Name A-Z</option>
                        <option value="name_desc">Name Z-A</option>
                    </select>
                </div>
                <button class="btn btn-outline" id="export-leads-csv" style="padding: 10px 16px; height: 38px;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Export CSV
                </button>
            </div>
        </div>

        <!-- Leads Table -->
        <div class="leads-tbl-card" style="background:#fff; border:1px solid var(--border); border-radius:var(--r); overflow:hidden; box-shadow:var(--sh);">
            <table class="leads-tbl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Country</th>
                        <th>Date Registered</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody id="leads-tbody">
                    <?php if (empty($leads)): ?>
                        <tr class="empty-leads-tr">
                            <td colspan="6" style="padding:50px; text-align:center; color:var(--muted);">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:12px; color:#b0bec5;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <h3>No leads collected yet</h3>
                                <p>When visitors fill out the popup on your landing page, they will appear here.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($leads as $l): ?>
                            <tr class="lead-row" data-id="<?php echo htmlspecialchars($l['id']); ?>" data-date-raw="<?php echo htmlspecialchars($l['date']); ?>" data-search-text="<?php echo htmlspecialchars(strtolower($l['name'] . ' ' . $l['email'] . ' ' . $l['phone'] . ' ' . (isset($l['country']) ? $l['country'] : ''))); ?>">
                                <td style="font-weight:600; color:var(--text);"><?php echo htmlspecialchars($l['name']); ?></td>
                                <td><a href="mailto:<?php echo htmlspecialchars($l['email']); ?>" style="color:var(--teal); text-decoration:none; font-weight:500;"><?php echo htmlspecialchars($l['email']); ?></a></td>
                                <td><?php echo htmlspecialchars($l['phone']); ?></td>
                                <td style="font-weight:500; color:var(--muted);"><?php echo htmlspecialchars(isset($l['country']) ? $l['country'] : 'N/A'); ?></td>
                                <td style="font-size:13px; color:var(--muted);"><?php echo htmlspecialchars(date('M d, Y h:i A', strtotime($l['date']))); ?></td>
                                <td style="text-align:right;">
                                    <button class="btn btn-danger btn-sm delete-lead-btn" data-id="<?php echo htmlspecialchars($l['id']); ?>" data-name="<?php echo htmlspecialchars($l['name']); ?>">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M9 6V4h6v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ════════════ MODULE 3: POPUP SETTINGS SECTION ════════════ -->
    <div id="popup-section" style="display: none;">
        <div style="background:#fff; border:1px solid var(--border); border-radius:var(--r); padding:32px; max-width:720px; box-shadow:var(--sh);">
            <h2 style="font-size:18px; font-weight:700; margin-bottom:6px;">Popup Settings</h2>
            <p style="color:var(--muted); font-size:13px; margin-bottom:24px;">These settings control the lead-capture popup on your landing page. Changes take effect immediately.</p>
            <div id="popup-settings-error" class="alert-error" style="display:none;"></div>
            <div class="form-group">
                <label>Popup Title <span class="req">*</span></label>
                <input type="text" id="ps-title" class="fc" value="<?php echo htmlspecialchars(isset($popup_settings['title']) ? $popup_settings['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label>Popup Subtitle / Description <span class="req">*</span></label>
                <textarea id="ps-subtitle" class="fc" rows="3" style="resize:vertical;"><?php echo htmlspecialchars(isset($popup_settings['subtitle']) ? $popup_settings['subtitle'] : ''); ?></textarea>
            </div>
            <div class="form-group">
                <label>Popup Button Label <span class="req">*</span></label>
                <input type="text" id="ps-button-text" class="fc" value="<?php echo htmlspecialchars(isset($popup_settings['button_text']) ? $popup_settings['button_text'] : 'Join Now'); ?>">
            </div>
            <div class="form-group">
                <label>"Join Now" Redirect URL <small style="font-weight:400;">(leave blank to show thank-you message)</small></label>
                <input type="url" id="ps-redirect" class="fc" placeholder="https://gulfpharmacy.com/..." value="<?php echo htmlspecialchars(isset($popup_settings['redirect_url']) ? $popup_settings['redirect_url'] : ''); ?>">
            </div>
            <button type="button" class="btn btn-primary" id="save-popup-btn">Save Popup Settings</button>
        </div>
    </div>

</main>

<!-- ════════════ ADD / EDIT PRODUCT MODAL ════════════ -->
<div class="modal-overlay" id="add-modal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
        <div class="modal-header">
            <h2 id="modal-title">Add New Product</h2>
            <button class="modal-close" id="close-modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <div id="modal-error" class="alert-error" style="display:none"></div>
            <form id="add-form" autocomplete="off">
                <input type="hidden" name="old_code" id="old-code">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Product Code <span class="req">*</span></label>
                        <input type="text" name="code" class="fc" placeholder="e.g. 10055405" required>
                    </div>
                    <div class="form-group">
                        <label>Price (AED) <span class="req">*</span></label>
                        <input type="number" name="price" class="fc" placeholder="e.g. 199.50" step="0.01" min="0" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Product Title <span class="req">*</span></label>
                    <input type="text" name="title" class="fc" placeholder="Full product name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Brand <span class="req">*</span></label>
                        <input type="text" name="brand" class="fc" placeholder="e.g. beurer, futuro" required>
                    </div>
                    <div class="form-group">
                        <label>Category <span class="req">*</span></label>
                        <select name="mkt_category" class="fc" required>
                            <option value="">-- Select --</option>
                            <option value="recovery">Post-Surgery Recovery &amp; Crutches</option>
                            <option value="mobility">Everyday Wellness</option>
                            <option value="elder_safety">Senior Protection</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Rating (1–5)</label>
                        <select name="rating" class="fc">
                            <option value="5" selected>5 ★★★★★</option>
                            <option value="4">4 ★★★★</option>
                            <option value="3">3 ★★★</option>
                            <option value="2">2 ★★</option>
                            <option value="1">1 ★</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Popularity Score</label>
                        <input type="number" name="pop" class="fc" placeholder="e.g. 150" min="0" value="100">
                    </div>
                </div>

                <div class="form-group">
                    <label>Image URL</label>
                    <div class="img-preview-wrap">
                        <input type="url" name="image" id="image-url" class="fc" placeholder="https://...s3.amazonaws.com/...">
                        <img id="img-preview" class="img-preview" src="" alt="Preview">
                    </div>
                </div>

                <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="desc" class="fc" placeholder="e.g. Home Health Care · Mobility Aids">
                </div>

                <div class="form-group">
                    <label>Detail URL on gulfpharmacy.com <span class="req">*</span></label>
                    <input type="url" name="detail_url" class="fc" placeholder="https://gulfpharmacy.com/c/home-health-care/p/..." required>
                </div>

                <div class="form-group">
                    <label>Initial Status</label>
                    <div class="toggle-row">
                        <label class="switch">
                            <input type="checkbox" name="active" id="add-active" checked>
                            <span class="slider"></span>
                        </label>
                        <span id="add-active-label">Active (will show on catalog)</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full" id="add-submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Add Product
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Toast Area -->
<div class="toast-area" id="toast-area"></div>

<script>
(function () {
    // ── Refs ──────────────────────────────────────────────────────────────────
    const pgrid        = document.getElementById('pgrid');
    const searchEl     = document.getElementById('search');
    const pills        = document.querySelectorAll('.pill');
    const toastArea    = document.getElementById('toast-area');
    const modal        = document.getElementById('add-modal');
    const addForm      = document.getElementById('add-form');
    const modalErr     = document.getElementById('modal-error');
    const addSubmit    = document.getElementById('add-submit');
    const imgPreview   = document.getElementById('img-preview');
    const addActiveEl  = document.getElementById('add-active');
    const addActiveLbl = document.getElementById('add-active-label');

    // Section Toggle Refs
    const tabProducts     = document.getElementById('tab-products');
    const tabLeads        = document.getElementById('tab-leads');
    const tabPopup        = document.getElementById('tab-popup');
    const productsSection = document.getElementById('products-section');
    const leadsSection    = document.getElementById('leads-section');
    const popupSection    = document.getElementById('popup-section');
    const openAddModalBtn = document.getElementById('open-add-modal');

    let activeFilter = 'all';

    // ── Navigation Switcher ───────────────────────────────────────────────────
    if (tabProducts && tabLeads && tabPopup) {
        tabProducts.addEventListener('click', () => {
            tabProducts.classList.add('active');
            tabLeads.classList.remove('active');
            tabPopup.classList.remove('active');
            productsSection.style.display = 'block';
            leadsSection.style.display = 'none';
            popupSection.style.display = 'none';
            openAddModalBtn.style.display = 'inline-flex';
        });

        tabLeads.addEventListener('click', () => {
            tabLeads.classList.add('active');
            tabProducts.classList.remove('active');
            tabPopup.classList.remove('active');
            productsSection.style.display = 'none';
            leadsSection.style.display = 'block';
            popupSection.style.display = 'none';
            openAddModalBtn.style.display = 'none';
            refreshLeadsCounters();
        });

        tabPopup.addEventListener('click', () => {
            tabPopup.classList.add('active');
            tabProducts.classList.remove('active');
            tabLeads.classList.remove('active');
            productsSection.style.display = 'none';
            leadsSection.style.display = 'none';
            popupSection.style.display = 'block';
            openAddModalBtn.style.display = 'none';
        });
    }

    // ── Counters ──────────────────────────────────────────────────────────────
    function refreshCounters() {
        const cards = pgrid.querySelectorAll('.pcard');
        let active = 0, inactive = 0;
        cards.forEach(c => { c.dataset.active === 'true' ? active++ : inactive++; });
        document.getElementById('cnt-total').textContent = cards.length;
        document.getElementById('cnt-active').textContent = active;
        document.getElementById('cnt-inactive').textContent = inactive;
    }
    refreshCounters();

    function refreshLeadsCounters() {
        const rows = document.querySelectorAll('.lead-row');
        const todayStr = new Date().toISOString().slice(0, 10); // "YYYY-MM-DD"
        let countToday = 0;
        const countries = new Set();

        rows.forEach(r => {
            const dateRaw = r.dataset.dateRaw || '';
            if (dateRaw.indexOf(todayStr) === 0) {
                countToday++;
            }
            const tds = r.querySelectorAll('td');
            if (tds.length >= 4) {
                const countryVal = tds[3].textContent.trim();
                if (countryVal && countryVal !== 'N/A') {
                    countries.add(countryVal);
                }
            }
        });

        document.getElementById('cnt-leads-total').textContent = rows.length;
        document.getElementById('cnt-leads-today').textContent = countToday;
        document.getElementById('cnt-leads-countries').textContent = countries.size;
    }
    refreshLeadsCounters();

    // ── Filter & Search: Products ─────────────────────────────────────────────
    function applyFilter() {
        const q = searchEl.value.trim().toLowerCase();
        let visible = 0;
        pgrid.querySelectorAll('.pcard').forEach(c => {
            const titleText = c.dataset.title.toLowerCase();
            const brandText = c.dataset.brand.toLowerCase();
            const codeText = c.dataset.code;
            const matchQ = !q || titleText.includes(q) || codeText.includes(q) || brandText.includes(q);
            const isActive = c.dataset.active === 'true';
            const matchF = activeFilter === 'all' || (activeFilter === 'active' && isActive) || (activeFilter === 'inactive' && !isActive);
            const show = matchQ && matchF;
            c.style.display = show ? 'flex' : 'none';
            if (show) visible++;
        });

        let noRes = document.getElementById('no-results');
        if (visible === 0 && !pgrid.querySelector('.empty')) {
            if (!noRes) {
                noRes = document.createElement('div');
                noRes.id = 'no-results';
                noRes.className = 'empty';
                noRes.innerHTML = '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg><h3>No products found</h3><p>Try a different search or filter.</p>';
                pgrid.appendChild(noRes);
            }
        } else if (noRes) noRes.remove();
    }

    searchEl.addEventListener('input', applyFilter);
    pills.forEach(p => {
        p.addEventListener('click', () => {
            pills.forEach(x => x.classList.remove('on'));
            p.classList.add('on');
            activeFilter = p.dataset.f;
            applyFilter();
        });
    });

    // ── Search & Filter & Sort: Leads ─────────────────────────────────────────
    const searchLeadsEl    = document.getElementById('search-leads');
    const filterLeadDateEl = document.getElementById('filter-lead-date');
    const clearLeadDateBtn = document.getElementById('clear-lead-date');
    const sortLeadsEl      = document.getElementById('sort-leads');
    const leadsTbody       = document.getElementById('leads-tbody');

    function applyLeadsFilterAndSort() {
        if (!leadsTbody) return;
        const q = searchLeadsEl ? searchLeadsEl.value.trim().toLowerCase() : '';
        const targetDate = filterLeadDateEl ? filterLeadDateEl.value : ''; // "YYYY-MM-DD"
        const rows = Array.from(leadsTbody.querySelectorAll('.lead-row'));

        let visibleCount = 0;
        rows.forEach(r => {
            const matchText = !q || r.dataset.searchText.includes(q);
            
            // dateRaw matches "YYYY-MM-DD HH:MM:SS"
            const dateRaw = r.dataset.dateRaw || '';
            const matchDate = !targetDate || dateRaw.indexOf(targetDate) === 0;

            const show = matchText && matchDate;
            r.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Toggle empty search results row
        let emptyRow = leadsTbody.querySelector('#no-leads-match');
        if (visibleCount === 0 && rows.length > 0) {
            if (!emptyRow) {
                emptyRow = document.createElement('tr');
                emptyRow.id = 'no-leads-match';
                emptyRow.innerHTML = `<td colspan="6" style="padding:40px; text-align:center; color:var(--muted);">
                    <h3>No matching leads found</h3>
                    <p>Try clearing your search query or date filter.</p>
                </td>`;
                leadsTbody.appendChild(emptyRow);
            } else {
                emptyRow.style.display = '';
            }
        } else if (emptyRow) {
            emptyRow.remove();
        }

        // Sort leads
        const sortVal = sortLeadsEl ? sortLeadsEl.value : 'date_desc';
        rows.sort((a, b) => {
            if (sortVal === 'date_desc') {
                return new Date(b.dataset.dateRaw.replace(/-/g, '/')) - new Date(a.dataset.dateRaw.replace(/-/g, '/'));
            }
            if (sortVal === 'date_asc') {
                return new Date(a.dataset.dateRaw.replace(/-/g, '/')) - new Date(b.dataset.dateRaw.replace(/-/g, '/'));
            }
            if (sortVal === 'name_asc') {
                const nameA = a.cells[0].textContent.toLowerCase();
                const nameB = b.cells[0].textContent.toLowerCase();
                return nameA.localeCompare(nameB);
            }
            if (sortVal === 'name_desc') {
                const nameA = a.cells[0].textContent.toLowerCase();
                const nameB = b.cells[0].textContent.toLowerCase();
                return nameB.localeCompare(nameA);
            }
            return 0;
        });

        rows.forEach(r => leadsTbody.appendChild(r));
    }

    if (searchLeadsEl) searchLeadsEl.addEventListener('input', applyLeadsFilterAndSort);
    if (filterLeadDateEl) filterLeadDateEl.addEventListener('change', applyLeadsFilterAndSort);
    if (clearLeadDateBtn) {
        clearLeadDateBtn.addEventListener('click', () => {
            filterLeadDateEl.value = '';
            applyLeadsFilterAndSort();
        });
    }
    if (sortLeadsEl) sortLeadsEl.addEventListener('change', applyLeadsFilterAndSort);
    
    // Run initial sorting descending
    applyLeadsFilterAndSort();

    // ── Export Leads CSV ──────────────────────────────────────────────────────
    const exportBtn = document.getElementById('export-leads-csv');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            const rows = Array.from(document.querySelectorAll('.lead-row'));
            if (rows.length === 0) {
                toast('No leads to export!', 'err');
                return;
            }
            
            let csv = 'Name,Email,Phone,Country,Date Joined\n';
            let countExported = 0;
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                const cells = Array.from(row.querySelectorAll('td'));
                const name = cells[0].textContent.replace(/"/g, '""');
                const email = cells[1].textContent.replace(/"/g, '""');
                const phone = cells[2].textContent.replace(/"/g, '""');
                const country = cells[3].textContent.replace(/"/g, '""');
                const date = cells[4].textContent.replace(/"/g, '""');
                csv += `"${name}","${email}","${phone}","${country}","${date}"\n`;
                countExported++;
            });

            if (countExported === 0) {
                toast('No matching leads found for current search.', 'err');
                return;
            }

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', `gulf_pharmacy_leads_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }

    // ── Toast ─────────────────────────────────────────────────────────────────
    function toast(msg, type = 'ok') {
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = type === 'ok'
            ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>${msg}</span>`
            : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d32f2f" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>${msg}</span>`;
        toastArea.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(10px)'; setTimeout(() => t.remove(), 300); }, 3500);
    }

    // ── Toggle Status ─────────────────────────────────────────────────────────
    function bindToggle(toggle) {
        toggle.addEventListener('change', async (e) => {
            e.stopPropagation();
            const code  = toggle.dataset.code;
            const state = toggle.checked;
            const card  = pgrid.querySelector(`.pcard[data-code="${code}"]`);
            const badge = card.querySelector('.badge');

            // optimistic UI
            card.dataset.active = state ? 'true' : 'false';
            card.classList.toggle('inactive-card', !state);
            badge.className = 'badge ' + (state ? 'badge-on' : 'badge-off');
            badge.textContent = state ? 'Active' : 'Inactive';
            refreshCounters();
            applyFilter();

            try {
                const res  = await fetch('manage.php?action=toggle', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ code, active: state }) });
                const data = await res.json();
                if (!data.success) throw new Error(data.message);
                const ptitle = card.querySelector('.ptitle').textContent;
                toast(`"${ptitle}" is now ${state ? 'active ✅' : 'inactive 🔴'}`);
            } catch (e) {
                // revert
                toggle.checked = !state;
                card.dataset.active = !state ? 'true' : 'false';
                card.classList.toggle('inactive-card', state);
                badge.className = 'badge ' + (!state ? 'badge-on' : 'badge-off');
                badge.textContent = !state ? 'Active' : 'Inactive';
                refreshCounters(); applyFilter();
                toast(e.message || 'Error — please try again.', 'err');
            }
        });
    }
    pgrid.querySelectorAll('.toggle').forEach(bindToggle);

    // ── Delete Product ────────────────────────────────────────────────────────
    function bindDelete(btn) {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const code  = btn.dataset.code;
            const title = btn.dataset.title;
            if (!confirm(`Delete "${title}"?\n\nThis cannot be undone!`)) return;
            try {
                const res  = await fetch('manage.php?action=delete', { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify({ code }) });
                const data = await res.json();
                if (!data.success) throw new Error(data.message);
                const card = pgrid.querySelector(`.pcard[data-code="${code}"]`);
                card.style.transition = 'opacity .3s, transform .3s';
                card.style.opacity = '0'; card.style.transform = 'scale(.95)';
                setTimeout(() => { card.remove(); refreshCounters(); applyFilter(); }, 300);
                toast(`"${title}" deleted.`);
            } catch (e) {
                toast(e.message || 'Delete failed.', 'err');
            }
        });
    }
    pgrid.querySelectorAll('.delete-btn').forEach(bindDelete);

    // ── Delete Lead ───────────────────────────────────────────────────────────
    function bindDeleteLead(btn) {
        btn.addEventListener('click', async (e) => {
            e.stopPropagation();
            const id = btn.dataset.id;
            const name = btn.dataset.name;
            if (!confirm(`Delete lead from "${name}"?\n\nThis cannot be undone!`)) return;
            try {
                const res = await fetch('manage.php?action=delete_lead', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({ id })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.message);
                const row = leadsTbody.querySelector(`.lead-row[data-id="${id}"]`);
                if (row) {
                    row.remove();
                    refreshLeadsCounters();
                }
                toast(`Lead from "${name}" deleted.`);
            } catch (err) {
                toast(err.message || 'Failed to delete lead.', 'err');
            }
        });
    }
    document.querySelectorAll('.delete-lead-btn').forEach(bindDeleteLead);

    // ── Card Click (Edit Product) ─────────────────────────────────────────────
    function bindCardClick(card) {
        card.addEventListener('click', (e) => {
            // Prevent triggering modal when interactive elements are clicked
            if (e.target.closest('.switch') || e.target.closest('.delete-btn') || e.target.closest('input') || e.target.closest('button')) {
                return;
            }

            modalErr.style.display = 'none';
            document.getElementById('modal-title').textContent = 'Edit Product Details';
            
            // Populate form fields
            document.getElementById('old-code').value = card.dataset.code;
            addForm.elements['code'].value = card.dataset.code;
            addForm.elements['price'].value = card.dataset.price;
            addForm.elements['title'].value = card.dataset.title;
            addForm.elements['brand'].value = card.dataset.brand;
            addForm.elements['mkt_category'].value = card.dataset.mkt_category;
            addForm.elements['rating'].value = card.dataset.rating;
            addForm.elements['pop'].value = card.dataset.pop;
            addForm.elements['image'].value = card.dataset.image;
            addForm.elements['desc'].value = card.dataset.desc;
            addForm.elements['detail_url'].value = card.dataset.detail_url;

            const isActive = card.dataset.active === 'true';
            addActiveEl.checked = isActive;
            addActiveLbl.textContent = isActive ? 'Active (will show on catalog)' : 'Inactive (hidden from catalog)';

            if (card.dataset.image) {
                imgPreview.src = card.dataset.image;
                imgPreview.classList.add('show');
            } else {
                imgPreview.classList.remove('show');
            }

            addSubmit.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Save Changes';
            modal.classList.add('open');
        });
    }
    pgrid.querySelectorAll('.pcard').forEach(bindCardClick);

    // ── Add Modal Open/Close ──────────────────────────────────────────────────
    document.getElementById('open-add-modal').addEventListener('click', () => {
        addForm.reset();
        document.getElementById('old-code').value = '';
        document.getElementById('modal-title').textContent = 'Add New Product';
        imgPreview.classList.remove('show');
        modalErr.style.display = 'none';
        addActiveEl.checked = true;
        addActiveLbl.textContent = 'Active (will show on catalog)';
        addSubmit.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Product';
        modal.classList.add('open');
    });

    function closeModal() { modal.classList.remove('open'); }
    document.getElementById('close-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

    // ── Image URL Preview ─────────────────────────────────────────────────────
    const imgInput   = document.getElementById('image-url');
    imgInput.addEventListener('blur', () => {
        const url = imgInput.value.trim();
        if (url) { imgPreview.src = url; imgPreview.classList.add('show'); }
        else imgPreview.classList.remove('show');
    });

    // ── Active label ──────────────────────────────────────────────────────────
    addActiveEl.addEventListener('change', () => {
        addActiveLbl.textContent = addActiveEl.checked ? 'Active (will show on catalog)' : 'Inactive (hidden from catalog)';
    });

    // ── Save Popup Settings Form Submit ───────────────────────────────────────
    const savePopupBtn = document.getElementById('save-popup-btn');
    if (savePopupBtn) {
        savePopupBtn.addEventListener('click', async () => {
            const title      = document.getElementById('ps-title').value.trim();
            const subtitle   = document.getElementById('ps-subtitle').value.trim();
            const buttonText = document.getElementById('ps-button-text').value.trim();
            const redirect   = document.getElementById('ps-redirect').value.trim();
            const errEl      = document.getElementById('popup-settings-error');
            errEl.style.display = 'none';

            if (!title || !subtitle || !buttonText) {
                errEl.textContent = 'Title, subtitle and button label are required.';
                errEl.style.display = 'block';
                return;
            }

            savePopupBtn.disabled = true;
            savePopupBtn.textContent = 'Saving…';

            try {
                const res  = await fetch('manage.php?action=save_popup', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({ title, subtitle, button_text: buttonText, redirect_url: redirect })
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.message);
                toast('Popup settings saved! ✅');
            } catch(e) {
                errEl.textContent = e.message || 'Save failed.';
                errEl.style.display = 'block';
            }
            savePopupBtn.disabled = false;
            savePopupBtn.textContent = 'Save Popup Settings';
        });
    }

    // ── Form Submit (Add or Edit Product) ─────────────────────────────────────
    addForm.addEventListener('submit', async e => {
        e.preventDefault();
        modalErr.style.display = 'none';
        
        const oldCode = document.getElementById('old-code').value;
        const isEditing = oldCode !== '';

        addSubmit.disabled = true;
        addSubmit.textContent = 'Saving…';

        const fd = new FormData(addForm);
        const payload = {
            old_code:     oldCode,
            code:         fd.get('code'),
            title:        fd.get('title'),
            price:        fd.get('price'),
            brand:        fd.get('brand'),
            mkt_category: fd.get('mkt_category'),
            rating:       fd.get('rating'),
            pop:          fd.get('pop'),
            image:        fd.get('image'),
            desc:         fd.get('desc'),
            detail_url:   fd.get('detail_url'),
            active:       addActiveEl.checked,
        };

        const actionUrl = isEditing ? 'manage.php?action=edit' : 'manage.php?action=add';

        try {
            const res  = await fetch(actionUrl, { method: 'POST', headers: {'Content-Type':'application/json'}, body: JSON.stringify(payload) });
            const data = await res.json();
            if (!data.success) throw new Error(data.message);

            const p = data.product;
            const isActive = p.active !== false;

            if (isEditing) {
                // Update card attributes and layout dynamically
                const card = pgrid.querySelector(`.pcard[data-code="${oldCode}"]`);
                if (card) {
                    card.dataset.code   = p.code;
                    card.dataset.title  = p.title;
                    card.dataset.price  = p.price;
                    card.dataset.brand  = p.brand;
                    card.dataset.mkt_category = p.mkt_category;
                    card.dataset.rating = p.rating;
                    card.dataset.pop    = p.pop;
                    card.dataset.image  = p.image;
                    card.dataset.desc   = p.desc;
                    card.dataset.detail_url = p.detail_url;
                    card.dataset.active = isActive ? 'true' : 'false';
                    card.className = 'pcard ' + (isActive ? '' : 'inactive-card');

                    card.querySelector('.pthumb').style.backgroundImage = `url('${p.image}')`;
                    card.querySelector('.ptitle').textContent = p.title;
                    card.querySelector('.pmeta').innerHTML = `Code: ${p.code} &nbsp;|&nbsp; ${p.brand.charAt(0).toUpperCase() + p.brand.slice(1)}`;
                    card.querySelector('.pprice').textContent = `${p.price} AED`;

                    const toggleInput = card.querySelector('.toggle');
                    toggleInput.dataset.code = p.code;
                    toggleInput.checked = isActive;

                    const badge = card.querySelector('.badge');
                    badge.className = 'badge ' + (isActive ? 'badge-on' : 'badge-off');
                    badge.textContent = isActive ? 'Active' : 'Inactive';

                    const delBtn = card.querySelector('.delete-btn');
                    delBtn.dataset.code = p.code;
                    delBtn.dataset.title = p.title;
                }
                toast(`"${p.title}" updated successfully! ✏️`);
            } else {
                // Build and insert new card
                const card = document.createElement('div');
                card.className = 'pcard ' + (isActive ? '' : 'inactive-card');
                card.dataset.code   = p.code;
                card.dataset.title  = p.title;
                card.dataset.price  = p.price;
                card.dataset.brand  = p.brand;
                card.dataset.mkt_category = p.mkt_category;
                card.dataset.rating = p.rating;
                card.dataset.pop    = p.pop;
                card.dataset.image  = p.image;
                card.dataset.desc   = p.desc;
                card.dataset.detail_url = p.detail_url;
                card.dataset.active = isActive ? 'true' : 'false';
                card.style.opacity  = '0';
                card.innerHTML = `
                    <div class="pthumb" style="background-image:url('${p.image}')"></div>
                    <div class="pinfo">
                        <div>
                            <h3 class="ptitle">${p.title}</h3>
                            <div class="pmeta">Code: ${p.code} &nbsp;|&nbsp; ${p.brand.charAt(0).toUpperCase()+p.brand.slice(1)}</div>
                            <div class="pprice">${p.price} AED</div>
                        </div>
                        <div class="pactions">
                            <label class="switch">
                                <input type="checkbox" class="toggle" data-code="${p.code}" ${isActive ? 'checked' : ''}>
                                <span class="slider"></span>
                            </label>
                            <span class="badge ${isActive ? 'badge-on' : 'badge-off'}">${isActive ? 'Active' : 'Inactive'}</span>
                            <button class="btn btn-danger btn-sm delete-btn" data-code="${p.code}" data-title="${p.title}" style="margin-left:auto">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                            </button>
                        </div>
                    </div>`;

                // Remove empty-state if present
                const empty = pgrid.querySelector('.empty');
                if (empty) empty.remove();

                pgrid.appendChild(card);
                requestAnimationFrame(() => { card.style.transition = 'opacity .4s'; card.style.opacity = '1'; });
                bindToggle(card.querySelector('.toggle'));
                bindDelete(card.querySelector('.delete-btn'));
                bindCardClick(card);

                toast(`"${p.title}" added successfully! 🎉`);
            }

            refreshCounters();
            closeModal();
        } catch (err) {
            modalErr.textContent = err.message || 'Failed to save product.';
            modalErr.style.display = 'block';
        }

        addSubmit.disabled = false;
        if (isEditing) {
            addSubmit.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Save Changes';
        } else {
            addSubmit.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Product';
        }
    });
})();
</script>
<?php endif; ?>
</body>
</html>
