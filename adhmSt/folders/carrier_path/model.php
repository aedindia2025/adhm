 <?php
// Form variables
$btn_text           = "Save";
$btn_action         = "create";

$student_name ="";
$student_id = "";
$student_qualification ="";
$employment_course = "";
$job        ="";
$course     = "";


$expenses_type      = "";
$is_active          = 1;

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $uni_dec = str_replace(" ", "+",$_GET['unique_id']);
        $get_uni_id           = openssl_decrypt(base64_decode($uni_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);

        $unique_id  = $get_uni_id; 
        $where      = [
            "unique_id" => $unique_id
        ];

        $table      =  "carrier_path_creation";

        $columns    = [
            "student_name",
            "student_id",
            "employment_course",
            "job",
            "course",
            "qualification",
            // "is_active",
            "unique_id",
            "is_active"
        ];

        $table_details   = [
            $table,
            $columns
        ];

        $result_values  = $pdo->select($table_details, $where);

        if ($result_values->status) {

            $result_values            =   $result_values->data;

            $student_name             =  $result_values[0]["student_name"];

            $student_id               =   $result_values[0]["student_id"];
            $student_qualification    =   $result_values[0]["qualification"];
            $employment_course        =   $result_values[0]["employment_course"];
            $job                      =   $result_values[0]["job"];
            $course                   =    $result_values[0]["course"];
            $is_active                =    $result_values[0]["is_active"];



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

$student_id_options = student_id("",$_SESSION['hostel_id']);
$student_id_options = select_option($student_id_options,'Select Student ID',$student_id);
// $student_names_list = select_option($student_names_list,"select Student");



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

                        <h4 class="page-title">Career Path</h4>
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
                                        <div class="col-md-3 fm">

                                        <input type="hidden" name="district_name" id="district_name" value=<?php echo  $_SESSION["district_id"];?>>
                                        <input type="hidden" name="taluk_name" id="taluk_name" value=<?php echo  $_SESSION["taluk_id"];?>>
                                        <input type="hidden" name="hostel_name" id="hostel_name" value=<?php echo  $_SESSION["hostel_id"];?>>
                                        <!-- <input type="hidden" name="hostel_name" id="hostel_name" value=<?php echo  $_SESSION["staff_id"];?>> -->
                                        




                                        <label for="simpleinput" class="form-label">Student ID</label>
                                            <!-- <input type="text" class="form-control" id="student_name" name="student_name" value="<?= $student_name;?>" required> -->
                                            <select class="form-control" id="std_reg_no" name="std_reg_no" value="<?=$student_id;?>" onchange="get_std_name()" required>
                                            <?php echo $student_id_options;?>
                                            </select>
                                        </div>
                                        <div class="col-md-3 fm">
                                        <label for="simpleinput" class="form-label">Student ID</label>
                                            <!-- <input type="text" class="form-control" id="student_id" name="student_id" value="<?= $student_id;?>" required> -->
                                            <input type="text" class="form-control" id="student_name" name="student_name" value="<?=$student_name;?>" required readonly>
                                             
                                        </div>
                                        <div class="col-md-3 fm">
                                        <label for="simpleinput" class="form-label">Student Qualification</label>
                                            <select class="form-control" id="student_class" name="student_class"  required>
                                            <option value=''>Select</option>
                                            <option value="10" <?php if($student_qualification == '10'){ echo 'selected';}?>>10</option>
                                        <option value="12"<?php if($student_qualification == '12'){ echo 'selected';}?>>12</option>
                                        <option value="college"<?php if($student_qualification == 'college'){ echo 'selected';}?>>College</option>
                                        </select>
                                       
                                        </div>

                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Employment/Course</label>
                                            <select id="employment_course" name="employment_course"  onchange="get_job_div()" value="<?php echo $employment_course;?>" class="form-control">
                                            <option value=''>Select</option>
                                            <option value="employment"  <?php if($employment_course == 'employment'){ echo 'selected';}?>>Employment</option>
                                        <option value="course"  <?php if($employment_course == 'course'){ echo 'selected';}?>>Course</option>
                                            </select>
                                        
                                           
                                        </div>
                                        <!-- <div class="row"> -->

                            <div id="textBoxContainer"  class="col-md-3 mt-2 fm"style="display:none;">
                            <label for="textBox" class="form-label">Organization name</label>
                            <input type="text" class="form-control" name="job" id="job" value="<?php echo $job;?>">
                             </div>
                             
                             <div id="course_div"  class="col-md-3 mt-2 fm"style="display:none;">
                            <label for="textBox" class="form-label">Course</label>
                            <input type="text" class="form-control" name="course" id="course"  value="<?php echo $course;?>">
                             </div>
<!-- </div> -->
                               

</div>
<div>
                                        

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

<script>
 

    </script>
