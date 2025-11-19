<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);
?>

<style>
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

    table#indent_count_datatable {
    width: 100%;
    display: block;
    overflow: scroll;
}
</style>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <!-- <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form> -->
                        </div>
                        <h4 class="page-title">Indent Count</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label class="form-label" for="example-select">District Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="district_name" name="district_name" onchange=get_taluk()>
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="taluk_name" name="taluk_name" onchange=get_hostel()>
                                        <?php echo $taluk_name_list; ?>

                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list; ?>

                                    </select>
                                </div>


                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter_records()">GO</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="csrf_token" name="csrf_token"
                                value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="indent_count_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>District Name</th>
                                        <th>Month</th>
                                        <th>Hostel Type</th>
                                        <th>Indent Count</th>
                                        <th>DADWO Approved Count</th>
                                        <th>DADWO Request</th>
                                        <th>Approved Count</th>
                                        <th>Final Count</th>
                                        <!-- <th>Percentage Applied</th> -->
                                        <!-- <th>Action</th> -->
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

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Count</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="hostel_id" id="hostel_id">
                <input type="hidden" name="base_count" id="base_count">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="col-md-12 mb-3">
                    <label class="form-label">Hostel ID</label>
                    <p id="label_hostel_id"></p>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Requested Count</label>
                    <input type="text" class="form-control" id="request_count" name="request_count" readonly>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Approved Count</label>
                    <input type="text" class="form-control" id="approved_count" name="approved_count" oninput="number(this)" maxlength="3">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="approve()">Approve</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="hostel_id_rej_val" id="hostel_id_rej_val">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="col-md-12 mb-3">
                    <label class="form-label">Hostel ID</label>
                    <p id="reject_hostel_id"></p>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Requested Count</label>
                    <input type="text" class="form-control" id="reject_request_count" name="request_count" readonly>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Reject Reason</label>
                    <textarea class="form-control" id="reject_reason" name="reject_reason" oninput="description_val(this)"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="reject()">Reject</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>