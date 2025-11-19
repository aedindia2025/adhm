<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);


$moveable_k_d = moveable_k_d();
$moveable_k_d = select_option($moveable_k_d, "Select Kitchen/Digital", '');

$category_k_d = category_k_d();
$category_k_d = select_option($category_k_d, "Select Category", '');

$asset_k_d = asset_k_d();
$asset_k_d = select_option($asset_k_d, "Select Asset", '');
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

    .nav-justified .nav-item,
    .nav-justified>.nav-link {
        -ms-flex-preferred-size: 0;
        flex-basis: 15%;
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 0;
        text-align: center;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
        color: var(--ct-nav-pills-link-active-color);
        background-color: #00aff0;
    }

    .nav-link:focus,
    .nav-link:hover {
        color: #000000;
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
                        <h4 class="page-title">Current Stock Report</h4>
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
                                        <?php echo $taluk_name_list ?>
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="example-select" class="form-label">Hostel Name</label>
                                    <select name="hostel_name" id="hostel_name" class="select2 form-control" required>
                                        <?php echo $hostel_name_list ?>
                                    </select>
                                </div>
                                <div class="col-md-2 align-self-center">
                                    <div class="page-title-right">

                                        <button class="btn btn-primary mt-3" onclick="filter()"
                                            style="float:left;">GO</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <br>

                            <button type="button" id="export_moveables" name="export_moveables"
                                class="btn   waves-effect waves-light wavw  mb-1"
                                style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill"
                                    style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button>
                            <br>
                            <div class="row">
                                <div class="col-md-12 load" id="loader_1">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>

                            <table id="assets_datatable" class="table  w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>District Name</th>
                                        <th>Taluk Name</th>
                                        <th>Hostel Name</th>
                                        <th>Kitchen Assets</th>
                                        <th>DigitalAssets</th>

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

<!-- Kitchen Assets Modal -->
<div class="modal fade" id="kitchenAssetsModal" tabindex="-1" role="dialog" aria-labelledby="kitchenAssetsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kitchenAssetsModalLabel">Kitchen Assets Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="kitchenAssetsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Entry Date</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Big/Small</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Kitchen assets details will be loaded here -->
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Digital Assets Modal -->
<div class="modal fade" id="DigitalAssetsModal" tabindex="-1" role="dialog" aria-labelledby="DigitalAssetsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DigitalAssetsModalLabel">Digital Assets Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="DigitalAssetsTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Entry Date</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Digital assets details will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>