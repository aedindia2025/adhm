<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">
                        <h4 class="page-title">Miscellaneous Report</h4>
                    </div>
                </div>
                <!-- <div class="col-md-2 align-self-center">
            <div class="page-title-right">
             <a href="index.php?file=dropout_report/model">  
                <button  class="btn btn-primary" style="float: right;">Add New</button>
            </a> -->
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="">
                                <div class="card">
                                    <div class="card-body">


                                        <form class="was-validated" autocomplete="off">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label for="example-select" class="form-label">Academic Year</label>
                                                    <input type="date" id="simpleinput" class="form-control">
                                                </div>
                                                <div class="col-3">
                                                    <label for="example-select" class="form-label">Academic Year</label>
                                                    <input type="date" id="simpleinput" class="form-control">
                                                </div>
                                                <div class="col-3">
                                                    <form class="d-flex">

                                                        <a href=""><button class="btn btn-primary" style="margin-top:25px;">Go</button></a>
                                                    </form>

                                                </div>
                                            </div>

                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">                                               
                                            <div class="dt-buttons btn-group" style="margin-bottom:24px;"><button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button>
                                                    <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button>
                                                    <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button>
                                                    <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button>
                                                    <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button>

                                                </div>
                                                </div>

                                                <div class="col-md-6 text-end"> 
                                                    <h4> Total Amount Allocated : 300</h4>
                                                 </div>
                                                 </div>
                                                <table id="demo" class="table dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>S.no</th>
                                                            <th>Date</th>
                                                            <th>Hostel Name</th>
                                                            <th>Student ID</th>
                                                            <th>Expenses Type</th>
                                                            <th>Amount</th>
                                                            <th>Balance</th>
                                                            <!-- <th>Rejected Count</th> -->
                                                            <!-- <th>Reason</th> -->
                                                            <!-- <th>Action</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>12-02-2024</td>
                                                            <td>Govt. boys hostel</td>
                                                            <td>ADHM026</td>
                                                            <td>Hair cut</td>
                                                            <td>120</td>
                                                            <td><b>180</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>17-01-2024</td>
                                                            <td>Govt. boys hostel</td>
                                                            <td>ADHM026</td>
                                                            <td>Oil</td>
                                                            <td>80</td>
                                                            <td><b>100</b></td>
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
                    <!-- <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script> -->
                    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
                    <script>
                        new DataTable('#demo');
                    </script>