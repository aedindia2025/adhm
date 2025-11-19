<?php

// Include DB file and Common Functions
include '../../config/dbconfig.php';

$unique_id      =    "";


if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        $where = [
            "unique_id" => $unique_id
        ];

        $table = "staff_leave_application";

        $columns = [
            "staff_id",
            "staff_name",
            "no_of_days",
            "reason",
            "unique_id",
            "from_date ",
            "to_date",
            "(select staff_name from staff_registration where staff_registration.unique_id = $table.warden_name) as dadwo_name",
            "approval_status",
            "reject_reason"
        ];

        $table_details = [
            $table,
            $columns
        ];

        $result_values = $pdo->select($table_details, $where);
        // print_r($result);

        if ($result_values->status) {

            $result_values      = $result_values->data;
           

            $staff_name       = $result_values[0]["staff_name"];
            $staff_id         = $result_values[0]["staff_id"];
            $no_of_days         = $result_values[0]["no_of_days"];
            $from_date          = $result_values[0]["from_date"];
            $to_date            = $result_values[0]["to_date"];
            $reason             = $result_values[0]["reason"];
            $dadwo_name    = $result_values[0]["dadwo_name"];
            $approval_status    = $result_values[0]["approval_status"];
            $reject_reason      = $result_values[0]["reject_reason"];
            $is_active          = $result_values[0]["is_active"];
           
            if ($approval_status == '1') {
                $approval_status  = 'pending';
            }
            if ($approval_status == '2') {
                $approval_status  = 'Approve';
            }
            if ($approval_status == '3') {
                $approval_status  = 'Rejected';
            }


            $btn_text           = "Update";
            $btn_action         = "update";
        } else {
            $btn_text           = "Error";
            $btn_action         = "error";
            $is_btn_disable     = "disabled='disabled'";
        }
    }
}

?>
<style>
    .forms-info-hostel label {
        color: #6c757dbf;
        font-weight: 400;
        font-size: 14px;
    }

    .forms-info-hostel p {
        color: #454747;
        font-size: 15px;
        border: 0px dotted #ccc;
        padding: 7px 7px;
        border-radius: 4px;
        margin: 5px 0px 14px 0px;
        font-weight: 400;
    }

    .forms-info-hostel select {
        color: #454747;
        font-size: 15px;
        border: 1px dotted #ccc;
        padding: 7px 7px;
        border-radius: 4px;
        width: 100%;
        margin: 5px 0px 14px 0px;
        height: 40px;
    }

    .forms-info-hostel textarea {
        color: #454747;
        font-size: 15px;
        border: 1px dotted #ccc;
        padding: 7px 7px;
        border-radius: 4px;
        width: 100%;
        margin: 5px 0px 14px 0px;
    }


    .container {
        margin: 0px 30px;
    }


    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
    }

    table tr td {
        width: 33.33%;
        padding: 0px 10px;
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

                        <h4 class="page-title" style="text-align: center;font-size: 21px;text-transform: uppercase;border-bottom: 1px dashed #ccc;padding-bottom: 14px;">Student Leave Details</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <form class="was-validated" autocomplete="off">


                <div class=" container">
                    <div class=" forms-info-hostel">
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Staff ID : </label>
                                        <p><?= $staff_id; ?></p>
                                    </div>
                                </td>

                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Staff Name : <p><?= $staff_name; ?></p></label>

                                    </div>
                                </td>

                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Dadwo Name: <p><?= $dadwo_name; ?></p></label>

                                    </div>
                                </td>
                                <td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">From Date</label>
                                        <p><?= $from_date; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">To Date</label>
                                        <p><?= $to_date; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">No Of Day</label>
                                        <p><?= $no_of_days; ?></p>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td>

                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Reason</label>
                                        <p><?= $reason; ?></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-3">
                                        <label for="simpleinput" class="form-label">Status:</label>
                                        <p><?= $approval_status; ?></p>
                                      
                                    </div>
                                </td>
                               
                            </tr>
                        </table>
                    </div>
                </div>
                

            </form>

        </div>
    </div>
</div>