<style>
    table#stock_consumble_entry_datatable {
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
                        <div class="page-title-right">
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <u class="page-title">Stock Consumble Entry</h4>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form class="was-validated" autocomplete="off">

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="example-select" class="form-label">From Date</label>
                                <input type="date" id="from_date" name="from_date" class="form-control"
                                    value="<?php echo date('Y-m-01'); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="example-select" class="form-label">To Date</label>
                                <input type="date" id="to_date" name="to_date" class="form-control"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>

                            <div class="col-md-3 mb-3">
                                <form class="d-flex">
                                        <buttont type="button" class="btn btn-primary" style="margin-top: 28px;" onclick="filter()">Go</button>
                                </form>
                            </div>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="stock_consumble_entry_datatable" class="table nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Stock Id</th>
                                        <th>Hostel Name</th>
                                        <th>District</th>
                                        <th>Taluk </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
