<?php 

// Form variables
$btn_text           = "Save";
$btn_action         = "create";


$is_active          = 1;
$warehouse_options  = "";
if(isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;

        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "product_type";

        $columns    = [
            
            "product_category",
            "product_type",
            "unit_category",
            "description",
            "is_active",
            "unique_id"
        ];

        $table_details   = [
            $table,
            $columns
        ]; 

        $result_values  = $pdo->select($table_details,$where);

        if ($result_values->status) {

            $result_values     = $result_values->data;

        
            $product_category        = $result_values[0]["product_category"];
            $product_type        = $result_values[0]["product_type"];
            $unit_category        = $result_values[0]["unit_category"];
            $description        = $result_values[0]["description"];
            $is_active        = $result_values[0]["is_active"];

            
            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

$active_status_options   = active_status($is_active);


// $staff_options      = staff_name();
// $staff_options      = select_option($staff_options,"Select Staff",$staff_name); 

$product_category_option      = product_category('');
$product_category_option      = select_option($product_category_option,"Select product category",$product_category); 

$uni_category_option      = uni_category('');
$uni_category_option      = select_option($uni_category_option,"Select Unit category",$unit_category); 



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

                        <h4 class="page-title">Product Type</h4>
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
                                    <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <div class="row mb-3">
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Product Category</label>
                                               
                                                <select name="product_category" id="product_category"
                                                    class="select2 form-control" required>
                                                    <?php echo $product_category_option;?>

                                                </select>
                                               
                                            </div>
                             </div>
                                            <div class="col-md-3 fm">
                                                <label for="simpleinput" class="form-label">Product Type</label>
                                                <input type="text" class="form-control" id="product_type" oninput="validateCharInput(this)"  name="product_type"
                                                    value="<?=$product_type;?>" required>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Unit Category</label>
                                               
                                                <select name="unit_category" id="unit_category"
                                                    class="select2 form-control" required>
                                                    <?php echo $uni_category_option;?>

                                                </select>
                                               
                                            </div>
                                            <div class="col-md-5 fm">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea id="description" name="description" class="form-control" oninput="validateCharInput(this)"  required><?=$description;?></textarea>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control"
                                                    required>
                                                    <?php echo $active_status_options;?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="btns">
                                            <?php echo btn_cancel($btn_cancel);?>
                                            <?php echo btn_createupdate($folder_name_org,$unique_id,$btn_text);?>
                                        </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    
function product_type_cu(unique_id = "") { 
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