<?php
include '../../config/common_fun.php';

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", "");

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", "");

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", "");

$renewal_reject_reason_options = renewal_reject_reason();
$renewal_reject_reason_options = select_option($renewal_reject_reason_options, "Select Reason");

$renewal_accept_reason_options = renewal_accept_reason();
$renewal_accept_reason_options = select_option($renewal_accept_reason_options, "Select Reason");


?>
<style>
    .accept-reason-selectbox,
    .reject-reason-selectbox {
        width: 100%;
    }

    .modal-header {
        background-color: #00aff0;
        color: #fff;
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

    .modal-content {
        width: 50%;
        margin-left: unset !important;
    }

    .modal-header h5 {
        font-size: 17px;
    }

    .modal-header.border-0 {
        background: #2993bc;
        color: #ffffff;
    }

    .modal-body {
        padding: 24px;
    }

    .select2-container {
        z-index: 9999 !important;
    }

    table#manual_datatable {
        width: 100%;
        display: block;
        overflow: scroll;
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
                        <div class="page-title-right">
                        </div>
                        <h4 class="page-title">Student Renewal</h4>
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

                                                <span class=" d-sm-inline">Exception</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab"
                                                class="nav-link rounded-0 pt-2 pb-2" disabled>

                                                <span class=" d-sm-inline"></span>
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

                                            <table id="manual_datatable" class="table nowrap w-100">
                                                <thead>
                                                    <tr>

                                                        <th>S No</th>
                                                        <th>Registration No</th>
                                                        <th>Name As Aadhaar</th>
                                                        <th>Name As EMIS/UMIS</th>
                                                        <th>Name Difference</th>
                                                        <th>Distance From Home to School/College</th>
                                                        <th>Distance From Home to Hostel</th>
                                                        <th>Father Name</th>
                                                        <th>Studying Class/Branch</th>
                                                        <th>Hostel ID</th>
                                                        <th>Hostel Name</th>
                                                        <th>Action </th>
                                                    </tr>
                                                </thead>
                                            </table>


                                        </div>

                                        <div class="tab-pane" id="basictab2">

                                            <div>
                                                <div class="col-md-12 load" id="loader_2">
                                                    <i class="mdi mdi-loading mdi-spin"></i>
                                                </div>
                                            </div>

                                            <table id="" class="table  w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Selection</th>
                                                        <th>S.no</th>
                                                        <th>Registration No</th>
                                                        <th>Student Name</th>
                                                        <th>Action </th>
                                                    </tr>
                                                </thead>
                                            </table>
                                            <div class="col-md-12" align="right">
                                                <!-- <button type="button" id="" class="btn btn-primary waves-effect waves-light" onclick="manualRenewal()">Submit</button> -->
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

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 text-center" id="transferModalLabel">Transfer Student</h5>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <input type="hidden" class="form-control" id="fromDistrict" name="fromDistrict"
                            value="<?= $_SESSION['district_id']; ?>">
                        <input type="hidden" class="form-control" id="fromTaluk" name="fromTaluk"
                            value="<?= $_SESSION['taluk_id']; ?>">
                        <input type="hidden" class="form-control" id="fromHostel" name="fromHostel"
                            value="<?= $_SESSION['hostel_id']; ?>">
                        <input type="hidden" class="form-control" id="s1UniqueId" name="s1UniqueId">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="regNo">Registration No</label>
                                <input type="text" class="form-control" id="regNo" name="regNo" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="studentName">Student Name</label>
                                <input type="text" class="form-control" id="studentName" name="studentName" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="district_id">Transfer District</label>
                                <select class="select2 form-control" id="district_id" name="district_id"
                                    onchange="get_taluk_id()" required>
                                    <?php echo $district_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="taluk_id">Transfer Taluk</label>
                                <select class="select2 form-control" id="taluk_id" name="taluk_id"
                                    onchange="get_hostel_id()" required>
                                    <?php echo $taluk_name_list; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="hostel_id">Transfer Hostel</label>
                                <select class="select2 form-control" id="hostelId" name="hostelId" required>
                                    <?php echo $hostel_name_list; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="transferBtn" class="btn btn-primary" onclick="transferStd()">Transfer</button>
            </div>
        </div>
    </div>
</div>

<!-- UMIS Modal -->
<div class="modal fade" id="umisModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-l" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title w-100 text-center">UMIS</h5>
            </div>
            <div class="row">
                <div class="col-md-12 load" id="loader">
                    <i class="mdi mdi-loading mdi-spin"></i>
                </div>
            </div>
            <div class="modal-body">
                <form class="was-validated" autocomplete="off">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" for="umis_number">UMIS NO</label>
                                <input type="text" class="form-control" id="umis_number" name="umis_number" required
                                    minlength="12" maxlength="12" oninput="number_only(this)">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center border-0">
                <button type="button" id="umisInsertBtn" class="btn btn-primary" onclick="submitUMIS()">Submit</button>
            </div>
        </div>
    </div>
</div>
<template id="accept-reason-template">
    <select name="accept_reason[]" class="accept-reason-selectbox select2 form-control mt-2" multiple>
        <?php echo $renewal_accept_reason_options; ?>
    </select>
</template>

<template id="reject-reason-template">
    <select name="reject_reason[]" class="reject-reason-selectbox select2 form-control mt-2" multiple>
        <?php echo $renewal_reject_reason_options; ?>
    </select>
</template>
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="assets/libs/select2/js/select2.min.js<?php
                                                    echo $js_css_file_comment;
                                                    ?>"></script>
<script src="assets/libs/jquery_multiselect/jquery.multiselect.js<?php echo $js_css_file_comment; ?>"></script>


<script>
    $(document).ready(function() {
        // alert();
        $(document).on('click', '.acceptbtn', function() {
            const std_name = $(this).data("std_name");
            const hostel_name = $(this).data("hostel_name");
            const s1_unique_id = $(this).data("s1_unique_id");
            const name_diff = $(this).data("name_diff");
            const inst_distance_check = $(this).data("inst_distance_check");



            if (name_diff == 'mismatched' || name_diff == 'partially_matched' || inst_distance_check <= 5) {

                const $cell = $(this).closest('td');
                const selectHTML = $('#accept-reason-template').html();

                $cell.html(`
           <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
        <div style="display: flex; gap: 10px;">
                <button class="confirm-accept-btn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
                        data-s1_unique_id="${s1_unique_id}"
                        data-hostel_name="${hostel_name}"
                        data-std_name="${std_name}">
                    Confirm Accept
                </button>

                <button class="rejectbtn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
                        data-s1_unique_id="${s1_unique_id}"
                        data-hostel_name="${hostel_name}"
                        data-std_name="${std_name}"
                        data-name_diff="${name_diff}"
                        data-inst_distance_check="${inst_distance_check}">
                    Reject
                </button>
            </div>
            <div style="width: 100%;">
            ${selectHTML}
        </div>
    </div>                          
        `);

                /* ---------- activate Select2 on the new box ----- */
                $cell.find('.accept-reason-selectbox').select2({
                    placeholder: 'Select reason',
                    width: '100%',
                    dropdownParent: $cell // keeps dropdown inside this <td>
                });
            } else {
                var title = "I have Physically verified that student " + std_name + " is currently staying in the hostel named " + hostel_name + ".";
               Swal.fire({
                    title: 'Confirm Verification',
                    text: title,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Accept',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = {
                            action: "updateReason",
                            s1_unique_id: s1_unique_id,
                            reason: reason,
                        };

                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: data,
                            success: function(response) {
                                var obj = JSON.parse(response);
                                var status = obj.status;

                                if (status === true) {
                                    acceptButton.hide(); // Hide confirm button
                                    acceptButton.closest('td').html('Accepted');
                                    Swal.fire('Verified!', 'The student has been verified.', 'success');
                                } else {
                                    Swal.fire('Error!', 'Something went wrong while updating.', 'error');
                                }
                            },
                            error: function() {
                                console.log("Error with: " + s1_unique_id);
                                document.getElementById('autoRenewal').disabled = false;
                                Swal.fire('Error!', 'AJAX request failed.', 'error');
                            },
                        });
                    }
                });
            }


        });

        // $(document).on('click', '.confirm-accept-btn', function () {

        //     const std_name = $(this).data("std_name");
        //     const hostel_name = $(this).data("hostel_name");
        //     const s1_unique_id = $(this).data("s1_unique_id");

        //     var reason = $(this).closest('td').find('.accept-reason-selectbox').val();



        //     const ajax_url = sessionStorage.getItem("folder_crud_link");
        //     var acceptButton = $(this);


        //     var title = "I have verified that student " + std_name + " is currently staying in the hostel named " + hostel_name + ".";
        //     if (reason) {
        //         confirm('std_confirm', title)
        //             .then((result) => {
        //                 if (result.isConfirmed) {

        //                     const data = {
        //                         action: "updateReason",
        //                         s1_unique_id: s1_unique_id,
        //                         reason: reason,
        //                     };

        //                     $.ajax({
        //                         type: "POST",
        //                         url: ajax_url,
        //                         data: data,
        //                         success: function (response) {

        //                             var obj = JSON.parse(response);
        //                             var status = obj.status;

        //                             if (status === true) {
        //                                 acceptButton.hide(); // Hide confirm reject button
        //                                 acceptButton.closest('td').html('Accepted');
        //                                 sweetalert("success_verify");
        //                             }
        //                         },
        //                         error: function () {
        //                             console.log("Error with: " + s1_unique_id);
        //                             document.getElementById('autoRenewal').disabled = false;
        //                             // hideLoader_1();
        //                         },
        //                     });



        //                 }
        //             });
        //     } else {
        //         sweetalert("form_alert");
        //     }
        // });


        $(document).on('click', '.confirm-accept-btn', function() {

            const std_name = $(this).data("std_name");
            const hostel_name = $(this).data("hostel_name");
            const s1_unique_id = $(this).data("s1_unique_id");

            var reason = $(this).closest('td').find('.accept-reason-selectbox').val();

            const ajax_url = sessionStorage.getItem("folder_crud_link");
            var acceptButton = $(this);

            var title = "I have Physically verified that student " + std_name + " is currently staying in the hostel named " + hostel_name + ".";

            if (reason) {
                Swal.fire({
                    title: 'Confirm Verification',
                    text: title,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Accept',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const data = {
                            action: "updateReason",
                            s1_unique_id: s1_unique_id,
                            reason: reason,
                        };

                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: data,
                            success: function(response) {
                                var obj = JSON.parse(response);
                                var status = obj.status;

                                if (status === true) {
                                    acceptButton.hide(); // Hide confirm button
                                    acceptButton.closest('td').html('Accepted');
                                    Swal.fire('Verified!', 'The student has been verified.', 'success');
                                } else {
                                    Swal.fire('Error!', 'Something went wrong while updating.', 'error');
                                }
                            },
                            error: function() {
                                console.log("Error with: " + s1_unique_id);
                                document.getElementById('autoRenewal').disabled = false;
                                Swal.fire('Error!', 'AJAX request failed.', 'error');
                            },
                        });
                    }
                });
            } else {
                Swal.fire('Missing Reason', 'Please select a reason before confirming.', 'warning');
            }
        });



        $(document).on('click', '.rejectbtn', function() {
            const std_name = $(this).data("std_name");
            const hostel_name = $(this).data("hostel_name");
            const s1_unique_id = $(this).data("s1_unique_id");
            const name_diff = $(this).data("name_diff");
            const inst_distance_check = $(this).data("inst_distance_check");

            const container = $(this).closest('td');
            const selectHTML = $('#reject-reason-template').html();

            container.html(`
        
        <div style="display: flex; flex-direction: column; align-items: flex-start; gap: 10px;">
        <div style="display: flex; gap: 10px;">
            <button class="acceptbtn" style="background-color: green; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
                data-s1_unique_id="${s1_unique_id}" 
                data-hostel_name="${hostel_name}" 
                data-std_name="${std_name}"
                data-name_diff="${name_diff}"
                data-inst_distance_check="${inst_distance_check}">
                Accept
            </button>
            <button class="confirm-reject-btn" style="background-color: red; color: white; padding: 5px 10px; border: none; border-radius: 4px; margin-right: 8px;"
                data-s1_unique_id="${s1_unique_id}" 
                data-hostel_name="${hostel_name}" 
                data-std_name="${std_name}">
                Confirm Reject
            </button>
        </div>
            <div style="width: 100%;">
            ${selectHTML}
        </div>
    </div>        
    `);
            container.find('.reject-reason-selectbox').select2({
                placeholder: 'Select reason',
                width: '100%',
                dropdownParent: container // keeps dropdown inside this <td>
            });
        });
        // Event listener for confirm reject button
        $(document).on('click', '.confirm-reject-btn', function() {
            const std_name = $(this).data("std_name");
            const hostel_name = $(this).data("hostel_name");
            const s1_unique_id = $(this).data("s1_unique_id");

            var reason = $(this).closest('td').find('.reject-reason-selectbox').val();



            var rejectButton = $(this); // Store reference to confirm reject button

            var ajax_url = sessionStorage.getItem("folder_crud_link");
            if (reason) {
                var data = {
                    "s1_unique_id": s1_unique_id,
                    "reason": reason,
                    "action": "reject"
                }

                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    success: function(data) {
                        var obj = JSON.parse(data);
                        var msg = obj.msg;
                        if (msg == 'rejected') {
                            rejectButton.hide(); // Hide confirm reject button
                            rejectButton.closest('td').html('Rejected'); // Show status as "Rejected"
                            log_sweetalert_approval("rejected", "");
                        }
                    }
                });
            } else {
                sweetalert("form_alert");
            }
        });




    });
</script>