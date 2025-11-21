<?php

// Get folder Name From Currnent Url 
$folder_name = explode("/", $_SERVER['PHP_SELF']);
$folder_name = $folder_name[count($folder_name) - 2];

// // Database Country Table Name
$table = "indent_count";

// // Include DB file and Common Functions
include '../config/dbconfig.php';

// // Variables Declaration
$action = $_POST['action'];

$feedback_type = "";
$is_active = "";
$unique_id = "";
$prefix = "";

$data = "";
$msg = "";
$error = "";
$status = "";
$test = ""; // For Developer Testing Purpose
$acc_year = $_SESSION['academic_year'];

function validateCSRFToken($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

switch ($action) {

    case 'datatable':

        // DataTable Variables
        $length = $_POST['length'];
        $start = $_POST['start'];
        $draw = $_POST['draw'];
        $search_value = $_POST['search']['value'] ?? '';
        $limit = $length == '-1' ? "" : $length;

        $data = [];

        // Columns to fetch
        $columns = [
            "unique_id",
            "'' as s_no",
            "hostel_id",
            "(SELECT taluk_name FROM taluk_creation WHERE taluk_creation.unique_id = hostel_name.taluk_name) AS taluk",
            "hostel_name"

        ];

        $table_details = 'hostel_name';

        // WHERE condition
        $where = 'district_name = "' . $_SESSION['district_id'] . '" and is_delete = 0 ';

        // Global search
        if (!empty($search_value)) {
            $where .= " AND (
            hostel_id LIKE '%$search_value%'
        )";
        }

        $order_by = " ORDER BY hostel_id ASC";

        $sql_function = "SQL_CALC_FOUND_ROWS";

        // Main query
        $sql = "SELECT $sql_function " . implode(", ", $columns) . " 
            FROM $table_details 
            WHERE $where
            $order_by";

        // if ($limit) {
        //     $sql .= " LIMIT ?, ?";
        // }

        // Prepare
        $stmt = $mysqli->prepare($sql);

        // if ($limit) {
        //     $stmt->bind_param("ii", $start, $limit);
        // } else {
        //     $stmt->bind_param("i", $start);
        // }

        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch total records
        $total_records_result = $mysqli->query("SELECT FOUND_ROWS() as total");
        $total_records = $total_records_result->fetch_assoc()['total'];

        if ($result) {

            $res_array = $result->fetch_all(MYSQLI_ASSOC);
            $sno = $start + 1;

            foreach ($res_array as $value) {
                $value['s_no'] = $sno++;
                $value['unique_id'] = '<input type="checkbox" class="row-check" onclick="toggleRowCheckbox()" value="' . $value['unique_id'] . '">
                                <input type="hidden" id="hostel_id" name="hostel_id[]" value="' . $value['hostel_id'] . '">';

                $data[] = array_values($value);
            }

            $json_array = [
                "draw" => intval($draw),
                "recordsTotal" => intval($total_records),
                "recordsFiltered" => intval($total_records),
                "data" => $data
            ];
        } else {
            print_r($result);
        }

        echo json_encode($json_array);

        // Close connection
        $stmt->close();
        $mysqli->close();

        break;

    case 'indent_raise':

        header('Content-Type: application/json');

        try {
            $pdo = new PDO("mysql:host=localhost;dbname=adi_dravidar", "root", "4/rb5sO2s3TpL4gu");
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'DB Connection failed: ' . $e->getMessage()]);
            exit;
        }

        $from_indent_date = $_POST['from_indent_date'] ?? null;
        $to_indent_date = $_POST['to_indent_date'] ?? null;
        $hostel_ids_input = $_POST['hostel_ids'] ?? null; // array or comma-separated

        if (!$from_indent_date || !$to_indent_date) {
            echo json_encode(['error' => 'Provide from_indent_date and to_indent_date']);
            exit;
        }

        // normalize hostel ids into array (if passed as comma-separated string)
        if (is_string($hostel_ids_input)) {
            $hostel_ids = array_filter(array_map('trim', explode(',', $hostel_ids_input)));
        } elseif (is_array($hostel_ids_input)) {
            $hostel_ids = $hostel_ids_input;
        } else {
            $hostel_ids = []; // empty => all hostels
        }

        // Build date range (inclusive)
        $start = new DateTime($from_indent_date);
        $end = new DateTime($to_indent_date);
        $end->modify('+1 day'); // include end
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        $dateRange = [];
        foreach ($period as $dt) {
            $dateRange[] = $dt->format('Y-m-d');
        }
        $totalDays = count($dateRange);

        // Fetch holidays in range
        $holidayStmt = $pdo->prepare("
            SELECT DATE(holiday_date) AS holiday_date
            FROM holiday_master
            WHERE is_delete = 0
            AND DATE(holiday_date) BETWEEN ? AND ?
        ");
        $holidayStmt->execute([$from_indent_date, $to_indent_date]);
        $holidayDates = $holidayStmt->fetchAll(PDO::FETCH_COLUMN);
        $holidaySet = array_flip($holidayDates);

        // Buffer map (category -> percent). If not present default 0
        $bufferMap = [];
        $bufStmt = $pdo->query("SELECT category, days FROM master_buffer_stock WHERE is_delete = 0");
        $bufRows = $bufStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($bufRows as $r) {
            $bufferMap[$r['category']] = isset($r['days']) ? floatval($r['days']) : 0.0;
        }

        // Reusable prepared statements (including the ones you asked to keep)
        $checkDuplicateStmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM monthly_indent_master WHERE hostel_id = ? AND month_year = ? AND is_delete = 0");

        $insertMaster = $pdo->prepare("
        INSERT INTO monthly_indent_master 
        (unique_id, screen_unique_id, raised_date, district_id, taluk_id, hostel_id, month_year, total_items, total_amount, is_active, is_delete, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0, 0.00, 1, 0, NOW(), NOW())
    ");

        $updateMaster = $pdo->prepare("
        UPDATE monthly_indent_master SET total_items = ?, total_amount = ?, updated_at = NOW() WHERE screen_unique_id = ?
    ");

        $insertItem = $pdo->prepare("
        INSERT INTO monthly_indent_items
        (unique_id, screen_unique_id, raised_date, district_id, taluk_id, hostel_id, month_year, item, category, unit, quantity, unit_price, gst_percentage, gst_amount, total_price, is_active, is_delete, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 0, NOW(), NOW())
    ");

        // Diet chart per hostel_type
        $getDietStmt = $pdo->prepare("SELECT item AS item_unique, category, quantity AS qty_per_month, unit FROM master_diet_chart_sublist WHERE hostel_type = ? AND is_delete = 0");

        // Special menus for hostel (note: we will bind hostel id later)
        $getSpecialStmt = $pdo->prepare("
        SELECT DATE(date) AS special_date, item AS item_unique, quantity AS qty_per_month, unit
        FROM special_menu_chart_sub
        WHERE is_delete = 0 AND DATE(date) BETWEEN ? AND ?
    ");

        // Hostels: filter if hostel_ids provided
        $hostelQuery = "SELECT unique_id, hostel_id, hostel_type, district_name AS district_id, taluk_name AS taluk_id, store_id FROM hostel_name WHERE is_delete = 0";
        if (!empty($hostel_ids)) {
            // sanitize potential single quotes in incoming ids
            $hostel_ids = array_map(function ($id) {
                return trim($id, "'");   // remove surrounding single quotes if present
            }, $hostel_ids);
            $placeholders = rtrim(str_repeat('?,', count($hostel_ids)), ',');
            $hostelQuery .= " AND unique_id IN ($placeholders)";
            $hostelStmt = $pdo->prepare($hostelQuery);
            $hostelStmt->execute($hostel_ids);
        } else {
            $hostelStmt = $pdo->query($hostelQuery);
        }
        $hostels = $hostelStmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($hostels)) {
            echo json_encode(['error' => 'No hostels found for the given filter.']);
            exit;
        }

        // Prepare a statement to fetch human-readable hostel_type (like "School", "ITI", etc.)
        $getHostelTypeStmt = $pdo->prepare("SELECT hostel_type FROM hostel_type WHERE unique_id = ? AND is_delete = 0 LIMIT 1");

        // Prepare item name fetcher - we'll fetch all item names used in dietItems in one query per hostel
        $getItemNameStmt = $pdo->prepare("SELECT unique_id, item FROM item WHERE unique_id IN (%s) AND is_delete = 0");

        // For response HTML collection
        $full_html = '';
        $summary = [];

        function fetchApiRatesForHostel($apiHostelType, $apiHostelId)
        {
            $apiHost = "https://rcs-dms.onlinetn.com/api/v1/DSHR";

            // Encode safely for URL
            $typeEnc = rawurlencode($apiHostelType);
            $idEnc = rawurlencode($apiHostelId);

            $url = "{$apiHost}/{$typeEnc}/{$idEnc}/items";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);

            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Authorization: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtb2JpbGUiOiI5NDQzMDYyOTE2IiwiY291bnQiOiIiLCJkb21haW4iOiJhZHciLCJpZCI6MTYyMywiaWF0IjoxNzYzNDQ0MzA2fQ.f7Dxu-D5Qa_s2cns---zhCETVZ4vUBOAJrBJZQckHYM'
            ]);

            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $resp = curl_exec($ch);
            $err = curl_error($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            // ----------------------------
            // 1. HTTP 503 = RCS downtime
            // ----------------------------
            if ($http_code == 503) {
                return [
                    'error' => true,
                    'message' => 'RCS downtime - Service Unavailable',
                    'rates' => []
                ];
            }

            // ----------------------------
            // 2. Curl-level failure
            // ----------------------------
            if ($err) {
                return [
                    'error' => true,
                    'message' => "Curl error: " . $err,
                    'rates' => []
                ];
            }

            // ----------------------------
            // 3. Detect HTML or invalid JSON
            // ----------------------------
            $json = json_decode($resp, true);

            if ($json === null) {
                // If HTML returned OR bad JSON
                if (stripos($resp, '<html') !== false || stripos($resp, '<!DOCTYPE') !== false) {
                    return [
                        'error' => true,
                        'message' => 'RCS downtime - Service Unavailable',
                        'rates' => []
                    ];
                }

                return [
                    'error' => true,
                    'message' => 'Invalid JSON from RCS API',
                    'rates' => []
                ];
            }

            // ----------------------------
            // 4. JSON exists but missing "items"
            // ----------------------------
            if (!isset($json['items']) || !is_array($json['items'])) {
                return [
                    'error' => true,
                    'message' => 'Invalid response from RCS server',
                    'rates' => []
                ];
            }

            // ----------------------------
            // 5. Extract item rates
            // ----------------------------
            $rates = [];

            foreach ($json['items'] as $it) {
                if (!isset($it['name']))
                    continue;

                $nameKey = mb_strtolower(trim($it['name']));
                $rateVal = floatval(str_replace(',', '', (string) ($it['rate'] ?? '0')));

                $rates[$nameKey] = $rateVal;
            }

            return [
                'error' => false,
                'message' => 'ok',
                'rates' => $rates
            ];
        }


        // Loop hostels
        foreach ($hostels as $hostel) {
            $hostel_unique_id = $hostel['unique_id'];
            $hostel_code = $hostel['hostel_id'];
            $hostel_type_ref = $hostel['hostel_type']; // this is unique_id referencing hostel_type table
            $store_id = $hostel['store_id'];
            $district_id = $hostel['district_id'];
            $taluk_id = $hostel['taluk_id'];

            // Resolve display hostel_type string (like "School", "ITI", etc.)
            $getHostelTypeStmt->execute([$hostel_type_ref]);
            $apiHostelType = $getHostelTypeStmt->fetchColumn(); // No fallback

            // Prepare API call once per hostel to fetch rates
            $apiResult = fetchApiRatesForHostel($apiHostelType, $hostel_code);
            if ($apiResult['error']) {
                // API failed — skip this hostel and continue
                $full_html .= "<div style='padding:10px;background:#fff;border:1px solid #fee;margin-bottom:10px;color:#900;'>Hostel {$hostel_code} - API fetch failed: " . htmlspecialchars($apiResult['message']) . ". Skipped.</div>";
                continue;
            }
            $apiRates = $apiResult['rates']; // nameKey => rateFloat

            // ------------------------------------------------------------
            // NEW CONDITION: SKIP HOSTEL IF NO STOCK OUTWARD FOR LAST 4 DAYS
            // ------------------------------------------------------------
            $checkOutwardStmt = $pdo->prepare("
                SELECT COUNT(*) 
                FROM stock_outward 
                WHERE hostel_unique_id = ? 
                AND DATE(entry_date) >= DATE_SUB(CURDATE(), INTERVAL 4 DAY)
                AND is_delete = 0
            ");

            $checkOutwardStmt->execute([$hostel_unique_id]);
            $outwardCount = (int) $checkOutwardStmt->fetchColumn();

            if ($outwardCount == 0) {
                $full_html .= "
                    <div style='padding:10px;background:#fff;border-left:5px solid #ff9800;
                                margin-bottom:10px;color:#b36b00;font-size:14px;'>
                        <strong>Hostel {$hostel_code} skipped:</strong><br>
                        No stock consumption recorded for this hostel in the last 4 days.<br>
                        Hence indent generation is not performed.<br>
                        Please ensure the hostel updates daily stock outward entries.
                    </div>
                ";
                continue; // skip this hostel
            }

            // Month-year used for duplicate check and master (based on from_indent_date)
            $month_for_master = date('Y-m', strtotime($from_indent_date));

            // Check duplicates
            $checkDuplicateStmt->execute([$hostel_unique_id, $month_for_master]);
            $dupRow = $checkDuplicateStmt->fetch(PDO::FETCH_ASSOC);
            if ($dupRow && $dupRow['cnt'] > 3) {
                // Skip this hostel — already has indent for that month-year
                $full_html .= "<div style='padding:10px;background:#fff;border:1px solid #eee;margin-bottom:10px;'>Hostel {$hostel_code} - already has indent for {$month_for_master}. Skipped.</div>";
                continue;
            }

            // Get final_count (indent_count) for month
            $getFinalCountStmt = $pdo->prepare("SELECT final_count FROM indent_count WHERE hostel_id = ? AND month_year = ? ORDER BY id DESC LIMIT 1");
            $getFinalCountStmt->execute([$hostel_code, $month_for_master]);
            $fcRow = $getFinalCountStmt->fetch(PDO::FETCH_ASSOC);
            $final_count = ($fcRow && is_numeric($fcRow['final_count'])) ? (int) $fcRow['final_count'] : 1;

            // Fetch diet items for this hostel type
            $getDietStmt->execute([$hostel_type_ref]);
            $dietItems = $getDietStmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($dietItems)) {
                $full_html .= "<div style='padding:10px;background:#fff;border:1px solid #eee;margin-bottom:10px;'>Hostel {$hostel_code} - no diet items for hostel type {$hostel_type_ref}. Skipped.</div>";
                continue;
            }

            // Build item meta & collect item_unique ids for name lookup
            $itemMeta = [];
            $itemIds = [];
            foreach ($dietItems as $di) {
                $itemMeta[$di['item_unique']] = [
                    'category' => $di['category'],
                    'unit' => $di['unit'],
                    'qty_per_month' => (float) $di['qty_per_month']
                ];
                $itemIds[] = $di['item_unique'];
            }

            // Resolve item names for matching with API (single query)
            if (!empty($itemIds)) {
                // build placeholders
                $placeholders = rtrim(str_repeat('?,', count($itemIds)), ',');
                $stmtNames = $pdo->prepare("SELECT unique_id, item FROM item WHERE unique_id IN ($placeholders) AND is_delete = 0");
                $stmtNames->execute($itemIds);
                $nameRows = $stmtNames->fetchAll(PDO::FETCH_KEY_PAIR); // unique_id => item
            } else {
                $nameRows = [];
            }

            // Fetch specials for hostel in range
            $getSpecialStmt->execute([$from_indent_date, $to_indent_date]);
            $specialRows = $getSpecialStmt->fetchAll(PDO::FETCH_ASSOC);
            $specialByDate = [];
            foreach ($specialRows as $sr) {
                $d = $sr['special_date'];
                if (!isset($specialByDate[$d]))
                    $specialByDate[$d] = [];
                $specialByDate[$d][] = $sr;
            }

            // Determine normalDaysCount and specialDaysCount (rules you confirmed)
            $normalDaysCount = 0;
            $specialDatesSet = [];
            foreach ($dateRange as $curDate) {
                $hasSpecial = isset($specialByDate[$curDate]);
                $isHoliday = isset($holidaySet[$curDate]);

                if ($hasSpecial) {
                    $specialDatesSet[$curDate] = true;
                } else {
                    if (!$isHoliday) {
                        $normalDaysCount++;
                    }
                    // holiday & no special => excluded
                }
            }
            $specialDaysCount = count($specialDatesSet);
            $countedDays = $normalDaysCount + $specialDaysCount;

            // --- First pass: compute final_qty per item (before closing stock) and collect items that require >0 qty
            $computedItems = []; // item_unique => ['normal' => x, 'special' => y, 'total_before_closing' => z]
            foreach ($itemMeta as $item_unique => $meta) {
                $per_day_per_person = $meta['qty_per_month'] / 30.0;
                $normal_qty = $per_day_per_person * $final_count * $normalDaysCount;
                $special_qty = $per_day_per_person * $final_count * $specialDaysCount;
                $total_before_closing = $normal_qty + $special_qty;
                // buffer percent
                $category = $meta['category'];
                $buffer_percent = isset($bufferMap[$category]) ? floatval($bufferMap[$category]) : 0.0;
                $buffer_qty = ($buffer_percent > 0) ? ($total_before_closing * ($buffer_percent / 100.0)) : 0.0;
                $subtotal_with_buffer = $total_before_closing + $buffer_qty;

                // closing stock adjustment using previous month
                $prev_month = date('Y-m', strtotime('-1 month', strtotime($from_indent_date)));
                $opening_stock = opening_stock($item_unique, $hostel_unique_id, $prev_month);
                $in_qty = get_in_qty($item_unique, $hostel_unique_id, $prev_month);
                $out_qty = get_out_qty($item_unique, $hostel_unique_id, $prev_month);
                $closing_stock = ($opening_stock + $in_qty) - $out_qty;
                $after_closing = max(0, $subtotal_with_buffer - $closing_stock);
                $final_qty = round($after_closing, 2);

                $computedItems[$item_unique] = [
                    'normal' => $normal_qty,
                    'special' => $special_qty,
                    'before_closing' => $subtotal_with_buffer,
                    'closing_stock' => $closing_stock,
                    'final_qty' => $final_qty,
                    'unit' => $meta['unit'],
                    'category' => $meta['category']
                ];
            }

            // --- Now check API rates for required items (only those with final_qty > 0)
            $missingRates = [];
            $zeroRates = [];
            foreach ($computedItems as $item_unique => $idata) {
                if (($idata['final_qty'] ?? 0) <= 0)
                    continue; // ignore zero requirement items

                $itemName = isset($nameRows[$item_unique]) ? $nameRows[$item_unique] : '';
                $key = mb_strtolower(trim($itemName));

                if ($key === '') {
                    // no item name found in DB — treat as missing
                    $missingRates[] = $item_unique;
                    continue;
                }

                if (!array_key_exists($key, $apiRates)) {
                    // missing in API → treat as missing
                    $missingRates[] = $itemName . " ({$item_unique})";
                    continue;
                }

                $rateVal = $apiRates[$key];
                if ($rateVal <= 0) {
                    // $zeroRates[] = $itemName . " ({$item_unique})";
                    $zeroRates[] = $itemName;
                }
            }

            if (!empty($missingRates) || !empty($zeroRates)) {
                $msgParts = [];

                if (!empty($missingRates)) {
                    $msgParts[] = "Missing rates for: " . implode(", ", $missingRates);
                }

                if (!empty($zeroRates)) {
                    $msgParts[] = "Zero rates for: " . implode(", ", $zeroRates)
                        . " — Rate not updated for the corresponding Store ID. Please inform Warden to update the item rates in the RCS system.";
                }

                $full_html .= "
        <div style='padding:10px;background:#fff;border-left:5px solid #dc3545;
                    margin-bottom:10px;color:#900;font-size:14px;'>
            <strong>Hostel {$hostel_code} skipped:</strong><br>
            &nbsp;• " . htmlspecialchars(implode("<br>&nbsp;• ", $msgParts)) . "
        </div>
    ";

                continue;
            }


            // Begin transaction for this hostel (master + items)
            try {
                $pdo->beginTransaction();

                // Create master record
                $screen_unique_id = unique_id();
                $master_unique_id = unique_id();

                $insertMaster->execute([
                    $master_unique_id,
                    $screen_unique_id,
                    date("Y-m-d"),   // ← current date
                    $district_id,
                    $taluk_id,
                    $hostel_unique_id,
                    $month_for_master
                ]);

                // Insert items using API rate values (gst & discount set to 0 since API doesn't provide them)
                $total_items = 0;
                $total_amount = 0.0;

                foreach ($computedItems as $item_unique => $idata) {
                    $final_qty = $idata['final_qty'];
                    if ($final_qty <= 0)
                        continue;

                    // find item name
                    $itemName = isset($nameRows[$item_unique]) ? $nameRows[$item_unique] : $item_unique;
                    $key = mb_strtolower(trim($itemName));
                    $unit_price = isset($apiRates[$key]) ? (float) $apiRates[$key] : 0.0;
                    $gst_percentage = 0.0;
                    $discount = 0.0;

                    $base_total = $final_qty * max(0, ($unit_price - $discount));
                    $gst_amount = $base_total * ($gst_percentage / 100.0);
                    $final_total = $base_total + $gst_amount;

                    // Insert item row
                    $insertItem->execute([
                        unique_id(),
                        $screen_unique_id,
                        date("Y-m-d"),   // ← current date
                        $district_id,
                        $taluk_id,
                        $hostel_unique_id,
                        $month_for_master,
                        $item_unique,
                        $idata['category'],
                        $idata['unit'],
                        $final_qty,
                        $unit_price,
                        $gst_percentage,
                        $gst_amount,
                        $final_total
                    ]);

                    $total_items++;
                    $total_amount += $final_total;
                }

                // Update master totals
                $updateMaster->execute([$total_items, $total_amount, $screen_unique_id]);

                $pdo->commit();

                // Build HTML block for this hostel summary
                $full_html .= "<div style='padding:12px;border:1px solid #e6e6e6;margin-bottom:10px;background:#fff;'>
                <div style='font-weight:700;color:#222;'>Hostel: {$hostel_code}</div>
                <div style='color:#666;font-size:13px;'>Period: {$from_indent_date} to {$to_indent_date} | Counted days: {$countedDays} | Items: {$total_items} | Amount: ₹" . number_format($total_amount, 2) . "</div>
            </div>";

                $summary[] = [
                    'hostel_id' => $hostel_code,
                    'final_count' => $final_count,
                    'total_days' => $totalDays,
                    'normal_days' => $normalDaysCount,
                    'special_days' => $specialDaysCount,
                    'counted_days' => $countedDays,
                    'total_items' => $total_items,
                    'total_amount' => $total_amount
                ];

            } catch (Exception $e) {
                $pdo->rollBack();
                $full_html .= "<div style='padding:10px;background:#fff;border:1px solid #fee;margin-bottom:10px;color:#900;'>Hostel {$hostel_code} - Failed: " . htmlspecialchars($e->getMessage()) . "</div>";
                continue;
            }

        } // end hostels loop

        // Wrap HTML
        $output_html = "
        <div style='padding:12px;background:#f6f7f9;border-radius:8px;'>
            <h3 style='margin:0 0 10px 0;font-family:Arial, sans-serif;color:#1f2d3d;'>Indent Summary ({$from_indent_date} → {$to_indent_date})</h3>
            {$full_html}
        </div>
    ";

        echo json_encode([
            'html' => $output_html,
            'summary' => $summary
        ]);

        exit;
        break;


    default:

        break;
}

function formatMonthYear($month_year)
{
    $month_year = trim($month_year);
    $month_year = str_replace(['/', '.', ' '], '-', $month_year); // normalize separators

    $parts = explode('-', $month_year);

    // Determine which part is month and which is year
    if (strlen($parts[0]) == 4) {
        // Format is YYYY-MM
        $year = $parts[0];
        $month = $parts[1];
    } else {
        // Format is MM-YYYY
        $month = $parts[0];
        $year = $parts[1];
    }

    // Ensure month is two digits
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);

    // Get month name
    $monthName = date('F', mktime(0, 0, 0, $month, 1));

    return $monthName . "<br>" . $year;
}

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