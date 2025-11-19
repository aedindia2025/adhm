<?php

$from_date = date('Y-m-d');
$to_date = date('Y-m-d');
$date_type = '1';
$date_type_option = [
    "1" => [
        "unique_id" => "1",
        "value" => "Past Week",
    ],
    "2" => [
        "unique_id" => "2",
        "value" => "Past Month",
    ],
    "3" => [
        "unique_id" => "3",
        "value" => "Custom",
    ],

];
$date_type_option = select_option($date_type_option, "Select", $date_type);


?>

<style>

.dt-right {
    text-align: right !important;
}
.dt-center {
    text-align: center !important;
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
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">

                        </div>
                        <h4 class="page-title">Application Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-md-4">
                                            <select name="date_type" id="date_type" class="select2 form-control"
                                                onchange="get_date_type();">
                                                <?php echo $date_type_option; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3" id="from_date_div">
                                            <input class="form-control date" id="from_date" type="date"
                                                value="<?php echo $from_date; ?>">
                                        </div>
                                        <div class="col-md-3" id="to_date_div">
                                            <input class="form-control date" id="to_date" type="date"
                                                value="<?php echo $to_date; ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary" onclick="filter()">GO</button>
                                        </div>
                                </div>
                            </div>


                            <br>

                            <br>
                            <!-- <button type="button" id="export" name="export"
                                class="btn   waves-effect waves-light wavw  mb-1" style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill" style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button> -->
                            <br>

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>


                            <table id="nr_report_datatable" class="table  w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>District</th>
                                        <th>Fresh Applications</th>
                                        <th>Renewal Applications</th>
                                        
                                        
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

