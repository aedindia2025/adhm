<style>
    table.dataTable td,
    table.dataTable th {
        white-space: nowrap !important;
    }

    table#consolidated_report_datatable {
        overflow: scroll !important;
        display: block !important;
        width: 100% !important;
    }

#consolidated_report_datatable th, 
#consolidated_report_datatable td {
    text-align: right; /* Right align all cells */
}
#consolidated_report_datatable th:first-child, 
#consolidated_report_datatable td:first-child {
    text-align: center; /* Left align first column */
}

#consolidated_report_datatable th:nth-child(2),
#consolidated_report_datatable td:nth-child(2),
#consolidated_report_datatable th:nth-child(3),
#consolidated_report_datatable td:nth-child(3),
#consolidated_report_datatable th:nth-child(4),
#consolidated_report_datatable td:nth-child(4) {
    text-align: left; /* Left align columns 2, 3, and 4 */
}

.bold-text {
    font-weight: bold !important;
}

#consolidated_report_datatable tbody tr,
#consolidated_report_datatable thead tr {
    border: 1px solid black !important; /* Border for all rows */
}


/* Add border between date groups */
#consolidated_report_datatable thead th,
#consolidated_report_datatable tbody td {
    border-right: 1px solid #333; /* Darker border between date groups */
}

input#offline,
    #tot_hostel,
    #bio_reg_cnt,
    #tot_days,
    #mrg_cnt,
    #eve_cnt,
    #tot_punch_cnt
     {
        border: none;
        font-weight: bold;
        background: unset;
        padding: 0px;
        font-size: 17px;
    }





</style>

<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);
?>

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <h4 class="page-title">Consolidated Report</h4>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
 				    <label for="example-select" class="form-label">Month</label>
                                    <input type="month" name="month_year" id="month_year" class="form-control"
                                        value='<?php echo date('Y-m'); ?>' required>
                                </div>
				<div class="col-3">
                                    <label for="example-select" class="form-label">District Name</label>
                                    <select name="district_name" id="district_name" class="select2 form-control" onchange="get_taluk()" required>
                                        <?php echo $district_name_list; ?>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Taluk Name</label>
                                    <select name="taluk_name" id="taluk_name" class="select2 form-control" onchange="get_hostel()" required>
                                        <?php echo $taluk_name_list ?>
                                    </select>
                                </div>
                               
                                <div class="col-md-2 mt-3 align-self-center">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter(); get_hostel_details(); get_att_details();"
                                            style="float:left;">GO</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Total Hostel </label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="tot_hostel" disabled>
                                </div>
                                <div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Biometric Registered Count</label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="bio_reg_cnt" disabled>
                                </div>
 				<div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Total Days</label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="tot_days" disabled>
                                </div>

                            </div>
                        </div>
                    </div>

<div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Morning </label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="mrg_cnt" disabled>
                                </div>
                                <div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Evening</label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="eve_cnt" disabled>
                                </div>
 				<div class="col-md-2 fm">
                                    <label class="form-label" for="example-select">Total</label>
                                </div>
                                <div class="col-md-2 fm">
                                    <input type="text" class="form-control" id="tot_punch_cnt" disabled>
                                </div>

                            </div>
                        </div>
                    </div>




            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <table id="consolidated_report_datatable" class="table dataTable w-100">
                                <thead>
                                    <tr></tr>
                                </thead>

                            </table>


                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>