<?php

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

// $taluk_name_list      = taluk_name();
// $taluk_name_list      = select_option($taluk_name_list, "Select Taluk", $taluk_name);

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

    table#hostel_creation_datatable {
    width: 100%;
    display: block;
    overflow: scroll;
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
                       
                        <h4 class="page-title">Hostel Creation</h4>
                        <input type="hidden" id="csrf_token" name="csrf_token"
                            value="<?php echo $_SESSION['csrf_token']; ?>">
                    </div>
                </div>
            </div>
            <!-- end page title -->
            <div class="row">
                <div class="col-12">
                  
                    <div class="card">
                        <div class="card-body">
                            <!-- <button type="button" id="export_hostel" name="export_hostel"
                                class="btn   waves-effect waves-light wavw  mb-1"
                                style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill"
                                    style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button> -->
                            <br>

                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>
                            <table id="hostel_creation_datatable" class="table nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Hostel District</th>
                                        <th>Hostel Taluk</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>GO Attachment</th>
                                        <th>Entrance Image</th>
                                        <th>Dining Image</th>
                                        <th>Building Image</th>
                                        <th>
                                            <div align="center">Action</div>
                                        </th>
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
