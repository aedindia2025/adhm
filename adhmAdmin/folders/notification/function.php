<?php
function main_screen_like ($search_key = "") {


    $result     = "''";

    if ($search_key) {
        global $pdo;

        $table_name = "user_screen_main";

        $columns        = [
            "CONCAT(\"'\",GROUP_CONCAT(DISTINCT unique_id SEPARATOR \"','\"),\"'\") as unique_id"
        ];

        $where          = " screen_main_name LIKE '".mysql_like($search_key)."' ";

        $table_details  = [
            $table_name,
            $columns
        ];

        // $group_by     = " quotation_unique_id ";
        // $group_by     = " ";

        $select_result  = $pdo->select($table_details,$where,"","","","","");
        // print_r($select_result);

        if (!($select_result->status)) {
            print_r($select_result);
        } else {
            $result     = $select_result->data[0];

            $result     = $result['unique_id'];

            if ($result == "") {
                $result = "''";
            }
        }
    }

    return $result;
}
// User Action list Array
function user_action_list($user_action_array = [],$selected = "") {
    $all_checked                = "";

    if ($selected) {
        $selected   = explode(",",$selected);
        $user_action_array_count    = count($user_action_array);
        $selected_count             = count($selected);

        if ($user_action_array_count == $selected_count) {
            $all_checked        = " checked ";
        }
    }

    $return_str =   '<li>
                        <input type="checkbox" id="dis_check" disabled>
                        <label for="dis_check">No Options</label>
                    </li>';

    if ($user_action_array) {

        $return_str = '<li>
                            <input type="checkbox" id="all" '.$all_checked.'>
                            <label for="all">All</label>
                        </li>';

        foreach ($user_action_array as $key => $value) {
            $checked    = "";

            if (is_array($selected)) {
                if (in_array($value["unique_id"],$selected)) {
                    $checked = " checked ";
                }
            }
            // print_r($action_name);
            $return_str .= '<li>
                                <input type="checkbox" id="'.$value["unique_id"].'" '.$checked.' name="user_actions" value="'.$value["unique_id"].'" class="action_check">
                                <label for="'.$value["unique_id"].'">'.disname($value["user_type"]).'</label>
                            </li>';
        }
    }

    return $return_str;
}
?>