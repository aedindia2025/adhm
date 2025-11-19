<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(300);

define('DB_HOST', 'localhost');
define('DB_NAME', 'adi_dravidar');
define('DB_USER', 'root');
define('DB_PASS', '4/rb5sO2s3TpL4gu');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<pre style='color:red;'>❌ Database Connection failed: " . $e->getMessage() . "</pre>");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Import Hostel Store Codes</title>
</head>

<body>
    <h2>Import Hostel Store Codes</h2>

    <?php
    if (isset($_FILES['excel_file']['tmp_name'])) {
        $file = $_FILES['excel_file']['tmp_name'];
        echo "<pre>📁 File uploaded: $file</pre>";

        try {
            // ✅ Load Excel file using PhpSpreadsheet
            $spreadsheet = IOFactory::load($file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            echo "<pre>✅ Excel file loaded successfully</pre>";

            if (empty($sheetData) || count($sheetData) < 2) {
                die("<pre style='color:red;'>❌ Error: Empty or invalid file.</pre>");
            }

            $updated = 0;
            $skipped = 0;
            $isHeader = true;

            foreach ($sheetData as $rowNum => $row) {
                if ($isHeader) { // Skip header row
                    $isHeader = false;
                    continue;
                }

                $store_id = trim($row['J']); // Column J → Store Id
                $store_code = trim($row['B']); // Column B → Store Code
    
                // Skip if store_code is empty or null
                if (empty($store_code)) {
                    echo "<span style='color:gray;'>Row $rowNum skipped — empty store_code.</span><br>";
                    $skipped++;
                    continue;
                }

                // ✅ Check if store_id exists in hostel_name table
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM hostel_name WHERE store_id = ?");
                $stmtCheck->execute([$store_id]);
                $exists = $stmtCheck->fetchColumn();

                if ($exists == 0) {
                    echo "<span style='color:orange;'>Row $rowNum skipped — store_id not found: $store_id</span><br>";
                    $skipped++;
                    continue;
                }

                // ✅ Update store_code for the given store_id
                $stmtUpdate = $pdo->prepare("UPDATE hostel_name SET store_code = ? WHERE store_id = ?");
                $stmtUpdate->execute([$store_code, $store_id]);

                if ($stmtUpdate->rowCount() > 0) {
                    echo "<span style='color:green;'>Row $rowNum updated → store_id: $store_id | store_code: $store_code</span><br>";
                    $updated++;
                } else {
                    echo "<span style='color:red;'>Row $rowNum not updated — already same or no change for $store_id</span><br>";
                }
            }

            echo "<h3 style='color:green;'>✅ Import Completed Successfully</h3>";
            echo "<p><b>Total Updated:</b> $updated</p>";
            echo "<p><b>Total Skipped:</b> $skipped</p>";

        } catch (Exception $e) {
            die("<pre style='color:red;'>❌ Error: " . $e->getMessage() . "</pre>");
        }
    } else {
        ?>
        <form method="post" enctype="multipart/form-data">
            <label>Select Excel File:</label><br><br>
            <input type="file" name="excel_file" required><br><br>
            <button type="submit">Import</button>
        </form>
    <?php } ?>
</body>

</html>