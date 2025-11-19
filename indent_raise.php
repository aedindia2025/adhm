<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(600);

require_once("config/dbconfig.php");
require_once("config/common_fun.php");

try {
    $pdo = new PDO("mysql:host=localhost;dbname=adi_dravidar", "root", "4/rb5sO2s3TpL4gu");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h3 style='color:green;'>✅ Database Connected</h3>";
} catch (PDOException $e) {
    die("<pre style='color:red;'>❌ DB Connection failed: " . $e->getMessage() . "</pre>");
}

$month_year = date('Y-m');
$totalDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$prev_month = date('Y-m', strtotime('-1 month'));

/* ---------------------------------------------------
    🎯 Step 1: Fetch Holidays
--------------------------------------------------- */
$holidayStmt = $pdo->prepare("
    SELECT holiday_date 
    FROM holiday_master 
    WHERE is_delete = 0 
    AND DATE_FORMAT(holiday_date, '%Y-%m') = ?
");
$holidayStmt->execute([$month_year]);
$holidayDates = $holidayStmt->fetchAll(PDO::FETCH_COLUMN);
$holidayCount = count($holidayDates);
$effectiveDays = $totalDays - $holidayCount;

/* ---------------------------------------------------
    🎯 Step 2: Fetch Buffer Stock Configurations
--------------------------------------------------- */
$bufferStockStmt = $pdo->query("SELECT category, days FROM master_buffer_stock WHERE is_delete = 0");
$bufferStockList = $bufferStockStmt->fetchAll(PDO::FETCH_KEY_PAIR);

/* ---------------------------------------------------
    🎯 Step 3: Fetch Active Hostels
--------------------------------------------------- */
$hostelStmt = $pdo->query("
    SELECT unique_id, hostel_id, hostel_type, district_name AS district_id, taluk_name AS taluk_id, store_id
    FROM hostel_name
    WHERE is_delete = 0
");
//  AND hostel_id = 'TNADW17001'
$hostels = $hostelStmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($hostels)) {
    die("<pre style='color:red;'>❌ No active hostels found.</pre>");
}
echo "<h2>🏫 Found " . count($hostels) . " active hostels.</h2>";

/* ---------------------------------------------------
    🎯 Step 4: Prepare Reusable Queries
--------------------------------------------------- */
$getFinalCountStmt = $pdo->prepare("SELECT final_count FROM indent_count WHERE hostel_id = ? AND month_year = ? ORDER BY id DESC LIMIT 1");
$getDietStmt = $pdo->prepare("SELECT item, category, quantity, unit FROM master_diet_chart_sublist WHERE hostel_type = ? AND is_delete = 0");
$getSpecialMenuStmt = $pdo->prepare("
    SELECT category, item, quantity, unit, DATE(date) AS special_date
    FROM special_menu_chart_sub
    WHERE is_delete = 0 AND DATE_FORMAT(date, '%Y-%m') = ?
");
$checkDuplicateStmt = $pdo->prepare("
    SELECT COUNT(*) AS cnt FROM monthly_indent_master WHERE hostel_id = ? AND month_year = ? AND is_delete = 0
");

/* ---------------------------------------------------
    🧾 For Display Summary
--------------------------------------------------- */
$summary = [];

/* ---------------------------------------------------
    🔁 Step 5: Loop Through Each Hostel
--------------------------------------------------- */
foreach ($hostels as $hostel) {

    $checkDuplicateStmt->execute([$hostel['unique_id'], $month_year]);
    $dup = $checkDuplicateStmt->fetch(PDO::FETCH_ASSOC);
    if ($dup['cnt'] > 0) {
        echo "<p style='color:red;'>⚠️ Skipped: {$hostel['hostel_id']} already has indent for {$month_year}.</p>";
        continue;
    }

    $screen_unique_id = unique_id();

    /* 🎯 Get Final Count */
    $getFinalCountStmt->execute([$hostel['hostel_id'], $month_year]);
    $fcRow = $getFinalCountStmt->fetch(PDO::FETCH_ASSOC);
    $final_count = ($fcRow && is_numeric($fcRow['final_count'])) ? (int) $fcRow['final_count'] : 1;

    /* 🎯 Get Diet Chart */
    $getDietStmt->execute([$hostel['hostel_type']]);
    $dietItems = $getDietStmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($dietItems))
        continue;

    /* 🎯 Load Special Menus for month (we will aggregate separately) */
    $getSpecialMenuStmt->execute([$month_year]);
    $specialMenus = $getSpecialMenuStmt->fetchAll(PDO::FETCH_ASSOC);

    /* ---------------------------------------------------
       Build itemMap from dietItems (normal daily quantities)
    --------------------------------------------------- */
    $itemMap = [];
    foreach ($dietItems as $row) {
        $itemMap[$row['item']] = [
            'quantity' => (float) $row['quantity'],
            'unit' => $row['unit'],
            'category' => $row['category']
        ];
    }

    /* ---------------------------------------------------
       IMPORTANT CHANGE:
       Collect special menu quantities SEPARATELY in $specialQty.
       DO NOT add special qty into $itemMap (so normal calculation stays clean).
       We'll add special qty & its amount after normal calculation.
    --------------------------------------------------- */
    $specialQty = []; // structure: [item_unique => ['qty' => float, 'unit' => string, 'category' => string]]
    $specialAdded = 0;
    foreach ($specialMenus as $special) {
        // only consider special entries that fall on holiday dates (as required)
        if (!in_array($special['special_date'], $holidayDates))
            continue;

        $itemKey = $special['item'];
        $specialAdded++;

        if (!isset($specialQty[$itemKey])) {
            $specialQty[$itemKey] = [
                'qty' => 0.0,
                'unit' => $special['unit'],
                'category' => $special['category']
            ];
        }

        $specialQty[$itemKey]['qty'] += (float) $special['quantity'];
        // print_r($specialQty);
        // echo $special['special_date'];
    }
    // die();

    // end of special aggregation

    /* 🎯 Insert Master Record */
    $insertMaster = $pdo->prepare("
        INSERT INTO monthly_indent_master 
        (unique_id, screen_unique_id, district_id, taluk_id, hostel_id, month_year, total_items, total_amount, is_active, is_delete, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, 0, 0.00, 1, 0, NOW(), NOW())
    ");
    $insertMaster->execute([
        unique_id(),
        $screen_unique_id,
        $hostel['district_id'],
        $hostel['taluk_id'],
        $hostel['unique_id'],
        $month_year
    ]);

    /* 🎯 Prepare Insert Item */
    $insertItem = $pdo->prepare("
        INSERT INTO monthly_indent_items
        (unique_id, screen_unique_id, district_id, taluk_id, hostel_id, month_year, item, category, unit, quantity, unit_price, gst_percentage, gst_amount, total_price, is_active, is_delete, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0, NOW(), NOW())
    ");

    $updateMaster = $pdo->prepare("
        UPDATE monthly_indent_master SET total_items = ?, total_amount = ?, updated_at = NOW() WHERE screen_unique_id = ?
    ");

    $total_items = 0;
    $total_amount = 0;
    $itemDetails = []; // 🟢 to store per-item display info

    /* ---------------------------------------------------
        🍱 Step 6: Calculate Each Item (normal calculation first)
    --------------------------------------------------- */
    foreach ($itemMap as $item_unique => $data) {
        $category = $data['category'];
        $unit = $data['unit'];

        // 🔹 Process only the selected category
        // if ($category !== "ec8c229e224e3c7879") {
        //     continue; // skip all others
        // }

        // Per-person monthly qty adjusted for working days (NORMAL qty only)
        $normal_quantity = (float) $data['quantity'] * $final_count * ($effectiveDays / $totalDays);

        // 🎯 Buffer stock addition if category matches (NORMAL buffer only)
        if (isset($bufferStockList[$category])) {
            $bufferDays = (int) $bufferStockList[$category];
            $normal_quantity += (float) $data['quantity'] * $final_count * ($bufferDays / $totalDays);
        }

        // 🎯 Adjust using stock availability (closing stock reduces requirement)
        $opening_stock = opening_stock($item_unique, $hostel['unique_id'], $prev_month);
        $in_qty = get_in_qty($item_unique, $hostel['unique_id'], $prev_month);
        $out_qty = get_out_qty($item_unique, $hostel['unique_id'], $prev_month);
        $closing_stock = ($opening_stock + $in_qty) - $out_qty;

        $required_quantity = max(($normal_quantity - $closing_stock), 0); // avoid negative requirement

        // Initialize price vars
        $discount = 0.0;
        $unit_price = 0.0;
        $gst_percentage = 0.0;
        $gst_amount = 0.0;
        $final_total = 0.00;

        if (trim($item_unique) !== 'C-V') {
            // Try fetching rate for hostel's store_id
            $rateData = null;
            if (!empty($hostel['store_id'])) {
                $stmt = $pdo->prepare("SELECT rate, gst, discount FROM item_rate WHERE item_unique_id = ? AND store_code = ? LIMIT 1");
                $stmt->execute([$item_unique, $hostel['store_id']]);
                $rateData = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            // Fallback to any available rate
            if (!$rateData) {
                $stmt = $pdo->prepare("SELECT rate, gst, discount FROM item_rate WHERE item_unique_id = ? LIMIT 1");
                $stmt->execute([$item_unique]);
                $rateData = $stmt->fetch(PDO::FETCH_ASSOC);
            }

            if ($rateData) {
                $discount = (float) $rateData['discount'];
                $unit_price = (float) $rateData['rate'];
                $gst_percentage = (float) $rateData['gst'];

                // Calculate base & gst for NORMAL qty
                $base_total = $required_quantity * ($unit_price - $discount);
                $gst_amount = $base_total * ($gst_percentage / 100);
                $final_total = $base_total + $gst_amount;
            }
        }

        // ------------------------------
        // ➕ NOW add special qty & amount (if any) AFTER normal calculation
        // ------------------------------
        if (isset($specialQty[$item_unique]) && $specialQty[$item_unique]['qty'] > 0) {
            $extra_qty = (float) $specialQty[$item_unique]['qty'];

            // Add extra qty to final ordered quantity
            $required_quantity += $extra_qty;

            // Calculate extra amount for special qty (use same unit_price & discount & gst)
            if ($unit_price > 0) {
                $extra_base = $extra_qty * ($unit_price - $discount);
                $extra_gst = $extra_base * ($gst_percentage / 100);
                $extra_total = $extra_base + $extra_gst;

                // Add to final_total and totals
                $final_total += $extra_total;
            } else {
                // if no unit_price found, extra_total remains 0
                $extra_total = 0;
            }
        }

        // Insert with the FINAL required_quantity (normal + special) and final_total (normal amount + extra amount)
        $insertItem->execute([
            unique_id(),
            $screen_unique_id,
            $hostel['district_id'],
            $hostel['taluk_id'],
            $hostel['unique_id'],
            $month_year,
            $item_unique,
            $category,
            $unit,
            $required_quantity,
            $unit_price,
            $gst_percentage,
            $gst_amount,
            $final_total
        ]);

        $total_items++;
        $total_amount += $final_total;

        // 🟩 Get Item Name
        if (trim($item_unique) === 'C-V') {
            $item_name = 'C-V';
        } else {
            $stmtItem = $pdo->prepare("SELECT item FROM item WHERE unique_id = ? LIMIT 1");
            $stmtItem->execute([$item_unique]);
            $item_name = $stmtItem->fetchColumn() ?: $item_unique; // fallback to ID if not found
        }

        // 🟩 Get Category Name
        if (trim($category) === 'Special') {
            $category_name = 'Special';
        } else {
            $stmtCat = $pdo->prepare("SELECT item_category FROM item_category WHERE unique_id = ? LIMIT 1");
            $stmtCat->execute([$category]);
            $category_name = $stmtCat->fetchColumn() ?: $category; // fallback to ID if not found
        }

        // 🟢 Store item detail for display
        $itemDetails[] = [
            'item' => $item_name,
            'category' => $category_name,
            'unit' => $unit,
            'closing_stock' => round($closing_stock, 2),
            'order_qty' => round($required_quantity, 2), // includes special qty now
            'rate' => round($unit_price, 2),
            'total' => round($final_total, 2)
        ];
    }

    $updateMaster->execute([$total_items, $total_amount, $screen_unique_id]);

    $summary[] = [
        'hostel_id' => $hostel['hostel_id'],
        'final_count' => $final_count,
        'normal_items' => count($dietItems),
        'special_added' => $specialAdded,
        'holidays' => $holidayCount,
        'effective_days' => $effectiveDays,
        'total_items' => $total_items,
        'total_amount' => $total_amount,
        'item_details' => $itemDetails
    ];
}

/* ---------------------------------------------------
    📋 Step 7: Display Summary with Item Details
--------------------------------------------------- */
echo "<hr><h2 style='color:darkgreen;'>📋 Monthly Indent Summary (₹)</h2>";

echo "<table border='1' cellspacing='0' cellpadding='6' style='border-collapse:collapse;width:95%;font-family:Arial;font-size:14px;'>
<tr style='background:#0066cc;color:#fff;text-align:center;'>
<th>Hostel ID</th>
<th>Count</th>
<th>Normal Diet Items</th>
<th>Special Menu Added</th>
<th>Holidays</th>
<th>Effective Days</th>
<th>Total Items</th>
<th>Total Amount (₹)</th>
</tr>";

foreach ($summary as $row) {
    echo "<tr style='text-align:center;background:#e6f2ff;font-weight:bold;'>
        <td>{$row['hostel_id']}</td>
        <td>{$row['final_count']}</td>
        <td>{$row['normal_items']}</td>
        <td>{$row['special_added']}</td>
        <td>{$row['holidays']}</td>
        <td>{$row['effective_days']}</td>
        <td>{$row['total_items']}</td>
        <td style='text-align:right;'>₹" . number_format($row['total_amount'], 2) . "</td>
    </tr>";

    // 🔹 Show each hostel’s item-wise details
    if (!empty($row['item_details'])) {
        echo "<tr><td colspan='8' style='padding:0;'>";
        echo "<table border='1' cellspacing='0' cellpadding='4' style='border-collapse:collapse;width:100%;font-family:Arial;font-size:13px;'>
        <tr style='background:#004080;color:#fff;text-align:center;'>
            <th>Item</th>
            <th>Category</th>
            <th>Unit</th>
            <th>Closing Stock</th>
            <th>Order Qty</th>
            <th>Rate (₹)</th>
            <th>Total (₹)</th>
        </tr>";

        foreach ($row['item_details'] as $it) {
            echo "<tr style='text-align:center;'>
                <td>{$it['item']}</td>
                <td>{$it['category']}</td>
                <td>{$it['unit']}</td>
                <td>{$it['closing_stock']}</td>
                <td>{$it['order_qty']}</td>
                <td>{$it['rate']}</td>
                <td style='text-align:right;'>₹" . number_format($it['total'], 2) . "</td>
            </tr>";
        }

        echo "</table>";
        echo "</td></tr>";
    }
}

echo "</table>";
echo "<hr><h2 style='color:green;'>🎉 Indent generation completed successfully!</h2>";

/* ---------------------------------------------------
    ⚙️ Stock Helper Functions
--------------------------------------------------- */
function get_in_qty($item_name, $hostel_id, $month_val)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT SUM(qty) AS in_qty FROM stock_inward
        WHERE hostel_unique_id = ? AND item_name = ? AND DATE_FORMAT(entry_date,'%Y-%m') = ? AND is_delete = 0
    ");
    $stmt->execute([$hostel_id, $item_name, $month_val]);
    return (float) ($stmt->fetchColumn() ?? 0);
}

function get_out_qty($item_name, $hostel_id, $month_val)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT SUM(qty) AS out_qty FROM stock_outward
        WHERE hostel_unique_id = ? AND item_name = ? AND DATE_FORMAT(entry_date,'%Y-%m') = ? AND is_delete = 0
    ");
    $stmt->execute([$hostel_id, $item_name, $month_val]);
    return (float) ($stmt->fetchColumn() ?? 0);
}

function opening_stock($item_name, $hostel_id, $month_val)
{
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            (SELECT SUM(qty) FROM stock_inward WHERE item_name = ? AND hostel_unique_id = ? AND DATE_FORMAT(entry_date,'%Y-%m') = ? AND is_delete = 0) AS in_qty,
            (SELECT SUM(qty) FROM stock_outward WHERE item_name = ? AND hostel_unique_id = ? AND DATE_FORMAT(entry_date,'%Y-%m') = ? AND is_delete = 0) AS out_qty
    ");
    $stmt->execute([$item_name, $hostel_id, $month_val, $item_name, $hostel_id, $month_val]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (float) (($row['in_qty'] ?? 0) - ($row['out_qty'] ?? 0));
}
?>