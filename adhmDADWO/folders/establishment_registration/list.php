<?php


    
        $taluk_name = $_SESSION["taluk_id"];
    
        $district_name = $_SESSION["district_id"];

        $hostel_name = $_SESSION["hostel_id"];


    $district_name_list     = district_name($district_name);
    $district_name_list     = select_option($district_name_list, "Select District",$district_name);

    $taluk_name_list        = taluk_name('',$district_name);
    $taluk_name_list        = select_option($taluk_name_list,"Select Taluk",$taluk_name_list);

//     $from_taluk_name_list = taluk_name('',$district_name);
// $from_taluk_name_list = select_option($from_taluk_name_list,"Select From Taluk",$from_taluk_name);

    $hostel_name_list       = hostel_name();
    $hostel_name_list       = select_option_host($hostel_name_list, "Select Hostel",$hostel_name_list);

        // $hostel_options = hostel_name();
        // $hostel_name_options = select_option($hostel_options, "Select Hostel");

    $establishment_type_options   = establishment_type();
    $establishment_type_options   = select_option($establishment_type_options,"Select Designation");

    $academic_year          = academic_year();
    $academic_year          = select_option_acc($academic_year, "Select Academic Year");

    $establishment_reject_list = establishment_reject_reason();
    $establishment_reject_options = select_option($establishment_reject_list, 'Select Reason');

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
                        <h4 class="page-title">Establishment</h4>
                    </div>
                </div>
            </div>
            <div class="row mb-2">

<div class="col-md-3 fm">
    <label for="example-select" class="form-label">Academic Year:</label>
    <!-- <input type ="text" readonly name="amc_name" id="amc_name" class="form-control" value="<?php echo $amc_name_list; ?>"> -->

    <select name="academic_year" id="academic_year" class="select2 form-control" disabled required>
        <?php echo $academic_year; ?>
    </select>
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">District Name</label>
    <!-- <input type="text" id="district_name" name="district_name" value="<?php echo $_SESSION["staff_"];?>" onchange="taluk()"> -->
    <select name="district_name" id="district_name" class="select2 form-control" disabled  required>
    <?php echo  $district_name_list;?>
    </select>
  
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">Taluk Name</label>
    <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
        <?php echo $taluk_name_list;?>
    </select>
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">Hostel Name</label>
    <select class="select2 form-control" id="hostel_name" name="hostel_name">
        <?php echo $hostel_name_list;?>
    </select>
</div>
<div class="col-md-3 fm">
    <label class="form-label" for="example-select">Designation</label>
    <select class="select2 form-control" id="department_new" name="department_new">
        <?php echo $establishment_type_options; ?>
    </select>
</div>

<div class="col-md-3 fm mt-3">
    <div class="page-title-right">
        <form class="d-flex">
            <buttont type="button" class="btn btn-primary" onclick="go_staff_filter()">Go</button>
        </form>
    </div>
</div>
</div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <table id="establishment_registration_datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>S.no</th>
                                            <th>Staff Name</th>
                                            <th>Designation</th>
                                            <th>District</th>
                                            <th>Taluk </th>
                                            <th>Hostel Name </th>
                                            <th>Action</th>
                                            <th>Action Taken Date</th>
                                            <th>View</th>
                                            
                                            <!-- <th>Action</th> -->
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
</div>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script>
   


   
$(document).on('click', '.accept-btn', function () {
        var uniqueId = $(this).data('unique-id'); // Get the unique ID
        var acceptButton = $(this); // Reference to the clicked accept button
        var ajaxUrl = sessionStorage.getItem("folder_crud_link"); // Get the AJAX URL

        if (!uniqueId || !ajaxUrl) {
            console.error("Missing uniqueId or AJAX URL");
            return;
        }

        // First AJAX request to accept the record
        $.ajax({
            type: "POST",
            url: ajaxUrl,
            data: {
                uniqueId: uniqueId,
                action: "at_accept"
            },
            success: function (data) {
                try {
                    var obj = JSON.parse(data); // Parse the data

                    if (obj.msg === "success") {
                        acceptButton.hide(); // Hide the accept button
                        acceptButton.closest('td').html('Accepted'); // Update the status to "Accepted"

                        // Second AJAX request to get the accepted data
                        $.ajax({
                            type: "POST",
                            url: ajaxUrl,
                            data: {
                                uniqueId: uniqueId,
                                action: "get_data"
                            },
                            success: function (data) {
                                try {
                                    var obj = JSON.parse(data); // Parse the data

                                    if (obj.status) {
                                        var firstRecord = obj.data[0];

                                        var sendUrl = "https://nallosaims.tn.gov.in/adw_biometric/folders/establishment_registration/crud.php";

                                        // Third AJAX request to insert the data into another table
                                        $.ajax({
                                            type: "POST",
                                            url: sendUrl,
                                            data: {
                                                data: firstRecord,
                                                action: "insert_data"
                                            },
                                            success: function (status) {
                                                try {
                                                    var obj = JSON.parse(ststus); // Parse the data

                                                    if (!obj.status) {
                                                        console.error("Data insertion failed", obj);
                                                    }
                                                } catch (err) {
                                                    console.error("Error parsing insert_data data", err);
                                                }
                                            },
                                            error: function (xhr, status, error) {
                                                console.error("Error during insert_data request", error);
                                            }
                                        });
                                    } else {
                                        console.error("Failed to retrieve data", obj);
                                    }
                                } catch (err) {
                                    console.error("Error parsing get_data data", err);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Error during get_data request", error);
                            }
                        });

                        log_sweetalert_approval("saved", ""); // Show a success alert
                         init_datatable('establishment_registration_datatable', form_name, 'datatable'); // Reinitialize the DataTable

                    } else {
                        console.error("Failed to accept record", obj);
                    }
                } catch (err) {
                    console.error("Error parsing at_accept data", err);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error during at_accept request", error);
            }
        });
    });

    
    $(document).on('click', '.reject-btn', function () {
    
        
        var uniqueId = $(this).data('unique-id');
       
        
    
    
        var reasonTextBox = `<br><br>
			<select class="reason-selectbox form-select">
				<?php echo $establishment_reject_options ?>
			</select>`;
			$(this).parent().append(reasonTextBox);
		var rejectButton = $(this);
		rejectButton.replaceWith('<button class="confirm-reject-btn" data-unique-id="' + uniqueId + '">Confirm Reject</button>');
    });
    
    // Event listener for confirm reject button
    $(document).on('click', '.confirm-reject-btn', function () {
       
        var uniqueId = $(this).data('unique-id');

        var reason = $(this).siblings('.reason-selectbox').val();
       
        var rejectButton = $(this); // Store reference to confirm reject button
    
        var ajax_url = sessionStorage.getItem("folder_crud_link");
       if(reason){

       
            var data = {
               
                "uniqueId": uniqueId,
              
               
                "reason": reason,
                "action": "at_reject"
            }
    
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: data,
                success: function (data) {
                    if (data) {
                        rejectButton.hide(); // Hide confirm reject button
                        rejectButton.closest('td').html('Rejected'); // Show status as "Rejected"
                        log_sweetalert_approval("rejected", "");
                        init_datatable('establishment_registration_datatable',form_name,'datatable');
                    }
                }
            });
        }else{
            log_sweetalert_approval("fill_reason", "");

        }
        
    });
    
    
    function log_sweetalert_approval(msg = '', url = '') {
        switch (msg) {
            case "saved":
                Swal.fire({
                    icon: 'success',
                    title: 'Approved Successfully',
                    showConfirmButton: true,
                    timer: 2000,
                    willClose: () => {
                        if (url !== '') {
                            window.location = url;
                        }
                    }
                });
                break;
    
            case "rejected":
                Swal.fire({
                    icon: 'warning',
                    title: 'Rejected !!',
                    showConfirmButton: true,
                    timer: 2000,
                    willClose: () => {
                        if (url !== '') {
                            window.location = url;
                        }
                    }
                });
                break;

                case "fill_reason":
                Swal.fire({
                    icon: 'info',
                    title: 'Select Reason',
                    showConfirmButton: true,
                    timer: 2000,
                    willClose: () => {
                        if (url !== '') {
                            window.location = url;
                        }
                    }
                });
                break;
    
                
        }
    }
    
    
</script>
