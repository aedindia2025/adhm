<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
set_time_limit(-1);

// DB connection
$pdo = new PDO('mysql:host=localhost;dbname=adi_dravidar', 'root', '4/rb5sO2s3TpL4gu');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn = mysqli_connect("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

$academic_year = '6847e16e7060491061';

// Step 1: Get duplicate std_app_no values
$duplicates = [];
$q1 = "
    SELECT std_app_no
    FROM std_app_s
    WHERE academic_year = '$academic_year'
    GROUP BY std_app_no
    HAVING COUNT(*) > 1
";
$res1 = mysqli_query($conn, $q1);
while ($row = mysqli_fetch_assoc($res1)) {
    $duplicates[] = $row['std_app_no'];
}

$deleted_count = 0;
$log = [];

foreach ($duplicates as $std_app_no) {
    // Step 2: Get all duplicate rows (ordered by id)
    $q2 = "
        SELECT unique_id
        FROM std_app_s
        WHERE academic_year = '$academic_year' AND std_app_no = '$std_app_no'
        ORDER BY id DESC
    ";
    $res2 = mysqli_query($conn, $q2);
    $ids = [];
    while ($r = mysqli_fetch_assoc($res2)) {
        $ids[] = $r['unique_id'];
    }

    // Keep the first (latest) one, delete rest
    $to_delete = array_slice($ids, 1);

    foreach ($to_delete as $uid) {
        $tables = [
            "batch_creation",
            "std_app_s2",
            "std_app_emis_s3",
            "std_app_umis_s4",
            "std_app_s5",
            "std_app_s6",
            "std_app_s7",
            "std_app"
        ];

        foreach ($tables as $table) {
            $qDel = "DELETE FROM $table WHERE s1_unique_id = ?";
            $stmt = $pdo->prepare($qDel);
            $stmt->execute([$uid]);
        }

        // Finally delete from std_app_s
        $stmt = $pdo->prepare("DELETE FROM std_app_s WHERE unique_id = ?");
        $stmt->execute([$uid]);

        $deleted_count++;
        $log[] = $uid;
    }
}

echo json_encode([
    "status" => true,
    "deleted_duplicate_records" => $deleted_count,
    "deleted_unique_ids" => $log
]);

?>
