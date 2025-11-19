<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);

$amc_name_list = academic_year($amc_name_list);
$amc_name_list = select_option_acc($amc_name_list);

?>

<div class="content-page">

    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                                <!-- <?php echo btn_add($btn_add); ?> -->
                            </form>
                        </div>
                        <h4 class="page-title">Career Guidence</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                        <div class="row">
                            <table id="carrier_guidance_datatable" class="table dt-responsive w-100">
                                <thead>
                                    <tr>
                                        <th>S.no</th>
                                        <!-- <th>Date</th> -->
                                        <th>Topic Name</th>
                                        <th>Social Media</th>
                                        <th>Image</th>
                                        <th>Document</th>
                                        <th>video</th>
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