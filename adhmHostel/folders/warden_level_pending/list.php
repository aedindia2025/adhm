<?php


$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);



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

.dt-buttons.btn-group.flex-wrap {
    display: none;
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
                        <h4 class="page-title">Warden Level Pending Application List</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                   
                    <div class="card">
                        <div class="card-body">
                            <br>

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>

                            <table id="warden_level_pending_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Student Name</th>
                                        <th>District Name</th>
                                        <th>Taluk Name</th>
                                        <th>Hostel Name</th>
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