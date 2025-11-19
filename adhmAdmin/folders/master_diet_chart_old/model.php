<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text = "Save";
$btn_action = "create";


$is_active = 1;
$warehouse_options = "";
if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+", $_GET['unique_id']);

        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

        $unique_id = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "master_diet_chart";

        $columns = [

            "screen_unique_id",
            "hostel_type",
            "description",
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


            $screen_unique_id = $result_values[0]["screen_unique_id"];
            $hostel_type = $result_values[0]["hostel_type"];
            $description = $result_values[0]["description"];
            $is_active = $result_values[0]["is_active"];
            $main_unique_id = $result_values[0]["unique_id"];

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

$hostel_type_options = hostel_type_name();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);
$hostel_type_options_popup = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);

$category_name_options = category_name();
$category_name_options = select_option($category_name_options, "Select Category", $category_name);

$item_options = item();
$item_options = select_option($item_options, "Select Items", $item);

if (empty($unique_id)) {
    $screen_unique_id = unique_id();
}

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

                        <h4 class="page-title">Master Diet Chart</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="row">

                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <form class="was-validated" autocomplete="off">
                                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                        <input type="hidden" id="screen_unique_id" name="screen_unique_id" value="<?php echo $screen_unique_id; ?>">
                                        <input type="hidden" id="main_unique_id" name="main_unique_id" value="<?php echo $main_unique_id; ?>">
                                        <div class="row">
                                            <div class="col-md-3 fm mb-3">
                                                <label class="form-label">Hostel Type</label>
                                                <select name="hostel_type" id="hostel_type"
                                                    class="form-control select2" required>
                                                    <?php echo $hostel_type_options; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" id="description" name="description"
                                                    oninput="description_val(this)" style="height: 60px"><?= $description ?></textarea>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 fm mb-3">
                                                <label for="example-select" class="form-label">Item Category</label>
                                                <select name="item_category" id="item_category" class="form-control select2"
                                                    onchange="get_items()" required>
                                                    <?php echo $category_name_options; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label class="form-label">Item</label>
                                                <select name="item" id="item" class="form-control select2"
                                                    required>
                                                    <?php echo $item_options; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="text" id="quantity" name="quantity" class="form-control" required>
                                                <input type="hidden" id="sub_unique_id" name="sub_unique_id">
                                            </div>
                                            <div class="col-md-3 fm mb-3">
                                                <button class="btn btn-info add_update_btn mt-3" type="button" onclick="save_entry()">Save</button>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <br>
                                            <div class="col-12">
                                                <table id="diet_chart_sub_datatable" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.No</th>
                                                            <th>Category</th>
                                                            <th>Item</th>
                                                            <th>Quantity</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
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

<!-- Modal -->
<div class="modal fade" id="popupModal" tabindex="-1" aria-labelledby="popupModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="popupModalLabel">Popup Title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Here you can put your form or content.</p>
        <input type="text" id="popup_input" class="form-control" placeholder="Enter something">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="popupSave">Save</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Bootstrap modal
    var myModal = new bootstrap.Modal(document.getElementById('popupModal'));

    // Open modal when icon is clicked
    $('#openPopup').on('click', function(e) {
        e.preventDefault();
        myModal.show();
    });

    // Optional: handle Save button click
    $('#popupSave').on('click', function() {
        var value = $('#popup_input').val();
        alert("You entered: " + value);
        myModal.hide();
    });
});
</script>
