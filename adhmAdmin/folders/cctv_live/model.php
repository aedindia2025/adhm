<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";
$hostel_name = "";

$district_name = "";
$taluk_name = "";

$unique_id = "";
$is_active = 1;




if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "cctv_live";

        $columns = [
            "district_name",
            "taluk_name",
            "hostel_name",
            "academic_year",
            "cam_name",
            "cam_link1",
            "cam_link2",
            "stream_type",
            "ip",
            "user_name",
            "password",
            "is_active",
            "unique_id"
        ];

        $table_details = [
            $table,
            $columns
        ];
        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $hostel_name = $result_values[0]["hostel_name"];
            // $hostel_id = $result_values[0]["hostel_id"];
            $district_name = $result_values[0]["district_name"];
            $taluk_name = $result_values[0]["taluk_name"];
            $cam_name = $result_values[0]["cam_name"];
            $link1 = $result_values[0]["cam_link1"];
            $link2 = $result_values[0]["cam_link2"];
            $stream_type = $result_values[0]["stream_type"];
            $ip = $result_values[0]["ip"];
            $user_name = $result_values[0]["user_name"];
            $d_password = $result_values[0]["password"];
            
            $academic_year = $result_values[0]["academic_year"];
            $is_active = $result_values[0]["is_active"];
            $unique_id = $result_values[0]["unique_id"];


            

            // $academic_year = academic_year();
            // $academic_year = select_option($academic_year, "Select Academic Year", $academic_year);




            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$active_status_options = active_status($is_active);

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options, "Select Academic Year");

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District Name");

$taluk_options = taluk_name();
$taluk_name_options = select_option($taluk_options, "Select Zone");

$hostel_options = hostel_name();
$hostel_name_options = select_option($hostel_options, "Select Hostel");

// $hostel_location_type_options = [
//     "1" => [
//         "unique_id" => "1",
//         "value" => "Rural",
//     ],
//     "2" => [
//         "unique_id" => "2",
//         "value" => "Urban",
//     ]
// ];
// $hostel_location_type_options = select_option($hostel_location_type_options, "Select Hostel Location", $hostel_location);

// $urban_type_options = [
//     "1" => [
//         "unique_id" => "1",
//         "value" => "Corporation",
//     ],
//     "2" => [
//         "unique_id" => "2",
//         "value" => "Municipality",
//     ],
//     "3" => [
//         "unique_id" => "3",
//         "value" => "Town Panchayat",
//     ]
// ];
// $urban_type_options = select_option($urban_type_options, "Select Urban Type",$urban_type);


// $district_name_options = district_name();
// $district_name_options = select_option($district_name_options, "Select Hostel District", $district_name);

// // $assembly_const_name_options        = assembly_constituency();
// // $assembly_const_name_options        = select_option($assembly_const_name_options, "Select Assembly Constituency", $assembly_const);

// $parliment_const_name_options = parliment_constituency();
// $parliment_const_name_options = select_option($parliment_const_name_options, "Select Parliment Constituency", $parliment_const_name);

// // $block_options                      = block();
// // $block_options                      = select_option($block_options, "Select Block Name", $block);


// // $village_name_options               = village_name();
// // $village_name_options               = select_option($village_name_options, "Select Village Name", $village_name);

// $corporation_options                = corporation();
// $corporation_options                = select_option($corporation_options, "Select Corporation Name", $corporation);

// $municipality_options               = municipality();
// $municipality_options               = select_option($municipality_options, "Select Municipality Name", $municipality);

// $town_panchayat_options             = town_panchayat();
// $town_panchayat_options             = select_option($town_panchayat_options, "Select Town Panchayat Name", $town_panchayat);

// $hostel_type_options = hostel_type_name();
// $hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

// $gender_type_options = hostel_gender_name();
// $gender_type_options = select_option($gender_type_options, "Select Hostel Gender Type", $gender_type);


$stream_type_options = [
    "1" => [
        "unique_id" => "Chennai",
        "value" => "Chennai",
    ],
    "2" => [
        "unique_id" => "WebRTCApp",
        "value" => "WebRTCApp",
    ]
];
$stream_type_options = select_option($stream_type_options, "Select Stream Type",$stream_type);


?>

<style>
    #error_message {
        color: red;
    }
    
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Modal with form -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">CCTV Live</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off">
                                <div class="row">
                                   
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="district_name" class="form-label">District Name</label>
                                            <select class="select2 form-control" id="district_name" name="district_name" onchange="get_taluk_name()" required>
             <?php echo $district_name_list; ?>
           </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" >Taluk Name</label>
                                            <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()" required>
             <?php echo $taluk_name_options; ?>
           </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="assembly_const" class="form-label">Hostel Name </label>
                                                <select class="select2 form-control" id="hostel_name" name="hostel_name" required>
             <?php echo $hostel_name_options; ?>
           </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="parliment_const" class="form-label">Academic Year</label>
                                                <select class="select2 form-control" id="academic_year" name="academic_year" required>
             <?php echo $academic_year_options; ?>
           </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Cam Name</label>
                                            <input type="text"  class="form-control" id="cam_name" name="cam_name" value="<?= $cam_name; ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Cam Link 1</label>
                                            <textarea  class="form-control" id="link1" name="link1"
                                                value="<?= $link1; ?>" required><?= $link1; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="hostel_location">Cam Link 2</label>
                                            <textarea  class="form-control" id="link2" name="link2"
                                                value="<?= $link2; ?>" ><?= $link2; ?>
</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Stream Type</label>
                                            <select class="select2 form-control" id="stream_type" name="stream_type" required>
             <?php echo $stream_type_options; ?>
           </select>
        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">IP</label>
                                            <input type="text"  class="form-control" id="ip" name="ip" value="<?= $ip; ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">User Name</label>
                                            <input type="text"  class="form-control" id="user_name" name="user_name" value="<?= $user_name; ?>" required/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Password</label>
                                            <input type="text"  class="form-control" id="d_password" name="d_password" value="<?= $d_password; ?>" required/>
                                        </div>
                                    </div>
                                   
                                    <!-- <input type="hidden" class="form-control" id="unique_id" name="unique_id"
                                        value="<?= $unique_id; ?>"> -->
                                </div>
                                <div class="btns">
                                    <?php echo btn_cancel($btn_cancel); ?>
                                    <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
    function get_taluk_name() {
    //  alert(hi);
     var district_name = $('#district_name').val();

     var data = "district_name=" + district_name + "&action=district_name";
     var ajax_url = sessionStorage.getItem("folder_crud_link");
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
         if (data) {
           $("#taluk_name").html(data);
         }
       }
     });
   }

   function get_hostel() {
     var taluk_name = $('#taluk_name').val();
     var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";
     var ajax_url = sessionStorage.getItem("folder_crud_link");
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
         if (data) {
           $("#hostel_name").html(data);
         }
       }
     });
   }
</script>