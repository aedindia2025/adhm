<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "4/rb5sO2s3TpL4gu";
$db   = "adi_dravidar";

/** Connect */
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

/** Utilities */
function genUniqueId() {
    return bin2hex(random_bytes(9)); // 18 hex chars like 68e4ce6eb443582227
}

function callApi($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    if (curl_errno($ch)) {
        throw new Exception("cURL Error: " . curl_error($ch));
    }
    curl_close($ch);
    return json_decode($res, true);
}

/** Normalization: trim, collapse spaces, lowercase */
function normalizeKey($str) {
    if ($str === null) return '';
    // replace all whitespace sequences (tabs/newlines/spaces) with single space
    $s = preg_replace('/\s+/u', ' ', $str);
    $s = trim($s);
    $s = mb_strtolower($s, 'UTF-8');
    return $s;
}

/** Load existing categories and items into maps to ensure consistent dedupe */
$categoryMap = []; // normalized_category => unique_id
$itemMap     = []; // normalized_item => unique_id

// load categories
$qr = $conn->query("SELECT unique_id, item_category FROM item_category");
if ($qr) {
    while ($r = $qr->fetch_assoc()) {
        $key = normalizeKey($r['item_category']);
        if ($key !== '') $categoryMap[$key] = $r['unique_id'];
    }
    $qr->free();
}

// load items
$qr = $conn->query("SELECT unique_id, item FROM item");
if ($qr) {
    while ($r = $qr->fetch_assoc()) {
        $key = normalizeKey($r['item']);
        if ($key !== '') $itemMap[$key] = $r['unique_id'];
    }
    $qr->free();
}

/** Insert store if not exists (check by code) */
function insertStoreIfNotExists($conn, $store) {
    $code = $store['code'] ?? null;
    if (!$code) return null;

    $check = $conn->prepare("SELECT unique_id FROM stores WHERE code = ? LIMIT 1");
    $check->bind_param("s", $code);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        echo "✅ Store exists (skipped): {$code}\n";
        return $code;
    }
    $check->close();

    $uid = genUniqueId();
    $stmt = $conn->prepare("
        INSERT INTO stores (unique_id, name, code, store, district, manager, address, contact, location, brand, type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $name = $store['name'] ?? null;
    $storeVal = $store['store'] ?? null;
    $district = $store['district'] ?? null;
    $manager = $store['manager'] ?? null;
    $address = $store['address'] ?? null;
    $contact = $store['contact'] ?? null;
    $location = $store['location'] ?? null;
    $brand = $store['brand'] ?? null;
    $type = $store['type'] ?? null;

    $stmt->bind_param("sssssssssss", $uid, $name, $code, $storeVal, $district, $manager, $address, $contact, $location, $brand, $type);
    if (!$stmt->execute()) {
        echo "❌ Failed to insert store {$code}: " . $stmt->error . "\n";
    } else {
        echo "✅ Inserted store: {$code}\n";
    }
    $stmt->close();
    return $code;
}

/** Get or create category using in-memory map and DB */
function getOrCreateCategory($conn, &$categoryMap, $categoryName, $description = null) {
    $norm = normalizeKey($categoryName);
    if ($norm === '') {
        return null;
    }
    if (isset($categoryMap[$norm])) {
        return $categoryMap[$norm];
    }
    // insert
    $uid = genUniqueId();
    $ins = $conn->prepare("INSERT INTO item_category (unique_id, item_category, description) VALUES (?, ?, ?)");
    $ins->bind_param("sss", $uid, $categoryName, $description);
    if (!$ins->execute()) {
        echo "❌ Failed to insert category '{$categoryName}': " . $ins->error . "\n";
        $ins->close();
        return null;
    }
    $ins->close();
    $categoryMap[$norm] = $uid;
    echo "✅ Inserted category: {$categoryName}\n";
    return $uid;
}

/** Get or create item using in-memory map and DB (items are global) */
function getOrCreateItem($conn, &$itemMap, $category_id, $categoryName, $itemName, $unit = null, $is_veg = 0) {
    $norm = normalizeKey($itemName);
    if ($norm === '') {
        return null;
    }
    if (isset($itemMap[$norm])) {
        return $itemMap[$norm];
    }

    // not found -> insert
    $uid = genUniqueId();
    $ins = $conn->prepare("INSERT INTO item (unique_id, category_id, category, item, unit, is_veg) VALUES (?, ?, ?, ?, ?, ?)");
    $isVegInt = (int)$is_veg;
    $ins->bind_param("ssssss", $uid, $category_id, $categoryName, $itemName, $unit, $isVegInt);
    if (!$ins->execute()) {
        echo "❌ Failed to insert item '{$itemName}': " . $ins->error . "\n";
        $ins->close();
        return null;
    }
    $ins->close();
    $itemMap[$norm] = $uid;
    echo "✅ Inserted item: {$itemName}\n";
    return $uid;
}

/** Insert item_rate if not exists for item_unique_id + store_code */
function insertItemRateIfNotExists($conn, $item_unique_id, $itemName, $rate, $unit, $discount, $pack, $gst, $store_code) {
    if (!$item_unique_id || !$store_code) return;

    $check = $conn->prepare("SELECT unique_id FROM item_rate WHERE item_unique_id = ? AND store_code = ? LIMIT 1");
    $check->bind_param("ss", $item_unique_id, $store_code);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $check->close();
        echo "✅ item_rate exists (skipped) for item_uid={$item_unique_id} store={$store_code}\n";
        return;
    }
    $check->close();

    $uid = genUniqueId();
    $ins = $conn->prepare("INSERT INTO item_rate (unique_id, item, item_unique_id, rate, unit, discount, pack, gst, store_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $ins->bind_param("sssssssss", $uid, $itemName, $item_unique_id, $rate, $unit, $discount, $pack, $gst, $store_code);
    if (!$ins->execute()) {
        echo "❌ Failed to insert item_rate for item_uid={$item_unique_id} store={$store_code}: " . $ins->error . "\n";
    } else {
        echo "✅ Inserted item_rate: item_uid={$item_unique_id} store={$store_code}\n";
    }
    $ins->close();
}

// ------------------- Main workflow -------------------

try {
    $storeApiUrl = "https://santhai.kooturavu.tn.gov.in/api/v1/DSHR/stores?Authorization=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MywicF9pZCI6MiwibmFtZSI6IkluZGVudCBDcmVhdGlvbiIsInVzZXJuYW1lIjoiaW5kZW50QGNoZW5uYWkiLCJyb2xlIjoiaW5kZW50IiwiZGV0YWlsIjp7ImJyYW5jaF90eXBlIjoiQ2hpbGQgQ2FyZSIsImJyYW5jaCI6IkNoZW5uYWkiLCJsb2NhdGlvbiI6Ikd1aW5keSIsImNvbnRhY3QiOiIxMjM0NTYiLCJzdG9yZSI6IlRSUiIsIm1vYmlsZSI6Ijg4NzA0MzY1MzAiLCJkZXNpZ25hdGlvbiI6IlJhbW5hZGFuIE0ifSwiaWF0IjoxNzU4NzE3MDcwfQ.JhDZJCCPotFsqOLFwEDJV1zTN90xoJc-zOKuvn-9Mm4";

    echo "🔎 Fetching stores...\n";
    $storeResponse = callApi($storeApiUrl);
    if (!$storeResponse || !isset($storeResponse['error']) || $storeResponse['error'] !== false) {
        throw new Exception("Failed to fetch stores from API.");
    }

    $stores = $storeResponse['data'] ?? [];
    echo "Found " . count($stores) . " stores.\n\n";

    // Insert stores (non-duplicating)
    foreach ($stores as $store) {
        insertStoreIfNotExists($conn, $store);
    }

    // For each store, fetch items and import categories/items/item_rate
    foreach ($stores as $store) {
        $code = $store['code'] ?? null;
        if (!$code) continue;

        echo "\n📦 Processing store: {$code}\n";
        $itemsApiUrl = "https://santhai.kooturavu.tn.gov.in/api/v1/DSHR/items/{$code}?Authorization=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6MywicF9pZCI6MiwibmFtZSI6IkluZGVudCBDcmVhdGlvbiIsInVzZXJuYW1lIjoiaW5kZW50QGNoZW5uYWkiLCJyb2xlIjoiaW5kZW50IiwiZGV0YWlsIjp7ImJyYW5jaF90eXBlIjoiQ2hpbGQgQ2FyZSIsImJyYW5jaCI6IkNoZW5uYWkiLCJsb2NhdGlvbiI6Ikd1aW5keSIsImNvbnRhY3QiOiIxMjM0NTYiLCJzdG9yZSI6IlRSUiIsIm1vYmlsZSI6Ijg4NzA0MzY1MzAiLCJkZXNpZ25hdGlvbiI6IlJhbW5hZGFuIE0ifSwiaWF0IjoxNzU4NzE3MDcwfQ.JhDZJCCPotFsqOLFwEDJV1zTN90xoJc-zOKuvn-9Mm4";
        try {
            $itemsResponse = callApi($itemsApiUrl);
        } catch (Exception $e) {
            echo "⚠️ Failed to fetch items for store {$code}: " . $e->getMessage() . "\n";
            continue;
        }

        if (!$itemsResponse || !isset($itemsResponse['error']) || $itemsResponse['error'] !== false) {
            echo "⚠️ Items API returned error for store {$code}\n";
            continue;
        }

        $items = $itemsResponse['data'] ?? [];
        echo "  -> " . count($items) . " items fetched for {$code}\n";

        foreach ($items as $it) {
            $categoryName = $it['category'] ?? ($it['cat'] ?? 'Unknown');
            $categoryDesc = $it['category_description'] ?? null;
            $itemName = $it['name'] ?? ($it['item'] ?? null);
            $unit = $it['unit'] ?? null;
            $rate = isset($it['rate']) ? (string)$it['rate'] : null;
            $discount = isset($it['discount']) ? (string)$it['discount'] : null;
            $pack = isset($it['pack']) ? (string)$it['pack'] : null;
            $gst = isset($it['gst']) ? (string)$it['gst'] : null;
            $is_veg = isset($it['is_veg']) ? (int)$it['is_veg'] : 0;

            // category
            $category_id = getOrCreateCategory($conn, $categoryMap, $categoryName, $categoryDesc);

            // item (global) - will NOT insert if normalized item exists
            $item_uid = getOrCreateItem($conn, $itemMap, $category_id, $categoryName, $itemName, $unit, $is_veg);

            // item_rate per store (unique by item_unique_id + store_code)
            insertItemRateIfNotExists($conn, $item_uid, $itemName, $rate, $unit, $discount, $pack, $gst, $code);
        }
    }

    echo "\n✅ Import finished. No duplicate items should be present now.\n\n";

} catch (Exception $ex) {
    echo "Fatal error: " . $ex->getMessage() . "\n";
} finally {
    $conn->close();
}
