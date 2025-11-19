<style>
a.btn.btn-danger.btn-action.specl2 i {
    color: #299fcb !important;
}
a.btn.btn-danger.btn-action.specl2 {
    background: unset;
    border: 0px;
    box-shadow: none;
}
.dt-right {
    text-align: right !important;
}
.dt-center {
    text-align: center !important;
}
</style>
<?php
$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options, "Select Academic Year");

?>
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Monthly Bill Submission</h4>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="academic_year" class="form-label">Academic Year</label>
                                        <select name="academic_year" id="academic_year" class="select2 form-control" required>
                                            <?php echo $academic_year_options; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary mt-3 btn-rounded mr-2 mb-mt" onclick="filter_records();">Filter</button>
                                    </div>
                                    <div class="col-md-6">
<?php 
                                            $password = '3sc3RLrpd17';
                                            $enc_method = 'aes-256-cbc';
                                            $enc_password = substr(hash('sha256', $password, true), 0, 32);
                                            $enc_iv = "av3DYGLkwBsErphc";

                                            $menu_screen            = "monthly_bill_submission/model";
                                            $file_name_update       = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                                        ?>

                                       <a href="index.php?file=<?= $file_name_update;?>"><button type="button" class="btn btn-primary mt-3 btn-rounded mr-2 flot-l" style="float: right;">Bill Creation</button></a>
                                    </div>
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
                        <table id="batch_datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Date</th>
                                    <th>Bill No</th>
                                    
                                    <!-- <th>Amount</th> -->
                                    <th>Bill Amount</th>
                                    <th>Bill Count</th>
                                    <th>Approved Bill Amount</th>
                                    <th>Rejected Bill Amount</th>
                                    <th>Approved Bill Count</th>
                                    <th>Rejected Bill Count</th>
				    <th>Status</th>
                                    <th>Rec Status</th>
                                    <th>Rec Time</th>
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
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="folders/print_for_dispatch/print_for_dispatch.js"></script> -->