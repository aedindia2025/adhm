<?php

?>

<style>
.dt-left {
    text-align: left;
}
.dt-right {
    text-align: right;
}
</style>

<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <!-- <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Stock Inward Entry</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                <div class="page-title-right">
                            <form class="d-flex">
                                <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                    <div class="page-title-right">
                        <a href="index.php?file=stock_entry/model"> <button class="btn btn-primary"
                                style="float: right;">Add New</button></a>
                    </div>
                </div>
            </div> -->

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
                            <!-- <div class="dt-buttons btn-group">          <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div> -->
                           
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="stock_entry_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Entry Date</th>
                                        <th>Stock Id</th>
                                        <th>Supplier Name</th>
                                        <th>Address</th>
					<th>Bill Amount</th>
                                        <th>Bill No</th>
<th>Bill Doc</th>
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

