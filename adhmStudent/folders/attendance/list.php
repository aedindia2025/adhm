<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Attendance - Report</h4>
                    </div>
                </div>
            </div>


            <!-- <div class="row">

                <div class="">
                    <div class="card">
                        <div class="card-body">
                            <form class="was-validated" autocomplete="off">

                                <div class="row">
                                    <div class="col-3">
                                        <label for="example-select" class="form-label">From Date</label>
                                        <input type="date" id="simpleinput" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="example-select" class="form-label">To Date</label>
                                        <input type="date" id="simpleinput" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <form class="d-flex">

                                            <a href=""><button class="btn btn-primary" style="margin-top:25px;">Go</button></a>
                                        </form>

                                    </div>
                                </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    
                                    <table id="attendance_list_datatable" class="table dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Date</th>
                                                <th>Student ID</th>
                                                <th>Attendance Type</th>
                                                <th>Time</th>
                                                <!-- <th>Status</th> -->
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


        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
        <script>
            // new DataTable('#attendance_list_datatable');
        </script>