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
$prefix = "";
$entry_date = date('Y-m-d');

// $unique_id          = "";
$district_name = "";
$is_active = 1;
$screen_unique_id = unique_id($preifx);

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = "unique_id = '$unique_id'";
        $table_main = "moveable_kitchen_sub";

        $columns = [
            "category",
            "asset",
            "big_small",
            "quantity",
            "unit",
            "procurement_year",
            "unique_id",
            "screen_unique_id",

        ];

        $table_details = [
            $table_main,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        if ($result_values->status) {

            $result_values = $result_values->data;

            $category = $result_values[0]["category"];
            $asset = $result_values[0]["asset"];
            $big_small = $result_values[0]["big_small"];
            $quantity = $result_values[0]["quantity"];
            $unit = $result_values[0]["unit"];
            $kitchen_procurement_year = $result_values[0]["procurement_year"];

            $hostel_id = $result_values[0]["hostel_id"];
            $district_id = $result_values[0]["district_id"];
            $taluk_id = $result_values[0]["taluk_id"];
            $unique_id = $result_values[0]["unique_id"];
            $screen_unique_id = $result_values[0]["screen_unique_id"];

            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$district_unique_id = $_SESSION["district_id"];
$taluk_unique_id = $_SESSION['taluk_id'];
$hostel_unique_id = $_SESSION['hostel_id'];

$kitchen_category_type = kitchen_category_type();
$kitchen_category_type = select_option($kitchen_category_type, "Select Category", $category);
$kitchen_asset_type = kitchen_asset_type();
$kitchen_asset_type = select_option($kitchen_asset_type, "Select Asset", $asset);

if ($digital_category_options == '66cc136163d4742652' || $digital_category_options == '66cc136b9023e52900') {
    $display_style = 'style="display:block"';
} else {
    $display_style = 'style="display:none"';
}

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;

        $where = "unique_id = '$unique_id'";
        $table_main = "moveables_digital_sub";

        $columns = [
            "category",
            "asset",
            "quantity",
            "spe_devices",
            "other_brand",
            "procurement_year",
            "location_dev",
            "size",
            "brand",
            "cableConnection",
            "unique_id",
            "screen_unique_id",

        ];

        $table_details = [
            $table_main,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);
        if ($result_values->status) {

            $result_values = $result_values->data;


            $category = $result_values[0]["category"];
            $assets_di = $result_values[0]["asset"];
            $digital_quantity = $result_values[0]["quantity"];
            $spe_devices = $result_values[0]["spe_devices"];
            $other_brand = $result_values[0]["other_brand"];
            $digital_procurement_year = $result_values[0]["procurement_year"];
            $location_dev = $result_values[0]["location_dev"];
            $size = $result_values[0]["size"];
            $brand_digit = $result_values[0]["brand"];
            $cableConnection = $result_values[0]["cableConnection"];
            $hostel_id = $result_values[0]["hostel_id"];
            $district_id = $result_values[0]["district_id"];
            $taluk_id = $result_values[0]["taluk_id"];
            $unique_id = $result_values[0]["unique_id"];
            $screen_unique_id = $result_values[0]["screen_unique_id"];


            $btn_text = "Update";
            $btn_action = "update";
        } else {
            $btn_text = "Error";
            $btn_action = "error";
            $is_btn_disable = "disabled='disabled'";
        }
    }
}

$size_options = [
    "1" => [
        "unique_id" => "big",
        "value" => "big",
    ],
    "2" => [
        "unique_id" => "small",
        "value" => "small",
    ]
];
$size_options = select_option($size_options, "Select Size", $big_small);

$digital_category_option = digital_category();
$digital_category_options = select_option($digital_category_option, 'Select', $category);

// $digital_asset_option = type_of_equipment();
// $digital_asset_options = select_option($digital_asset_option, 'Select', $assets_di);

$digital_assets = type_of_equipment();
$digital_asset = select_option($digital_assets, 'Select', $assets_di);


$procurement_year = [];

$current_year = date("Y");

for ($year = 2000; $year <= $current_year; $year++) {
    $procurement_year[$year] = [
        "unique_id" => $year,
        "value" => $year,
    ];
}

$procurement_years = select_option($procurement_year, "Select Year", $kitchen_procurement_year);

$d_procurement_year = [];

for ($year = 2000; $year <= 2024; $year++) {
    $d_procurement_year[$year] = [
        "unique_id" => $year,
        "value" => $year,
    ];
}
$digital_procurement_year = select_option($d_procurement_year, "Select Year", $digital_procurement_year);

$exp_location_name = explode(',', $location_dev);

$location_name_list = [
    "1" => [
        "unique_id" => "Warden Office",
        "value" => "Warden Office",
    ],
    "2" => [
        "unique_id" => "Dining Hall",
        "value" => "Dining Hall",
    ],
    "3" => [
        "unique_id" => "Library",
        "value" => "Library",
    ],
    "4" => [
        "unique_id" => "Common Room",
        "value" => "Common Room",
    ]
];

$location_name_list = select_option($location_name_list, "Select Location", $exp_location_name);

$location_names = implode(',', $exp_location_name);


// Define the array of brands with 'unique_id' and 'value'
$brands = [
    "1" => [
        "unique_id" => "Samsung",
        "value" => "Samsung"
    ],
    "2" => [
        "unique_id" => "Dell",
        "value" => "Dell"
    ],
    "3" => [
        "unique_id" => "HP",
        "value" => "HP"
    ],
    "4" => [
        "unique_id" => "Lenovo",
        "value" => "Lenovo"
    ],
    "5" => [
        "unique_id" => "Apple",
        "value" => "Apple"
    ],
    "6" => [
        "unique_id" => "Acer",
        "value" => "Acer"
    ],
    "7" => [
        "unique_id" => "Asus",
        "value" => "Asus"
    ],
    "8" => [
        "unique_id" => "Xiaomi",
        "value" => "Xiaomi"
    ],
    "9" => [
        "unique_id" => "HCL",
        "value" => "HCL"
    ],
    "10" => [
        "unique_id" => "Microsoft",
        "value" => "Microsoft"
    ],
    "11" => [
        "unique_id" => "MacBook",
        "value" => "MacBook"
    ],
    "12" => [
        "unique_id" => "others",
        "value" => "Others"
    ]
];
$brands = select_option($brands, "Select Brand", $brand_digit);

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


    #error_message {
        color: red;
    }

    h5.tab-hed {
        text-align: center;
        font-weight: 600;
        margin: 19px 0px;
    }

    b.xy-lab {
        color: #484545;
    }

    .input-group {
        display: flex;
        align-items: center;
    }

    .input-group-text {
        padding: 0.375rem 0.75rem;
        background-color: #e9ecef;
        border: 1px solid #ced4da;
        border-left: 0;
    }
</style>
<!-- Modal with form -->

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->


            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <?php echo btn_cancel($btn_cancel); ?>
                        </form>
                    </div>
                    <h4 class="page-title">Moveables Assets</h4>

                    <input type="hidden" id="screen_unique_id" name="screen_unique_id"
                        value="<?= $screen_unique_id; ?>">
                    <input type="hidden" id="unique_id" name="unique_id" value="<?php echo $get_uni_id; ?>"></input>
                </div>
            </div>

            <div class="col-12">
                <div class="card" style="width: 100%">

                    <div class="card-body">

                        <!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

                        

                            <div class="row">
                                <div class="col-md-4 fm">
                                    <label for="staff_id" class="form-label">Hostel ID:
                                        <?= $_SESSION['hostel_main_id']; ?></label>
                                    <input type="hidden" id="hostel_id" name="hostel_id"
                                        value="<?php echo $_SESSION['hostel_id']; ?>"></input>
                                </div>
                                <div class="col-md-4 fm">
                                    <label for="hostel_id" class="form-label">Hostel Name:
                                        <?php echo $_SESSION['hostel_name']; ?></label>
                                    <input type="hidden" id="csrf_token" name="csrf_token"
                                        value="<?php echo $_SESSION['csrf_token']; ?>">

                                    <input type="hidden" id="hostel_id" name="hostel_id"
                                        value="<?php echo $_SESSION['hostel_id']; ?>"></input>
                                </div>
                                <div class="col-md-4 fm">
                                    <label for="academic_year" class="form-label">Academic Year:
                                        <?php echo $_SESSION["acc_year"]; ?></label>
                                    <input type="hidden" id="academic_year" name="academic_year"
                                        value="<?php echo $ses_academic_year; ?>"></input>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-4 fm">
                                    <label for="district_id" class="form-label">District Name:
                                        <?php echo $_SESSION["district_name"]; ?></label>

                                    <input type="hidden" id="district_id" name="district_id"
                                        value="<?php echo $_SESSION["district_id"]; ?>"></input>
                                </div>
                                <div class="col-md-4 fm">
                                    <label for="taluk_id" class="form-label">Taluk Name:
                                        <?php echo $_SESSION['taluk_name']; ?></label>
                                    <input type="hidden" id="taluk_id" name="taluk_id"
                                        value="<?php echo $_SESSION['taluk_id']; ?>"></input>
                                </div>

                                <div class="col-md-4 fm">
                                    <label for="staff_name" class="form-label">Staff Name:
                                        <?php echo $_SESSION["user_name"]; ?></label>
                                    <input type="hidden" id="staff_name" name="staff_name"
                                        value="<?php echo $user_name; ?>"></input>

                                </div>
                            </div>
                        

                        <!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div id="basicwizard">

                                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2 active">

                                                <span class=" d-sm-inline">Kitchen</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">

                                                <span class=" d-sm-inline">Digital</span>
                                            </a>
                                        </li>

                                    </ul>

                                    <div class="tab-content b-0 mb-0">
                                        <div class="tab-pane active" id="basictab1">
                                            <h5 class="tab-hed">உபயோகத்தில் உள்ள சமையல் அறை மற்றும் எண்ணியல் உபகரணங்கள்
                                                | In-Use Kitchen and Digital Equipment
                                            </h5>
                                            <hr>

                                            <form class="was-validated1" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-12 col-md-4 fm">

                                                        <label for="simpleinput" class="form-label">Category</label>
                                                        <select class="form-control" name="category" id="category"
                                                            onchange="get_asset_name(this.value)" required>
                                                            <?php echo $kitchen_category_type; ?>
                                                        </select>
                                                        <input type="hidden" id="kitchen_sub_id" name="kitchen_sub_id"
                                                            value="<?= $kitchen_sub_id; ?>">
                                                    </div>

                                                    <div class="col-12 col-md-4 fm">
                                                        <label for="simpleinput" class="form-label">Kitchen
                                                            Asset</label>
                                                        <select class="form-control" name="asset" id="asset"
                                                            onchange="get_unit_name(this.value);" required>
                                                            <?php echo $kitchen_asset_type; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4 fm big-small"
                                                        style="<?php echo !empty($big_small) ? '' : 'display:none;'; ?>">

                                                        <label for="simpleinput" class="form-label">Size</label>
                                                        <select class="form-control" name="big_small" id="big_small">
                                                            <?php echo $size_options; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-4 fm">
                                                        <label class="form-label">Capacity/ Quantity</label>
                                                        <input type="text" id="capacity" name="capacity"
                                                            class="form-control" oninput="number_only(this)" required
                                                            value="<?php echo $quantity; ?>">
                                                    </div>
                                                    <div class="col-12  col-md-4 fm">

                                                        <label class="form-label">Unit Types </label>
                                                        <input type="year" class="form-control" id="unit" name="unit"
                                                            value="<?php echo $unit; ?>" readonly>
                                                    </div>

                                                    <div class="col-12 col-md-4 fm">

                                                        <label for="simpleinput" class="form-label">Procurement
                                                            Year</label>
                                                        <select class="form-control" name="p_year" id="p_year" required>
                                                            <?php echo $procurement_years; ?>
                                                        </select>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-12" style="text-align: center;">
                                                            <button type="button" name="btn" id="btn"
                                                                class="save btn btn-primary"
                                                                onclick="moveable_kitchen()">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div> <!-- end row -->

                                        <!--/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

                                        <div class="tab-pane" id="basictab2">
                                            <div class="">
                                                <form class="was-validated2" autocomplete="off">
                                                    <div class="row">
                                                        <div class="col-12 col-md-3 fm">

                                                            <label class="form-label">Category</label>
                                                            <select class="form-control" name="digital_category"
                                                                id="digital_category" onchange="equipment_type()"
                                                                required>
                                                                <?php echo $digital_category_options; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-md-3 fm">
                                                            <label class="form-label">Type of equipment </label>
                                                            <select class="form-control" name="type_of_equipment"
                                                                id="type_of_equipment" required>
                                                                <?php echo $digital_asset; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-md-3 fm">
                                                            <label class="form-label"> Number of Functional devices
                                                            </label>
                                                            <input type="text" class="form-control" id="no_of_dev"
                                                                oninput="number_only(this)" name="no_of_dev"
                                                                value="<?php echo $digital_quantity; ?>">
                                                        </div>

                                                        <div class="col-12 col-md-3 fm">
                                                            <label for="simpleinput" class="form-label">Location
                                                                Of Functional Devices</label>
                                                            <select name="location_dev" id="location_dev" required
                                                                onchange="get_location()" class="select2 form-control" multiple 
                                                                >
                                                                <?php echo $location_name_list; ?>
                                                            </select>
                                                            <input type="hidden" name="loc_dev" id="loc_dev"
                                                                value="<?php echo $location_names; ?>">
                                                        </div>

                                                        <div class="col-12 col-md-3 fm sh" <?php echo $display_style; ?>>
                                                            <label class="form-label">Specifications of devices </label>
                                                            <input type="text" class="form-control" id="spe_devices"
                                                                name="spe_devices" value="<?php echo $spe_devices; ?>">
                                                            <small id="speDevicesHelp" class="form-text text-muted">CPU
                                                                ID, Monitor ID, TV Brand</small>
                                                        </div>
                                                        <div class="col-12 col-md-3 fm">
                                                            <label class="form-label"> Brand </label>
                                                            <select class="form-control" id="options" name="options"
                                                                required>
                                                                <?php echo $brands; ?>
                                                            </select>

                                                            <input type="text" id="othertext" name="othertext"
                                                                class="form-control col-3 mt-2 specification"
                                                                placeholder="Please specify"
                                                                value="<?php echo $other_brand; ?>"
                                                                style="display:none;">
                                                        </div>

                                                        <div class="col-12 col-md-3 fm ccn"
                                                            style="<?php echo !empty($brand) ? 'display:none;' : ''; ?>">
                                                            <label class="form-label">Cable Connection</label>
                                                            <div class="mt-2">
                                                                <div class="form-check form-check-inline">
                                                                    <input type="radio" id="customRadio3"
                                                                        name="customRadio1" class="form-check-input"
                                                                        value="1" <?php echo ($cableConnection == '1') ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="customRadio3">Yes</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input type="radio" id="customRadio4"
                                                                        name="customRadio1" class="form-check-input"
                                                                        value="0" <?php echo ($cableConnection == '0') ? 'checked' : ''; ?>>
                                                                    <label class="form-check-label"
                                                                        for="customRadio4">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-3 fm sz">
                                                            <label class="form-label">Size</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control"
                                                                    oninput="dec_number(this)" id="size" name="size"
                                                                    value="<?php echo $size; ?>">
                                                                <span class="input-group-text">inches</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-12 col-md-3 fm">

                                                            <label for="simpleinput" class="form-label">Procurement
                                                                Year</label>
                                                            <select class="form-control" name="procurement_year"
                                                                id="procurement_year" required>
                                                                <?php echo $digital_procurement_year; ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">

                                                        <div class="col-12" style="text-align: center;">
                                                            <button type="button" name="btn" class="btn btn-primary"
                                                                onclick="digital_equipment()">Save</button>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                    </div>

                                                </form>
                                            </div> <!-- end row -->
                                        </div>
                                    </div> <!-- tab-content -->
                                </div>
                            </div> <!-- end #basicwizard-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="capacityModal" tabindex="-1" role="dialog" aria-labelledby="capacityModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capacityModalLabel">Update Capacity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>An entry with this asset already exists. Please enter the new capacity:</p>
                <form id="capacityForm">
                    <div class="form-group">
                        <input type="number" class="form-control" id="newCapacity" name="newCapacity"
                            placeholder="New capacity" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-secondary" value="Cancel" data-dismiss="modal"></input>
                <input type="button" class="btn btn-primary" value="Save" onclick="updateEntry()"></input>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('options').addEventListener('change', function () {
        // alert();
        var otherTextField = document.getElementById('othertext');
        // alert(otherTextField);
        if (this.value === 'others') {
            otherTextField.style.display = 'block';
            otherTextField.setAttribute('required', 'required');
        } else {
            otherTextField.style.display = 'none';
            otherTextField.removeAttribute('required');
        }
    });
</script>