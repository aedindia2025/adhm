<style>
    button {
        margin-top: 34px;
    }
</style>
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Establishment Report</h4>
                    </div>
                </div>
                <!-- <div class="col-md-2 align-self-center">
            <div class="page-title-right">
             <a href="index.php?file=establishment_report/model">  
                <button  class="btn btn-primary" style="float: right;">Add New</button>
            </a>
            </div>
            </div> -->
            </div>


            <div class="row">


                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">Hostel Name</label>
                                        <select class="form-control" id="from_date" name="from_date">
                                            <option>Select</option>
                                            <option selected>Govt Boys Hostel</option>
                                            <!-- <option>xyz Hostel</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">From Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo date('d-m-Y'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo date('d-m-Y'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-2">

                                        <button type="button" class="btn btn-primary btn-sm" value="GO">Filter</button>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="dt-buttons btn-group"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                                            <br>
                                            <br>
                                            <table id="demo" class="table dt-responsive nowrap w-100">
                                                <thead>
                                                    <tr>
                                                        <th>S.no</th>
                                                        <th>Date</th>
                                                        <th>Hostel Name</th>
                                                        <th>Type</th>
                                                        <th>Description</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>02-01-2024</td>
                                                        <td>Govt Boys Hostel</td>
                                                        <td>Cook</td>
                                                        <td>-</td>
                                                        <!-- <td class=" text-center">
                    <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                    <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a></td> -->
                                                    </tr>
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