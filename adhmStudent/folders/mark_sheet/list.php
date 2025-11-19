<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-10">
                    <div class="page-title-box">

                        <h4 class="page-title">Mark Sheet</h4>
                    </div>
                </div>
            </div>

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
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dt-buttons btn-group"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                            <br>
                            <br>

                            <table id="attendance_list_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Date</th>
                                        <th>Exam Type</th>
                                        <th>Precentage</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                        <td>1</td>
                                        <td>05-09-2023</td>
                                        <td>Half Yearly Exam</td>
                                        <td>98%</td>
                                        <td>456</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>02-06-2023</td>
                                        <td>Quaterly Exam</td>
                                        <td>87%</td>
                                        <td>369</td>
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