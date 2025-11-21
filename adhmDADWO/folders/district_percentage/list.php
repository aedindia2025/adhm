<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$district_name_options = district_name();
$district_name_options = select_option($district_name_options, "Select District", $district);

?>

<style>
    .select2-container--open {
        z-index: 9999 !important;
    }
</style>

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
                        <h4 class="page-title">District Percentage</h4>
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
                                        <th>District</th>
                                        <th>Month</th>
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
<?php
$nextMonth = date('Y-m', strtotime('first day of next month'));
$selectedMonth = $month ?? $nextMonth;
?>


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
                <input type="hidden" id="copy_month" name="month" class="form-control">
                <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="col-md-12 mb-3">
                    <!-- <label class="form-label">District</label>
                    <select name="district" id="modal_district" class="form-control select2" multiple required>
                        <?= $district_name_options ?>
                    </select> -->

<label class="form-label">Month</label>
                 <input type="month" id="month" name="month"
    class="form-control <?= $readonlyClass ?>"
    value="<?= $selectedMonth ?>"
    min="<?= $nextMonth ?>">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="copy_data()">Copy</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("month").addEventListener("input", function () {
    let selected = this.value;
    let minMonth = "<?= $nextMonth ?>";

    // Allow future months
    if (selected >= minMonth) {
        return; 
    }

    // Block past months
    alert("You cannot select current or previous months.");
    this.value = minMonth;
});
</script>

