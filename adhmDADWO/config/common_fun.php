<?php
//include_once  'dbconfig.php';
function today($format = "")
{
    if ($format) {
        return date($format);
    }

    return date("Y-m-d");
}

$today = today();
function disdate($date)
{

    $result = "";

    if ($date) {
        $result = implode("-", array_reverse(explode("-", $date)));
    }

    return $result;
}

function establishment_type($unique_id = "")
{
    global $pdo;

    $table_name = "establishment_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "establishment_type",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];



    if ($unique_id) {

        $where["unique_id"] = $unique_id;
    }
    $establishment_name_list = $pdo->select($table_details, $where);

    if ($establishment_name_list->status) {
        return $establishment_name_list->data;
    } else {
        print_r($establishment_name_list);
        return 0;
    }
}


function establishment_reject_reason($unique_id = "")
{
    global $pdo;

    $table_name = "establishment_reject_reason";
    $where = [];
    $table_columns = [
        "reject_reason as unique_id",
        "reject_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $fund_type_list = $pdo->select($table_details, $where);

    if ($fund_type_list->status) {
        return $fund_type_list->data;
    } else {
        print_r($fund_type_list);
        return 0;
    }
}

function product_type_name($unique_id = "", $ex_stk_val = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;

    $table_name = "product_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "product_type",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($ex_stk_val) {
        // $where              = [];
        $where = "";
        $where .= ' unique_id NOT IN (' . $ex_stk_val . ') and is_delete = "0"';
    }

    $product_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($product_type_name->status) {
        return $product_type_name->data;
    } else {
        print_r($product_type_name);
        return 0;
    }
}


function dropout_reason($unique_id = "")
{
    // echo $taluk_name;

    global $pdo;

    $table_name = "dropout_reason";
    $where = [];
    $table_columns = [
        "unique_id",
        "dropout_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }



    $dropout_reason_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);die();

    if ($dropout_reason_list->status) {
        return $dropout_reason_list->data;
    } else {
        print_r($dropout_reason_list);
        return 0;
    }
}

function dropout_reject_reason($unique_id = "")
{
    global $pdo;

    $table_name = "dropout_reject_reason";
    $where = [];
    $table_columns = [
        "reject_reason as unique_id",
        "reject_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $fund_type_list = $pdo->select($table_details, $where);

    if ($fund_type_list->status) {
        return $fund_type_list->data;
    } else {
        print_r($fund_type_list);
        return 0;
    }
}


function supplier_name_creation($unique_id = "")
{
    global $pdo;

    $table_name = "supplier_name_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "supplier_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $supplier_name_list = $pdo->select($table_details, $where);

    if ($supplier_name_list->status) {
        return $supplier_name_list->data;
    } else {
        print_r($supplier_name_list);
        return 0;
    }
}





function dadwo_hostel_list($hostel_values = '')
{

    // print_r($hostel_values);die();

    $option_str = "<option value='' disabled>No Options to Select</option>";


    $dbname = 'adi_dravidar';
    $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $rep_site = str_replace(',', "','", $hostel_values);
    $hostel_name_options = "and unique_id in ('$rep_site')";

    $query = "select unique_id,hostel_name from hostel_name where is_delete='0' $hostel_name_options";
    // echo $query;
    $sql_query = $dbh->prepare($query);
    $sql_query->execute();
    $result = $sql_query->fetchAll();

    foreach ($result as $value) {
        $hostel_unique_id = $value['unique_id'];
        $hostel_name = $value['hostel_name'];

        $option_str .= "<option value=" . $hostel_unique_id . ">" . $hostel_name . "</option>";

    }
    return $option_str;


}



// Convert Original folder Name to Display Name
function disname($name = "")
{
    if ($name) {
        $name = explode("_", $name);
        $name = array_map("ucfirst", $name);
        $name = implode(" ", $name);

        return $name;
    } else {
        return "Empty Title";
    }
}

//Acc Year
function acc_year()
{
    $acc_year = '';
    $curr_year = date("Y");

    $today = strtotime(date("d-m-Y"));
    $end_date = strtotime("31-03-" . $curr_year);
    $start_date = strtotime("01-04-" . $curr_year);

    if ($today >= $start_date) {
        $next_year = $curr_year + 1;
        $acc_year = $curr_year . "-" . $next_year;
    } else if ($today <= $end_date) {
        $previous_year = $curr_year - 1;
        $acc_year = $previous_year . "-" . $curr_year;
    }

    return $acc_year;

}
// Uniqui ID Geneartor
function unique_id($prefix = "")
{

    $unique_id = uniqid() . rand(10000, 99999);

    if ($prefix) {
        $unique_id = $prefix . $unique_id;
    }

    return $unique_id;
}

function taluk_name($unique_id = "", $district_name = "")
{


    global $pdo;

    $table_name = "taluk_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "taluk_name",
        // "district_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }


    if ($district_name) {
        // $where              = [];
        $where["district_name"] .= $district_name;

    }


    $taluk_name_list = $pdo->select($table_details, $where);
    // print_r($taluk_name_list);

    if ($taluk_name_list->status) {
        return $taluk_name_list->data;
    } else {
        print_r($taluk_name_list);
        return 0;
    }
}


//inspection hostel
function inspection_hostel($unique_id = "", $district_id = "")
{
    global $pdo;

    $table_name = "hostel_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "hostel_name",
        // "district_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_id) {
        $where = [];
        $where["district_name"] = $district_id;
    }


    $taluk_name_list = $pdo->select($table_details, $where);
    // print_r($taluk_name_list);

    if ($taluk_name_list->status) {
        return $taluk_name_list->data;
    } else {
        print_r($taluk_name_list);
        return 0;
    }
}




function product_category($unique_id = "")
{
    global $pdo;

    $table_name = "product_category";
    $where = [];
    $table_columns = [
        "unique_id",
        "product_category"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $product_category_list = $pdo->select($table_details, $where);

    if ($product_category_list->status) {
        return $product_category_list->data;
    } else {
        print_r($product_category_list);
        return 0;
    }
}


// Datatables Total Records Count Function
function total_records()
{
    global $pdo;

    $total_records = 0;
    $sql = "SELECT FOUND_ROWS() as total";
    $result = $pdo->query($sql);
    if ($result->status) {
        $total = $result->data[0]["total"];
    }

    return $total;
}

// Active and In Active Show in Data Table
function is_active_show($is_active = 0)
{
    $act_str = "<span style='color: red'>In Active</span>";

    if ($is_active) {
        $act_str = "<span style='color: green'>Active</span>";
    }

    return $act_str;
}

function active_status($is_active_val = 1)
{
    $option_str = "";
    $is_active = "";
    $is_inactive = "";

    if ($is_active_val == 1) {
        $is_active = " selected = 'selected' ";
    } else {
        $is_inactive = " selected = 'selected' ";
    }

    $option_str = "<option value='1'" . $is_active . ">Active</option>";
    $option_str .= "<option value='0'" . $is_inactive . ">In Active</option>";

    return $option_str;
}


// Main Screen Function
function main_screen($unique_id = "")
{
    global $pdo;

    $table_name = "user_screen_main";
    $where = [];
    $table_columns = [
        "unique_id",
        "screen_main_name",
        "icon_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    $order_by = "order_no ASC";

    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $main_screens = $pdo->select($table_details, $where, '', '', $order_by);

    if ($main_screens->status) {
        return $main_screens->data;
    } else {
        print_r($main_screens);
        return 0;
    }
}

// Screen Type Function
function screen_type($unique_id = "")
{
    global $pdo;

    $table_name = "user_screen_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "type_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    $screen_types = $pdo->select($table_details, $where);

    if ($screen_types->status) {
        return $screen_types->data;
    } else {
        print_r($screen_types);
        return 0;
    }
}


function user_type($unique_id = "")
{

    global $pdo;

    $table_name = "user_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "user_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    $user_types = $pdo->select($table_details, $where);
    if ($user_types->status) {
        return $user_types->data;
    } else {
        print_r($user_types);
        return 0;
    }
}
// User Screen
function user_screen($unique_id = "", $screen_section_id = "", $folder_name = "")
{
    global $pdo;

    $table_name = "user_screen";
    $where = [];
    $table_columns = [
        "unique_id",
        "screen_name",
        "folder_name",
        "icon_name",
        "main_screen_unique_id",
        "actions",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    $order_by = [
        "order_no"
    ];

    if ($screen_section_id) {

        $where["main_screen_unique_id"] = $screen_section_id;
    }
    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($folder_name) {

        $where = [];
        $where["folder_name"] = $folder_name;
    }

    $user_screens = $pdo->select($table_details, $where, "", "", $order_by);

    // print_r($user_screens);
    if ($user_screens->status) {
        return $user_screens->data;
    } else {
        print_r($user_screens);
        return 0;
    }
}

// User Screen Actions Function
function user_actions($unique_id = "")
{
    global $pdo;

    $table_name = "user_screen_actions";
    $where = [];
    $table_columns = [
        "unique_id",
        "action_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $user_actions = $pdo->select($table_details, $where);

    if ($user_actions->status) {
        return $user_actions->data;
    } else {
        print_r($user_actions);
        return 0;
    }
}

function reject_reason($unique_id = '')
{
    global $pdo;

    $table_name = 'reject_reason';
    $where = [];
    $table_columns = [
        'reject_reason as unique_id',
        'reject_reason',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_active' => 1,
        'is_delete' => 0,
    ];

    if ($unique_id) {
        // $where              = [];
        $where['unique_id'] .= $unique_id;
    }

    $reject_reason_list = $pdo->select($table_details, $where);

    if ($reject_reason_list->status) {
        return $reject_reason_list->data;
    } else {
        print_r($reject_reason_list);

        return 0;
    }
}


function datatable_sorting($column = 0, $direction = "ASC", $columns_array = [])
{
    $order_by = "";
    if (!empty($columns_array)) {
        if ($column) {

            $is_found = strripos($columns_array[$column], " AS ");

            if ($is_found) {
                $as_column = substr($columns_array[$column], $is_found + 3);
            } else {
                $as_column = false;
            }

            if ($as_column) {
                $order_by = $as_column . " " . $direction;
            } else {
                $order_by = $columns_array[$column] . " " . $direction;
            }
        }
    }
    return $order_by;
}

function btn_close($list_link = "")
{
    $final_str = '<a href="' . $list_link . '"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2" >Close</button></a>';

    return $final_str;
}

function datatable_searching($search_query = '', $columns_array = [])
{
    $search_string = "";

    if ($search_query) {
        if (!empty($columns_array)) {
            // Remove AS in Subquery in $columns_array
            $temp_arr = [];
            foreach ($columns_array as $col_key => $col_value) {

                $is_found = strripos($col_value, " AS ");

                if ($is_found) {
                    $as_column = substr($col_value, 0, $is_found);
                } else {
                    $as_column = $col_value;
                }
                $temp_arr[] = $as_column . " LIKE '%" . $search_query . "%' ";
            }

            unset($temp_arr[count($temp_arr) - 1]); // Unique ID Endry Disable
            unset($temp_arr[0]); // S.No Search Disable
            $search_string = implode(" OR ", $temp_arr);
        }
    }
    return $search_string;
}


function select_option($options = [], $description = "", $is_selected = [], $is_disabled = [])
{

    $option_str = "<option value='' disabled>No Options to Select</option>";

    $data_attribute = "";

    if ($options) {

        $option_str = "<option value=''>Select</option>";

        if ($description) {
            $option_str = "<option value='' selected>" . $description . "</option>";
        }
        foreach ($options as $key => $value) {

            $value = array_values($value);
            $selected = "";
            $disabled = "";

            if (is_array($is_selected) and in_array($value[0], $is_selected)) {
                $selected = " selected='selected' ";
            } elseif ($is_selected == $value[0]) {

                $selected = " selected='selected' ";
            }

            if (is_array($is_disabled) and in_array($value[0], $is_disabled)) {
                $disabled = " disabled='disabled' ";
            } elseif ($is_disabled == $value[0]) {
                $disabled = " disabled='disabled' ";
            }

            if (strpos($value[1], "_")) {
                $value[1] = disname($value[1]);
            } else {
                $value[1] = ucfirst($value[1]);
            }

            if (isset($value[2])) {
                $data_attribute = "data-extra='" . $value[2] . "'";
            }

            $option_str .= "<option value='" . $value[0] . "'" . $data_attribute . $selected . $disabled . ">" . $value[1] . "</option>";
        }
    }

    return $option_str;
}

function btn_add($add_link = "")
{
    $final_str = '<a href="' . $add_link . '" class="btn btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> Add New</a>';

    return $final_str;
}

function btn_cancel($list_link = "")
{
    $final_str = '<a href="' . $list_link . '"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2" >Cancel</button></a>';

    return $final_str;
}

function btn_createupdate($folder_name = "", $unique_id = "", $btn_text, $prefix = "", $suffix = "_cu", $custom_class = "")
{

    $final_str = '<button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="' . $folder_name . $suffix . '(\'' . $unique_id . '\')">' . $btn_text . '</button>';

    return $final_str;
}

function btn_update($folder_name = "", $unique_id = "", $prefix = "", $suffix = "")
{

    // $final_str = '<a class="btn btn-primary btn-action mr-1" data-toggle="modal" data-target="#exampleModal" data-unique_id = "'.$unique_id.'"  data-toggle="tooltip" title="Edit"><i
    //                           class="fas fa-pencil-alt"></i></a>';

    $password = '3sc3RLrpd17';
    $enc_method = 'aes-256-cbc';
    $enc_password = substr(hash('sha256', $password, true), 0, 32);
    $enc_iv = "av3DYGLkwBsErphc";


    $menu_screen = $folder_name . "/model";
    $file_name_update = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    $uni_id = base64_encode(openssl_encrypt($unique_id, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    //    $final_str = ' <a href="index.php?file='.$prefix.$folder_name.$suffix.'/model&unique_id='.$unique_id.'" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';

    $final_str = ' <a href="index.php?file=' . $file_name_update . '&unique_id=' . $uni_id . '" class="font-18 text-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Edit" data-bs-original-title="Edit"><i class="uil uil-pen"></i></a>';


    return $final_str;
}
//<a class="btn  btn-action mr-1 specl" href="index.php?file='.$prefix.$folder_name.$suffix.'/model&unique_id='.$unique_id.'"><i class="far fa-edit"></i></a>
function btn_edit($folder_name = "", $unique_id = "")
{
    $final_str = '<button type="button" class="btn btn-primary btn-action mr-1" onclick="' . $folder_name . '_edit(\'' . $unique_id . '\')"><i class="fas fa-pencil-alt"></i></button>';

    $final_str = '<a href="#" class="btn btn-primary btn-action mr-1" onclick="' . $folder_name . '_edit(\'' . $unique_id . '\')"><i class="fas fa-pencil-alt"></i></a>';

    return $final_str;
}

function btn_delete($folder_name = "", $unique_id = "")
{

    $final_str = '<a href="#" onclick="' . $folder_name . '_delete(\'' . $unique_id . '\')" class="font-18 text-danger" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Delete" data-bs-original-title="Delete"><i class="uil uil-trash"></i></a>';


    return $final_str;
}
//<a class="btn btn-danger btn-action specl2" href="#" onclick="'.$folder_name.'_delete(\''.$unique_id.'\')"><i class="far fa-trash-alt"></i></a>

function menu_permission($user_type_id = "")
{

    $return_arr = [
        "main_screens" => "",
        "sections" => "",
        "screens" => ""
    ];

    if ($user_type_id) {

        global $pdo;

        $table_user_permission = "user_screen_permission";

        $select_where = [
            "user_type" => $user_type_id
        ];

        $screen_columns = [
            // "GROUP_CONCAT(main_screen_unique_id) AS main_screens",
            // "GROUP_CONCAT(section_unique_id) AS sections",
            "GROUP_CONCAT(DISTINCT  screen_unique_id) AS screens"
        ];

        $table_details = [
            $table_user_permission,
            $screen_columns
        ];

        $group_by = " screen_unique_id ";

        $screen_result = $pdo->select($table_details, $select_where, "", "", "", "", $group_by);

        // print_r($screen_result);
        if ($screen_result->status) {
            $screen_result_data = $screen_result->data;

            $return_arr["screens"] = array_implode($screen_result_data);
        } else {
            print_r($screen_result);
            echo "Menu Permission Error";
            exit;
        }


        $main_screen_columns = [
            "GROUP_CONCAT(DISTINCT  main_screen_unique_id) AS main_screens"
        ];

        $table_details = [
            $table_user_permission,
            $main_screen_columns
        ];

        $group_by = " main_screen_unique_id ";

        $main_screen_result = $pdo->select($table_details, $select_where, "", "", "", "", $group_by);

        if ($main_screen_result->status) {
            $main_screen_result_data = $main_screen_result->data;
            //print_r($main_screen_result_data);

            $return_arr["main_screens"] = array_implode($main_screen_result_data);

        } else {
            print_r($main_screen_result);
            echo "Section Permission Error";
            exit;
        }
    }

    return $return_arr;
}


function array_implode($value_arr = "")
{

    $return_arr = [];

    if (is_array($value_arr)) {

        foreach ($value_arr as $arr_key => $arr_value) {

            $return_arr[] = array_values($arr_value)[0];

        }

    }

    return $return_arr;
}

// Bill No Generation
function bill_no($table_name, $where, $prefix = "", $y = 1, $m = 1, $d = 1, $custom_date = false, $separator = "-")
{
    $billno = $prefix;

    if (!$custom_date) {
        $custom_date = date("Y-m-d");
    }

    if ($y) {
        $billno .= date('Y', strtotime($custom_date));
    }

    if ($m) {
        $billno .= date('m', strtotime($custom_date)) . $separator;
    }



    $bill_order_no = save_status($table_name, $where);

    $billno .= sprintf("%04d", $bill_order_no);

    return $billno;
}

// Get Final Bill No

function save_status($table_name, $where)
{
    if ($table_name) {
        global $pdo;

        $columns = [
            "count(acc_year) AS save_status"
        ];

        $table_details = [
            $table_name,
            $columns
        ];

        $result = $pdo->select($table_details, $where);

        if ($result->status) {

            $res_array = $result->data[0]['save_status'] + 1;

        } else {
            print_r($result);
            $res_array = uniqid() . rand(10000, 99999) . "Error";
        }

        return $res_array;
    }
}


function btn_print($folder_name = "", $unique_id = "", $file_name = "", $prefix = "", $suffix = "")
{
    $final_str = '<a class="btn btn-action specl2" target="_blank" href="index.php?file=' . $prefix . $folder_name . $suffix . '/' . $file_name . '&unique_id=' . $unique_id . '"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

    $final_str = '<a class="btn  btn-action specl2" target="_blank" href="index.php?file=' . $prefix . $folder_name . $suffix . '/' . $file_name . '&unique_id=' . $unique_id . '"><i class="fa fa-eye" style="color:green;"></i></a>';

    return $final_str;
}

function btn_print_approval($folder_name = "", $batch_no = "", $file_name = "", $prefix = "", $suffix = "")
{
    $password = '3sc3RLrpd17';
    $enc_method = 'aes-256-cbc';
    $enc_password = substr(hash('sha256', $password, true), 0, 32);
    $enc_iv = "av3DYGLkwBsErphc";

    $menu_screen = $batch_no;
    $batch_no = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    $menu_screen = $folder_name . '/' . $file_name;
    $folder_name = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));

    // $menu_screen    = $file_name;
    // $file_name      = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));


    $final_str = '<a class="btn btn-action specl2" target="_blank" href="index.php?file=' . $prefix . $folder_name . $suffix . '&batch_no=' . $batch_no . '"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

    $final_str = '<a class="btn btn-action specl2" target="_blank" href="index.php?file=' . $prefix . $folder_name . $suffix . '&batch_no=' . $batch_no . '"><i class="fa fa-eye" style="color:green;"></i></a>';

    return $final_str;
}

function btn_prints($folder_name = "", $unique_id = "", $file_name = "", $prefix = "", $suffix = "")
{


    $final_str = '<a class="btn btn-action specl2" target="_blank" onclick="new_external_window_prints(event,' . $unique_id . ');"><button type="button" ><i class="fa fa-eye" ></i></button></a>';

    // $final_str = '<a class="btn btn-danger btn-action specl2" target="_blank" onclick="new_external_window_prints(event,'.$unique_id.');"><i class="fa fa-eye" style="color:green;"></i></a>';

    return $final_str;

}





function sublist_delete($table_name = "", $sub_unique_ids = "", $main_unique_id = [])
{

    global $pdo;

    if ($table_name) {

        if (($sub_unique_ids) && (!empty($main_unique_id))) {

            $column_name = array_keys($main_unique_id)[0];
            $column_value = $main_unique_id[$column_name];

            $where = " unique_id NOT IN (" . $sub_unique_ids . ") AND " . $column_name . "  = '" . $column_value . "'";

            $columns = [
                "is_delete" => 1
            ];

            $update_result = $pdo->update($table_name, $columns, $where);

            if ($update_result->status) {

            } else {
                print_r($update_result);
            }
        } else {
            echo "Sub List Delete Status Update Error";
        }

    }
}

function sublist_insert_update($table_name = "", $data = "", $prefix = "")
{
    global $pdo;
    if ($table_name) {
        foreach ($data as $data_key => $columns) {

            $unique_id = $columns['unique_id'];

            if ($unique_id) {

                unset($columns['unique_id']);

                $update_where = [
                    "unique_id" => $unique_id
                ];

                $action_obj = $pdo->update($table_name, $columns, $update_where);

                // Update Ends
            } else {
                $columns['unique_id'] = $prefix . unique_id();
                // Insert Begins            
                $action_obj = $pdo->insert($table_name, $columns);
                // Insert Ends
            }

            if ($action_obj->status) {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = "";
                $sql = $action_obj->sql;

                if ($unique_id) {
                    $msg = "update";
                } else {
                    $msg = "create";
                }
            } else {
                $status = $action_obj->status;
                $data = $action_obj->data;
                $error = $action_obj->error;
                $sql = $action_obj->sql;
                $msg = "error";

                print_r($action_obj);
                break;
            }
        }
    } else {
        echo "table name not given";
    }
}


function student_id($unique_id = "", $hostel_id = "")
{

    global $pdo;

    $table_name = "std_reg_s";
    $where = [];
    $table_columns = [
        "unique_id",
        "std_reg_no"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        "dropout_status" => 1
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($hostel_id) {
        $where = [];
        $where["hostel_name"] = $hostel_id;
    }

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);die();

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function company_name($unique_id = "")
{
    global $pdo;

    $table_name = "company_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "company_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $company_name_list = $pdo->select($table_details, $where);

    if ($company_name_list->status) {
        return $company_name_list->data;
    } else {
        print_r($company_name_list);
        return 0;
    }
}

// under user Function
function team_user($user_id = "")
{
    global $pdo;

    $table_name = "user";
    $where = "";
    $table_columns = [
        "unique_id",
        "user_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "is_delete = 0 AND is_active = 1   AND is_team_head = 0 AND user_name != '" . $user_id . "'";

    /* if ($unique_id) {

         $where["unique_id"] = $unique_id;
     }*/

    $user_name_list = $pdo->select($table_details, $where);

    if ($user_name_list->status) {
        return $user_name_list->data;
    } else {
        print_r($user_name_list);
        return 0;
    }
}
// under user Function
function under_user($user_id = "")
{
    global $pdo;

    $table_name = "user";
    $where = "";
    $table_columns = [
        "unique_id",
        "user_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "is_delete = 0 AND is_active = 1  AND user_name != '" . $user_id . "'";

    /* if ($unique_id) {

         $where["unique_id"] = $unique_id;
     }*/

    $user_name_list = $pdo->select($table_details, $where);

    if ($user_name_list->status) {
        return $user_name_list->data;
    } else {
        print_r($user_name_list);
        return 0;
    }
}
function staff_name($unique_id = "")
{
    global $pdo;

    $table_name = "staff_incharge";
    $where = [];
    $table_columns = [
        "unique_id",
        "staff_name",
        "phone_no"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $staff_name_list = $pdo->select($table_details, $where);

    if ($staff_name_list->status) {
        return $staff_name_list->data;
    } else {
        print_r($staff_name_list);
        return 0;
    }
}

function project($unique_id = "")
{
    global $pdo;
    // print_r($unique_id);
    $table_name = "project_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "project_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];
    // $where = "is_delete =  0";

    if ($unique_id) {

        $where = [];

        $where["unique_id"] = $unique_id;
    }

    $project_name_list = $pdo->select($table_details, $where);
    // print_r($project_name_list);
    if ($project_name_list->status) {
        return $project_name_list->data;
    } else {
        print_r($project_name_list);
        return 0;
    }
}
function get_project($project_name = "")
{
    global $pdo;
    // print_r($project_name);
    $table_name = "project_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "project_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0
    ];
    // $where = "is_delete =  0";

    if ($project_name) {
        // $where = " and unique_id = '".$unique_id."'";
        $table_details = $table_name;
        $where = [];
        // $where .= " and unique_id = '".$project_name."'";
        $where["unique_id"] = $project_name;
    }

    $project_name_list = $pdo->select($table_details, $where);
    // print_r($project_name_list);
    if ($project_name_list->status) {
        return $project_name_list->data;
    } else {
        print_r($project_name_list);
        return 0;
    }
}

function call_type($unique_id = "")
{
    global $pdo;

    $table_name = "call_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "call_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $call_type_list = $pdo->select($table_details, $where);
    // print_r($call_type_list);
    if ($call_type_list->status) {
        return $call_type_list->data;
    } else {
        print_r($call_type_list);
        return 0;
    }
}
function get_my_projectname($unique_id = "")
{
    global $pdo;
    // print_r($unique_id);
    $table_name = "project_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "project_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $call_type_list = $pdo->select($table_details, $where);

    if ($call_type_list->status) {
        return $call_type_list->data;
    } else {
        print_r($call_type_list);
        return 0;
    }
}

function task_type($unique_id = "")
{
    global $pdo;

    $table_name = "task_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "task_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $task_type_list = $pdo->select($table_details, $where);

    if ($task_type_list->status) {
        return $task_type_list->data;
    } else {
        print_r($task_type_list);
        return 0;
    }
}

function status_type($unique_id = "")
{
    global $pdo;

    $table_name = "status_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "status"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $status_list = $pdo->select($table_details, $where);

    if ($status_list->status) {
        return $status_list->data;
    } else {
        print_r($status_list);
        return 0;
    }
}

function remark_type($unique_id = "")
{
    global $pdo;

    $table_name = "remark_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "remark_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $remark_type_list = $pdo->select($table_details, $where);

    if ($remark_type_list->status) {
        return $remark_type_list->data;
    } else {
        print_r($remark_type_list);
        return 0;
    }
}

function designation($unique_id = "")
{
    global $pdo;

    $table_name = "designation_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "designation_name"

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $desination_type_list = $pdo->select($table_details, $where);

    if ($desination_type_list->status) {
        return $desination_type_list->data;
    } else {
        print_r($desination_type_list);
        return 0;
    }
}

function hostel_designation($unique_id = "")
{
    global $pdo;

    $table_name = "designation_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "designation_name",
        "designation_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0,
        "designation_type" => 'Establishment'
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $desination_type_list = $pdo->select($table_details, $where);

    if ($desination_type_list->status) {
        return $desination_type_list->data;
    } else {
        print_r($desination_type_list);
        return 0;
    }
}



function check_remark_type($unique_id = "")
{
    global $pdo;

    $table_name = "check_remark_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "remark_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [

    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $remark_type_list = $pdo->select($table_details, $where);

    if ($remark_type_list->status) {
        return $remark_type_list->data;
    } else {
        print_r($remark_type_list);
        return 0;
    }
}

//datatable search
function mysql_like($search_query = "", $search_term = "")
{

    $return_result = "";

    if ($search_query) {
        switch ($search_term) {
            case "first":
                $return_result = "%" . $search_query;
                break;

            case "last":
                $return_result = $search_query . "%";
                break;

            default:
                // For All result
                $return_result = "%" . $search_query . "%";
                break;
        }
    }

    return $return_result;
}


// district name get function
// district name
function district_name($unique_id = "")
{
    global $pdo;

    $table_name = "district_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "district_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $district_name_list = $pdo->select($table_details, $where);


    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);
        return 0;
    }
}

function student_name($unique_id = "")
{
    global $pdo;

    $table_name = "student_onboarding";
    $where = [];
    $table_columns = [
        "unique_id",
        "student_name",
        "student_id",
        "student_district",
        "student_zone",
        "student_hostel"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $student_name_list = $pdo->select($table_details, $where);

    if ($student_name_list->status) {
        return $student_name_list->data;
    } else {
        print_r($student_name_list);
        return 0;
    }
}




// zone name
function zone_name($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "zone_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "zone_name",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {

        $where["district_name"] = $district_name;
    }

    $zone_name_list = $pdo->select($table_details, $where);

    if ($zone_name_list->status) {
        return $zone_name_list->data;
    } else {
        print_r($zone_name_list);
        return 0;
    }
}

function fetch_academic_year($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "academic_year";
    $where = [];
    $table_columns = [
        "unique_id",
        "acc_year"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    // if ($acc_year) {
    //     $where              = [];
    //     $where["acc_year"] = $acc_year;
    // }
    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }



    $hostel_name_list = $pdo->select($table_details, $where);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}
// hostel name

function hostel_type_name($unique_id = "")
{
    global $pdo;

    $table_name = "hostel_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "hostel_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $hostel_type_list = $pdo->select($table_details, $where);


    if ($hostel_type_list->status) {
        return $hostel_type_list->data;
    } else {
        print_r($hostel_type_list);
        return 0;
    }
}

function hostel_gender_name($unique_id = "")
{
    global $pdo;

    $table_name = "hostel_gender_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "gender_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $gender_type_list = $pdo->select($table_details, $where);


    if ($gender_type_list->status) {
        return $gender_type_list->data;
    } else {
        print_r($gender_type_list);
        return 0;
    }
}


function hostel_type($unique_id = '', $district_name = '', $taluk_name = '', $gender_type = '')
{
    global $pdo;

    $table_name = 'hostel_type';
    $where = [];
    $table_columns = [
        'unique_id',
        'hostel_type',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_active' => 1,
        'is_delete' => 0,
    ];

    if ($unique_id) {
        // $where = [];
        $where['unique_id'] .= $unique_id;
    }

    if ($district_name) {
        // $where              = [];
        $where['district_name'] .= $district_name;
    }

    if ($taluk_name) {
        // $where              = [];
        $where['taluk_name'] .= $taluk_name;
    }

    if ($gender_type) {
        // $where              = [];
        $where['gender_type'] .= $gender_type;
    }

    $group_by = 'hostel_type';

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);

        return 0;
    }
}



function renewal_reject_reason($unique_id = "")
{
    global $pdo;

    $table_name = "renewal_reject_reason";
    $where = [];
    $table_columns = [
        "reject_reason as unique_id",
        "reject_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $fund_type_list = $pdo->select($table_details, $where);

    if ($fund_type_list->status) {
        return $fund_type_list->data;
    } else {
        print_r($fund_type_list);
        return 0;
    }
}


function renewal_accept_reason($unique_id = "")
{
    global $pdo;

    $table_name = "renewal_accept_reason";
    $where = [];
    $table_columns = [
        "accept_reason as unique_id",
        "accept_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $fund_type_list = $pdo->select($table_details, $where);

    if ($fund_type_list->status) {
        return $fund_type_list->data;
    } else {
        print_r($fund_type_list);
        return 0;
    }
}


function gender_type($unique_id = '', $district_name = '', $taluk_name = '')
{
    global $pdo;

    $table_name = 'hostel_gender_type';
    $where = [];
    $table_columns = [
        'unique_id',
        'gender_type',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_active' => 1,
        'is_delete' => 0,
    ];

    if ($unique_id) {
        // $where              = [];
        $where['unique_id'] .= $unique_id;
    }

    // $group_by = "gender_type";

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);

        return 0;
    }
}

function medium_type($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "medium_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "medium_type"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }



    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);
    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}


function courseName_options($unique_id = '', $no_umis_stream = '')
{
    global $pdo;

    $table_name = 'umis_course';
    $where = [];
    $table_columns = [
        'id',
        'CourseName',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    if ($unique_id) {
        // $where = [];
        $where['id'] .= $unique_id;
    }

    if ($no_umis_stream) {
        // $where = [];
        $where['CourseType'] .= $no_umis_stream;
    }

    $district_name_list = $pdo->select($table_details, $where);

    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);

        return 0;
    }
}

function course_name($unique_id = "", $stream_type = "", $university_name = "", $std_college_name = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "course_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "course_name"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    // if ($school_name) {
    //     // $where              = [];
    //     $where["school_unique_id"] .= $school_name;
    // }

    if ($university_name) {
        // $where              = [];
        $where["university_unique_id"] .= $university_name;
    }

    if ($std_college_name) {
        // $where              = [];
        $where["college_unique_id"] .= $std_college_name;
    }


    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);
    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}


function stream_type($unique_id = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "stream_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "stream_type"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $hostel_name_list = $pdo->select($table_details, $where);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function university_name($unique_id = "", $stream_type = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "university_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "university_name"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($stream_type) {
        $where = [];
        $where["stream_unique_id"] .= $stream_type;
    }

    $hostel_name_list = $pdo->select($table_details, $where);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function college_name($unique_id = "", $stream_type = "", $university_name = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "college_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "college_name"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($stream_type) {
        // $where              = [];
        $where["stream_unique_id"] .= $stream_type;
    }

    if ($university_name) {
        // $where              = [];
        $where["university_unique_id"] .= $university_name;
    }

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);
    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function school_name($unique_id = "", $stream_type = "", $university_name = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "school_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "school_name"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($stream_type) {
        // $where              = [];
        $where["stream_unique_id"] .= $stream_type;
    }

    if ($university_name) {
        // $where              = [];
        $where["university_unique_id"] .= $university_name;
    }

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);
    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function academic_years($unique_id = "", $amc_year = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "academic_year_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "amc_year"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($amc_year) {
        $where = [];
        $where["amc_year"] = $amc_year;
    }
    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $amc_name_list = $pdo->select($table_details, $where);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function student_names($unique_id = "")
{
    global $pdo;

    $table_name = "std_reg_p1";
    $where = [];
    $table_columns = [
        "unique_id",
        "std_name",
        "std_reg_no"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $student_names_list = $pdo->select($table_details, $where);

    if ($student_names_list->status) {
        return $student_names_list->data;
    } else {
        print_r($student_names_list);
        return 0;
    }
}


function group_name($unique_id = "", $std_class = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "subject_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "subject_name"


    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];


    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($std_class) {
        // $where              = [];
        $where["standard_unique_id"] .= $std_class;
    }

    $group_by = "subject_name";

    $hostel_name_list = $pdo->select($table_details, $where, "", "", "", "", $group_by);
    // print_r($hostel_name_list);
    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}

function hostel_name($unique_id = "", $taluk_name = "", $district_name = "")
{

    global $pdo;

    $table_name = "hostel_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "hostel_name",
        "hostel_id",
        "sanctioned_strength",
        "(sanctioned_strength + additional_strength - transfer_strength) as sanc_strength"

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    if ($taluk_name) {
        // $where              = [];
        $where["taluk_name"] .= $taluk_name;
    }

    if ($district_name) {
        // $where              = [];
        $where["district_name"] = $district_name;
    }

    $hostel_name_list = $pdo->select($table_details, $where);
    // print_r($hostel_name_list);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);
        return 0;
    }
}


function batch_no($unique_id = '', $district_name = '', $hostel_name = '', $academic_year = '')
{
    global $pdo;

    $table_name = 'batch_creation';
    $where = [];
    $table_columns = [
        'batch_no as id',
        'batch_no',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    // $where     = [
    //     "is_active" => 1,
    //     "is_delete" => 0
    // ];

    if ($unique_id) {
        // $where = [];
        $where['batch_no'] .= $unique_id;
    }

    if ($district_name) {
        // $where = [];
        $where['hostel_district'] = $district_name;
    }

    if ($hostel_name) {
        // $where = [];
        $where['hostel_name'] .= $hostel_name;
    }

    if ($academic_year) {
        // $where = [];
        $where['academic_year'] .= $academic_year;
    }

    $group_by = 'batch_no';

    $district_name_list = $pdo->select($table_details, $where, '', '', '', '', $group_by);

    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);

        return 0;
    }
}


function select_option_host($options = [], $description = "", $is_selected = [], $is_disabled = [])
{

    $option_str = "<option value='' disabled>No Options to Select</option>";

    $data_attribute = "";

    if ($options) {

        $option_str = "<option value=''>Select</option>";

        if ($description) {
            $option_str = "<option value='' selected>" . $description . "</option>";
        }
        foreach ($options as $key => $value) {

            $value = array_values($value);
            $selected = "";
            $disabled = "";

            if (is_array($is_selected) and in_array($value[0], $is_selected)) {
                $selected = " selected='selected' ";
            } elseif ($is_selected == $value[0]) {

                $selected = " selected='selected' ";
            }

            if (is_array($is_disabled) and in_array($value[0], $is_disabled)) {
                $disabled = " disabled='disabled' ";
            } elseif ($is_disabled == $value[0]) {
                $disabled = " disabled='disabled' ";
            }

            if (strpos($value[1], "_")) {
                $value[1] = disname($value[1]);
            } else {
                $value[1] = ucfirst($value[1]);
            }

            if (isset($value[2])) {
                $data_attribute = "data-extra='" . $value[2] . "'";
            }

            $option_str .= "<option value='" . $value[0] . "'" . $data_attribute . $selected . $disabled . ">" . $value[1] . " - " . $value[2] . "</option>";
        }
    }

    return $option_str;
}

// get room and bed
function hostel_room($unique_id = "", $hostel_name = "")
{
    global $pdo;

    $table_name = "room_in_hostel";
    $where = [];
    $table_columns = [
        "unique_id",
        'room_no'
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($hostel_name) {
        $where = [];
        $where["hostel_name"] = $hostel_name;
    }

    $hostel_room_list = $pdo->select($table_details, $where);

    if ($hostel_room_list->status) {
        return $hostel_room_list->data;
    } else {
        print_r($hostel_room_list);
        return 0;
    }
}

function room_bed($unique_id = "", $room_no = "")
{
    print_r($room_no);

    global $pdo;

    $table_name = "room_in_hostel";
    $where = [];
    $table_columns = [
        "unique_id",
        'no_of_bed'
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($room_no) {
        $where = [];
        $where["unique_id"] = $room_no;
    }

    $room_bed_list = $pdo->select($table_details, $where);

    if ($room_bed_list->status) {
        return $room_bed_list->data;
    } else {
        print_r($room_bed_list);
        return 0;
    }
}

// fund name get
function fund_name($unique_id = "")
{

    global $pdo;

    $table_name = "fund_name_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "fund_name",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }


    $fund_name = $pdo->select($table_details, $where);
    if ($fund_name->status) {
        return $fund_name->data;
    } else {
        print_r($fund_name);
        return 0;
    }
}



//asset_category function write by Manoj
function asset_category($unique_id = "")
{

    global $pdo;

    $table_name = "asset_category";
    $where = [];
    $table_columns = [
        "unique_id",
        "asset_category"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $asset_categorys = $pdo->select($table_details, $where);
    if ($asset_categorys->status) {
        return $asset_categorys->data;
    } else {
        print_r($asset_categorys);
        return 0;
    }
}


// get fund category write by jeeva
function fund_category($unique_id = "")
{
    global $pdo;

    $table_name = "fund_name_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "fund_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        'is_delete' => 0,
        'is_active' => 1,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $fund_type_list = $pdo->select($table_details, $where);

    if ($fund_type_list->status) {
        return $fund_type_list->data;
    } else {
        print_r($fund_type_list);
        return 0;
    }
}



function academic_year($unique_id = "", $amc_year = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "academic_year_creation";
    $where = [];
    $table_columns = [
        "unique_id",

        "amc_year",
        "acc_year",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($amc_year) {
        $where = [];
        $where["amc_year"] = $amc_year;
    }
    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }
    $group_by = "acc_year";

    $amc_name_list = $pdo->select($table_details, $where, "", "", "", "", $group_by);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}



function last_academic_year($unique_id = "", $amc_year = "")
{
    // echo $zone_name;

    global $pdo;

    $table_name = "academic_year_creation";
    $where = [];
    $table_columns = [
        "unique_id",

        "amc_year",
        "acc_year",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($amc_year) {
        $where = [];
        $where["amc_year"] = $amc_year;
    }
    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }
    // $group_by = "acc_year";
    $order_by = 's_no desc limit 1';

    $amc_name_list = $pdo->select($table_details, $where, "", "", $order_by);

    // print_r( $amc_name_list);

    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['unique_id'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}




function random_strings($length_of_string)
{

    // String of all alphanumeric character
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    // Shuffle the $str_result and returns substring
    // of specified length
    return substr(
        str_shuffle($str_result),
        0,
        $length_of_string
    );
}



function select_option_acc($options = [], $description = "", $is_selected = [], $is_disabled = [])
{

    $option_str = "<option value='' disabled>No Options to Select</option>";

    $data_attribute = "";

    if ($options) {

        // $option_str     = "<option value=''>Select</option>";

        // if ($description) {
        //     $option_str     = "<option value='' selected>".$description."</option>";
        // }
        foreach ($options as $key => $value) {

            $value = array_values($value);
            $selected = "";
            $disabled = "";

            if (is_array($is_selected) and in_array($value[0], $is_selected)) {
                $selected = " selected='selected' ";
            } elseif ($is_selected == $value[0]) {

                $selected = " selected='selected' ";
            }

            if (is_array($is_disabled) and in_array($value[0], $is_disabled)) {
                $disabled = " disabled='disabled' ";
            } elseif ($is_disabled == $value[0]) {
                $disabled = " disabled='disabled' ";
            }

            if (strpos($value[1], "_")) {
                $value[1] = disname($value[1]);
            } else {
                $value[1] = ucfirst($value[1]);
            }

            if (isset($value[2])) {
                $data_attribute = "data-extra='" . $value[2] . "'";
            }

            $option_str .= "<option value='" . $value[0] . "'" . $data_attribute . $selected . $disabled . ">" . $value[1] . "</option>";
        }
    }

    return $option_str;
}


function age($unique_id = "")
{
    // print_r($unique_id);

    global $pdo;

    $table_name = "std_reg_p4";
    $where = [];
    $table_columns = [
        "age",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = "is_active = 1 AND is_delete = 0 AND p1_unique_id = $unique_id";
    $amc_name_list = $pdo->select($table_details, $where);
    print_r($amc_name_list);
    if ($amc_name_list->status) {
        return $amc_name_list->data[0]['age'];
    } else {
        print_r($amc_name_list);
        return 0;
    }
}




function district_name_un($unique_id = "")
{
    global $pdo;

    $table_name = "district_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "district_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $result_values = $pdo->select($table_details, $where);


    if ($result_values->status) {

        $result_values = $result_values->data;

        $district_name = $result_values[0]["district_name"];
        return $district_name;

    }
}
function taluk_name_un($unique_id = "")
{
    global $pdo;

    $table_name = "taluk_creation";
    $where = [];
    $table_columns = [
        "unique_id",
        "taluk_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $result_values = $pdo->select($table_details, $where);


    if ($result_values->status) {

        $result_values = $result_values->data;

        $tauk_name = $result_values[0]["taluk_name"];
        return $tauk_name;

    }
}

function hostel_name_un($unique_id = "")
{
    global $pdo;

    $table_name = "hostel_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "hostel_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $result_values = $pdo->select($table_details, $where);


    if ($result_values->status) {

        $result_values = $result_values->data;

        $hostel_name = $result_values[0]["hostel_name"];
        return $hostel_name;

    }
}


function feedback_name_un($unique_id = "")
{
    global $pdo;

    $table_name = "feedback_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "feedback_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $result_values = $pdo->select($table_details, $where);


    if ($result_values->status) {

        $result_values = $result_values->data;

        $feedback_type = $result_values[0]["feedback_type"];
        return $feedback_type;

    }
}

function get_reason_name($unique_id = "", $status = "")
{
    global $pdo;

    // 
    $table_name = "dadwo_approval";
    $where = [];
    $table_columns = [
        "max(grievance_id)",
        "entry_date",
        "reason",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    // if ($unique_id) {
    //     $where              = [];
    //     $where["unique_id"] = $unique_id;
    // }
    $where = "grievance_id='$unique_id' and  is_delete = 0 and is_active=1 and dadwo_status='$status' order by grievance_id DESC";

    $product_category_list = $pdo->select($table_details, $where);
    // print_r($product_category_list);
    if ($product_category_list->status) {
        return $product_category_list->data;
    } else {
        print_r($product_category_list);
        return 0;
    }
}






function sanitizeInput($input)
{
    // return htmlspecialchars(strip_tags(trim($input)));
    $host = "localhost";
    $username = "root";
    $password = "4/rb5sO2s3TpL4gu";
    $databasename = "adi_dravidar";

    $mysqli = new mysqli($host, $username, $password, $databasename);

    $esc_str = $mysqli->real_escape_string($input);
    $sanitize = htmlspecialchars(strip_tags(trim($esc_str)));
    return $sanitize;
}





function reg_register($entry_date = "", $district_id = '', $type = '')
{
    global $pdo;

    $table_name = "std_app_s";
    $table_columns = [
        "count(id) as cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // Initialize the $where condition
    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    // Update the $where condition if $entry_date is provided
    $where = " is_delete = 0 AND entry_date = '" . $entry_date . "' and application_type = '" . $type . "' and hostel_district_1='" . $district_id . "'";

    // Execute the query
    $result = $pdo->select($table_details, $where);

    // Check the result and return the appropriate value
    if ($result->status) {
        // Ensure data is returned as expected
        return isset($result->data[0]['cnt']) ? (int) $result->data[0]['cnt'] : 0;
    } else {
        // Handle the case when the result is not successful or null
        return 0;
    }
}

function week_reg($startOfWeek = "", $endOfWeek = "", $district_id = '', $type = "")
{
    global $pdo;

    $table_name = "std_app_s";
    $table_columns = [
        "count(id) as cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // Initialize the $where condition


    // Update the $where condition if $entry_date is provided
    $where = " is_delete = 0 AND entry_date BETWEEN '" . $startOfWeek . "' and '" . $endOfWeek . "' and application_type = '" . $type . "'and hostel_district_1='" . $district_id . "'";
    // Execute the query
    $result = $pdo->select($table_details, $where);
    // print_r($result);
    // Check the result and return the appropriate value
    if ($result->status) {
        // Ensure data is returned as expected
        return isset($result->data[0]['cnt']) ? (int) $result->data[0]['cnt'] : 0;
    } else {
        // Handle the case when the result is not successful or null
        return 0;
    }
}


function cus_reg($startOfWeek = "", $endOfWeek = "", $district_id = '', $type = '')
{
    global $pdo;
    $table_name = "std_app_s";
    $table_columns = [
        "count(id) as cnt",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    // Initialize the $where condition


    // Update the $where condition if $entry_date is provided
    $where = " is_delete = 0 AND entry_date BETWEEN '" . $startOfWeek . "' and '" . $endOfWeek . "'and application_type = '" . $type . "'and hostel_district_1='" . $district_id . "'";
    // Execute the query
    $result = $pdo->select($table_details, $where);
    // print_r($result);
    // Check the result and return the appropriate value
    if ($result->status) {
        // Ensure data is returned as expected
        return isset($result->data[0]['cnt']) ? (int) $result->data[0]['cnt'] : 0;
    } else {
        // Handle the case when the result is not successful or null
        return 0;
    }
}


function select_option_1($options = [], $description = "", $is_selected = [], $is_disabled = [])
{

    // $option_str     = "<option value='' disabled>No Options to Select</option>";

    $data_attribute = "";

    if ($options) {

        // $option_str     = "<option value=''>Select</option>";

        // if ($description) {
        //     $option_str     = "<option value='' selected>".$description."</option>";
        // }
        foreach ($options as $key => $value) {

            $value = array_values($value);
            $selected = "";
            $disabled = "";

            if (is_array($is_selected) and in_array($value[0], $is_selected)) {
                $selected = " selected='selected' ";
            } elseif ($is_selected == $value[0]) {

                $selected = " selected='selected' ";
            }

            if (is_array($is_disabled) and in_array($value[0], $is_disabled)) {
                $disabled = " disabled='disabled' ";
            } elseif ($is_disabled == $value[0]) {
                $disabled = " disabled='disabled' ";
            }

            if (strpos($value[1], "_")) {
                $value[1] = disname($value[1]);
            } else {
                $value[1] = ucfirst($value[1]);
            }

            if (isset($value[2])) {
                $data_attribute = "data-extra='" . $value[2] . "'";
            }

            $option_str .= "<option value='" . $value[0] . "'" . $data_attribute . $selected . $disabled . ">" . $value[1] . "</option>";
        }
    }

    return $option_str;
}

function kitchen_asset_count($hostel_id = "")
{
    global $pdo;

    $table_name = "moveable_kitchen_sub";
    $where = [];
    $table_columns = [
        "count(*) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0,

    ];

    if ($hostel_id) {

        // $where .= [];
        $where["hostel_id"] .= $hostel_id;
    }


    $user_actions = $pdo->select($table_details, $where);

    if ($user_actions->status) {
        return $user_actions->data;
    } else {
        print_r($user_actions);
        return 0;
    }
}



function digital_asset_count($hostel_id = "")
{
    global $pdo;

    $table_name = "moveables_digital_sub";
    $where = [];
    $table_columns = [
        "count(*) as count"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_delete" => 0,

    ];

    if ($hostel_id) {

        // $where .= [];
        $where["hostel_id"] .= $hostel_id;
    }


    $user_actions = $pdo->select($table_details, $where);

    if ($user_actions->status) {
        return $user_actions->data;
    } else {
        print_r($user_actions);
        return 0;
    }
}

function kitchen_category($unique_id = "", $hostel_id = '')
{
    global $pdo;

    $table_name = "kitchen_category";
    $where = [];
    $table_columns = [
        "unique_id",
        "category"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }
    if ($hostel_id) {
        $where = [];
        $where["hostel_id"] = $hostel_id;
    }

    $kitchen_category_list = $pdo->select($table_details, $where);


    if ($kitchen_category_list->status) {
        return $kitchen_category_list->data;
    } else {
        print_r($kitchen_category_list);
        return 0;
    }
}

function kitchen_asset($unique_id = "", $hostel_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
    // echo "$hostel_id";
    $table_name = "kitchen_asset";
    $where = [];
    $table_columns = [
        "unique_id",
        "kitchen_asset",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] .= $unique_id;
    }

    if ($hostel_id) {
        $where = [];
        $where["hostel_id"] = $hostel_id;
    }

    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data;
    } else {
        print_r($asset_type_name);
        return 0;
    }
}

//digital category
function digital_category($unique_id = "")
{
    global $pdo;

    $table_name = "digital_category";
    $where = [];
    $table_columns = [
        "unique_id",
        "digital_category"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    $digital_category_list = $pdo->select($table_details, $where);


    if ($digital_category_list->status) {
        return $digital_category_list->data;
    } else {
        print_r($digital_category_list);
        return 0;
    }
}

function Digital_asset($unique_id = "")
{
    // echo $zone_name;
// print_r($unique_id);die();
    global $pdo;
    // echo "eeeee";
    $table_name = "digital_asset";
    $where = [];
    $table_columns = [
        "unique_id",
        "digital_asset",

    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] .= $unique_id;
    }


    $asset_type_name = $pdo->select($table_details, $where);

    // print_R( $product_type_list);
    if ($asset_type_name->status) {
        return $asset_type_name->data;
    } else {
        print_r($asset_type_name);
        return 0;
    }
}




function special_tahsildar($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "special_tahsildar";
    $where = [];
    $table_columns = [
        "unique_id",
        "special_tahsildar",
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        $where = [];
        $where["district_name"] = $district_name;
    }

    $special_tahsildar_list = $pdo->select($table_details, $where);

    if ($special_tahsildar_list->status) {
        return $special_tahsildar_list->data;
    } else {
        print_r($special_tahsildar_list);
        return 0;
    }
}



function rental_reason($unique_id = "")
{
    global $pdo;

    $table_name = "rental_reason";
    $where = [];
    $table_columns = [
        "unique_id",
        "rental_reason"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where["unique_id"] .= $unique_id;
    }

    $rental_reason_list = $pdo->select($table_details, $where);


    if ($rental_reason_list->status) {
        return $rental_reason_list->data;
    } else {
        print_r($rental_reason_list);
        return 0;
    }
}

function building_status($unique_id = "")
{
    global $pdo;

    $table_name = "building_status";
    $where = [];
    $table_columns = [
        "unique_id",
        "building_status"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where["unique_id"] .= $unique_id;
    }

    $building_status_list = $pdo->select($table_details, $where);


    if ($building_status_list->status) {
        return $building_status_list->data;
    } else {
        print_r($building_status_list);
        return 0;
    }
}

function onership_status($unique_id = "")
{
    global $pdo;

    $table_name = "ownership";
    $where = [];
    $table_columns = [
        "unique_id",
        "ownership"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    $ownership_list = $pdo->select($table_details, $where);


    if ($ownership_list->status) {
        return $ownership_list->data;
    } else {
        print_r($ownership_list);
        return 0;
    }
}



function assembly_constituency($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "assembly_constituency";
    $where = [];
    $table_columns = [
        "unique_id",
        "assembly_const_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        // $where              = [];
        $where["district_name"] .= $district_name;
    }

    $assembly_const_name_list = $pdo->select($table_details, $where);


    if ($assembly_const_name_list->status) {
        return $assembly_const_name_list->data;
    } else {
        print_r($assembly_const_name_list);
        return 0;
    }
}


function parliment_constituency($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "parliament_constituency";
    $where = [];
    $table_columns = [
        "unique_id",
        "parliament_const_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        // $where              = [];
        $where["district_name"] .= $district_name;
    }

    $parliment_const_name_list = $pdo->select($table_details, $where);


    if ($parliment_const_name_list->status) {
        return $parliment_const_name_list->data;
    } else {
        print_r($parliment_const_name_list);
        return 0;
    }
}

function block($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "block_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "block_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        //$where              = [];
        $where["district_name"] .= $district_name;
    }

    $block_list = $pdo->select($table_details, $where);


    if ($block_list->status) {
        return $block_list->data;
    } else {
        print_r($block_list);
        return 0;
    }
}

function village_name($unique_id = "", $block_name = "")
{
    global $pdo;

    $table_name = "village_name";
    $where = [];
    $table_columns = [
        "unique_id",
        "village_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($block_name) {
        $where = [];
        $where["block_name"] = $block_name;
    }

    $village_name_list = $pdo->select($table_details, $where);


    if ($village_name_list->status) {
        return $village_name_list->data;
    } else {
        print_r($village_name_list);
        return 0;
    }
}

function corporation($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "corporation";
    $where = [];
    $table_columns = [
        "unique_id",
        "corporation_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        $where = [];
        $where["district_name"] = $district_name;
    }

    $corporation_list = $pdo->select($table_details, $where);


    if ($corporation_list->status) {
        return $corporation_list->data;
    } else {
        print_r($corporation_list);
        return 0;
    }
}

function municipality($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "municipality";
    $where = [];
    $table_columns = [
        "unique_id",
        "municipality_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        $where = [];
        $where["district_name"] = $district_name;
    }

    $municipality_list = $pdo->select($table_details, $where);


    if ($municipality_list->status) {
        return $municipality_list->data;
    } else {
        print_r($municipality_list);
        return 0;
    }
}

function town_panchayat($unique_id = "", $district_name = "")
{
    global $pdo;

    $table_name = "town_panchayat";
    $where = [];
    $table_columns = [
        "unique_id",
        "town_panchayat_name"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {
        $where = [];
        $where["unique_id"] = $unique_id;
    }

    if ($district_name) {
        $where = [];
        $where["district_name"] = $district_name;
    }

    $town_panchayat_list = $pdo->select($table_details, $where);


    if ($town_panchayat_list->status) {
        return $town_panchayat_list->data;
    } else {
        print_r($town_panchayat_list);
        return 0;
    }
}


function blood_group($unique_id = '')
{
    global $pdo;

    $table_name = 'umis_bloodgroup';
    $where = [];
    $table_columns = [
        'name as id',
        'name',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    // $where     = [
    //     "is_active" => 1,
    //     "is_delete" => 0
    // ];

    if ($unique_id) {
        // $where = [];
        $where['id'] .= $unique_id;
    }

    $district_name_list = $pdo->select($table_details, $where);

    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);

        return 0;
    }
}


function physically_challenged($unique_id = '')
{
    // echo $zone_name;

    global $pdo;

    $table_name = 'physically_challenged';
    $where = [];
    $table_columns = [
        'unique_id',
        'physically_challenged',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_active' => 1,
        'is_delete' => 0,
    ];

    // if ($acc_year) {
    //     $where              = [];
    //     $where["acc_year"] = $acc_year;
    // }
    if ($unique_id) {
        // $where = [];
        $where['unique_id'] .= $unique_id;
    }

    $hostel_name_list = $pdo->select($table_details, $where);

    if ($hostel_name_list->status) {
        return $hostel_name_list->data;
    } else {
        print_r($hostel_name_list);

        return 0;
    }
}

function priority($unique_id = '', $priority = '')
{
    global $pdo;

    $table_name = 'priority_type';
    // $where         = [];
    $where = '';
    $table_columns = [
        'unique_id',
        'priority',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_active' => 1,
        'is_delete' => 0,
    ];
    $where = "is_delete = '0' and is_active = '1'";

    if ($priority) {
        // $table_details      = $table_name;
        // $where              = [];
        // $where["unique_id"] = $unique_id;
        $where .= 'and unique_id NOT IN (' . $priority . ')';
    }
    if ($unique_id) {
        $where .= "and unique_id = '" . $unique_id . "'";
    }

    if ($unique_id && $priority) {
        $where = '';
        $where = "is_delete = '0' and is_active = '1'  and unique_id = '" . $unique_id . "'";
    }

    $desination_type_list = $pdo->select($table_details, $where);
    // print_r($desination_type_list);
    if ($desination_type_list->status) {
        return $desination_type_list->data;
    } else {
        print_r($desination_type_list);

        return 0;
    }
}



function institute_id($unique_id = '', $district_name = "")
{
    global $pdo;

    $table_name = 'institute_details';
    $where = [];
    $table_columns = [
        'institute_id',
        'institution_name',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    $where = [
        'is_delete' => 0,
    ];

    if ($unique_id) {
        $table_details = $table_name;
        // $where = [];
        $where['institute_id'] .= $unique_id;
    }

    if ($district_name) {
        $where['district'] .= $district_name;
    }

    $company_name_list = $pdo->select($table_details, $where);

    if ($company_name_list->status) {
        return $company_name_list->data;
    } else {
        print_r($company_name_list);

        return 0;
    }
}



function course_name_options($unique_id = '', $no_umis_stream = '')
{
    global $pdo;

    $table_name = 'umis_course_branch';
    $where = [];
    $table_columns = [
        'umiscourseid',
        'course_name',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    if ($unique_id) {
        // $where = [];
        $where['umiscourseid'] .= $unique_id;
    }

    if ($no_umis_stream) {
        // $where = [];
        $where['umiscoursetypeid'] .= $no_umis_stream;
    }

    $group_by = "umiscourseid";

    $district_name_list = $pdo->select($table_details, $where, "", "", "", "", $group_by);

    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);

        return 0;
    }
}



function course_branch_options($unique_id = '', $no_umis_course = '')
{
    global $pdo;

    $table_name = 'umis_course_branch';
    $where = [];
    $table_columns = [
        'umisbranchid',
        'course_branch_name',
    ];

    $table_details = [
        $table_name,
        $table_columns,
    ];

    if ($unique_id) {
        // $where = [];
        $where['umisbranchid'] .= $unique_id;
    }

    if ($no_umis_course) {
        // $where = []; 
        $where['umiscourseid'] .= $no_umis_course;
    }

    $group_by = "umisbranchid";

    $district_name_list = $pdo->select($table_details, $where, "", "", "", "", $group_by);

    if ($district_name_list->status) {
        return $district_name_list->data;
    } else {
        print_r($district_name_list);

        return 0;
    }
}


function all_academic_year($unique_id = "", $amc_year ="") {
    // echo $zone_name;

    global $pdo;

    $table_name    = "academic_year_creation";
    $where         = [];
    $table_columns = [
        "unique_id",
        "amc_year",  
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where     = [
        "is_active" => 1,
        "is_delete" => 0
    ];

   

    if ($unique_id) {
        // $where              = [];
        $where["unique_id"] .= $unique_id;
    }

    $order_by = 's_no desc';

    $amc_name_list = $pdo->select($table_details, $where, "", "", $order_by);

   

    if ($amc_name_list->status) {
        return $amc_name_list->data;
    } else {
        print_r($amc_name_list);
        return 0;
    }
}


function notification_actions($unique_id = "")
{
    global $pdo;

    $table_name = "user_type";
    $where = [];
    $table_columns = [
        "unique_id",
        "user_type"
    ];

    $table_details = [
        $table_name,
        $table_columns
    ];

    $where = [
        "is_active" => 1,
        "is_delete" => 0
    ];

    if ($unique_id) {

        $where = [];
        $where["unique_id"] = $unique_id;
    }

    $notification_actions = $pdo->select($table_details, $where);

    if ($notification_actions->status) {
        return $notification_actions->data;
    } else {
        print_r($notification_actions);
        return 0;
    }
}
?>