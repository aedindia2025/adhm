<?php

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$academic_year = academic_year();
$academic_year = select_option($academic_year, "Select Academic Year", $academic_year);

$screen_unique_id = unique_id($prefix);

?>

<style>
    button#filter {
        margin-top: 4px;
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

                        <h4 class="page-title">Attendance Report</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label for="simpleinput" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="from_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="simpleinput" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="to_date">
                                </div>
                                <div class="col-md-3">
                                    <label for="simpleinput" class="form-label">District Name</label>
                                    <select class="form-control select2" id="district_name" name="district_name" onchange="get_taluk()">
                                        <?php echo $district_name_list;?>
                                        <!-- <option>Select District</option>
                                        <option value="AK">Erode</option>
                                        <option value="HI">Salem</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="simpleinput" class="form-label">Taluk Name</label>
                                    <select class="form-control select2"  id="taluk_name" name="taluk_name" onchange="get_hostel()">
                                        <?php echo $taluk_name_list;?>
                                        <!-- <option>Select Zone</option>
                                        <option value="AK">Zone 1</option>
                                        <option value="HI">Zone 2</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="simpleinput" class="form-label">Hostel Name</label>
                                    <select class="form-control select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list;?>
                                        <!-- <option>Select Hostel</option>
                                        <option value="AK">ADM Boys Hostel</option>
                                        <option value="HI">ERD Girls Hostel</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3 mt-3">
                                    <label for="simpleinput" class="form-label">Academic Year</label>
                                    <select class="form-control select2" data-toggle="select2" id="academic_year" name="academic_year">
                                        <?php echo $academic_year; ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-4">
                                    <div class="page-title-right mt-2">
                                        <form class="d-flex">
                                            <a href=""> <button id="filter" class="btn btn-primary" style="float: right;">Filter</button></a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="dt-buttons btn-group"> <button class="btn btn-secondary buttons-copy buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Copy</span></button> <button class="btn btn-secondary buttons-csv buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>CSV</span></button> <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>Excel</span></button> <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0" aria-controls="student_onboarding_datatable"><span>PDF</span></button> <button class="btn btn-secondary buttons-print" tabindex="0" aria-controls="student_onboarding_datatable"><span>Print</span></button> </div>
                            <br>
                            <br>
                            <table id="demo" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Hostel Name</th>
                                        <th>Present Count</th>
                                        <th>Absent Count</th>
                                        <th>Total</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ADM Boys Hostel</td>
                                        <td>70</td>
                                        <td>10</td>
                                        <td>80</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>ERD Girls Hostel</td>
                                        <td>50</td>
                                        <td>2</td>
                                        <td>52</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>SLM Boys Hostel</td>
                                        <td>50</td>
                                        <td>2</td>
                                        <td>52</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>CHN Boys Hostel</td>
                                        <td>50</td>
                                        <td>2</td>
                                        <td>52</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>SRG Boys Hostel</td>
                                        <td>30</td>
                                        <td>12</td>
                                        <td>42</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>FTS Boys Hostel</td>
                                        <td>39</td>
                                        <td>6</td>
                                        <td>45</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>IIY Boys Hostel</td>
                                        <td>43</td>
                                        <td>2</td>
                                        <td>45</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>PIY Boys Hostel</td>
                                        <td>23</td>
                                        <td>8</td>
                                        <td>31</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>TYE Boys Hostel</td>
                                        <td>10</td>
                                        <td>2</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>ERO Boys Hostel</td>
                                        <td>46</td>
                                        <td>2</td>
                                        <td>48</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>SRT Boys Hostel</td>
                                        <td>22</td>
                                        <td>3</td>
                                        <td>25</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>ETU Boys Hostel</td>
                                        <td>23</td>
                                        <td>6</td>
                                        <td>29</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>TYT Boys Hostel</td>
                                        <td>21</td>
                                        <td>2</td>
                                        <td>23</td>
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
    // new DataTable('#demo');

    function get_taluk(){
	
	var district_name = $("#district_name").val();
	// alert(district_name);
	var data = "district_name="+district_name+"&action=district_name";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type : "POST",
		data : data,
		url : ajax_url,
		success: function(data){
			if(data){
				$("#taluk_name").html(data);
			}
		}
	});
}
</script>