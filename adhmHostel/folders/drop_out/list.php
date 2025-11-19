<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Dropout / Discontinue</h4>
                    </div>
                </div>
                <div class="col-md-2 align-self-center">
                    <div class="page-title-left" style="float: right;">
                        <form class="d-flex">
                            <?php echo btn_add($btn_add); ?>
                            <!-- <button type="submit" class="btn btn-primary" style="float: right;">Add New</button> -->
                        </form>
                        <!-- <a href="index.php?file=drop_out/model">
                            <button class="btn btn-primary" style="float: right;">Add New</button>
                        </a> -->
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- <div class="dt-buttons btn-group mt-2 mb-3"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                            <br>
                            <br> -->
                            <input type="hidden"  id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <table id="drop_out" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Reason For Dropout/Discontinue</th>
                                        <th>Status</th>
                                        <th>DADWO Action Date</th>
                                        <th>Reject Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>Praveen</td>
                                        <td>ADH026</td>
                                        <td>Moved to native place</td>
                                        <td class=" text-center">
                                            <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                                            <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>

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