<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";

$unique_id = "";
$user_type = "";
$is_active = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "benefit_item_management";

        $columns = [
            "benefit_category",
            "benefit_item",
            "frequency_type",
            "student_type",
            "status",
            
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $benefit_category = $result_values[0]["benefit_category"];
            $benefit_item = $result_values[0]["benefit_item"];
            $frequency_type = $result_values[0]["frequency_type"];
            $student_type = $result_values[0]["student_type"];
            $status = $result_values[0]["status"];
            $is_active = $result_values[0]["is_active"];

            $benefit_item_explode = explode(',',$benefit_item);

            $benefit_item_options = benefit_item("", $benefit_category);
            $benefit_item_options = select_option($benefit_item_options, '', $benefit_item_explode);



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

$benefit_category_options = benefit_category();
$benefit_category_options = select_option($benefit_category_options, 'Select Category', $benefit_category);

$frequency_type_options        = [
    "1" => [
        "unique_id" => "Annual",
        "value"     => "Annual",
    ],
    "2" => [
        "unique_id" => "Monthly",
        "value"     => "Monthly",
    ],
    "3" => [
        "unique_id" => "Quarterly",
        "value"     => "Quarterly",
    ]
];

$frequency_type_options        = select_option( $frequency_type_options, "Select", $frequency_type);

$student_type_options        = [
    "1" => [
        "unique_id" => "1",
        "value"     => "New",
    ],
    "2" => [
        "unique_id" => "2",
        "value"     => "Renewal",
    ],
    "3" => [
        "unique_id" => "3",
        "value"     => "All",
    ]
];

$student_type_options        = select_option( $student_type_options, "Select", $student_type);


$status_type_options        = [
    "1" => [
        "unique_id" => "0",
        "value"     => "Pending",
    ],
    "2" => [
        "unique_id" => "1",
        "value"     => "Initiated",
    ],
    "3" => [
        "unique_id" => "2",
        "value"     => "Completed",
    ]
];

$status_type_options        = select_option( $status_type_options, "Select", $status);


?>
<!-- Modal with form -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Benefit Item Management Form</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">



                                <form class="was-validated" autocomplete="off">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Benefit Category</label>
                                                    <select name="benefit_category" id="benefit_category"
                                                        class="select2 form-control" onchange="get_benefit_item()" required>
                                                        <?php echo $benefit_category_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Benefit Item</label>
                                                    <select name="benefit_item" id="benefit_item"
                                                        class="select2 form-control" multiple onchange = "get_imploded_item()" required>
                                                        <?php echo $benefit_item_options; ?>
                                                    </select>
                                                    <input type="hidden" id="imploded_item" name="imploded_item">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Frequency Of Distribution</label>
                                                    <select name="frequency_type" id="frequency_type"
                                                        class="select2 form-control" required>
                                                        <?php echo $frequency_type_options; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Student Type</label>
                                                    <select name="student_type" id="student_type"
                                                        class="select2 form-control" required>
                                                        <?php echo $student_type_options; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label>Status</label>
                                                    <select name="status" id="status"
                                                        class="select2 form-control" required>
                                                        <?php echo $status_type_options; ?>
                                                    </select>
                                                </div>
                                            </div>

                                           


                                           
                                        </div>
                                        <div class="btns">
                                        <?php echo btn_cancel($btn_cancel); ?>
                                        <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                        </div>
                                    </form>

                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->

                    </div>




                </div>
            </div>



        </div>
    </div>
</div>