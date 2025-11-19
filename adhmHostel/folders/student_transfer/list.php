<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

$ses_district_office = $_SESSION["district_id"];
$ses_district_name = $_SESSION["district_name"];
$ses_taluk_name = $_SESSION['taluk_name'];
$ses_taluk_id = $_SESSION['taluk_id'];
$ses_designation = $_SESSION["designation"];
$ses_designation_name = $_SESSION["designation_name"];

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District");

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk");

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel");

$desination_type_list = designation();
$desination_type_list = select_option($desination_type_list, "select Designation");

$academic_year = academic_year();
$academic_year = select_option_acc($academic_year, "Select Academic Year");

$academic_year_options = all_academic_year();
$academic_year_options = select_option($academic_year_options, "Select Academic Year");

?>
<style>
    table#transfer_datatable,
    table#approval_datatable {
        width: 100%;
        table-layout: auto;
        white-space: nowrap;
    }


    .approval-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }


    .load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }

    .nav-justified .nav-item,
    .nav-justified>.nav-link {
        -ms-flex-preferred-size: 0;
        flex-basis: 15%;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 0;
        text-align: center;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: var(--ct-nav-pills-link-active-color);
        background-color: #00aff0;
    }

    .nav-link:focus,
    .nav-link:hover {
        color: #000000;
    }

    .page-title-box .page-title {

        line-height: 54px !important;
    }

    div#basicwizard li a {
        padding: 6px 10px !important;
    }
</style>
<?php

$reject_reason_list = transfer_reject_reason();
$reject_reason_options = select_option($reject_reason_list, 'Select Reason', $reject_reason);
?>



<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">Student Transfer List</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row my-2">
                                <div class="col-md-3 col-sm-12 ">
                                    <label class="form-label">Academic Year</label>
                                    <select name="acc_year" id="acc_year" class="select2 form-control">
                                        <?php echo $academic_year_options; ?>

                                        <input type="hidden" id="csrf_token" name="csrf_token"
                                            value="<?php echo $_SESSION['csrf_token']; ?>">

                                    </select>
                                </div>

                                <div class="col-md-2 align-self-center mt-2">
                                    <button type="button" class="btn btn-primary" onclick="go_filter()">GO</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div>
                                <div id="basicwizard">
                                    <ul class="nav nav-pills nav-justified form-wizard-header mb-4">
                                        <li class="nav-item">
                                            <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2 active">

                                                <span class=" d-sm-inline">Transfer List</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2">

                                                <span class=" d-sm-inline">Approval</span>
                                            </a>
                                        </li>

                                    </ul>

                                    <div class="tab-content b-0 mb-0">
                                        <div class="tab-pane active" id="basictab1">
                                            <div class="row">
                                                <div class="col-md-12 load" id="loader_1">
                                                    <i class="mdi mdi-loading mdi-spin"></i>
                                                </div>
                                            </div>
                                            <div class="approval-table-wrapper">
                                                <table id="transfer_datatable" class="table w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.NO</th>
                                                            <th>Acc Year</th>
                                                            <th>Registration No</th>
                                                            <th>Student Name</th>
                                                            <th>To District</th>
                                                            <th>To Taluk</th>
                                                            <th>To Hostel</th>
                                                            <th>Status</th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>


                                        <div class="tab-pane" id="basictab2">


                                            <div class="row">
                                                <div class="col-md-12 load" id="loader_2">
                                                    <i class="mdi mdi-loading mdi-spin"></i>
                                                </div>
                                            </div>
                                            <div class="approval-table-wrapper">
                                                <table id="approval_datatable" class="table w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>
                                                            <th>Acc Year</th>
                                                            <th>Registration No</th>
                                                            <th>Student Name</th>
                                                            <th>Hostel ID</th>
                                                            <th>From District</th>
                                                            <th>From Taluk</th>
                                                            <th>From Hostel</th>
                                                            <th>Action </th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="student_transfer.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

<script>

function showLoader() {
	$("#loader_2").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
	$("#loader_2").css("display", "none");
}



    $(document).ready(function () {
        $(document).on('click', '.accept-btn', function () {

            showLoader();

             $(this).prop('disabled', true);


            var from_hostel = $(this).data('hostel-id');
            var uniqueId = $(this).data('unique-id');
            var district = $(this).data('district');
            var taluk = $(this).data('taluk');
            var insert_type = $(this).data('insert_type');
            var to_taluk = $(this).data('to_taluk');
            var to_district = $(this).data('to_district');

            var student_reg_no = $(this).data('std-reg-no');

            var std_id = $(this).data('std-id');

            var to_hostel = $(this).data('to-hostel');

            var acceptButton = $(this); // Store reference to accept button

            var ajax_url = sessionStorage.getItem("folder_crud_link");


            var data = {
                "from_hostel": from_hostel,
                "to_hostel": to_hostel,
                "uniqueId": uniqueId,
                "district": district,
                "student_reg_no": student_reg_no,
                "taluk": taluk,
                "insert_type": insert_type,
                "std_id": std_id,
                "action": "at_accept"
            }

            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                

                    var obj = JSON.parse(data);
                    var msg = obj.msg;

                    if (msg == "success") {

                        if (insert_type == 1) {
                          

                            var data2 = {
                                "to_hostel": to_hostel,
                                "student_reg_no": student_reg_no,
                                "insert_type": insert_type,
                                "to_taluk": to_taluk,
                                "to_district": to_district,
                                "std_id": std_id,
                                "action": "new_app"
                            }
                            $.ajax({
                                type: "POST",
                                url: ajax_url,
                                data: data2,
                                success: function (data) {
                                        hideLoader();


                                    if (msg == 'success') {
                                        log_sweetalert_approval("saved");
                                         approval_datatable('approval_datatable', '', 'approval_datatable');
                                    }

                                }
                            });
                        } else {
                                        hideLoader();
                            log_sweetalert_approval("saved");
                             approval_datatable('approval_datatable', '', 'approval_datatable');
                        }



                    }

                    // $(this).prop('disabled', false);
                    // hideLoader();
                }
                //  $(this).prop('disabled', false);
                // hideLoader();

            });

        });
    });


    $(document).on('click', '.reject-btn', function () {

        var uniqueId = $(this).data('unique-id');
        var reasonTextBox =
            `<br><br>
            <select class="reason-selectbox form-select">
                <?php echo $reject_reason_options; ?>
            </select>`;
        $(this).parent().append(reasonTextBox);

        var rejectButton = $(this);

        rejectButton.replaceWith('<button class="confirm-reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"  data-unique-id="' + uniqueId + '">Confirm Reject</button>');
    });

    // Event listener for confirm reject button
    $(document).on('click', '.confirm-reject-btn', function () {

        // alert('reject');
        var uniqueId = $(this).data('unique-id');
        var reason = $(this).siblings('.reason-selectbox').val();
        var status = 2;
        var rejectButton = $(this); // Store reference to confirm reject button

        var ajax_url = sessionStorage.getItem("folder_crud_link");

        var data = {
            "uniqueId": uniqueId,
            "reason": reason,
            "action": "at_reject"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
                if (data) {
                    rejectButton.hide(); // Hide confirm reject button
                    rejectButton.closest('td').html('Rejected'); // Show status as "Rejected"
                    log_sweetalert_approval("rejected", "");
                }
            }
        });

    });




    function log_sweetalert_approval(msg = '', url = '') {
        switch (msg) {
            case "saved":
                Swal.fire({
                    icon: 'success',
                    title: 'Approved Successfully',
                    showConfirmButton: true,
                    timer: 2000

                });
                break;

            case "rejected":
                Swal.fire({
                    icon: 'warning',
                    title: 'Rejected !!',
                    showConfirmButton: true,
                    timer: 2000,
                    willClose: () => {
                        window.location.reload();
                    }

                });
                break;

            case "sanc_cnt_exceed":
                Swal.fire({
                    icon: 'warning',
                    title: 'Hostel Vacancy Count Exceeded',
                    showConfirmButton: true,
                    timer: 2000,

                });
                break;
        }
    }
</script>