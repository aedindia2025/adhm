<?php

$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

?>
<style>
   a.btn-action.specl2 {
    background: #0095cc;
    border: 1px solid #0095cc;
    font-size: 13px;
    text-align: center;
    border-radius: 4px;
    color: #fff;
    font-weight: 600;
    padding: 2px 10px;
}

 .load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
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
                        <div class="page-title-right">
                            
                        </div>
                        <h4 class="page-title">User Insertion</h4>
                    </div>
                </div>
            </div>
            <input type="hidden" id="district_id" name="district_id" value="<?php echo $_SESSION['district_id'];?>">
           
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
			<div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>
                            <table id="user_insertion_datatable" class="table dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <th>Hostel ID</th>
                                        <th>Hostel Name</th>
                                        <th>Total Approval Count</th>
                                        <th>Total Registered Count</th>
                                        <th>Total Unregistered Count</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

