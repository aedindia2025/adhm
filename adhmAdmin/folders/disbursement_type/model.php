<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Form variables
$btn_text   =   "Save";
$btn_action =   "create";

$unique_id  =    "";
$disbursement_type  =    "";
$description  =    "";
$is_active  =    1;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "disbursement_type";

        $columns = [
            "disbursement_type",
            "description",
            "is_active"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values = $result_values->data;
            $disbursement_type = $result_values[0]["disbursement_type"];
            $description = $result_values[0]["description"];
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

$active_status_options = active_status($is_active); ?>



<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Disbursement Type</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <form class="was-validated" autocomplete="off">


                        <div class="row">

                            <div class="">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="fund_name" class="form-label">Disbursement Type</label>
                                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                                <input type="text" id="disbursement_type" name="disbursement_type" oninput="validateCharInput(this)"  class="form-control" value="<?php echo $disbursement_type; ?>" required>
                                            </div>

                                            <div class="col-md-3 fm">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea id="description" name="description" class="form-control" oninput="validateCharInput(this)"  value="<?php echo $description;?>" required><?php echo $description; ?></textarea>
                                            </div>

                                            <div class="col-md-3 fm">
                                                <label class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control" required>
                                                    <?php echo $active_status_options; ?>
                                                </select>



                                            </div>
                                        </div>
                                        <div class="btns">

                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>

                                        </div>

                    </form>


                </div>
            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col -->


    <script>

        
function disbursement_type_cu(unique_id = "") {
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    var is_form = form_validity_check("was-validated");

    if (is_form) {

        var data 	 = $(".was-validated").serialize();
        data 		+= "&unique_id="+unique_id+"&action=createupdate";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");

        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;

				if (!status) {
					url 	= '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
				} else {
					if (msg=="already") {
						// Button Change Attribute
						url 		= '';

						$(".createupdate_btn").removeAttr("disabled","disabled");
						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
				}

				sweetalert(msg,url);
			},
			error 		: function(data) {
				alert("Network Error");
			}
		});


    } else {
        sweetalert("form_alert");
    }
}

    </script>