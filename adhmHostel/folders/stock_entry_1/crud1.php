<?php 

// Get folder Name From Currnent Url 
$folder_name        = explode("/",$_SERVER['PHP_SELF']);
$folder_name        = $folder_name[count($folder_name)-2];

// // Database Country Table Name
$table             = "stock_entry_sub";
$table_main             = "stock_entry";

// // Include DB file and Common Functions
include '../../config/dbconfig.php';

// // Variables Declaration
$action             = $_POST['action'];

$feedback_type      = "";
$is_active          = "";
$unique_id          = "";
$prefix             = "";

$data               = "";
$msg                = "";
$error              = "";
$status             = "";
$test               = ""; // For Developer Testing Purpose

// $fileUpload         = new Alirdn\SecureUPload\SecureUPload( $fileUploadConfig );


// $fileUploadPath = $fileUploadConfig->get("upload_folder");

// Create Folder in root->uploads->(this_folder_name) Before using this file upload

// $fileUploadConfig->set("upload_folder",$fileUploadPath. $folder_name . DIRECTORY_SEPARATOR);
switch ($action) {
    case 'main_createupdate':

        $stock_id      = $_POST["stock_id"];
        $supplier_name   =$_POST["supplier_name"];
        $address   =$_POST["address"];
        // $entry_date   =$_POST["entry_date"];
        $bill_no   =$_POST["bill_no"];
        $hostel_name   =$_POST["hostel_name"];
        $discount          = $_POST["discount"];
        $expense          = $_POST["expense"];
        $gst          = $_POST["gst"];
        $net_total_amount          = $_POST["net_total_amount"];
        $district          = $_POST["district"];
        $taluk          = $_POST["taluk"];
        $is_active          = $_POST["is_active"];
        
if($_POST["entry_date"] == ''){
    $entry_date = date('Y-m-d');
}else{
    $entry_date = $_POST["entry_date"];
}

if($_POST["unique_id"] == ''){
    $unique_id          = unique_id($prefix);
}else{
    $unique_id          = $_POST["unique_id"];
}


        $update_where       = "";

        if (is_array($_FILES["test_file"]['name'])) {
           
            if ($_FILES["test_file"]['name'][0] != "") {
 
                // Multi file Upload 
                $confirm_upload     = $fileUpload->uploadFiles("test_file");

                    if (is_array($confirm_upload)) {
                        // print_r($_FILES["test_file"]['name']);
                        $_FILES["test_file"]['file_name'] = [];
                            foreach ($confirm_upload as $c_key => $c_value) {
                                if ($c_value->status == 1) {
                                    $c_file_name = $c_value->name ? $c_value->name.".".$c_value->ext : "";
                                    array_push($_FILES["test_file"]['file_name'],$c_file_name);
                                } else {// if Any Error Occured in File Upload Stop the loop
                                    $status     = $confirm_upload->status;
                                    $data       = "file not uploaded";
                                    $error      = $confirm_upload->error;
                                    $sql        = "file upload error";
                                    $msg        = "file_error";
                                    break;
                                }
                            }  

                    } else if (!empty($_FILES["test_file"]['name'])) {// Single File Upload
                        $confirm_upload     = $fileUpload->uploadFile("test_file");
                        
                        if($confirm_upload->status == 1) {
                            $c_file_name = $confirm_upload->name ? $confirm_upload->name.".".$confirm_upload->ext : "";
                            $_FILES["test_file"]['file_name']  = $c_file_name;
                        } else {// if Any Error Occured in File Upload Stop the loop
                            $status     = $confirm_upload->status;
                            $data       = "file not uploaded";
                            $error      = $confirm_upload->error;
                            $sql        = "file upload error";
                            $msg        = "file_error";
                        }                    
                    }
            }
        }

        // print_r($_FILES["test_file"]['name']);

        if (is_array($_FILES["test_file"]['name'])) {
            if ($_FILES["test_file"]['name'][0] != "") {
                $file_names     = implode(",",$_FILES["test_file"]['file_name']);
                $file_org_names = implode(",",$_FILES["test_file"]['name']);
            }                            
        } else if (!empty($_FILES["test_file"]['name'])) {
            $file_names     = $_FILES["test_file"]['file_name'];
            $file_org_names = $_FILES["test_file"]['name'];
        }
        if($file_names !=''){
        $columns            = [
            "supplier_name"       => $supplier_name,
            "address"    => $address,
            "entry_date"    => date('Y-m-d'),
            "bill_no"    => $bill_no,
            "hostel_name"    => $hostel_name,
            "stock_id"    => $stock_id,
            "discount"           => $discount,
            "expense"           => $expense,
            "gst"           => $gst,
            "net_total_amount"           => $net_total_amount,
            "district"           => $district,
            "taluk"           => $taluk,
            "file_name"           => $file_names,
            "file_org_name"           => $file_org_names,
            "is_active"           => 1,
            "unique_id"           => $unique_id 
        ];
        }else{
            $columns            = [
                "supplier_name"       => $supplier_name,
                "address"    => $address,
                "entry_date"    => $entry_date,
                "bill_no"    => $bill_no,
                "hostel_name"    => $hostel_name,
                "stock_id"    => $stock_id,
                "discount"           => $discount,
                "expense"           => $expense,
                "gst"           => $gst,
                "net_total_amount"           => $net_total_amount,
                "district"           => $district,
                "taluk"           => $taluk,
                // "file_name"           => $file_name,
                // "file_org_name"           => $file_org_name,
                "is_active"           => 1,
                "unique_id"           => $unique_id
            ];
        }
            // check already Exist Or not
        $table_details      = [
            $table_main,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'item_name = "'.$item_name.'"  and stock_id !="'.$stock_id.'"  AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

        $action_obj         = $pdo->select($table_details,$select_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if($_POST["unique_id"]) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $_POST["unique_id"]
                ];
                

                $action_obj     = $pdo->update($table_main,$columns,$update_where);

            // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table_main,$columns);
                // Insert Ends

            }
// print_r($action_obj);die();
            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($_POST["unique_id"]) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);

        break;
        case 'datatable':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];
            
    
            if($length == '-1') {
                $limit  = "";
            }
            // "supplier_name"       => $supplier_name,
            // "address"    => $address,
            // "entry_date"    => date('Y-m-d'),
            // "bill_no"    => $bill_no,
            // "hostel_name"    => $hostel_name,
            // "stock_id"    => $stock_id,
            // "discount"           => $discount,
            // "expense"           => $expense,
            // "net_total_amount"           => $net_total_amount,
            // "district"           => $district,
            // "taluk"           => $taluk,
            // "file_name"           => $file_name,
            // "file_org_name"           => $file_org_name,
    
            // Query Variables
            $json_array     = "";
            
            $columns        = [
                "' ' as sno",
                "entry_date",
                "stock_id",
                "(select supplier_name from supplier_name_creation where unique_id = $table_main.supplier_name) as supplier_name",
                // "supplier_name",
                "address",
                 
                "bill_no",
                "(select hostel_name from hostel_name where unique_id = $table_main.hostel_name) as hostel_name",
                "(select district_name from district_name where unique_id = $table_main.district) as district",
                // "hostel_name",
                // "district",
                // "taluk",
                "(select taluk_name from taluk_creation where unique_id = $table_main.taluk) as taluk",

                "unique_id",
                
            ];
            $table_details  = [
                $table_main." , (SELECT @a:= ".$start.") AS a ",
                $columns
            ];
            $where          = "is_delete = 0 ";
            $order_by       = "";
    
            if ($_POST['search']['value']) {
               $where .= " AND event_name LIKE '".mysql_like($_POST['search']['value'])."' ";
            }
            
            // Datatable Searching
            $search         = datatable_searching($search,$columns);
    
            if ($search) {
                if ($where) {
                    $where .= " AND ";
                }
    
                $where .= $search;
            }
    
            $sql_function   = "SQL_CALC_FOUND_ROWS";
    
            $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
            $total_records  = total_records();
    
            if ($result->status) {
    
                $res_array      = $result->data;
                $sno = 0;
                foreach ($res_array as $key => $value) {
                    $sno = $sno + 1;
                    $value['sno'] = $sno;
                    //  $id =  $value['id'];
                    // $i= $sno+1;
                    
                    // $value['entry_date'] = disdate($value['entry_date']);
                    $unique =$value['unique_id'];
                    $btn_update         = btn_update($folder_name,$value['unique_id']);
                    $btn_delete         = btn_delete($folder_name,$value['unique_id']);
    
                   
    
                    $value['unique_id'] = $btn_update.$btn_delete;
                    $data[]             = array_values($value);
                   
                    // $val = $value['unique_id'];
                    // $value['is_active'] = is_active_show($value['is_active']);
                    
        //             if($value['status'] == 0){
        //                 $value['unique_id']='<img src="../adhmDADWO/uploads/pending3.png"  height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button">
        //             <div class="modal fade bs-example-modal-x2' .  $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        //             <div class="modal-dialog modal-xl">
        //                 <div class="modal-content">
        //                     <div class="modal-header">
        //                         <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Approvel List</h5>
        //                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //                     </div>
        //                     <div class="modal-body">
        //                     <form>
        //                         <div class="row mt-2" >
        //                         <div class="col-md-4">
        //                         <label for="student_id" class="form-label">Event Name </label>
        //                         </div>
        //                         <div class="col-md-8">
                                
        //                         <label for="">'.$value['event_name'].'</label>
        //                         <input type="hidden" id="s_no" name="s_no" class="form-control" value="'.$id.'" required>
        //                         <input type="hidden" id="unique_id" name="unique_id" class="form-control" value="'.$val.'" required></input>
        //                     <input type="hidden" id="session_user_id" name="session_user_id" class="form-control" value="'.$_SESSION['sess_user_id'].'" required></input>
                                
                                 
        //                         </div>
        //                         </div>
        //                         <div class="row mt-2" >
        //                         <div class="col-md-4">
        //                         <label for="student_id" class="form-label">Event Date: </label>
        //                             </div>
        //                         <div class="col-md-8">
        //                         <label for="" value="">'.$value['cur_date'].'</label>
        //                             <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
        //                            </div>
        //                         </div>
        //                         <div class="row mt-2" >
        //                         <div class="col-md-4">
        //                         <label for="student_id" class="form-label">Hostel Name </label>
        //                             </div>
        //                         <div class="col-md-8">
        //                         <label for="" value="">'.$value['hostel_name'].'</label>
        //                             <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
        //                            </div>
        //                         </div>
        //                         <div class="row mt-2" >
        //                         <div class="col-md-4">
        //                         <label for="student_id" class="form-label">User Id </label>
        //                             </div>
        //                         <div class="col-md-8">
        //                         <label for="" value="">'.$value['user_id'].'</label>
        //                             <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
        //                            </div>
        //                         </div>
        //                         <div class="row mt-2" >
        //                         <div class="col-md-4">
        //                         <label for="example-select" class="form-label">Event Remark</label>
        //                         </div>
        //                         <div class="col-md-8">
        //                         <label for="">'.$value['remarks'].'</label>
        //                         <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['from_date'].'" required></input>
        //                                     </div>
        //                         </div>
                                
        //                         </div><div class="row mt-2" >
        //                         <div class="col-md-6">
        //                         <label class="form-label me-2" for="example-select">Status:</label>
        //                         </div>
        //                         <div class="col-md-5">
        //                         <select class="select2 form-control me-2" style="position: unset; width: 100%; height: auto; margin: 0px;" id="status" name="status">
        //                         <option value="1">Approval</option>
                                
        //                      </select>
       
        //                         </div>
        //                         </div><br>
        //                         <div class="row mt-2" id="description_div" style="display:none;" >
        //                         <div class="col-md-4" >
        //                         <label for="example-select" class="form-label">Reject Reason:</label>
        //                           </div>
        //                         <div class="col-md-8" >
        //                         <hiddenarea type="text" id="description" name="description" class="form-control" value=""></textarea>
        //                         </div>
        //                         </div>
        //                           <div class="modal-footer">
        //                         <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
        //                         <button type="button" class="btn btn-primary" onclick="create('.$id.')">Save</button>
        //                     </div>
        //                 </div><!-- /.modal-content -->
        //                 </form>
        //             </div><!-- /.modal-dialog -->
        //         </div><!-- /.modal -->';
                
        //     }
        //     if($value['status'] == 1){
        //         $value['unique_id']='<img src="../adhmAdmin/uploads/completed.png"  height="30px" width="30px" type="button">
        //        ';
        // }
        // if($value['status'] == 2){
        //     $value['unique_id']='<img src="../adhmAdmin/uploads/reject.jpg"  height="30px" width="30px" data-bs-toggle="modal" data-bs-target=".bs-example-modal-x2' . $sno . '" type="button">
        //     <div class="modal fade bs-example-modal-x2' .  $sno . '" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        //     <div class="modal-dialog modal-xl">
        //         <div class="modal-content">
        //             <div class="modal-header">
        //                 <h5 class="modal-title w-100 text-center" id="myExtraLargeModalLabel">Approvel List</h5>
        //                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        //             </div>
        //             <div class="modal-body">
        //                 <div class="row mt-2" >
        //                 <div class="col-md-4">
        //                 <label for="student_id" class="form-label">Event Name </label>
        //                 </div>
        //                 <div class="col-md-8">
        //                 <label for="">'.$value['event_name'].'</label>
                        
        //                 <input type="hidden" id="unique_id" name="unique_id" class="form-control" value="'.$value['unique_id'].'" required></input>
        //                 <input type="hidden" id="session_user_id" name="session_user_id" class="form-control" value="'.$_SESSION['sess_user_id'].'" required></input>
                        
                         
        //                 </div>
        //                 </div>
        //                 <div class="row mt-2" >
        //                 <div class="col-md-4">
        //                 <label for="student_id" class="form-label">Event Date: </label>
        //                     </div>
        //                 <div class="col-md-8">
        //                 <label for="" value="">'.$value['cur_date'].'</label>
        //                     <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
        //                    </div>
        //                 </div>
        //                 <div class="row mt-2" >
        //                 <div class="col-md-4">
        //                 <label for="example-select" class="form-label">Event Remark</label>
        //                 </div>
        //                 <div class="col-md-8">
        //                 <label for="">'.$value['remarks'].'</label>
        //                 <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['from_date'].'" required></input>
        //                             </div>
        //                 </div>
                        
        //                 </div><div class="row mt-2" >
        //                 <div class="col-md-4">
        //                 <label class="form-label me-2" for="example-select">Status:</label>
        //                 </div>
        //                 <div class="col-md-8">
        //                 <select class="select2 form-control me-2" style="position: unset; width: 100%; height: auto; margin: 0px;" id="status" name="status" >
        //                 <option value="1">Approval</option>
        //                 <option value="2">Rejected</option>
        //              </select>
    
        //                 </div>
        //                 </div><div class="row mt-2" id="description_div" style="display:none;" >
        //                 <div class="col-md-4" >
        //                 <label for="example-select" class="form-label">Reject Reason:</label>
        //                   </div>
        //                 <div class="col-md-8" >
        //                 <textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
        //                 </div>
        //                 </div>
        //                   <div class="modal-footer">
        //                 <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
        //                 <button type="button" class="btn btn-primary" onclick="create(s_no.value)">Save</button>
        //             </div>
        //         </div><!-- /.modal-content -->
        //     </div><!-- /.modal-dialog -->
        // </div><!-- /.modal -->';
    // }
                    
                }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $data,
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
            
            echo json_encode($json_array);
            break;
    case 'createupdate':

        $stock_id      = $_POST["stock_id"];
        $item_name   =$_POST["item_name"];
        $qty   =$_POST["qty"];
        $unit   =$_POST["unit"];
        $rate   =$_POST["rate"];
        $amount   =$_POST["amount"];
        $is_active          = $_POST["is_active"];
        $unique_id          = $_POST["unique_id"];

        $update_where       = "";

        $columns            = [
            "item_name"       => $item_name,
            "qty"    => $qty,
            "unit"    => $unit,
            "rate"    => $rate,
            "amount"    => $amount,
            "stock_id"    => $stock_id,
            "is_active"           => 1,
            "unique_id"           => unique_id($prefix)
        ];

            // check already Exist Or not
        $table_details      = [
            $table,
            [
                "COUNT(unique_id) AS count"
            ]
        ];
        $select_where       = 'item_name = "'.$item_name.'" and stock_id ="'.$stock_id.'" AND is_delete = 0  ';

        // When Update Check without current id
        if ($unique_id) {
            $select_where   .= ' AND unique_id !="'.$unique_id.'" ';
        }

        $action_obj         = $pdo->select($table_details,$select_where);
        // print_r($action_obj);
        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }
        if ($data[0]["count"]) {
            $msg        = "already";
        } else if ($data[0]["count"] == 0) {
            // Update Begins
            if($unique_id) {

                unset($columns['unique_id']);

                $update_where   = [
                    "unique_id"     => $unique_id
                ];

                $action_obj     = $pdo->update($table,$columns,$update_where);

            // Update Ends
            } else {

                // Insert Begins            
                $action_obj     = $pdo->insert($table,$columns);
                // Insert Ends

            }
// print_r($action_obj);
            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;

                if ($unique_id) {
                    $msg        = "update";
                } else {
                    $msg        = "create";
                }
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);

        break;

        case 'get_zone_name':
            $district_name          = $_POST['district'];
            $district_name_options  = taluk_name_get('', $district_name);
    
            $district_name_options  = select_option($district_name_options, 'Select Taulk');
    
            echo $district_name_options;
    
            break;
    
        case 'get_hostel_name':
            $taulk_name          = $_POST['taluk'];
            $taluk_name_options  = hostel_name('', $taluk_name);
    
            $taluk_name_options  = select_option($taluk_name_options, 'Select Hostel Name ');
    
            echo $taluk_name_options;
    
            break;
    
        case 'get_unit_name':
    
            $item_name          = $_POST['item_name'];
            $item_name_options  = product_type($item_name);
    
            $item_name_options  = select_option($item_name_options, 'Select Unit');
    
            echo $item_name_options;
    
            break;



    case 'document_upload_sub_datatable':
        // DataTable Variables
        $search     = $_POST['search']['value'];
        $length     = $_POST['length'];
        $start      = $_POST['start'];
        $draw       = $_POST['draw'];
        $limit      = $length;

        $data       = [];
        
$stock_id = $_POST['stock_id'];
        if($length == '-1') {
            $limit  = "";
        }
        // "(select supplier_name from supplier_name_creation where unique_id = $table_main.supplier_name) as supplier_name",
       
        // Query Variables
        $json_array     = "";
        
        $columns        = [
            "@a:=@a+1 s_no",
            // "(select product_type from product_type where unique_id = $table.item_name) as item_name",
            // "supplier_name",
            "item_name",
            "qty",
            "unit",
            "rate",
            "amount",
            "unique_id",
            "id",
            // "'' as tot_qty",
            // "sum(amount) as tot_amount",

        ];
        $table_details  = [
            $table." , (SELECT @a:= ".$start.") AS a ",
            $columns
        ];
        $where          = "is_delete = 0 and stock_id='$stock_id'";
        $order_by       = "";

        if ($_POST['search']['value']) {
           $where .= " AND feedback LIKE '".mysql_like($_POST['search']['value'])."' ";
        }
        
        // Datatable Searching
        $search         = datatable_searching($search,$columns);

        if ($search) {
            if ($where) {
                $where .= " AND ";
            }

            $where .= $search;
        }

        $sql_function   = "SQL_CALC_FOUND_ROWS";

        $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
        $total_records  = total_records();

        if ($result->status) {

            $res_array      = $result->data;
$table_date = ' <div class="table-responsive mb-4">
<div id="product_details_datatable_wrapper"
    class="dataTables_wrapper dt-bootstrap5 no-footer">
    <div class="row">
        <div class="col-sm-12 col-md-6"></div>
        <div class="col-sm-12 col-md-6"></div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        <form class="was-validated" autocomplete="off" >

            <table id="document_upload_sub_datatable"
                class="table table-hover table-bordered align-middle mb-0 dataTable no-footer"
                width="100%" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th class="sorting_disabled text-center"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            S.NO </th>
                        <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Item Name</th>
                            <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Qty</th>
                        
                        <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Unit</th>
                        <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Rate </th>
                            <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Amount </th>
                            <th scope="col" class="sorting_disabled"
                            rowspan="1" colspan="1"
                            style="width: 0px;">
                            Action </th>
                            
                   
                  
                        
                        
                    </tr>
                    
                    <tr>
                        <td>#</td>
                        <td><select  class="form-control" name="item_name" id="item_name" onchange="get_unit_name(this.value)"><?php  echo $product_type_option;  ?></td>
                        <td><input type="text" class="form-control" name="qty" id="qty" onkeyup="get_total()"></td>
                        <td><select name="unit" id="unit"  class="form-control"><?php echo $unit_options;?></select></td>
                        <td><input type="text" class="form-control" name="rate" id="rate" onkeyup="get_total()"></td>
                        <td><input type="text" class="form-control" name="amount" id="amount">
                        <input type="hidden" class="form-control" name="unique_id" id="unique_id">
                        <input type="hidden" id="stock_id" name="stock_id" class="form-control" placeholder=" " value="'.stock_id().'">
                        
                    </td>


                        <td><button type="button" class="btn btn-primary" onclick="save_data()" id="btn">Add</button></td>
</tr>   
                </thead>';

            foreach ($res_array as $key => $value) {
                // $value['feedback'] = disname($value['feedback']);
                // $value['description'] = disname($value['description']);
                // $value['is_active'] = is_active_show($value['is_active']);
                $id = $value['id'];
$unique_id = $value['unique_id'];
$tot_qty += $value['qty'];
$tot_amount += $value['amount'];
                $btn_update         ='<i class="uil uil-pen" onclick="get_records('.$id.')"></i>';
                $btn_delete         = '<i class="uil uil-trash" onclick="get_delete('.$id.')"></i>';

                if ($value['unique_id'] == "5f97fc3257f2525529") {
                    $btn_update         = "";
                    $btn_delete         = "";
                } 

                $value['unique_id'] = $btn_update . $btn_delete;
                // $value['tot_qty'] = '';
                // $data[]             = array_values($value);

            }

            $table_data .='<tr>
            <td></td>
                            <td></td>
                            <td>Total Qty : <br>"'.$tot_qty.'"</td>
                            <td></td>
                            <td></td>
                            <td>Total Amount :  <br>"'.$tot_amount.'"</td>
           
                            <tr>';
            // $data[] .= 
            
            $json_array = [
                "draw"              => intval($draw),
                "recordsTotal"      => intval($total_records),
                "recordsFiltered"   => intval($total_records),
                "data"              => $table_data,
                "testing"           => $result->sql,
                "tot_qty"           =>$tot_qty,
                "tot_amount"           =>$tot_amount,
            ];
        } else {
            print_r($result);
        }
        
        echo json_encode($json_array);
        break;
    
    
    case 'sub_delete':
        
        $unique_id      = $_POST['id'];

        $columns        = [
            "is_delete"   => 1
        ];

        $update_where   = [
            "id"     => $unique_id
        ];

        $action_obj     = $pdo->update($table,$columns,$update_where);

        if ($action_obj->status) {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = "";
            $sql        = $action_obj->sql;
            $msg        = "success_delete";

        } else {
            $status     = $action_obj->status;
            $data       = $action_obj->data;
            $error      = $action_obj->error;
            $sql        = $action_obj->sql;
            $msg        = "error";
        }

        $json_array   = [
            "status"    => $status,
            "data"      => $data,
            "error"     => $error,
            "msg"       => $msg,
            "sql"       => $sql
        ];

        echo json_encode($json_array);
        break;

        case 'delete':
        
            $unique_id      = $_POST['unique_id'];
    
            $columns        = [
                "is_delete"   => 1
            ];
    
            $update_where   = [
                "unique_id"     => $unique_id
            ];
    
            $action_obj     = $pdo->update($table_main,$columns,$update_where);
    
            if ($action_obj->status) {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = "";
                $sql        = $action_obj->sql;
                $msg        = "success_delete";
    
            } else {
                $status     = $action_obj->status;
                $data       = $action_obj->data;
                $error      = $action_obj->error;
                $sql        = $action_obj->sql;
                $msg        = "error";
            }
    
            $json_array   = [
                "status"    => $status,
                "data"      => $data,
                "error"     => $error,
                "msg"       => $msg,
                "sql"       => $sql
            ];
    
            echo json_encode($json_array);
            break;


        
        case 'updatevalues':
            // DataTable Variables
            $search     = $_POST['search']['value'];
            $length     = $_POST['length'];
            $start      = $_POST['start'];
            $draw       = $_POST['draw'];
            $limit      = $length;
    
            $data       = [];
            
    $stock_id = $_POST['id'];
            if($length == '-1') {
                $limit  = "";
            }
    
            // Query Variables
            $json_array     = "";
            
            $columns        = [
                "@a:=@a+1 s_no",
                "item_name",
                "qty",
                "unit",
                "rate",
                "amount",
                "stock_id",
                "unique_id",
                "id",
            ];
            $table_details  = [
                $table,
                $columns
            ];
            $where          = "is_delete = 0 and id='$stock_id'";
            $order_by       = "";
    
            if ($_POST['search']['value']) {
               $where .= " AND feedback LIKE '".mysql_like($_POST['search']['value'])."' ";
            }
            
            // Datatable Searching
            $search         = datatable_searching($search,$columns);
    
            if ($search) {
                if ($where) {
                    $where .= " AND ";
                }
    
                $where .= $search;
            }
    
            $sql_function   = "SQL_CALC_FOUND_ROWS";
    
            $result         = $pdo->select($table_details,$where,$limit,$start,$order_by,$sql_function);
            $total_records  = total_records();
    
            if ($result->status) {
    
                // $res_array      = $result->data;
    
    //             foreach ($res_array as $key => $value) {
    //                 // $value['feedback'] = disname($value['feedback']);
    //                 // $value['description'] = disname($value['description']);
    //                 // $value['is_active'] = is_active_show($value['is_active']);
    //                 $id = $value['id'];
    // $unique_id = $value['unique_id'];
    //                 $btn_update         ='<i class="uil uil-pen" onclick="get_records('.$id.')"></i>';
    //                 $btn_delete         = btn_delete($folder_name,$value['unique_id']);
    
    //                 if ( $value['unique_id'] == "5f97fc3257f2525529") {
    //                     $btn_update         = "";
    //                     $btn_delete         = "";
    //                 } 
    
    //                 $value['unique_id'] = $btn_update.$btn_delete;
    //                 // $data[]             = array_values($value);
    //                 $data            = array("id"=>$value['id'],"item_name"=>$value['item_name'],"qty"=>$value['qty'],"unit"=>$value['unit'],"rate"=>$value['rate'],"amount"=>$value['amount'],"stock_id"=>$value['stock_id']);
    //             }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $result->data[0],
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
            
            echo json_encode($json_array);
            break;



    default:
        
        break;
}
    
        // $user_type          = $_POST["user_type"];
        // $is_active          = $_POST["is_active"];
        // $unique_id          = $_POST["unique_id"];

        // $update_where       = "";

        // //count user_type
        // if($unique_id == ''){
        //     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1'");
        // }else{
        //     $get_user_type=$pdo_conn->prepare("SELECT count(*) FROM `user_type` WHERE `user_type`= '".$user_type."' and `is_delete` != '1' and `unique_id` != '".$unique_id."'" );
        // }

        // $get_user_type->execute();
        // $user_type_count  = $get_user_type->fetchColumn();    

        // if($user_type_count == 0){
            
        
        //     if($unique_id == ''){//insert
        //         $unique_id = uniqid().rand(10000,99999);

        //         if($prefix) {
        //             $unique_id = $prefix.$unique_id;
        //         }
              
        //         $Insql=$pdo_conn->prepare("insert into user_type (`unique_id`,`user_type`, `is_active`, `is_delete`)values('".$unique_id."','".$user_type."','1','0')");
        //         $Insql->execute();
        //         $msg = "Created";
        //         echo $msg;
        //     }else{//update
        //         $Insql=$pdo_conn->prepare("UPDATE `user_type` SET `user_type`= '".$user_type."',`is_active`= '".$is_active."' WHERE unique_id = '".$unique_id."'");
                
        //         $Insql->execute();
        //         $msg  = "Updated";
        //         echo $msg;
        //     }
        // }else{ 
        //     $msg  = "already";
        //     echo $msg;
        // }
//     break;
//     case 'delete' :
//         $unique_id          = $_POST["unique_id"];
//         $del_sql=$pdo_conn->prepare("update user_type set is_delete='1' where unique_id ='".$unique_id."'" );
//         $del_sql->execute();
//             $msg  = "success_delete";
//             echo $msg;

//     break;
//     default:
        
//     break;
// }
?>
