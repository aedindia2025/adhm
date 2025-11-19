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

table#batch_datatable {
        width: 100%;
        display: block;
        overflow: scroll;
    }   
</style>
<?php
$academic_year_options = all_academic_year();
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
                        <h4 class="page-title">Print For Dispatch</h4>
                        
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
                                        <select name="academic_year" id="academic_year" class="select2 form-control" >
                                            <?php echo $academic_year_options; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-primary mt-3 btn-rounded mr-2" onclick="filter_records();">Filter</button>
                                    </div>
                                    <div class="col-md-6">
                                    <?php 
                                            $password = '3sc3RLrpd17';
                                            $enc_method = 'aes-256-cbc';
                                            $enc_password = substr(hash('sha256', $password, true), 0, 32);
                                            $enc_iv = "av3DYGLkwBsErphc";

                                            $menu_screen            = "print_for_dispatch/model";
                                            $file_name_update       = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
                                        ?>
                                       <a href="index.php?file=<?= $file_name_update;?>"><button type="button" class="btn btn-primary mt-3 btn-rounded mr-2 print-btn" >Print For Dispatch</button></a>
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
                        <table id="batch_datatable" class="table nowrap w-100">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Date</th>
                                    <th>Batch type</th>
                                    <th>Batch No</th>
                                    <th>Total</th>
                                    <th>Approved</th>
                                    <th>Rejected</th>
                                    <th>Status</th>
                                    <th>Rec Status</th>
                                    <th>Rec Time</th>
                                    <th>view</th>
                                    <th>Print</th>
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