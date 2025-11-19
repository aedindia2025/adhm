<?php

// Form variables
$btn_text = "Save";
$btn_action = "create";

// $unique_id          = "";

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "";

        $columns = [];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $is_active = $result_values[0]["is_active"];



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

                        <h4 class="page-title">Digital Infrastructure</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">


                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                    <div class="row mb-3">
                                    <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Hostel</label>
                                            <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="Govt Boys Hostel" required readonly>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Taluk</label>
                                            <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="Bhavani" required readonly>
                                        
                                        </div>

                                        <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Applied Date</label><br>
                                            <label for="simpleinput" class="form-label"><h4><?=date('Y-m-d');?></h4></label>
                                            <!-- <input type="text" class="form-control" id="carrier_options" name="carrier_options" value="Govt Boys Hostel" required> -->
                                        
                                        </div>
                                    </div>
                                    <hr>

                                        <div class="row">
                                            <div class="row mb-3">
                                                <div class="col-md-4 fm">
                                                    <label for="example-select" class="form-label" >Digital Infrastructure Type</label>
                                                    <select class="form-control" id="feedback_type" onchange="get_val()" name="feedback_type" value="<?= $feedback_type; ?>" required>
                                                        <option>Select Digital Infrastructure Type</option>
                                                        <option value=1>Computer</option>
                                                        <option value=2>Projector</option>
                                                        <option value=3>LED Screen</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4 fm">
                                                <label for="example-select" class="form-label">Quantity</label>
                                                <input class="form-control" type="number" value="" id="no_of_items" min="1" required>
                                                </div>
                                            
                                            <div class="col-md-4 fm">
                                            <label for="simpleinput" class="form-label">Document Upload</label>
                                            <input type="file" class="form-control" id="carrier_opportunity" name="carrier_opportunity" value="<?= $carrier_opportunity; ?>">
                                        
                                        </div>
                                        </div>
                                            
                                        <div class="row">
                                                <div class="col-md-4 fm">
                                                    <label for="example-select" class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" required></textarea>
                                                </div>
                                            </div>

                                            <div class="btns">
                                                <?php echo btn_cancel($btn_cancel); ?>
                                                <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                            </div>
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

    <script>
        function get_val() {

            var feedback_type = document.getElementById('feedback_type').value;
            if (feedback_type == 4) {
                $('#star').show();
            } else {
                $('#star').hide();
            }
        }
    </script>