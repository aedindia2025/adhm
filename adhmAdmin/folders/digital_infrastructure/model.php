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

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        // $unique_id  = $_GET["unique_id"];

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = [
            "unique_id" => $unique_id
        ];

        $table = "digital_infra_creation";

        $columns = [
            "academic_year",
            "hostel_name",
            "hostel_id",
            "taluk",
            "district",
            "land_type",
            "owner_of_land",
            "reg_of_land",
            "area_of_land",
            "con_area_land",
            "existing_demolished",
            "no_floors",
            "toilet_each_floor",
            "compound_wall",
            "water_facilities",
            "living_area",
            "living_area_size",
            "no_of_rooms",
            "room_size",
            "kitchen_size",
            "demolished",
            "land_doc_name",
            "land_doc_org_name",
            "unique_id",

        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        // print_r($result_values);die();

        if ($result_values->status) {

            $result_values = $result_values->data;

            $academic_year = $result_values[0]["academic_year"];
            $hostel_name = $result_values[0]["hostel_name"];
            $hostel_id = $result_values[0]["hostel_id"];
            $taluk = $result_values[0]["taluk"];
            $district = $result_values[0]["district"];
            $land_type = $result_values[0]["land_type"];
            $owner_of_land = $result_values[0]["owner_of_land"];
            $reg_of_land = $result_values[0]["reg_of_land"];
            $area_of_land = $result_values[0]["area_of_land"];
            $con_area_land = $result_values[0]["con_area_land"];
            $existing_demolished = $result_values[0]["existing_demolished"];
            $no_floors = $result_values[0]["no_floors"];
            $toilet_each_floor = $result_values[0]["toilet_each_floor"];
            $compound_wall = $result_values[0]["compound_wall"];
            $water_facilities = $result_values[0]["water_facilities"];
            $living_area = $result_values[0]["living_area"];
            $living_area_size = $result_values[0]["living_area_size"];
            $no_of_rooms = $result_values[0]["no_of_rooms"];
            $room_size = $result_values[0]["room_size"];
            $no_of_kitchen = $result_values[0]["no_of_kitchen"];
            $kitchen_size = $result_values[0]["kitchen_size"];
            $demolished = $result_values[0]["demolished"];
            $land_doc_name = $result_values[0]["land_doc_name"];
            $land_doc_org_name = $result_values[0]["land_doc_org_name"];
            $m_unique_id = $result_values[0]["unique_id"];

            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$land_type_options = [
    "1" => [
        "unique_id" => "own_land",
        "value" => "own_land",
    ],
    "2" => [
        "unique_id" => "not_own_land",
        "value" => "not_own_land",
    ]
];
$land_type_options = select_option($land_type_options, "Select Land Type", $land_type);


$owner_of_land_options = [
    "1" => [
        "unique_id" => "DADAWO",
        "value" => "DADAWO",
    ],
    "2" => [
        "unique_id" => "Poramboke",
        "value" => "Poramboke",
    ]
];
$owner_of_land_options = select_option($owner_of_land_options, "Select Owner Of The Land", $owner_of_land);

$existing_demolished_options = [
    "1" => [
        "unique_id" => "existing",
        "value" => "existing",
    ],
    "2" => [
        "unique_id" => "demolished",
        "value" => "demolished",
    ]
];
$existing_demolished_options = select_option($existing_demolished_options, "Select Option", $existing_demolished);

$no_floors_options = [
    "1" => [
        "unique_id" => "first_floor",
        "value" => "first_floor",
    ],
    "2" => [
        "unique_id" => "second_floor",
        "value" => "second_floor",
    ],
    "3" => [
        "unique_id" => "Third_floor",
        "value" => "Third_floor",
    ],
    "4" => [
        "unique_id" => "fourth_floor",
        "value" => "fourth_floor",
    ],
    "5" => [
        "unique_id" => "fifth_floor",
        "value" => "fifth_floor",
    ],
    "6" => [
        "unique_id" => "sixth_floor",
        "value" => "sixth_floor",
    ],
    "7" => [
        "unique_id" => "seventh_floor",
        "value" => "seventh_floor",
    ],
    "8" => [
        "unique_id" => "eighth_floor",
        "value" => "eighth_floor",
    ],
    "9" => [
        "unique_id" => "ninth_floor",
        "value" => "ninth_floor",
    ],

];
$no_floors_options = select_option($no_floors_options, "Select Floor", $no_floors);

$compound_wall_options = [
    "1" => [
        "unique_id" => "4 sides available",
        "value" => "4 sides available",
    ],
    "2" => [
        "unique_id" => "partially available",
        "value" => "partially available",
    ],
    "3" => [
        "unique_id" => "4 sides available but damaged",
        "value" => "4 sides available but damaged",
    ],
    "4" => [
        "unique_id" => "not available",
        "value" => "not available",
    ]
];
$compound_wall_options = select_option($compound_wall_options, "Select Option", $compound_wall);

$water_facilities_options = [
    "1" => [
        "unique_id" => "borewell",
        "value" => "borewell",
    ],
    "2" => [
        "unique_id" => "panchayat union water",
        "value" => "panchayat union water",
    ],
    "3" => [
        "unique_id" => "corporation water",
        "value" => "corporation water",
    ],
    "4" => [
        "unique_id" => "private water",
        "value" => "private water",
    ],
    "5" => [
        "unique_id" => "RO water facilities available",
        "value" => "RO water facilities available",
    ]
];
$water_facilities_options = select_option($water_facilities_options, "Select Water Facility", $water_facilities);

$demolished_options = [
    "1" => [
        "unique_id" => "New construction is ongoing",
        "value" => "New construction is ongoing",
    ],
    "2" => [
        "unique_id" => "New construction is yet not taken",
        "value" => "New construction is yet not taken",
    ]
];
$demolished_options = select_option($demolished_options, "Select Land Type", $demolished);

$academic_year_options = academic_year($academic_year);
$academic_year_options = select_option_acc($academic_year_options, $academic_year);

$facility_type_options = facility_type();
$facility_type_options = select_option($facility_type_options, 'Select Facility Type', $asset_category);

$facility_name_options = facility_name();
$facility_name_options = select_option($facility_name_options, 'Select Facility Name', $asset_name);

if ($_GET["unique_id"] != '') {
    $unique_id = $m_unique_id;
} else {
    // $prefix    = "PO";
    $unique_id = unique_id($prefix);
}

?>

<style>
    .fm {
        margin-bottom: 15px;
    }

    .btn {
        background: #149cce;
        color: white;
        margin-bottom: 25px;
    }

    .btn-light:hover {
        background-color: white !important;
        color: black !important;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: var(--ct-nav-pills-link-active-color);
        background-color: #149cce;
    }

    .form-label {
        margin-bottom: 2px;
        font-weight: 300;
    }

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

                        <h4 class="page-title">Infra and facilities</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form>
                                <div id="basicwizard">
                                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#home1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 py-2 active">
                                                <i class="mdi mdi-account-circle font-18 align-middle me-1"></i>
                                                <span class="d-none d-sm-inline">Hostel Info</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profile1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 py-2">
                                                <i class="mdi mdi-face-man-profile font-18 align-middle me-1"></i>
                                                <span class="d-none d-sm-inline">Land Details</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#settings1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 py-2">
                                                <i
                                                    class="mdi mdi-checkbox-marked-circle-outline font-18 align-middle me-1"></i>
                                                <span class="d-none d-sm-inline">Building Details</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="#settings2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 py-2">
                                                <i
                                                    class="mdi mdi-checkbox-marked-circle-outline font-18 align-middle me-1"></i>
                                                <span class="d-none d-sm-inline">Other facilities</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content b-0 mb-0">
                                        <div class="tab-pane active" id="home1">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-12 fm">
                                                            <label for="example-select" class="form-label">Acadamic
                                                                Year</label>
                                                            <select class="form-select" id="acc_year" name="acc_year"
                                                                disabled>
                                                                <?php echo $academic_year_options; ?></select>
                                                        </div>
                                                        <div class="col-md-12 fm">
                                                            <label for="simpleinput" class="form-label">Hostel
                                                                Name</label>
                                                            <input type="text" class="form-control" id="hostel_name"
                                                                name="hostel_name"  value="<?php echo hostel_name($hostel_name)[0]['hostel_name']; ?>"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-12 fm">
                                                            <label for="simpleinput" class="form-label">Hostel
                                                                ID</label>
                                                            <input type="text" class="form-control" id="hostel_id"
                                                                name="hostel_id"
                                                                value="<?php echo $hostel_id;?>"
                                                                readonly>
                                                            <input type="hidden" id="unique_id" name="unique_id"
                                                                class="form-control" value="<?php echo $unique_id; ?>">
                                                            <input type="hidden" id="csrf_token" name="csrf_token"
                                                                value="<?php echo $_SESSION['csrf_token']; ?>">

                                                            <input type="hidden" id="update_unique_id"
                                                                name="update_unique_id"
                                                                value="<?php echo $m_unique_id; ?>">
                                                        </div>
                                                        <div class="col-md-12 fm">
                                                            <label for="example-select" id="taluk" name="taluk"
                                                                class="form-label">Taulk</label>
                                                            <input type="text" class="form-control"
                                                            value="<?php echo taluk_name($taluk)[0]['taluk_name'];?>"
                                                            readonly>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-md-6 fm">
                                                    <label for="example-select" class="form-label">District</label>
                                                    <input class="form-control" id="district" name="district"

                                                    value="<?php echo district_name($district)[0]['district_name'];?>"
                                                    readonly>
                                                </div>
                                                <input type="hidden" id="district_id" name="district_id"
                                                    class="form-control"
                                                    value="<?php echo $_SESSION["district_id"]; ?>">

                                                <input type="hidden" id="taluk_id" name="taluk_id" class="form-control"
                                                    value="<?php echo $_SESSION["taluk_id"]; ?>">

                                                <input type="hidden" id="hostel_unique_id" name="hostel_unique_id"
                                                    class="form-control" value="<?php echo $_SESSION["hostel_id"]; ?>">
                                            </div>
                                            <ul class="list-inline wizard mb-0">
                                                <li class="next list-inline-item float-end">
                                                    <button type="button" class="btn btn-info">Next <i
                                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-pane" id="profile1">
                                            <div class="row">
                                                <div class="col-md-5 fm">
                                                    <label for="example-select" class="form-label">Having own land / Not
                                                        having own land</label>
                                                    <select name="land_type" id="land_type" class="select2 form-control"
                                                        required onchange="changeLandType()">
                                                        <?php echo $land_type_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="own_land_div" style="display:none;">
                                                <br>
                                                <hr>
                                                <br>
                                                <div class="row">
                                                    <div class="col-md-3 fm">
                                                        <label for="example-select" class="form-label">Owner of the
                                                            land</label>
                                                        <select name="owner_of_land" id="owner_of_land"
                                                            class="select2 form-control" required>
                                                            <?php echo $owner_of_land_options; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label for="simpleinput" class="form-label">A-Register of the
                                                            land</label>
                                                        <input type="text" id="reg_of_land" name="reg_of_land"
                                                            class="form-control" oninput="off_id(this)"
                                                            value="<?php echo $reg_of_land; ?>">
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Doc Upload</label>
                                                        <input class="form-control" type="file" id="doc_file"
                                                            name="doc_file" accept=".doc, .docx, .pdf, .txt, image/*">
                                                        <input class="form-control" type="hidden" id="land_pic"
                                                            name="land_pic" value="<?= $land_doc_name;?>">
                                                    </div>

                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Total Area of land</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="area_of_land"
                                                                name="area_of_land" oninput="number_only(this)"
                                                                value="<?php echo $area_of_land; ?>">
                                                            <span class="input-group-text">sq ft</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Constructed area of land</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="con_area_land"
                                                                name="con_area_land" oninput="number_only(this)"
                                                                value="<?php echo $con_area_land; ?>">
                                                            <span class="input-group-text">sq ft</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="pager wizard mb-0 list-inline">
                                                <li class="previous list-inline-item">
                                                    <button type="button" class="btn btn-light"><i
                                                            class="mdi mdi-arrow-left me-1"></i>Back</button>
                                                </li>
                                                <li class="next list-inline-item float-end">
                                                    <button type="button" class="btn btn-info">Next <i
                                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-pane" id="settings1">
                                            <div class="row">
                                                <div class="col-md-4 fm">
                                                    <label for="existing_demolished" class="form-label">Existing or
                                                        Demolished</label>
                                                    <select name="existing_demolished" id="existing_demolished"
                                                        class="select2 form-control"
                                                        onchange="exisiting_demolished(this)" required>
                                                        <?php echo $existing_demolished_options; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm" id="demolished_div" style="display: none;">
                                                    <label for="demolished" class="form-label">If demolished</label>
                                                    <select name="demolished" id="demolished"
                                                        class="select2 form-control" >
                                                        <?php echo $demolished_options; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm" id="compound_waal_div" style="display: none;">
                                                    <label class="form-label">Compound wall</label>
                                                    <select name="compound_wall" id="compound_wall"
                                                        class="select2 form-control">
                                                        <?php echo $compound_wall_options; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm" id="water_fac_div" style="display: none;">
                                                    <label class="form-label">Water facilities</label>
                                                    <select name="water_facilities" id="water_facilities"
                                                        class="select2 form-control">
                                                        <?php echo $water_facilities_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="ground_floor_div" style="display:none">
                                                <br>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-3 fm" id="no_floor_div">
                                                        <label for="no_floors" class="form-label">Floor</label>
                                                        <select name="no_floors" id="no_floors"
                                                            class="select2 form-control" >
                                                            <?php echo $no_floors_options; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 fm" id="no_toilet_div">
                                                        <label class="form-label">Number of toliet</label>
                                                        <input type="text" id="toilet_each_floor"
                                                            name="toilet_each_floor" class="form-control"
                                                            oninput="number_only(this)" minlength="1" maxlength="3"
                                                            value="<?php echo $toilet_each_floor; ?>">
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Number of living area</label>
                                                        <input type="text" id="living_area" name="living_area"
                                                            class="form-control" oninput="number_only(this)"
                                                            minlength="1" maxlength="3"
                                                            value="<?php echo $living_area; ?>">
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Living Area Size</label>
                                                        <div class="input-group">
                                                            <input type="text" id="living_area_size"
                                                                name="living_area_size" class="form-control"
                                                                oninput="number_only(this)"
                                                                value="<?php echo $living_area_size; ?>">
                                                            <span class="input-group-text">sq ft</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Number of Rooms</label>
                                                        <input type="text" id="no_of_rooms" name="no_of_rooms"
                                                            class="form-control" oninput="number_only(this)"
                                                            minlength="1" maxlength="3"
                                                            value="<?php echo $no_of_rooms; ?>">
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Room Size</label>
                                                        <div class="input-group">
                                                            <input type="text" id="room_size" name="room_size"
                                                                class="form-control" oninput="number_only(this)"
                                                                value="<?php echo $room_size; ?>">
                                                            <span class="input-group-text">sq ft</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Number of Kitchen</label>
                                                        <input type="text" id="no_of_kitchen" name="no_of_kitchen"
                                                            class="form-control" oninput="number_only(this)"
                                                            minlength="1" maxlength="3"
                                                            value="<?php echo $no_of_kitchen; ?>">
                                                    </div>
                                                    <div class="col-md-3 fm">
                                                        <label class="form-label">Kitchen Size</label>
                                                        <div class="input-group">
                                                            <input type="text" id="kitchen_size" name="kitchen_size"
                                                                class="form-control" oninput="number_only(this)"
                                                                value="<?php echo $kitchen_size; ?>">
                                                            <span class="input-group-text">sq ft</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <button class="btn" type="button"
                                                            onclick="buildings_sub_add_update()">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="datatableSub" style="display:none">
                                                <br>
                                                <div class="col-12">
                                                    <table id="buildings_sub_datatable"
                                                        class="table dt-responsive nowrap w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>S.no</th>
                                                                <th>Floor</th>
                                                                <th>No Of Toilet</th>
                                                                <th>No Of Living Area</th>
                                                                <th>Living Area Size</th>
                                                                <th>No Of Room</th>
                                                                <th>Room Size</th>
                                                                <th>No Of Kitchen</th>
                                                                <th>Kitchen Size</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <ul class="pager wizard mb-0 list-inline mt-1">
                                                <li class="previous list-inline-item">
                                                    <button type="button" class="btn btn-light"><i
                                                            class="mdi mdi-arrow-left me-1"></i> Back
                                                    </button>
                                                </li>
                                                <li class="next list-inline-item float-end">
                                                    <button type="button" class="btn btn-info">Next <i
                                                            class="mdi mdi-arrow-right ms-1"></i></button>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-pane" id="settings2">
                                            <div class="row">
                                                <div class="col-md-4 fm">
                                                    <label class="form-label">Facility Type</label>
                                                    <select name="facilities_type" id="facilities_type"
                                                        class="form-control" onchange="get_asset_name()">
                                                        <?php echo $facility_type_options; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm">
                                                    <label for="example-select" class="form-label">Facility Name</label>
                                                    <select name="facilities" id="facilities" class="form-control"
                                                        >
                                                        <?php echo $facility_name_options; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="text" id="quantity" name="quantity" minlength="1"
                                                        maxlength="3" oninput="number_only(this)" class="form-control">
                                                </div>
                                                <div class="col-md-4 fm">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description"
                                                        oninput="description_val(this)" style="height: 60px"></textarea>
                                                </div>
                                            </div>
                                            <div>
                                                <button class="btn" type="button"
                                                    onclick="save_facilities()">Save</button>
                                            </div>
                                            <div class="row">
                                                <br>
                                                <div class="col-12">
                                                    <table id="digital_infra_datatable"
                                                        class="table dt-responsive nowrap w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Facility Type</th>
                                                                <th>Facility Name</th>
                                                                <th>Quantity</th>
                                                                <th>Description</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                            <ul class="pager wizard mb-0 list-inline mt-1">
                                                <li class="previous list-inline-item">
                                                    <button type="button" class="btn btn-light"><i
                                                            class="mdi mdi-arrow-left me-1"></i> Back
                                                    </button>
                                                </li>
                                                <li class="next list-inline-item float-end">
                                                    <?php echo btn_cancel($btn_cancel); ?>
                                                    <button type="button" class="btn btn-info"
                                                        onclick="digital_infrastructure_cu()">Submit</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- tab-content -->
                                </div> <!-- end #basicwizard-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>