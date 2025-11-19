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
                        <h4 class="page-title">Monthly Bill Submission</h4>

                    </div>
                </div>
            </div>

        </div>


  <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                           
                            <div class="col-3">
                                <label for="example-select" class="form-label">District Name</label>
                                <select name="district_name" id="district_name" class="select2 form-control"
                                    onchange=get_taluk()>

                                    <?php echo $district_name_list; ?>
                                </select>

                            </div>

                            <div class="col-3">
                                <label for="example-select" class="form-label">Taluk Name</label>
                                <select name="taluk_name" id="taluk_name" class="select2 form-control"
                                    onchange="get_hostel()" required>
                                   
                                 

                                </select>

                            </div>

                            <div class="col-3">
                                <label for="example-select" class="form-label">Hostel Name</label>
                                <select name="hostel_name" id="hostel_name" class="select2 form-control" required>

                            


                                </select>

                            </div>
                         <!-- </div><br>
                        <div class="row"> -->
                            <div class="col-md-2 mt-3 align-self-center">
                                <div class="page-title-right">

                                    <button class="btn btn-primary" onclick="filter()" style="float:left;">GO</button>
                                    </a>
                                </div>
                            </div>
                        </div><br><br>





        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="batch_datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Submitted Date</th>
                                    <th>Hostel Name</th>
                                    <th>Bill No</th>
                                    <th>Bill Amount</th>
                                    <th>Bill Count</th>
                                    <th>Approved Bill Amount</th>
                                    <th>Rejected Bill Amount</th>
                                    <th>Approved Bill Count</th>
                                    <th>Rejected Bill Count</th>
                                    <th>Received Status</th>
                                    <th>View</th>
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