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
    <title>Import Hostel Store IDs</title>
</head>
<body>
    <h2>Import Hostel Store IDs</h2>

<?php
if (isset($_FILES['excel_file']['tmp_name'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    echo "<pre>File: $file</pre>";

    try {
        // ✅ Load Excel file using PhpSpreadsheet
        $spreadsheet = IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        echo "<pre>✅ Excel file loaded successfully</pre>";

        if (empty($sheetData) || count($sheetData) < 2) {
            die("<pre style='color:red;'>❌ Error: Empty or invalid file.</pre>");
        }

        $stmt = $pdo->prepare("UPDATE hostel_name SET store_id = ? WHERE hostel_id = ?");
        $updated = $notFound = 0;
        $isHeader = true;

        foreach ($sheetData as $row) {
            if ($isHeader) { 
                $isHeader = false; 
                continue; 
            }

            $hostel_id = trim($row['B']); // Column B → hostel_id
            $store_id  = trim($row['H']); // Column H → store_id

            if (empty($hostel_id) || empty($store_id)) continue;

            $stmt->execute([$store_id, $hostel_id]);
            if ($stmt->rowCount() > 0) {
                $updated++;
            } else {
                $notFound++;
            }
        }

        echo "<h3 style='color:green;'>✅ Completed Successfully</h3>";
        echo "<p><b>Total Updated:</b> $updated</p>";
        echo "<p><b>Not Found:</b> $notFound</p>";

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