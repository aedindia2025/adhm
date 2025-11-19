<?php


function get_in_qty($hostel_id,$month_val) {
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

    $where = "hostel_unique_id = '".$hostel_id."' and date_format(entry_date,'%Y-%m') = '".$month_val."' and is_delete = 0";


    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['in_qty'];
        
    } else {
        print_r($result_values1);
        return 0;
    }
}

function get_out_qty($hostel_id,$month_val) {
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

    $where = "hostel_unique_id = '".$hostel_id."' and date_format(entry_date,'%Y-%m') = '".$month_val."' and is_delete = 0";


    $result_values1 = $pdo->select($table_details, $where);

    if ($result_values1->status) {
        return $result_values1->data[0]['out_qty'];
        
    } else {
        print_r($result_values1);
        return 0;
    }
    
}


function opening_stock($hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "stock_inward";
    // $where         = [];

    $table_columns = [
        "(select sum(qty) from stock_inward where is_delete = 0 and hostel_unique_id = '".$hostel_id."' and date_format(entry_date,'%Y-%m') < '".$month_val."') as in_qty",
        "(select sum(qty) from stock_outward where is_delete = 0 and hostel_unique_id = '".$hostel_id."' and date_format(entry_date,'%Y-%m') < '".$month_val."') as out_qty",
        // "sum(out_qty) as out_qty",
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
    $where = "hostel_unique_id = '".$hostel_id."' and date_format(entry_date,'%Y-%m') < '".$month_val."' and is_delete = 0";

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


function get_in_qty_item($item_name,$hostel_id,$month_val) {
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

function get_out_qty_item($item_name,$hostel_id,$month_val) {
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


function opening_stock_item($item_name,$hostel_id,$month_val) {
    // echo $item_name_val;
    global $pdo;

    $table_name    = "stock_inward";
    // $where         = [];

    $table_columns = [
        "(select sum(qty) from stock_inward where is_delete = 0 and hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') < '".$month_val."') as in_qty",
        "(select sum(qty) from stock_outward where is_delete = 0 and hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') < '".$month_val."') as out_qty",
        // "sum(out_qty) as out_qty",
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
    $where = "hostel_unique_id = '".$hostel_id."' and item_name = '".$item_name."' and date_format(entry_date,'%Y-%m') < '".$month_val."' and is_delete = 0";

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


function item_stock_inward($unique_id = "", $category = "")
{
    global $pdo;

    $table_name = "item";
    $table_columns = [
        "unique_id",
        "item"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // Default where condition
    $where = "is_delete = 0 AND is_active = 1";

    if ($unique_id) {
        // If specific unique_id is provided, override where
        $where .= " AND unique_id = '$unique_id'";
    }

    if ($category) {
        // If specific category is provided, override where
        $where .= " AND category_id = '$category'";
    }

    $item_list = $pdo->select($table_details, $where);

    if ($item_list->status) {
        return $item_list->data;
    } else {
        print_r($item_list);
        return 0;
    }
}

?>