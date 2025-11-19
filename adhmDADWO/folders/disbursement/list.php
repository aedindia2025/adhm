<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <!-- <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form> -->
                        </div>
                        <h4 class="page-title">Disbursement</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <table id="disbursement_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                        <th>S.no</th>
                                            <th>Disbursement Type</th>
                                            <th>ACC. Year</th>
                                            <th>Month</th>
                                            <th>Conn.No.</th>
                                            <th>Letter No.</th>
                                            <th>Applied Date</th>
                                            <th>ST.Letter No.</th>
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
</div>

