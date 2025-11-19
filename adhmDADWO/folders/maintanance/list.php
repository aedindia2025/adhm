<style>
a.btn.btn-action.specl2 button {
    border: 0px;
    background: unset;
    color: #00afef;
    font-size: 15px;
}
a.btn.btn-action.specl2 {
    padding: 0px;
}
</style>

<?php 

$amc_name_list = academic_years($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);


$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name("",$_SESSION['district_id']);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

$academic_year = academic_year();
$academic_year = select_option($academic_year, "Select Academic Year", $academic_year);



?>


<div class="content-page">
<div class="content">
<!-- Start Content-->
<div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-10">
            <div class="page-title-box">                                    
            
            <h4 class="page-title">Maintenance List</h4>
            </div>
            </div>
            <div class="col-md-2 align-self-center">
            <div class="page-title-right">
            <form class="d-flex">
                                <!-- <?php echo btn_add($btn_add); ?> -->
                            </form>
           
            </div>
            </div>
        </div>
     
        <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-2">

                            <div class="col-2">
                                    <label for="example-select" class="form-label">Academic Year:</label>
                                    <select name="amc_name" id="amc_name" class="select2 form-control" disabled
                                        required>
                                        <?php echo $amc_name_list; ?>
                                    </select>
                                </div>

                                
                                <div class="col-md-3">
                                <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="taluk_name" name="taluk_name" onchange=get_hostel()>
                                        <?php echo $taluk_name_list; ?>
                                        <!-- <option>Select Zone</option>
                                        <option value="AK">Zone 1</option>
                                        <option value="HI">Zone 2</option> -->
                                    </select>
                                </div>
                                <div class="col-md-3">
                                <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list;?>
                                        <!-- <option>Select Hostel</option>
                                        <option value="AK">ADM Boys Hostel</option>
                                        <option value="HI">ERD Girls Hostel</option> -->
                                    </select>
                                </div>
                                
                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter_records()" style="float:left;">Filter</button>
                                    </div>
                                </div>
                            </div>
</div>

                 
                       
                        
    <div class="row">
    <div class="col-12">
    <div class="card">
    <div class="card-body">
        <table id="maintanance_datatable"     class="table dt-responsive nowrap w-100">
            <thead>
                <tr>
                    <th>S.no</th>
                    <th>Date</th>
                    <th>Maintenance No</th>
                    <th>Asset Category</th>
                    <th>Asset name</th>
                    <th>Hostel name</th>
                    <th>Invoice</th>
                    <th>View</th>
                    
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
    new DataTable('#demo');
</script>
