<!-- Modal with form -->

<div class="content-page">
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">

                        <h4 class="page-title">Biometric Attendance</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">

                
                    <div class="row">

                        <div class="">
                            <div class="card">
                                <div class="card-body">

                                    <div class="row mb-3">
                                        <form class="was-validated"></form>
                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Student Id</label>
                                            <input type="text" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Student Name</label>
                                            <input type="text" id="simpleinput" class="form-control">
                                        </div>
                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Biometric ID</label>
                                            <input type="text" id="simpleinput" class="form-control">
                                        </div>

                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Time</label>
                                            <input type="time" id="simpleinput" class="form-control">
                                        </div>

                                        <div class="col-md-3 fm">
                                            <label for="simpleinput" class="form-label">Permises Type</label>
                                            <select class="form-select" id="example-select">
                                                <option>Select</option>
                                                <option>IN Time</option>
                                                <option>OUT Time</option>
                                            </select>
                                        </div>

                                        </form>
                                    </div>
                                    <div class="btns">
                                        <a href="index.php?file=biometric_attendance/list"><button type="button" class="btn btn-danger  m-t-15 btn-rounded waves-effect waves-light float-right ml-2">Cancel</button></a>
                                        <button type="button" class="btn btn-primary m-t-15 waves-effect createupdate_btn" onclick="user_type_cu('')">Save</button>
                                    </div>
                                </div> <!-- end card-body -->
                            </div> <!-- end card-->
                        </div> <!-- end col -->

                    </div>




                </div>
            </div>



        </div>
    </div>
</div>