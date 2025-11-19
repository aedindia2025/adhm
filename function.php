<?php
function get_academic_year($unique_id = "") {
    global $pdo;

    $table_name    = "academic_year_creation";
    $where         = [];
    $table_columns = [
        "unique_id",
        "amc_year"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    $acc_year = $pdo->select($table_details, $where);

    if ($acc_year->status) {
        return $acc_year->data;
    } else {
        print_r($acc_year);
        return 0;
    }
}
?>