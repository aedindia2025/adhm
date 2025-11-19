<?php


function get_in_qty($item_name,$hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "view_stock_in_outward_list";
    // $where         = [];

    $table_columns = [
        "sum(in_qty) as in_qty",
       
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."'";


    $result_values1 = $pdo->select($table_details, $where);
//print_r($result_values1);

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

    $table_name    = "view_stock_in_outward_list";
    // $where         = [];

    $table_columns = [
        "sum(out_qty) as out_qty",
       
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."'";


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

    $table_name    = "view_stock_in_outward_list";
    // $where         = [];

    $table_columns = [
        "sum(in_qty) as in_qty",
        "sum(out_qty) as out_qty",
        // "hostel_unique_id",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') = '".$month_val."'";

    $result_values1 = $pdo->select($table_details, $where);
    
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