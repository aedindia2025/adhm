<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// $hostel_type_options = hostel_type_name();
$hostel_type_options = hostel_type_except_school();
$hostel_type_options = select_option($hostel_type_options, "Select Hostel Type", $hostel_type);
?>

<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">Diet Chart</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="csrf_token" name="csrf_token"
                                value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="diet_chart_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Hostel Type</th>
                                        <th>Description</th>
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

<!-- Copy Modal -->
<div class="modal fade" id="copyModal" tabindex="-1" aria-labelledby="copyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copy Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="copy_unique_id" id="copy_unique_id">
                <input type="hidden" name="copy_screen_id" id="copy_screen_id">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="col-md-12 mb-3">
                    <label class="form-label">Hostel Type</label>
                    <select name="hostel_type" id="modal_hostel_type" class="form-control" required>
                        <?= $hostel_type_options ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="copy_data()">Copy</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>