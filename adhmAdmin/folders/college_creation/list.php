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
                                <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            </form>
                        </div>
                        <h4 class="page-title">College</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="college_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Stream Name</th>
                                        <th>University Name</th>
                                        <th>College Name</th>
                                        <th>Status</th>
                                        <th>Action</th>
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