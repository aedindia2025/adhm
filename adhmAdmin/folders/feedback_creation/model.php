<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" rel="stylesheet" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/> -->


<?php

// Form variables
$btn_text = "Save";
$btn_action = "create";

$unique_id = "";
$from_year = "";
$to_year = "";
$is_active = 1;






if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "feedback_creation";

        $columns = [
            "feedback_name",
            "rating",
            "description",
            "is_active"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result_values);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $feedback_name = $result_values[0]["feedback_name"];
            $rating = $result_values[0]["rating"];
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

$current_date = date('d-m-Y');

$active_status_options = active_status($is_active);

$feedback_type_list = feedback_type();
$feedback_type_list = select_option($feedback_type_list, "select feedback_type",$feedback_name);



?>
<style>
    #full-stars-example {

        /* use display:inline-flex to prevent whitespace issues. alternatively, you can put all the children of .rating-group on a single line */
        .rating-group {
            display: inline-flex;
        }

        /* make hover effect work properly in IE */
        .rating__icon {
            pointer-events: none;
        }

        /* hide radio inputs */
        .rating__input {
            position: absolute !important;
            left: -9999px !important;
        }

        /* set icon padding and size */
        .rating__label {
            cursor: pointer;
            padding: 0 0.1em;
            font-size: 2rem;
        }

        /* set default star color */
        .rating__icon--star {
            color: orange;
        }

        /* set color of none icon when unchecked */
        .rating__icon--none {
            color: #eee;
        }

        /* if none icon is checked, make it red */
        .rating__input--none:checked+.rating__label .rating__icon--none {
            color: red;
        }

        /* if any input is checked, make its following siblings grey */
        .rating__input:checked~.rating__label .rating__icon--star {
            color: #ddd;
        }

        /* make all stars orange on rating group hover */
        .rating-group:hover .rating__label .rating__icon--star {
            color: orange;
        }

        /* make hovered input's following siblings grey on hover */
        .rating__input:hover~.rating__label .rating__icon--star {
            color: #ddd;
        }

        /* make none icon grey on rating group hover */
        .rating-group:hover .rating__input--none:not(:hover)+.rating__label .rating__icon--none {
            color: #eee;
        }

        /* make none icon red on hover */
        .rating__input--none:hover+.rating__label .rating__icon--none {
            color: red;
        }
    }

    #half-stars-example {

        /* use display:inline-flex to prevent whitespace issues. alternatively, you can put all the children of .rating-group on a single line */
        .rating-group {
            display: inline-flex;
        }

        /* make hover effect work properly in IE */
        .rating__icon {
            pointer-events: none;
        }

        /* hide radio inputs */
        .rating__input {
            position: absolute !important;
            left: -9999px !important;
        }

        /* set icon padding and size */
        .rating__label {
            cursor: pointer;
            /* if you change the left/right padding, update the margin-right property of .rating__label--half as well. */
            padding: 0 0.1em;
            font-size: 2rem;
        }

        /* add padding and positioning to half star labels */
        .rating__label--half {
            padding-right: 0;
            margin-right: -0.6em;
            z-index: 2;
        }

        /* set default star color */
        .rating__icon--star {
            color: orange;
        }

        /* set color of none icon when unchecked */
        .rating__icon--none {
            color: #eee;
        }

        /* if none icon is checked, make it red */
        .rating__input--none:checked+.rating__label .rating__icon--none {
            color: red;
        }

        /* if any input is checked, make its following siblings grey */
        .rating__input:checked~.rating__label .rating__icon--star {
            color: #ddd;
        }

        /* make all stars orange on rating group hover */
        .rating-group:hover .rating__label .rating__icon--star,
        .rating-group:hover .rating__label--half .rating__icon--star {
            color: orange;
        }

        /* make hovered input's following siblings grey on hover */
        .rating__input:hover~.rating__label .rating__icon--star,
        .rating__input:hover~.rating__label--half .rating__icon--star {
            color: #ddd;
        }

        /* make none icon grey on rating group hover */
        .rating-group:hover .rating__input--none:not(:hover)+.rating__label .rating__icon--none {
            color: #eee;
        }

        /* make none icon red on hover */
        .rating__input--none:hover+.rating__label .rating__icon--none {
            color: red;
        }
    }

    #full-stars-example-two {

        /* use display:inline-flex to prevent whitespace issues. alternatively, you can put all the children of .rating-group on a single line */
        .rating-group {
            display: inline-flex;
        }

        /* make hover effect work properly in IE */
        .rating__icon {
            pointer-events: none;
        }

        /* hide radio inputs */
        .rating__input {
            position: absolute !important;
            left: -9999px !important;
        }

        /* hide 'none' input from screenreaders */
        .rating__input--none {
            display: none;
        }

        /* set icon padding and size */
        .rating__label {
            cursor: pointer;
            padding: 0 0.1em;
            font-size: 2rem;
        }

        /* set default star color */
        .rating__icon--star {
            color: orange;
        }

        /* if any input is checked, make its following siblings grey */
        .rating__input:checked~.rating__label .rating__icon--star {
            color: #ddd;
        }

        /* make all stars orange on rating group hover */
        .rating-group:hover .rating__label .rating__icon--star {
            color: orange;
        }

        /* make hovered input's following siblings grey on hover */
        .rating__input:hover~.rating__label .rating__icon--star {
            color: #ddd;
        }
    }
</style>



<?php

$district_unique_id = "65584660e85f131401";
$taluk_unique_id      = "65584660e85d24200559";
$hostel_unique_id = "65584660e85as2403310";

$academic_year = $_SESSION["academic_year"];
$district_name = $_SESSION["district_name"];
$taluk_name = $_SESSION["taluk_name"];

?>





<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Feedback Creation</h4>
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
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">STUDENT NAME:</label>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION["std_name"]; ?>
                                                <input type="hidden" class="form-control" name="student_id" id="student_id" value="<?php echo $_SESSION["std_name"]; ?>">
                                            </div>
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">STUDENT ID:</label>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION["std_reg_no"]; ?>
                                                <input type="hidden" class="form-control" name="student_id" id="student_id" value="<?php echo $_SESSION["std_reg_no"]; ?>">
                                            </div>
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">ACADEMIC YEAR:</label>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION["acc_year"]; ?>
                                                <!-- <input type="text" class="form-control" name="amc_year" id="amc_year"> -->
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">DISTRICT NAME:</label>&nbsp;&nbsp;&nbsp; <?php echo $_SESSION["district_name"]; ?>
                                                <input type="text" hidden id="district_id" name="district_id" value="<?php echo $_SESSION["hostel_district"]; ?>">
                                            </div>
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">TALUK NAME:</label>&nbsp;&nbsp;&nbsp;<?php echo $taluk_name; ?>
                                                <input type="text" hidden id="taluk_id" name="taluk_id" value="<?php echo $_SESSION["hostel_taluk"]; ?>">
                                            </div>
                                            <div class="col-md-4 fm">
                                                <label for="product_category" class="form-label">HOSTEL NAME:</label>
                                                &nbsp;&nbsp;&nbsp; <?php echo $_SESSION["hostel_names"]; ?>
                                                <input type="text" hidden id="hostel_id" name="hostel_id" value="<?php echo $_SESSION["hostel_name"]; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2 fm">
                                            <input type="date" hidden readonly id="date" name="date" class="form-control" value="<?php echo $current_date; ?>">
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 fm">
                                                <label for="example-select" class="form-label">Feedback </label>
                                                <select name="feedback_name" id="feedback_name" class="form-control" required>
                                                    <?php echo $feedback_type_list; ?>
                                                </select>
                                            </div>
                                       
                                        
                                            <div class="col-md-3 fm">
                                                <label for="example-select" class="form-label">Rating</label>
                                                <div id="full-stars-example-two">
                                                    <div class="rating-group">
                                                        <input disabled checked class="rating__input rating__input--none" name="rating3" id="rating3-none rating" value="0" type="radio">
                                                        <label aria-label="1 star" class="rating__label" for="rating3-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                        <input class="rating__input" name="rating3" id="rating3-1 rating" value="1" type="radio">
                                                        <label aria-label="2 stars" class="rating__label" for="rating3-2 rating"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                        <input class="rating__input" name="rating3" id="rating3-2 rating" value="2" type="radio">
                                                        <label aria-label="3 stars" class="rating__label" for="rating3-3 rating"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                        <input class="rating__input" name="rating3" id="rating3-3 rating" value="3" type="radio">
                                                        <label aria-label="4 stars" class="rating__label" for="rating3-4 rating"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                        <input class="rating__input" name="rating3" id="rating3-4 rating" value="4" type="radio">
                                                        <label aria-label="5 stars" class="rating__label" for="rating3-5 rating"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                        <input class="rating__input" name="rating3" id="rating3-5 rating" value="5" type="radio">
                                                    </div>
                                                </div>
                                            </div>
                                        
                                       
                                            <div class="col-md-5 fm ">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" id="description" class="select2 form-control" required><?php echo $description; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="btns mt-3">
                                            <?php echo btn_cancel($btn_cancel); ?>
                                            <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div> <!-- end card-body -->
    </div> <!-- end card-->
</div> <!-- end col -->
</div>
<script>
    // Get the current date
    var currentDate = new Date();

    // Format the date as "MM/DD/YYYY"
    var dateString = (currentDate.getMonth() + 1) + "/" + currentDate.getDate() + "/" + currentDate.getFullYear();

    // Set the content of the HTML element with the id "date"
    document.getElementById("date").innerHTML = dateString;
</script>