<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// $hostel_type_options = hostel_type_name();
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
                                        <th>DADWO Approved Count</th>
                                        <th>DADWO Request</th>
                                        <th>Approved Count</th>
                                        <th>Final Count</th>
                                        <!-- <th>Percentage Applied</th> -->
                                        <!-- <th>Request Status</th> -->
                                        <th>Action</th>
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

<!-- request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Popup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="req_hostel_id" id="req_hostel_id_val">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="col-md-12 mb-3">
                    <label class="form-label">Hostel ID</label>
                    <p id="req_hostel_id"></p>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Requested Count</label>
                    <input type="text" class="form-control" id="request_count" name="request_count" oninput="number(this)" maxlength="3">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="request()">Request</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>