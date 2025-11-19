<style>
    table#stock_entry_datatable {
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
                        <u class="page-title">Stock Inward Entry</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="stock_entry_datatable" class="table nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Entry Date</th>
                                        <th>Stock Id</th>
                                        <th>Supplier Name</th>
                                        <th>Bill No</th>
                                        <th>Invoice</th>
                                        <!-- <th>Hostel Name</th>
                                        <th>District</th>
                                        <th>Taluk </th> -->
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

