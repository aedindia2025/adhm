<style>
    .btn {
        margin-top: 27px;
    }
    .modal-content {
        margin-left: 229px;
    padding: 10px;
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 60%;
    color: black;
    pointer-events: auto;
    background-color: var(--ct-modal-bg);
    background-clip: padding-box;
    border: var(--ct-modal-border-width) solid var(--ct-modal-border-color);
    border-radius: var(--ct-modal-border-radius);
    outline: 0;
}
</style>
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


<div class="content-page">
<div class="content">
<!-- Start Content-->
<div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-10">
            <div class="page-title-box">                                    
            
            <h4 class="page-title">Grievance List</h4>
            </div>
            </div>
            <div class="col-md-2 align-self-center">
            <div class="page-title-right">
             <a href="index.php?file=grievance_category/model">  
                <!-- <button  class="btn btn-primary" style="float: right;">Add New</button> -->
            </a>
           
            </div>
            </div>
     
            <div class="col-md-3 fm">
                                <label for="example-select" class="form-label">Status</label>
                                <select name="grievance_category" id="grievance_category" class="form-control" required>
                                <option value=''>Select</option>
                                    <option value=0>Pending</option>
                                    <option value=1>Processing</option>
                                    <option value=2>Completed</option>
                                </select>
                            </div>
        <div class="col-md-3 fm">

        
                                <label class="form-label" for="example-select">District Name</label>
                                <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk()">
                                    <?php echo $district_name_list; ?>
                                </select>
                            </div>
                            <div class="col-md-3 fm">
                                <label class="form-label" for="example-select">Taluk Name</label>
                                <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
                                    <?php echo $taluk_name_list ?>
                                </select>
                            </div>
                            <div class="col-md-3 fm">
                                <label class="form-label" for="example-select">Hostel Name</label>
                                <select class="select2 form-control" id="hostel_name" name="hostel_name">
                                    <?php echo $hostel_name_list ?>
                                </select>
                            </div>
                            <div class="col-md-3 fm">
                                <label class="form-label" for="example-select">Academic Year</label>
                                <select class="select2 form-control" id="academic_year" name="academic_year">
                                    <?php echo $academic_year; ?>
                                </select>
                            </div>
                            <div class="col-md-3 fm">
                                <button type="button" class="btn btn-primary" for="example-select" onclick="go()">GO</label></button>
                            </div><br>
                                                </div>
                                                </div>
                                                <br>

                 
                       
                        
    <div class="row">
    <div class="col-12">
    <div class="card">
    <div class="card-body">
    <table id="grievance_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Date</th>
                                        <th>Grievance Id</th>
                                        <th>Grievance Category</th>
                                        <th>Grievance Description</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <tr>
                                        <td>1</td>
                                        <td>10-01-2024</td>
                                        <td>Test</td>
                                        <td style="color:blue;">Pending</td>
                                        <td>
                                            <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                                            <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>18-01-2024</td>
                                        <td>Demo</td>
                                        <td style="color:green;">Completed</td>
                                        <td>

                                            <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                                            <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>3</td>
                                        <td>02-02-2024</td>
                                        <td> Description</td>
                                        <td style="color:red;">Cancelled</td>
                                        <td>

                                            <a href="javascript: void(0);" class="btn btn-success ms-2"><i class=" uil-edit"></i></a>
                                            <a href="javascript: void(0);" class="btn btn-danger ms-2"><i class="uil-trash-alt"></i></a>
                                        </td> -->
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

<script>
        function taluk() {
            var district_name = $('#district_name').val();
            var data = "district_name=" + district_name + "&action=district_name";
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function(data) {
                    if (data) {
                        $("#taluk_name").html(data);
                    }
                }
            });
        }
        function get_hostel() {
            var taluk_name = $('#taluk_name').val();
            var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function(data) {
                    if (data) {
                        $("#hostel_name").html(data);
                    }
                }
            });
        }
    </script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#demo');
</script>
