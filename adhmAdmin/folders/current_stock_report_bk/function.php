<?php


function get_in_qty($item_name,$hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "stock_inward";
    // $where         = [];

    $table_columns = [
        "sum(qty) as in_qty",
       
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."' and is_delete = 0";


    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['in_qty'];
        
    } else {
        print_r($result_values1);
        return 0;
    }
}

function get_out_qty($item_name,$hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "stock_outward";
    // $where         = [];

    $table_columns = [
        "sum(qty) as out_qty",
       
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."' and is_delete = 0";


    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['out_qty'];
        
    } else {
        print_r($result_values1);
        return 0;
    }
    
}


function opening_stock($item_name,$hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "stock_inward";
    // $where         = [];

    $table_columns = [
        "(select sum(qty) from stock_inward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0 and date_format(entry_date,'%Y-%m') = '".$month_val."' ) as in_qty",
        "(select sum(qty) from stock_outward where item_name = '" . $item_name . "' AND hostel_unique_id = '$hostel_id' and is_delete = 0 and date_format(entry_date,'%Y-%m') = '".$month_val."' ) as out_qty",
        // "sum(qty) as out_qty",
        // "hostel_unique_id",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // $where     = [
    //    "hostel_unique_id" => $hostel_id,
    //    "item_name" => $item_name,
    // //    "date_format(entry_date,'%Y-%m')" => $month_val,
    // ];
    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."'";

    $result_values1 = $pdo->select($table_details, $where);
// print_r($result_values1);
    if ($result_values1->status) {
        $in_qty =  $result_values1->data[0]['in_qty'];
        $out_qty =  $result_values1->data[0]['out_qty'];
        $opening_stock = $in_qty - $out_qty;
        return $opening_stock;
    } else {
        print_r($result_values1);
        return 0;
    }
    
}

?>