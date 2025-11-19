<?php

// Form variables
$btn_text = "Save";
$btn_action = "create";

// $unique_id          = "";
$feedback_type = "";
$is_active = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec    = str_replace(" ", "+",$_GET['unique_id']);
        
        $get_uni_id = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id;
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "feedback_type";

        $columns = [
            "feedback_type",
            "is_active"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values = $result_values->data;

            $feedback_type = $result_values[0]["feedback_type"];
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



<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">FeedBack</h4>
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

                                        <div class="row">

                                            <div class="col-3">
                                                <label for="example-select" class="form-label"> User Name: </label>&nbsp; <?php echo $_SESSION["staff_name"]; ?>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="row mb-3">
                                                <div class="col-md-4 fm">
                                                    <label for="simpleinput" class="form-label">Feedback Type</label>
                                                    <!-- <select class="form-control" id="feedback_type" onchange="get_val()" name="feedback_type" value="<?= $feedback_type; ?>" required>
                                                        <option>Select Feedback Type</option>
                                                        <option value=1>Hostel</option>
                                                        <option value=2>Food</option>
                                                        <option value=3>Warden</option>
                                                        <option value="4">others</option>
                                                    </select> -->
                                                    <input type="text" id="feedback_type"  name="feedback_type" class="form-control" value="<?= $feedback_type; ?>">
                                                </div>
                                                <!-- <div class="col-4" style="display:none" id="star">
                                                    <label for="example-select" class="form-label">Comment</label>
                                                    <input type="text" class="form-control">
                                                </div>
                                                <div class="col-md-4 fm">
                                                    <label for="example-select" class="form-label">Rating</label>
                                                    <div id="full-stars-example-two">
                                                        <div class="rating-group">
                                                            <input disabled checked class="rating__input rating__input--none" name="rating3" id="rating3-none" value="0" type="radio">
                                                            <label aria-label="1 star" class="rating__label" for="rating3-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                            <input class="rating__input" name="rating3" id="rating3-1" value="1" type="radio">
                                                            <label aria-label="2 stars" class="rating__label" for="rating3-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                            <input class="rating__input" name="rating3" id="rating3-2" value="2" type="radio">
                                                            <label aria-label="3 stars" class="rating__label" for="rating3-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                            <input class="rating__input" name="rating3" id="rating3-3" value="3" type="radio">
                                                            <label aria-label="4 stars" class="rating__label" for="rating3-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                            <input class="rating__input" name="rating3" id="rating3-4" value="4" type="radio">
                                                            <label aria-label="5 stars" class="rating__label" for="rating3-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                                            <input class="rating__input" name="rating3" id="rating3-5" value="5" type="radio">
                                                        </div>

                                                    </div>
                                                </div> -->
                                                <!-- <div class="col-md-6 fm">
                                                    <label for="example-select" class="form-label">Description</label>
                                                    <textarea name="description" class="form-control" required></textarea>
                                                </div>
                                            </div> -->

                                            <div class="col-md-3">
                                    <div class="mb-3">
                                    <label  for="is_active"> Status </label>
                                        <select name="active_status" id="active_status" class="select2 form-control" required>
                                        <?php echo $active_status_options;?>
                                        </select>
                                    </div>
                                </div>
                                            <div class="btns">
                                                <?php echo btn_cancel($btn_cancel); ?>
                                                <?php echo btn_createupdate($folder_name_org, $unique_id, $btn_text); ?>
                                            </div>
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