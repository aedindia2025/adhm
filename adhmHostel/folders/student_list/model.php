<!-- <?php
        // Form variables
        $btn_text           = "Save";
        $btn_action         = "create";

        $student_id = "";
        $student_name = "";
        $drop_discontinue_date = "";
        $reason = "";

        $unique_id          = "";
        $expenses_type      = "";
        $is_active          = 1;

        if (isset($_GET["unique_id"])) {
            if (!empty($_GET["unique_id"])) {

                $unique_id  = $_GET["unique_id"];
                $where      = [
                    "unique_id" => $unique_id
                ];

                $table      =  "dropout";

                $columns    = [
                    "student_id",
                    "student_name",
                    "drop_discontinue_date",
                    "reason",
                    // "is_active"
                    "unique_id"
                ];

                $table_details   = [
                    $table,
                    $columns
                ];

                $result_values  = $pdo->select($table_details, $where);

                if ($result_values->status) {

                    $result_values      = $result_values->data;

                    // print_r($result_values);

                    $student_id             = $result_values[0]["student_id"];
                    $student_name           = $result_values[0]["student_name"];
                    $drop_discontinue_date  = $result_values[0]["drop_discontinue_date"];
                    $reason                 = $result_values[0]["reason"];
                    $is_active              = $result_values[0]["is_active"];

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
        ?> -->

<!-- Modal with form -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Dropout/Discontinue</h4>
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
                                            
                                            <!-- <div class="col-md-3 fm">
                                            <label for="drop-discont" class="form-label"> Dropout/Discontinued<span class="red">*</span></label>
                                                <select class="form-select" id="drop-discont" name="drop-discont" required>
                                                    <option>Dropout</option>
                                                    <option>Discontinued</option>
                                                </select>
                                            </div> -->
                                            <div class="col-md-3 fm">
                                                <label for="hostel_type" class="form-label">Hostel Type</label>
                                                <select class="form-select" id="student_id" name="student_id">
                                                    <option>School</option>
                                                    <option>ITI</option>
                                                    <option>Polytechnic</option>
                                                    <option>School</option>
                                                    <?php
                                                        echo "<option value='$student_id' >".$student_id."</option>";
                                                    ?>
                                                    <option>SD-1</option>
                                                    <option>SD-2</option>
                                                    <option>SD-3</option>
                                                    <option>SD-4</option>
                                                    <option>SD-5</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="student_name" class="form-label"> Student Name</label>
                                                <input type="text" class="form-control" id="student_name" name="student_name" placeholder="Student Name" value="<?php echo $student_name; ?>">
                                                <!-- <select class="form-select" id="student-name" name="student-name">
                                                    <option>Student Name</option>
                                                </select> -->
                                            </div>
                                            <div class="col-md-3 fm">
                                                <label for="drop_discontinue_date" class="form-label"> Dropout/ Discontinued Date</label>
                                                <input type="date" class="form-control" id="drop_discontinue_date" name="drop_discontinue_date" value="<?php echo $drop_discontinue_date; ?>">

                                            </div>

                                            <!-- <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Academic  Year</label>
                                            <input type="date" class="form-control" id="carrier_options" name="carrier_options" value="<?= $carrier_options; ?>" required>
                                        
                                        </div> -->

                                            <div class="col-md-3 fm">
                                                <label for="reason" class="form-label">Reason</label>
                                                <textarea class="form-control" id="reason" name="reason"><?php echo $reason; ?></textarea>
                                            </div>

                                            <div class="col-md-3 fm" hidden>
                                                <label class="form-label">Status</label>
                                                <select name="is_active" id="is_active" class="select2 form-control" required>
                                                    <?php echo $active_status_options; ?>
                                                </select>
                                            </div>

                                        </div>


                                        <!-- <div class="btns">
                                       <a href="index.php?file=user_type/list"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2">Cancel</button></a>
                                    <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="user_type_cu('')">Save</button>
                                    </div> -->
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