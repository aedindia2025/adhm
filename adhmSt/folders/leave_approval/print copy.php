<?php

if (isset($_GET["unique_id"])) {
    if (!empty($_GET["unique_id"])) {

        $unique_id = $_GET["unique_id"];
        echo $unique_id;
    }
}
?>
<style>
.forms-info-hostel label {
    color: #6c757dbf;
    font-weight: 400;
    font-size: 14px;
}
.forms-info-hostel p {
    color: #454747;
    font-size: 15px;
    border: 0px dotted #ccc;
    padding: 7px 7px;
    border-radius: 4px;
    margin: 5px 0px 14px 0px;
	font-weight: 400;
}
.forms-info-hostel select {
    color: #454747;
    font-size: 15px;
    border: 1px dotted #ccc;
    padding: 7px 7px;
    border-radius: 4px;
	width: 100%;
	margin: 5px 0px 14px 0px;
	height: 40px;
}
.forms-info-hostel textarea {
    color: #454747;
    font-size: 15px;
    border: 1px dotted #ccc;
    padding: 7px 7px;
    border-radius: 4px;
	width: 100%;
	margin: 5px 0px 14px 0px;
}


.container {
    margin: 0px 30px;
}


body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
}
table tr td {
    width: 33.33%;
    padding: 0px 10px;
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

                        <h4 class="page-title" style="text-align: center;font-size: 21px;text-transform: uppercase;border-bottom: 1px dashed #ccc;padding-bottom: 14px;">Leave Application</h4>
                    </div>
                </div>
            </div>
            <!-- end page title -->
            
                    <form class="was-validated" autocomplete="off">
                       
                           
                                <div class=" container">
										<div class=" forms-info-hostel">
										<table style="width: 100%;">
										<tr>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">Hostel Name : </label>
										<p>XYZ Hostel</p>
										</div>
										</td>
										
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">warden name : <p>XYZ Hostel</p></label>
										
										</div>
										</td>
										
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">Student Name : <p>XYZ Hostel</p></label>
										
										</div>
										</td>
										<td>
										</tr>
										
										<tr>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">From Date</label>
										<p>XYZ Hostel</p>
										</div>
										</td>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">To Date</label>
										<p>XYZ Hostel</p>
										</div>
										</td>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">No Of Day</label>
										<p>XYZ Hostel</p>
										</div>
										</td>
										</tr>
										
										
										<tr>
										<td>
										
										<div class="mb-3">
										<label for="simpleinput" class="form-label">Reason</label>
										<p>XYZ Hostel</p>
										</div>
										</td>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">Status</label>
										<select name="zone_name" id="zone_name" class="select2 form-control" required="">
                                                <option value="0">Approval</option>
                                                <option value="1">Reject</option>
                                                
                                            </select>
										</div>
										</td>
										<td>
										<div class="mb-3">
										<label for="simpleinput" class="form-label">Reject Reason</label>
										<textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
										</div>
										</td>
										</tr>
										</table>
										</div>
										</div>
                                    <!--<div class="row">
                                        <label for="student_id" class="form-label">Student Id: </label>
                                        <label for=""></label>
                                        <input type="hidden" id="student_id" name="student_id[]" class="form-control" value="'.$value['student_id'].$value['s_no'].'"></input>
                                        <input type="text" id="form_unique_id" name="form_unique_id[]" class="form-control" value="'.$value['unique_id'].$value['s_no'].'"></input>
                                        <input type="hidden" id="warden_name" name="warden_name[]" class="form-control" value="'.$userid.$value['s_no'].'"></input>
                                        <label for="student_id" class="form-label">Student Name: </label>
                                        <label for="" value="">'.$value['student_name'].'</label>
                                        <input type="hidden" id="student_name" name="student_name" class="form-control" value="'.$value['student_id'].'" required></input>
                                        <label for="example-select" class="form-label">From Date</label>
                                        <label for="">'.$value['from_date'].'</label>
                                        <input type="hidden" id="from_date" name="from_date" class="form-control" value="'.$value['from_date'].'" required></input>
                                        <label for="example-select" class="form-label">To Date</label>
                                        <label for="">'.$value['to_date'].'</label>
                                        <input type="hidden" id="to_date" name="to_date" class="form-control" value="'.$value['to_date'].'" required></input>
                                        <label for="example-select" class="form-label">No Of Days</label>
                                        <label for="">'.$value['no_of_days'].'</label>
                                        <input type="hidden" id="no_of_days" name="no_of_days" class="form-control" value="'.$value['no_of_days'].'"></input>
                                        <label for="example-select" class="form-label">Reason</label>
                                        <label for="">'.$value['reason'].'</label>
                                        <input type="hidden" id="reason" name="reason" class="form-control" value=""></input>
                                        <label class="form-label me-2" for="example-select">Status:</label>
                                        <select class="select2 form-control me-2" style="position: unset; width: 100%; height: auto; margin: 0px;" id="status" name="status" onchange="checkSts(this,'.$sno.')">
                                            <option value="2">Approval</option>
                                            <option value="3">Rejected</option>
                                        </select>
                                        <div class="row mt-2" id="description_div" style="display:none;">
                                            <div class="col-md-4">
                                                <label for="example-select" class="form-label">Reject Reason:</label>
                                            </div>
                                            <div class="col-md-8">
                                                <textarea type="text" id="description" name="description" class="form-control" value=""></textarea>
                                            </div>
                                        </div>

                                    </div>----->
                                
                    </form>
               
</div>
</div>
</div>