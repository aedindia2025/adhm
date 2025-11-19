<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}
// Form variables
$btn_text = "Save";
$btn_action = "create";
$hostel_name = "";
$hostel_id = "";
$district_name = "";
$taluk_name = "";
$special_tahsildar = "";
$assembly_const = "";
$parliment_const = "";
$address = "";
$hostel_location = "";
$urban_type = "";
$corporation = "";
$municipality = "";
$town_panchayat = "";
$block_name = "";
$village_name = "";
$hostel_type = "";
$gender_type = "";
$yob = "";
$sanctioned_strength = "";
$distance_btw_phc = "";
$phc_name = "";
$distance_btw_ps = "";
$ps_name = "";
$staff_count = "";
$unique_id = "";
$is_active = 1;
$hybrid_hostel = "No";

$hostel_taluk_options = "<option value=''>Select Taluk</option>";
// $assembly_const_name_options = "<option value=''>Select Assembly</option>";
$parliment_const_name_options = "<option value=''>Select Parliament</option>";
$block_options = "<option value=''>Select Block</option>";
$village_options = "<option value=''>Select Village</option>";
$corporation_options = "<option value=''>Select Corporation</option>";
$municipality_options = "<option value=''>Select Municipality</option>";
$town_panchayat_options = "<option value=''>Select Town Panchayat</option>";


if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "hostel_name";

        $columns = [
            "hostel_name",
            "hostel_id",
            "district_name",
            "hostel_name",
            "taluk_name",
            "special_tahsildar",
            "assembly_const",
            "parliment_const",
            "address",
            "hostel_location",
            "urban_type",
            "corporation",
            "municipality",
            "town_panchayat",
            "block_name",
            "village_name",
            "hostel_type",
            "gender_type",
            "yob",
            "sanctioned_strength",
            "distance_btw_phc",
            "phc_name",
            "distance_btw_ps",
            "ps_name",
            "go_attach_file",
            "staff_count",
            "is_active",
            "unique_id",
            "hostel_status",
            "rental_reason",
            "building_status",
            "hybrid_hostel"
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
            $hostel_id = $result_values[0]["hostel_id"];
            $district_name = $result_values[0]["district_name"];
            $taluk_name = $result_values[0]["taluk_name"];
            $special_tahsildar = $result_values[0]["special_tahsildar"];
            $assembly_const = $result_values[0]["assembly_const"];
            $parliment_const_name = $result_values[0]["parliment_const"];
            $address = $result_values[0]["address"];
            $hostel_location = $result_values[0]["hostel_location"];
            $urban_type = $result_values[0]["urban_type"];
            $corporation = $result_values[0]["corporation"];
            $municipality = $result_values[0]["municipality"];
            $town_panchayat = $result_values[0]["town_panchayat"];
            $block_name = $result_values[0]["block_name"];
            $village_name = $result_values[0]["village_name"];
            $hostel_type = $result_values[0]["hostel_type"];
            $gender_type = $result_values[0]["gender_type"];
            $yob = $result_values[0]["yob"];
            $sanctioned_strength = $result_values[0]["sanctioned_strength"];
            $distance_btw_phc = $result_values[0]["distance_btw_phc"];
            $phc_name = $result_values[0]["phc_name"];
            $distance_btw_ps = $result_values[0]["distance_btw_ps"];
            $ps_name = $result_values[0]["ps_name"];
            $staff_count = $result_values[0]["staff_count"];
            $file_names = $result_values[0]["go_attach_file"];
            $is_active = $result_values[0]["is_active"];
            $unique_id = $result_values[0]["unique_id"];
            $hybrid_hostel = $result_values[0]["hybrid_hostel"];
            $hostel_status = $result_values[0]["hostel_status"];
            $rental_reason = $result_values[0]["rental_reason"];
            $building_status = $result_values[0]["building_status"];


            $district_options = taluk_name("", $district_name);
            $hostel_taluk_options = select_option($district_options, "Select Taluk", $taluk_name);

            // $taluk_options = assembly_constituency("", $taluk_name);
            // $assembly_options = select_option($taluk_options, "Select Assembly", $assembly_const);

            $district_options = block("", $district_name);
            $block_options = select_option($district_options, "Select Block Name", $block_name);

            $village_option  = village_name("", $block_name);
            $village_options = select_option($village_option, "Select Village Name", $village_name);




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

$hostel_location_type_options = [
    "1" => [
        "unique_id" => "1",
        "value" => "Rural",
    ],
    "2" => [
        "unique_id" => "2",
        "value" => "Urban",
    ]
];
$hostel_location_type_options = select_option($hostel_location_type_options, "Select Hostel Location", $hostel_location);

$urban_type_options = [
    "1" => [
        "unique_id" => "1",
        "value" => "Corporation",
    ],
    "2" => [
        "unique_id" => "2",
        "value" => "Municipality",
    ],
    "3" => [
        "unique_id" => "3",
        "value" => "Town Panchayat",
    ]
];
$urban_type_options = select_option($urban_type_options, "Select Urban Type",$urban_type);


$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select Hostel District", $district_name);

$assembly_const_name_options        = assembly_constituency();
$assembly_const_name_options        = select_option($assembly_const_name_options, "Select Assembly Constituency", $assembly_const);

$parliment_const_name_options = parliment_constituency();
$parliment_const_name_options = select_option($parliment_const_name_options, "Select Parliment Constituency", $parliment_const_name);

// $block_options                      = block();
// $block_options                      = select_option($block_options, "Select Block Name", $block);


// $village_name_options               = village_name();
// $village_name_options               = select_option($village_name_options, "Select Village Name", $village_name);

$corporation_options                = corporation();
$corporation_options                = select_option($corporation_options, "Select Corporation Name", $corporation);

$municipality_options               = municipality();
$municipality_options               = select_option($municipality_options, "Select Municipality Name", $municipality);

$town_panchayat_options             = town_panchayat();
$town_panchayat_options             = select_option($town_panchayat_options, "Select Town Panchayat Name", $town_panchayat);

$hostel_type_options = hostel_type_name();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

$gender_type_options = hostel_gender_name();
$gender_type_options = select_option($gender_type_options, "Select Hostel Gender Type", $gender_type);

$ownership_options = onership_status();
$ownership_options = select_option($ownership_options, "Select Ownership", $hostel_status);

$building_status_options = building_status();
$building_status_options = select_option($building_status_options, "Select Building Status", $building_status);


$hybrid_hostel_options = [
    "1" => [
        "unique_id" => "Yes",
        "value" => "Yes",
    ],
    "2" => [
        "unique_id" => "No",
        "value" => "No",
    ]
];
$hybrid_hostel_options = select_option($hybrid_hostel_options, "Select Hostel Status",$hybrid_hostel);


?>

<style>
    #error_message {
        color: red;
    }
</style>


<!-- Modal with form -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Hostel Creation</h4>
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
                                            <label class="form-label" for="hostel_name">Hostel Name</label>
                                            <input type="text" class="form-control" oninput="valid_user_name(this)" id="hostel_name" name="hostel_name"
                                                value="<?= $hostel_name; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="hostel_id">Hostel Id</label>
                                            <input type="text" oninput="off_id(this)" class="form-control" id="hostel_id" name="hostel_id"
                                                value="<?= $hostel_id; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="district_name" class="form-label">District Name</label>
                                            <select class="select2 form-control" id="district_name" name="district_name"
                                                onchange="get_taluk_name(); get_block(); get_assembly(); get_parliament(); get_corporation(); get_municipality(); get_town_panchayat();" required>
                                                <?php echo $district_name_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="taluk_name" class="form-label">Taluk Name</label>
                                            <select class="select2 form-control" id="taluk_name" name="taluk_name"required>
                                                <?php echo $hostel_taluk_options; ?>
                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="special_tahsildar">Special Thasildhar</label>
                                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                 <input type="text" class="form-control" id="special_tahsildar"
                                                name="special_tahsildar" oninput="valid_user_name(this)" value="<?= $special_tahsildar; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="assembly_const" class="form-label">Name of the Assembly
                                                Constituency </label>
                                            <select name="assembly_const" id="assembly_const"
                                                class="select2 form-control" required>
                                                <?php echo $assembly_const_name_options; ?>
                                                <!-- <?php echo $assembly_options; ?> -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="parliment_const" class="form-label">Name of the parliament
                                                Constituency</label>
                                            <select name="parliment_const" id="parliment_const"
                                                class="select2 form-control" required>
                                                <?php echo $parliment_const_name_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="address">Hostel Address</label>
                                            <input type="textarea" oninput="valid_address(this)" class="form-control" id="address" name="address"
                                                value="<?= $address; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="hostel_location">Hostel Location</label>
                                            <select name="hostel_location" id="hostel_location"
                                                class="select2 form-control" required
                                                onchange="get_hostel_location_type()">
                                                <?php echo $hostel_location_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="urbanFieldsType" style="display: none;">
                                        <div class="mb-3">
                                            <label for="urban_type" class="form-label">Urban Type</label>
                                            <select name="urban_type" id="urban_type" class="select2 form-control"
                                                onchange="get_urban_type()">
                                                <?php echo $urban_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="corporationField" style="display: none;">
                                        <div class="mb-3">
                                            <label for="corporation" class="form-label">Corporation</label>
                                            <select name="corporation" id="corporation" class="select2 form-control">
                                                <?php echo $corporation_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="municipalityField" style="display: none;">
                                        <div class="mb-3">
                                            <label for="municipality" class="form-label">Municipality</label>
                                            <select name="municipality" id="municipality" class="select2 form-control">
                                                <?php echo $municipality_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="town_panchayatField" style="display: none;">
                                        <div class="mb-3">
                                            <label for="town_panchayat" class="form-label">Town Panchayat</label>
                                            <select name="town_panchayat" id="town_panchayat"
                                                class="select2 form-control">
                                                <?php echo $town_panchayat_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="ruralFieldsTypeBlock" style="display: none;">
                                        <div class="mb-3">
                                            <label for="block_name" class="form-label">Block</label>
                                            <select name="block_name" id="block_name" class="select2 form-control"
                                                onchange="get_village()">
                                                <?php echo $block_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="ruralFieldsTypeVillage" style="display: none;">
                                        <div class="mb-3">
                                            <label for="village_name" class="form-label">Village Name</label>
                                            <select name="village_name" id="village_name" class="select2 form-control">
                                                <?php echo $village_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="hostel_type" class="form-label">Hostel Type</label>
                                            <select name="hostel_type" id="hostel_type" class="select2 form-control"
                                                required>
                                                <?php echo $hostel_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="gender_type" class="form-label">Hostel Gender Category</label>
                                            <select name="gender_type" id="gender_type" class="select2 form-control"
                                                required>
                                                <?php echo $gender_type_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Year of Established</label>
                                            <input type="text" class="form-control" id="yob" name="yob"
                                                value="<?= $yob; ?>" oninput="year_only(this)" maxlength="4"
                                                minlength="4" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Government order for Hostel</label>
                                            <input type="file" class="form-control" id="test_file" name="test_file"
                                                accept=".pdf, .doc, .docx, image/*">
                                            <input type="hidden" class="form-control" id="file_name" name="file_name"
                                                value="<?php echo $file_names; ?>">
                                        </div>
                                        <span id="error_message"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Sanctioned Strength</label>
                                            <input type="text" oninput="year_only(this)" class="form-control" id="sanctioned_strength"
                                                name="sanctioned_strength" value="<?= $sanctioned_strength; ?>"
                                                maxlength="3" minlength="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="distance_btw_phc">Kilometer Distance Between
                                                PHC and Hostel</label>
                                            <input type="text" class="form-control" id="distance_btw_phc"
                                                name="distance_btw_phc" value="<?= $distance_btw_phc; ?>"
                                                oninput="number_only(this)" maxlength="2" minlength="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="phc_name">Primary Health Center Name</label>
                                            <input type="text" oninput="valid_user_name(this)" class="form-control" id="phc_name" name="phc_name"
                                                value="<?= $phc_name; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="distance_btw_ps">Kilometer Distance Between  Police Station and Hostel</label>
                                            <input type="text" class="form-control" oninput="number_only(this)" id="distance_btw_ps"
                                                name="distance_btw_ps" value="<?= $distance_btw_ps; ?>"
                                                oninput="number_only(this)" maxlength="2" minlength="1" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="ps_name">Police Station Name</label>
                                            <input type="text" oninput="off_id(this)" class="form-control" id="ps_name" name="ps_name"
                                                value="<?= $ps_name; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="staff_count">Staff Count</label>
                                            <input type="text" oninput="valid_aadhar_number(this)" class="form-control" id="staff_count" name="staff_count"
                                                value="<?= $staff_count; ?>" 
                                                maxlength="2" minlength="1" required>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="hostel_status" class="form-label">Owner Ship</label>

                                            
                                            <select name="hostel_status" id="hostel_status" onchange="get_ownership();" class="select2 form-control"
                                            required> -->
                                            <!-- This select take the label value of the select and displays the Rental Reason -->
                                                <!-- <?php echo $ownership_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="rental_div" style="display:none;">
                                        <div class="md-3">
                                            <label for="rental_reason" class="form-label">Rental Reason</label>
                                            <input type="text" class="form-control" id="rental_reason" name="rental_reason"
                                                value="<?= $rental_reason; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="building_status" class="form-label">Building Status</label>
                                            <select name="building_status" id="building_status" class="select2 form-control"
                                                required>
                                                <?php echo $building_status_options; ?>
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="hybrid_hostel" class="form-label">Hybrid Hostel</label>
                                            <select name="hybrid_hostel" id="hybrid_hostel" class="select2 form-control"
                                                required>
                                                <?php echo $hybrid_hostel_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="md-3">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select name="is_active" id="is_active" class="select2 form-control"
                                                required>
                                                <?php echo $active_status_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" class="form-control" id="unique_id" name="unique_id"
                                        value="<?= $unique_id; ?>">
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