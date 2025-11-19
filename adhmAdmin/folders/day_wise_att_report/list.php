
<style>

table.dataTable td, 
table.dataTable th {
    white-space: nowrap !important;
}

table#day_wise_att_report_datatable {
    overflow: scroll !important;
    display: block !important;
    width: 100% !important;
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
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">
                        <h4 class="page-title">Biometric Attendance Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">From Date</label>
                                    <input type='date' id="from_date" name='from_date' class='form-control' value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                
                                <div class="col-md-2 mt-1 pt-3 align-self-center" style="padding-top:3px;">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter()" style="float:left;">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <button type="button" id="export" name="export" class="btn   waves-effect waves-light wavw  mb-1" style="background: #337734;color: #fff;font-size:16px;">
                <i class="ri-file-excel-2-fill" style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
            </button>
            <div class="row">
                <div class="col-md-12 load" id="loader">
                    <i class="mdi mdi-loading mdi-spin"></i>
                </div>
            </div> -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="day_wise_att_report_datatable" class="table dataTable w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>District</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>Sanctioned Strength</th>
                                        <th>DADWO Approved Count</th>
                                        <th>Biometric Registered Count</th>
                                        <th>Morning Status</th>
                                        <th>Evening Status</th>
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