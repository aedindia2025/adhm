<?php

$moveable_k_d = moveable_k_d();
$moveable_k_d = select_option($moveable_k_d, "Select Kitchen/Digital", '');

$category_k_d = category_k_d();
$category_k_d = select_option($category_k_d, "Select Category", '');

$asset_k_d = asset_k_d();
$asset_k_d = select_option($asset_k_d, "Select Asset", '');

?>
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
                        <h4 class="page-title">Moveables Assets</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Kitchen/Digital</label>
                                    <select name="list_type" id="list_type" class="form-control"
                                        onchange="get_list_category_name(this.value)">
                                        <option value="">All</option>
                                        <option value="1">Kitchen</option>
                                        <option value="2">Digital</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="example-select" class="form-label">Category</label>
                                    <select name="list_category" id="list_category" class="select2 form-control" disabled>
                                    <!-- onchange="get_list_asset_name(this.value)" -->
                                        <?php echo $category_k_d; ?>
                                    </select>
                                </div>
                                <!-- <div class="col-3">
                                    <label for="example-select" class="form-label">Asset</label>
                                    <select name="list_asset" id="list_asset" class="select2 form-control" disabled>
                                        <?php echo $asset_k_d ?>
                                    </select>
                                </div> -->
                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="go_filter()">GO</button>
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
                        <input type="hidden" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <table id="moveables_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Asset ID</th>
                                        <th>Category</th>
                                        <th>Assets</th>
                                        <th>Quantity</th>
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
</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#demo');
</script>