<?php
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$unique_id          = "";
$district_name      = "";
$is_active          = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
$where = "unique_id = '$unique_id'";
        $table      =  "grievance_category";

        $columns        = [
            "entry_date",
            "grievance_id",
            "grievance_cate",
            "grievance_description",
            "student_name",
            "reg_no",
            "hostel_name as hostel_name_id",
            "(select hostel_name from hostel_name where unique_id = $table.hostel_name) as hostel_name",
            // "(select hostel_id from hostel_name where unique_id = $table.hostel_id) as hostel_id",
            "hostel_id as hostel_id",
            "hostel_id as hostel_id_val",
            "grievance_no",
            "district as district_id_val",
            "(select district_name from district_name where unique_id = $table.district) as district",
            "taluk as taluk_id_val",
            "(select taluk_name from taluk_creation where unique_id = $table.taluk) as taluk",
            "tahsildar",
            "file_name",
            "is_active",
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);
// print_r($result_values);
        if ($result_values->status) {

            $result_values      = $result_values->data;
            $grievance_id      = $result_values[0]["grievance_id"];
            $grievance_cate_id      = $result_values[0]["grievance_cate"];
            $grievance_description      = $result_values[0]["grievance_description"];
            $grievance_cate      = $result_values[0]["grievance_cate"];
            $student_name      = $result_values[0]["student_name"];
            $reg_no      = $result_values[0]["reg_no"];
            $hostel_name_id      = $result_values[0]["hostel_name_id"];
            $hostel_name      = $result_values[0]["hostel_name"];
            $hostel_id_val      = $result_values[0]["hostel_id_val"];
            $hostel_id      = $result_values[0]["hostel_id"];
            $grievance_no      = $result_values[0]["grievance_no"];
            $district_id_val      = $result_values[0]["district_id_val"];
            $district      = $result_values[0]["district"];
            $taluk_id_val      = $result_values[0]["taluk_id_val"];
            $taluk      = $result_values[0]["taluk"];
            $tahsildar      = $result_values[0]["tahsildar"];
            $file_name      = $result_values[0]["file_name"];
            $is_active          = $result_values[0]["is_active"];
            $unique_id          = $result_values[0]["unique_id"];
            if($result_values[0]["grievance_cate"] == '0'){
               $grievance_cate = 'Test';
            }else{
               $grievance_cate = 'Demo';
            }



            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}


?>
<!-- Modal with form -->

<style>
    hr {
  height:5px;
  border-width:0;
  background-color:#00A4BD;
}
</style>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Grievance</h4>
                        
                        <div class="row">



<!-- <div class="col-md-3 fm">
<label for="example-select" class="form-label">StudentID</label>
<input type="text" id="simpleinput" class="form-control">
</div> -->
<div class="col-4">
    <label for="example-select" class="form-label"> Student Name</label>&nbsp;&nbsp;: &nbsp;&nbsp; <?php echo $student_name; ?>
    <!-- <input type="text" id="simpleinput" class="form-control"> -->

</div>
<div class="col-4">
    <label for="example-select" class="form-label"> Grievance no</label>&nbsp;&nbsp;: &nbsp;&nbsp; <?php echo $grievance_no;?> 
    <!-- <input type="text" id="simpleinput" class="form-control"> -->

</div>
                        </div>
<div class="row">
<div class="col-4">
    <label for="example-select" class="form-label"> Register No</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :  &nbsp;&nbsp; <?php echo $reg_no;?> 
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label"> District </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : &nbsp;&nbsp;&nbsp; <?php echo $district;?>
</div>
</div>

<div class="row">
<div class="col-4">
    <label for="example-select" class="form-label"> Hostel Name</label>&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;<?php echo $hostel_name;?>
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label">Taluk   </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   : &nbsp;&nbsp;&nbsp;<?php echo $taluk;?>
</div>
</div>

<div class="row">
<div class="col-4">
    <label for="example-select" class="form-label">Hostel Id</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp; <?php echo $hostel_id;?>
  

</div>
<div class="col-4">
    <label for="example-select" class="form-label">Tahsildar</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;&nbsp; <?php echo $tahsildar;?> 
</div>
</div>






                    </div>
                </div>
            </div>
            <hr>
            <!-- end page title -->

            <div class="row"></div>
                <div class="col-12">


                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                    <input name="hostel_id" id="hostel_id" type="hidden" value="<?php echo $hostel_id;?>">
<input name="student_name" id="student_name" type="hidden" value="<?php echo $student_name;?>">
<input name="reg_no" id="reg_no" type="hidden" value="<?php echo $reg_no;?>">
<input name="hostel_name" id="hostel_name" type="hidden" value="<?php echo $hostel_name_id;?>">
<input name="grievance_no" id="grievance_no" type="hidden" value="<?php echo $grievance_no;?>">
<input name="district" id="district" type="hidden" value="<?php echo $district_id_val;?>">
<input name="taluk" id="taluk" type="hidden" value="<?php echo $taluk_id_val;?>">
<input name="tahsildar" id="tahsildar" type="hidden" value="<?php echo $tahsildar;?>">
<input name="grievance_cate_id" id="grievance_cate_id" type="hidden" value="<?php echo $grievance_id ;?>">
<input name="grievance_cate" id="grievance_cate" type="hidden" value="<?php echo $grievance_cate ;?>">
<input name="grievance_description" id="grievance_description" type="hidden" value="<?php echo $grievance_description ;?>">


                                            <br>
                                            <br>
                                            <div class="row mb-3">
                                                <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Status</label>
                                                  <select name ="grievance_category" id="grievance_category" class="form-control" required>  
                                                    <option value='0'>Pending</option>
                                                    <option value='1'>Processing</option>
                                                    <option value='2'>Completed</option>
                                                  </select>
                                                </div>

                                                <div class="col-5">
                                                    <label for="example-select" class="form-label">Reason</label>
                                                    <textarea name="reason" id="reason" class="form-control" rows="2"col="20" required></textarea>
                                                </div>
                                            </div>   

                                               <!-- <div class="row mb-3">
                                                <div class="col-5">
                                                    <label for="example-select" class="form-label">File Upload: </label>
                                                    <input type="file"  style="color:green;" accept="application/pdf">
                                                </div>
                                            </div> -->
                                            <div class="btns">
                                                
                                                <button type="button" id="btn_add" class="btn btn-success btn-block status_sub_add_update_btn mb-2" onclick="status_sub_add_update()">Add</button>
                                               
                                            </div>
                                    </form>
</hr>

                                        </div> <!-- end card-body -->
                                        <div class="row">
                            <div class="col-md-12">
                                <!-- Table Begiins -->
                                <div id="status_sub_datatable_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="status_sub_datatable" class="table table-bordered table-md dataTable no-footer mt-2 mb-2">
                                                <thead>
                                                    <tr>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 5%;">#</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20%;">Entry Date</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20%;">Grievance ID</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20%;">Status</th>
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 20%;">Reason</th>
                                                        <!-- <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 30%">Status</th> -->
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 10%;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id='document_upload_sub_datatable'>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end card-->
                            </div> <!-- end col -->
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
        <script>
 $(document).ready(function () {
	
	sub_list_datatable("status_sub_datatable", form_name, "status_sub_datatable");
	// alert("hii");
});
            function status_sub_add_update() {
// alert("hii");
var grievance_cate = $("#grievance_cate").val();

var grievance_cate_id = $("#grievance_cate_id").val();
var hostel_id = $("#hostel_id").val();






var student_name = $("#student_name").val();

var reg_no = $("#reg_no").val();

var status = $("#grievance_category").val();

var reason = $("#reason").val();
var hostel_name = $("#hostel_name").val();
var grievance_no = $("#grievance_no").val();

var district     = $("#district").val();
var taluk     = $("#taluk").val();
var tahsildar     = $("#tahsildar").val();
var grievance_description     = $("#grievance_description").val();




var data = new FormData();





data.append("grievance_description", grievance_description);

data.append("student_name", student_name);
data.append("reg_no", reg_no);
data.append("hostel_name",hostel_name);
data.append("grievance_no", "grievance_no");
data.append("district", district);
data.append("taluk", taluk);
data.append("tahsildar", tahsildar);
data.append("grievance_cate", grievance_cate);
data.append("grievance_cate_id", grievance_cate_id);
data.append("status", status);
data.append("reason", reason);
data.append("hostel_id", hostel_id);
data.append("action", "sub_add_update");


var ajax_url = sessionStorage.getItem("folder_crud_link");
var url      = '';

$.ajax({
    type: "POST",
    url: ajax_url,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    method: 'POST',
    // beforeSend: function () {
    // 	$(".status_sub_add_update_btn").attr("disabled", "disabled");
    // 	$(".status_sub_add_update_btn").text("Loading...");
    // },
    
    success: function (data) {

        var obj = JSON.parse(data);
        var msg = obj.msg;
        var status = obj.status;
        var error = obj.error;
        if(msg =='create'){
            sweetalert(msg);
            sub_list_datatable("status_sub_datatable");
            var reason = $("#reason").val('');
        }
        
        else{
            sweetalert(msg);
        }
        

    },
    error: function (data) {
        alert("Network Error");
    }
});




}
function sub_list_datatable(table_id = "", form_name = "", action = "") {

    var grievance_cate_id = $("#grievance_cate_id").val();
// var screen_unique_id = $("#screen_unique_id").val();

var table = $("#" + table_id);
var data = {
    "grievance_cate_id": grievance_cate_id,
    // "screen_unique_id": screen_unique_id,
    "action": table_id,
};
var ajax_url = sessionStorage.getItem("folder_crud_link");
var datatable = new DataTable(table, {
    destroy: true,
    "searching": false,
    "paging": false,
    "ordering": false,
    "info": false,
    "ajax": {
        url: ajax_url,
        type: "POST",
        data: data
    }

});
}

function status_sub_delete(unique_id = "", screen_unique_id = "") {

if (unique_id) {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    confirm_delete('delete')
        .then((result) => {
            if (result.isConfirmed) {

                var data = {
                    "unique_id": unique_id,
                    // "screen_unique_id": screen_unique_id,
                    "action": "status_sub_delete"
                }

                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    success: function (data) {

                        var obj = JSON.parse(data);
                        var msg = obj.msg;
                        var status = obj.status;
                        var error = obj.error;

                        if (!status) {
                            url = '';
                        } else {
                            sub_list_datatable("status_sub_datatable");
                        }
                        $("#status_option").val(null).trigger('change');
                        $("#status_description").val("");
                        sweetalert(msg, url);
                    }
                });

            } else {
                // alert("cancel");
            }
        });
}
}
</script>