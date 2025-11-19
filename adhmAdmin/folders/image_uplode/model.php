<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .imgUp {
            margin-top: 20px;
        }

        .imagePreview {
            width: 100px;
            height: 100px;
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        .imagePreview img {
            max-width: 100%;
            max-height: 100%;
        }
    </style>
</head>

<?php
// Form variables
$btn_text = "Save";
$btn_action = "create";

$unique_id = "";
$fund_name = "";
$is_active = 1;



if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "content_management";

        $columns    = [
            "ambedkar_quotes",
            "thirukkural",
            "cm_image",
            "cm_image_org_name",
            "ambedkar_image",
            "ambedkar_image_original",
            "is_active",
            "unique_id",
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values      = $result_values->data;

            $ambedkar_quotes        = $result_values[0]["ambedkar_quotes"];
            $thirukkural        = $result_values[0]["thirukkural"];
            $cm_image        = $result_values[0]["test_cm_image"];
            $cm_image        = $result_values[0]["cm_image"];
            $cm_image_org_name        = $result_values[0]["cm_image_org_name"];
            $ambedkar_image        = $result_values[0]["ambedkar_image"];
            $ambedkar_image_org_name        = $result_values[0]["ambedkar_image_original"];

            $unique_id        = $result_values[0]["unique_id"];

            $is_active          = $result_values[0]["is_active"];



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

?>


<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Image Upload</h4>
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
                                        <div class="row">
                                            <div class="col-md-6 fm">
                                                <label for="description" class="form-label">Ambedkar Quotes</label>
                                                <textarea id="ambedkar_quotes" name="ambedkar_quotes" class="form-control" oninput="validateCharInput(this)"  value="<?php echo $ambedkar_quotes; ?>"><?php echo $ambedkar_quotes; ?></textarea>
                                            </div>
                                          

                                                        <input type="hidden" class="" id="unique_id" name="unique_id" value="<?php echo $unique_id; ?>">
                                                   
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 fm">
                                                <label for="description" class="form-label">Thirukkural</label>
                                                <textarea id="thirukkural"  oninput="thirukural(this)"  name="thirukkural" class="form-control" value="<?php echo $thirukkural; ?>"><?php echo $thirukkural; ?></textarea>
                                            </div>
                                           
                                        </div>

                                        <div class="col-md-3 fm">
                                            <label for="is_active" class="form-label">Status</label>
                                            <select name="is_active" id="is_active" class="select2 form-control" required>
                                                <?php echo $active_status_options; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="btns">
                                    <?php echo btn_cancel($btn_cancel); ?>
                                    <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div> <!-- end card-body -->
    </div> <!-- end card-->
</div> <!-- end col -->
</div> <!-- end container-fluid -->

<script>
    test_file1.onchange = evt => {
        const [file] = test_file1.files;
        if (file) {
            cm_image_preview.src = URL.createObjectURL(file);
        } else {
            cm_image_preview.src = 'uploads/download.png';
        }
    };

    test_file.onchange = evt => {
        const [file] = test_file.files;
        if (file) {
            ambedkar_image_preview.src = URL.createObjectURL(file);
        } else {
            ambedkar_image_preview.src = 'uploads/download.png';
        }
    };

    function image_uplode_cu() {

        // alert();
        var data = new FormData();
        var ambedkar_quotes = $("#ambedkar_quotes").val();
        var thirukkural = $("#thirukkural").val();
        var is_active = $("#is_active").val();
        var unique_id = $("#unique_id").val();

       

        data.append("ambedkar_quotes", ambedkar_quotes);
        data.append("thirukkural", thirukkural);
        data.append("is_active", is_active);
        data.append("unique_id", unique_id);
        data.append("action", "createupdate");

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            // beforeSend 	: function() {
            // 	$("#Save_and_Continue_button").attr("disabled","disabled");
            // 	$("#Save_and_Continue_button").text("Loading...");
            // },
            success: function(data) {
                // alert(data);
                var obj = JSON.parse(data);
                var msg = obj.msg;
                // var std_app_no = obj.std_app_no;
                var status = obj.status;
                var error = obj.error;

                if (!status) {
                    url = '';
                    $("#Save_and_Continue_button").text("Error");
                    console.log(error);
                } else {
                    if (msg == "already") {
                        // Button Change Attribute
                        url = '';

                        $("#Save_and_Continue_button").removeAttr("disabled", "disabled");
                        if (unique_id) {
                            $("#Save_and_Continue_button").text("Update");
                        } else {
                            $("#Save_and_Continue_button").text("Save");
                        }


                        sweetalert(msg);
                    } else {
                        sweetalert("create");
                        $("#Save_and_Continue_button").text("Save");



                    }
                    sweetalert(msg, url);

                }



            },
            error: function(data) {
                alert("Network Error");
            }
        });


    }
</script>