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

            <div class="row mb-2">

                <div class="col-md-3">
                    <select class="form-control select2" data-toggle="select2">
                        <option>Select Year</option>
                        <option>Jan,2024</option>
                        <option>Feb,2024</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <a href=""> <button class="btn btn-primary" style="float: right;">Filter</button></a>
                        </form>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
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
                                        <td>2</td>
                                        <td>02-06-2023</td>
                                        <td>Quaterly Exam</td>
                                        <td>87%</td>
                                        <td>369</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>05-09-2023</td>
                                        <td>Half Yearly Exam</td>
                                        <td>98%</td>
                                        <td>456</td>
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