$(document).ready(function () {
    get_holiday_details();
    get_application_count();
    get_notification_details();
    get_vacancy_count();
    applied_leave_details();
    get_bio_reg_count();
    get_attendance_details();
    get_hostel_location_type();
    get_urban_type();


    //    ticket_Filter();
    // get_task_details();

    // get_top_most_completed();
    // get_top_most_complaints();
    // registered_complaints();
    // top_most_completed();
    // overall_complaint_status();
    // sourcewise_complaints();
});
function get_month_details() {

    get_region_details();
    get_top_most_completed();
    get_top_most_complaints();
    registered_complaints();
    top_most_completed();

}
function ticket_Filter() {
    init_datatable(table_id, form_name, action);
}

document.addEventListener("DOMContentLoaded", function () {
    var comStatus = sessionStorage.getItem("com_status");
    console.log(comStatus);

    if (comStatus == "0") {
        $('#completionModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#completionModal").modal("show");

    }

});


function get_region_details() {
    $("#loading-image").show();
    var month = $("#month_filter").val();
    var user_type_unique_id = $('#user_type_unique_id').val();
    ajax_url;
    var data =
    {
        "action": "region_details",
        "user_type_unique_id": user_type_unique_id,

        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    alert(ajax_url);
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#region_details_div').html(data);
        }
    });
}



function applied_leave_details() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "applied_leave_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            var no_of_days = obj.no_of_days;
            var student_name = obj.student_name;
            // alert(student_name);
            if (student_name != null) {

                $('#no_of_student').html(student_name);
            }
            else {
                $('#no_of_student').html('0');
            }

            // $('#no_of_student').html(data);




        }
    });
}


function get_bio_reg_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var hostel_id = $("#hostel_id").val();
    var data = {
        "action": "get_reg_cnt",
        "hostel_name": hostel_id
    }

    $.ajax({
        type: "POST",
        url: 'https://nallosaims.tn.gov.in/adw_biometric/folders/biometric_list/crud.php',
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var reg_cnt = obj.reg_cnt;



            $('#bio_reg_cnt').html(reg_cnt);

            // $('#cancel_comp').html(cancel_comp);
        }
    });
}

function get_holiday_details() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    //     var fil_date = $("#filter_date").val();
    //    alert(fil_date);

    var data = {
        // "region_name":region_name,
        // "user_type_unique_id":user_type_unique_id,
        // "branch_name" :branch_name,
        // "cate" : cate,
        // "branch_id" : branch_id,
        "action": "holiday_details"
    }
    // task_details

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var holiday_data = obj.holiday_details;
            var date = obj.date;
            var holiday = obj.holiday;

            var holiday_li = "";
            Object.keys(holiday_data).forEach(function (key, index) {
                var iClassName = index % 2 === 0 ? "text-primary" : "link-danger";
                var dClassName = index % 3 === 0 ? "text-primary" : (index % 3 === 1 ? "link-success" : "link-danger");


                // holiday_li += '<li class=" mb-1  psd"><p class=" mb-1 font-13"><i class="mdi mdi-calendar"></i>' + holiday_data[key].date +
                // '</p><h5 class="'+ h5ClassName +'">'+ holiday_data[key].holiday +'</h5></li>';
                holiday_li += '<div class="align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center"><div class="flex-shrink-0 me-2"><h4 class="' + iClassName + '"><i class="uil-calender widget-icon  bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my-0"><h5 class="' + dClassName + '">' + holiday_data[key].holiday + '</h5></h5></div><p class="mb-0 fw-semibold"></p>' + holiday_data[key].date + '</div></div>';

                $('#holiday_list').html(holiday_li);

            });


        }
    });
}


function new_external_window_print_new(event, url, status) {

    var link = url;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover', 'height=650,width=1050,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}





function get_notification_details() {
    //  alert('hi');   
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    //     var fil_date = $("#filter_date").val();
    //    alert(fil_date);

    var data = {
        // "region_name":region_name,
        // "user_type_unique_id":user_type_unique_id,
        // "branch_name" :branch_name,
        // "cate" : cate,
        // "branch_id" : branch_id,
        "action": "notification_details"
    }
    // task_details

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var notification_data = obj.notification_details;
            var date = obj.date;
            var title = obj.title;
            var content = obj.content;



            var notification_li = "";
            Object.keys(notification_data).forEach(function (key, index) {
                var h5ClassName = index % 2 === 0 ? "text-primary" : "link-danger";
                var dClassName = index % 3 === 0 ? "text-primary" : (index % 3 === 1 ? "link-success" : "link-danger");
                // var h5ClassName = index === 3 ? "text-primary" : "link-green";

                notification_li += '<div class="align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center br-noti"><div class="flex-shrink- me-1"><i class="mdi mdi-information-outline widget-icon"></i> </div><div class="flex-grow" style="margin-left:2px;"><h5 class="fw-semibold my-0 "><h4 class="' + h5ClassName + '"><i class=" bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my margin-left:20px;"><h5 class="' + dClassName + '">' + notification_data[key].title + '</h5></h5> </div><p class="mb-0 fw-semibold"> ' + notification_data[key].date + ' </p></div> <div class="noti-des"><p class="mb-0">' + notification_data[key].content + '</p></div></div>';

                // $('#notification_list').html(notification_li);


                // notification_li += '<div class=" align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center "><div class="flex-shrink-0 me-2"><h4 class="'+ h5ClassName +'"><i class="uil-calender widget-icon  bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my-0"><h5 class="'+ dClassName +'">'+ notification_data[key].date+'</h5></h5></div><p class="mb-0 fw-semibold"></p>'+ notification_data[key].title +'</div></div>'+ notification_data[key].content +'</h5></li>';;

                $('#notification_list').html(notification_li);

            });


        }
    });
}


function get_top_most_completed() {
    var month = $("#month_filter").val();
    var data =
    {
        "action": "top_most_completed",
        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#top_most_completed').html(data);
        }
    });
}

function get_top_most_complaints() {
    var month = $("#month_filter").val();
    var data =
    {
        "action": "top_most_complaints",
        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#top_most_complaints').html(data);
        }
    });
}


function get_application_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "get_application_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var applied_cnt = obj.applied_cnt;
            var accp_cnt = obj.accp_cnt;
            var approved_cnt = obj.approved_cnt;
            var rejected_cnt = obj.rejected_cnt;


            $('#appl_cnt').html(applied_cnt);
            $('#accp_cnt').html(accp_cnt);
            $('#appr_cnt').html(approved_cnt);
            $('#rej_cnt').html(rejected_cnt);
            // $('#cancel_comp').html(cancel_comp);
        }
    });
}


function get_attendance_details() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "get_attendance_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var total_strength = obj.total_strength;
            var present = obj.present;
            var absent = obj.absent;



            $('#total_strength').html(total_strength);
            $('#present').html(present);
            $('#absent').html(absent);

        }
    });
}


function get_vacancy_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "get_vacancy_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var tot_cap = obj.tot_cap;
            var old_std = obj.old_std;
            var approved_cnt = obj.approved_cnt;
            var hos_vac = obj.hos_vac;


            $('#tot_cap').html(tot_cap);
            $('#old_std').html(old_std);
            $('#new_std').html(approved_cnt);
            $('#hos_vac').html(hos_vac);
            // $('#cancel_comp').html(cancel_comp);
        }
    });
}



function sourcewise_complaints() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "sourcewise_complaints"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var app = obj.app;
            var web = obj.web;
            var admin_portal = obj.admin_portal;
            var chatbot = obj.chatbot;

            $('#web').html(web);
            $('#admin').html(admin_portal);
            $('#chatbot').html(chatbot);
            $('#app').html(app);

        }
    });
}

function new_external_window_print(event, url, status) {

    var link = url + '?status=' + status;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}


function openCameraWindow1() {
    window.open('folders/dashboard/camera.php', '_blank', 'width=500,height=600');
}

function openCameraWindow2() {
    window.open('folders/dashboard/camera1.php', '_blank', 'width=500,height=600');
}

function openCameraWindow3() {
    window.open('folders/dashboard/camera2.php', '_blank', 'width=500,height=600');
}

function setCapturedImage1(base64Image) {
    // Set to preview
    // alert(base64Image);
    const preview = document.getElementById('entrance_preview');
    preview.src = base64Image;

    // Store in hidden input
    document.getElementById('entrance_image').value = base64Image;
    // fetchLocation();
}

function setCapturedImage2(base64Image) {
    // Set to preview
    const preview = document.getElementById('dining_preview');
    preview.src = base64Image;

    // Store in hidden input
    document.getElementById('dining_image').value = base64Image;
    // fetchLocation();
}

function setCapturedImage3(base64Image) {
    // Set to preview
    const preview = document.getElementById('building_preview');
    preview.src = base64Image;

    // Store in hidden input
    document.getElementById('building_image').value = base64Image;
    // fetchLocation();
}

function set_coords(latitude, longitude, address) {


    document.getElementById("latitude").value = latitude;
    document.getElementById("longitude").value = longitude;
    document.getElementById("address").value = address;


}


function get_assembly() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_assembly"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#assembly_const").html(data);
                }
            }
        });
    }
}

function get_parliament() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_parliament"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#parliment_const").html(data);
                }
            }
        });
    }
}
function get_block() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_block"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#block_name").html(data);
                }
            }
        });
    }
}
function get_village() {

    var block_name = $("#block_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (block_name) {
        var data = {
            "block_name": block_name,
            "action": "get_village"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#village_name").html(data);
                }
            }
        });
    }
}
function get_corporation() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_corporation"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#corporation").html(data);
                }
            }
        });
    }
}
function get_municipality() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_municipality"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#municipality").html(data);
                }
            }
        });
    }
}
function get_town_panchayat() {

    var district_name = $("#district_name").val();

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_town_panchayat"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#town_panchayat").html(data);
                }
            }
        });
    }
}

function get_hostel_location_type() {

    var hostel_location = $("#hostel_location").val();

    var urbanFieldsType = document.getElementById("urbanFieldsType");
    var ruralFieldsTypeBlock = document.getElementById("ruralFieldsTypeBlock");
    var ruralFieldsTypeVillage = document.getElementById("ruralFieldsTypeVillage");

    var corporationField = document.getElementById("corporationField");
    var municipalityField = document.getElementById("municipalityField");
    var town_panchayatField = document.getElementById("town_panchayatField");

    if (hostel_location == 2) {
        //urban
        urbanFieldsType.style.display = "block";
        ruralFieldsTypeBlock.style.display = "none";
        ruralFieldsTypeVillage.style.display = "none";
        $("#block_name").val(null).trigger("change");
        $("#village_name").val(null).trigger("change");

    }
    else if (hostel_location == 1) {
        //rural
        ruralFieldsTypeBlock.style.display = "block";
        ruralFieldsTypeVillage.style.display = "block";
        urbanFieldsType.style.display = "none";
        corporationField.style.display = "none";
        municipalityField.style.display = "none";
        town_panchayatField.style.display = "none";
        $("#urban_type").val(null).trigger("change");
        $('#municipality').val(null).trigger("change");
        $('#corporation').val(null).trigger("change");
        $('#town_panchayat').val(null).trigger("change");

    }

}

function get_urban_type() {
    var urban_type = $("#urban_type").val();
    var corporationField = document.getElementById("corporationField");
    var municipalityField = document.getElementById("municipalityField");
    var town_panchayatField = document.getElementById("town_panchayatField");


    if (urban_type == 1) {
        //corporation
        corporationField.style.display = "block";
        municipalityField.style.display = "none";
        town_panchayatField.style.display = "none";
        $('#town_panchayat').val(null).trigger("change");
        $('#municipality').val(null).trigger("change")

    }
    else if (urban_type == 2) {
        //municipality
        corporationField.style.display = "none";
        municipalityField.style.display = "block";
        town_panchayatField.style.display = "none";
        $('#town_panchayat').val(null).trigger("change");
        $('#corporation').val(null).trigger("change")
    }
    else if (urban_type == 3) {
        //town_panchayat
        corporationField.style.display = "none";
        municipalityField.style.display = "none";
        town_panchayatField.style.display = "block";
        $('#corporation').val(null).trigger("change");
        $('#municipality').val(null).trigger("change")
    }
}

function get_ownership() {

    var building_status = $("#building_status").val();


    var rental_reason = document.getElementById("rental_div");

    if (building_status === "66fa42230e30690883" || building_status === "66fa42230e30690885") {

        rental_reason.style.display = "block";

    } else {
        rental_reason.style.display = "none";
    }
}

function get_special_tahsildar() {

    var district_name = $("#district_name").val();


    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    if (district_name) {
        var data = {
            "district_name": district_name,
            "action": "get_special_tahsildar"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#special_tahsildar").html(data);
                }
            }
        });
    }
}



function hostel_update(unique_id = "") {
    var internet_status = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

    var special_tahsildar = document.getElementById('special_tahsildar').value;
    var assembly_const = document.getElementById('assembly_const').value;
    var parliment_const = document.getElementById('parliment_const').value;
    var hostel_location = document.getElementById('hostel_location').value;
    var urban_type = document.getElementById('urban_type').value;
    var corporation = document.getElementById('corporation').value;
    var municipality = document.getElementById('municipality').value;
    var town_panchayat = document.getElementById('town_panchayat').value;
    var block_name = document.getElementById('block_name').value;
    var village_name = document.getElementById('village_name').value;
    var yob = document.getElementById('yob').value;
    var distance_btw_phc = document.getElementById('distance_btw_phc').value;
    var phc_name = document.getElementById('phc_name').value;
    var distance_btw_ps = document.getElementById('distance_btw_ps').value;
    var ps_name = document.getElementById('ps_name').value;
    var staff_count = document.getElementById('staff_count').value;
    var file_name = document.getElementById('file_name').value;
    var latitude = document.getElementById('latitude').value;
    var longitude = document.getElementById('longitude').value;
    var unique_id = document.getElementById('unique_id').value;
    var entrance_image = document.getElementById('entrance_image').value;
    var dining_image = document.getElementById('dining_image').value;
    var building_image = document.getElementById('building_image').value;
    var address = document.getElementById('address').value;

    var file_val = document.getElementById("test_file").value;

    var data = new FormData();

    var image_s = $("#test_file");

    var files = document.getElementById('test_file').files;

    const fileInput = document.getElementById('test_file');
    const file = fileInput.files[0];


    const allowedFileTypes = [
        'image/jpeg', 'image/png', 'image/gif', // Images
        'application/pdf',                     // PDF
        'application/msword',                  // DOC
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX
        'application/vnd.ms-excel',            // XLS
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // XLSX
    ];
    const maxFileSize = 5 * 1024 * 1024; // 5MB

    if (file) {
        if (!allowedFileTypes.includes(file.type)) {
            sweetalert('invalid_ext');
            return false;
        }
    }

    if (image_s != '') {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i;  // Regular expression for allowed extensions
        for (var i = 0; i < image_s.length; i++) {

            data.append("test_file", document.getElementById('test_file').files[i]);
        }
    }
    else {
        data.append("test_file", '');
    }



    if (special_tahsildar != '' && assembly_const != '' && parliment_const != '' && hostel_location != '' && yob != '' && distance_btw_phc != '' && phc_name != '' && distance_btw_ps != '' && ps_name != '' && staff_count != '' && entrance_image != '' && dining_image != '' && building_image != '') {

        var actions = "hostel_update";

        data.append("special_tahsildar", special_tahsildar);
        data.append("assembly_const", assembly_const);
        data.append("parliment_const", parliment_const);
        data.append("hostel_location", hostel_location);
        data.append("hostel_name", hostel_name);
        data.append("urban_type", urban_type);
        data.append("corporation", corporation);
        data.append("municipality", municipality);
        data.append("town_panchayat", town_panchayat);
        data.append("block_name", block_name);
        data.append("village_name", village_name);
        data.append("yob", yob);
        data.append("distance_btw_phc", distance_btw_phc);
        data.append("phc_name", phc_name);
        data.append("distance_btw_ps", distance_btw_ps);
        data.append("ps_name", ps_name);
        data.append("staff_count", staff_count);
        data.append("latitude", latitude);
        data.append("longitude", longitude);
        data.append("entrance_image", entrance_image);
        data.append("dining_image", dining_image);
        data.append("building_image", building_image);
        data.append("address", address);

        data.append("unique_id", unique_id);
        data.append("action", actions);

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");
        $('#submitBtn').prop('disabled', true);

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',

            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;
                var status = obj.status;
                var error = obj.error;
                if (status === "Success") {
                    sweetalert(msg, "");
                    $('#submitBtn').prop('disabled', false);
                    $.ajax({
                        url: ajax_url,
                        type: 'POST',
                        data: { action: 'checkHostelFields', unique_id: unique_id },
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                const d = response.data;
                                const location = d.hostel_location;
                                const urban_type = d.urban_type;

                                const isEmpty = (val) => val === null || val === '';

                                // Common fields to validate
                                let fieldsToCheck = [
                                    'special_tahsildar',
                                    'assembly_const',
                                    'parliment_const',
                                    'hostel_location',
                                    'yob',
                                    'distance_btw_phc',
                                    'phc_name',
                                    'distance_btw_ps',
                                    'ps_name',
                                    'staff_count',
                                    'go_attach_org_name',
                                    'go_attach_file'
                                ];

                                if (location === '1') {
                                    fieldsToCheck.push('block_name', 'village_name');
                                    // urban area fields are excluded
                                } else if (location === '2') {
                                    fieldsToCheck.push('urban_type');
                                    if (urban_type === '1') fieldsToCheck.push('corporation');
                                    else if (urban_type === '2') fieldsToCheck.push('municipality');
                                    else if (urban_type === '3') fieldsToCheck.push('town_panchayat');
                                    // exclude block_name, village_name
                                }

                                let allFilled = true;
                                for (let field of fieldsToCheck) {
                                    if (isEmpty(d[field])) {
                                        allFilled = false;
                                        break;
                                    }
                                }

                                if (allFilled) {
                                    $.ajax({
                                        url: ajax_url,
                                        type: 'POST',
                                        data: {
                                            action: 'updateCompletionStatus',
                                            unique_id: unique_id
                                        },
                                        success: function (res) {
                                            // Remove sessionStorage only after update is successful
                                            sessionStorage.removeItem('com_status');
                                            window.location.reload();
                                        },
                                        error: function () {
                                            console.log("Error updating completion status.");
                                        }
                                    });
                                }
                            } else {
                                console.log('Field check error:', response.msg);
                            }
                        },
                        error: function (err) {
                            console.log('AJAX field check error', err);
                        }
                    });
                }
                else if (status === "error") {
                    sweetalert(error);
                    console.log(error);
                    $('#submitBtn').prop('disabled', false);
                }
                else if (msg === "form_alert") {
                    sweetalert("form_alert");
                    $('#submitBtn').prop('disabled', false);
                }
                // sweetalert(msg, url);
            },
            error: function (data) {
                alert("Network Error");
                $('#submitBtn').prop('disabled', false);
            }
        });

    } else {
        sweetalert("form_alert");
    }
}

function startCamera() {
    // Check if getUserMedia is available
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        // Show the video element and hide the "No image" text
        document.getElementById("video").style.display = "block";
        document.getElementById("no_image_text").style.display = "none";
        document.getElementById("captureBtn").style.display = "inline-block";

        // Access the webcam
        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                let videoDevices = devices.filter(device => device.kind === 'videoinput');

                if (videoDevices.length === 0) {
                    alert("No camera found on your device.");
                    return;
                }

                // Select the first available video device (if more than one camera is present)
                let videoDevice = videoDevices[0];

                // If multiple devices are present (like front and back cameras on mobile), you can select the desired one
                console.log('Available video devices:', videoDevices);

                // Use the selected camera device to stream the video
                return navigator.mediaDevices.getUserMedia({
                    video: { deviceId: videoDevice.deviceId }
                });
            })
            .then(function (stream) {
                // Success: Attach the video stream to the video element
                document.getElementById("video").srcObject = stream;
            })
            .catch(function (err) {
                // Error handling
                console.error("Error accessing webcam: ", err);
                if (err.name === 'NotAllowedError') {
                    alert("Camera access denied. Please enable camera permissions.");
                    // Optionally, you can open the permissions window for most browsers:
                    openPermissions();
                } else if (err.name === 'NotFoundError') {
                    alert("No camera found. Please make sure a camera is connected.");
                    openPermissions();
                } else {
                    alert("Could not access the camera. Please check your browser settings.");
                    openPermissions();
                }
            });
    } else {
        alert("Your browser does not support camera access.");
    }
}

// Function to help users go to the camera permissions directly (works in most browsers)
function openPermissions() {
    // Open the camera permissions window directly for most modern browsers
    const permissionsLink = 'chrome://settings/content/camera'; // For Chrome
    const browser = window.navigator.userAgent.toLowerCase();

    if (browser.indexOf('chrome') > -1) {
        alert("Opening camera permissions for Chrome.");
        window.location.href = permissionsLink;
    } else if (browser.indexOf('firefox') > -1) {
        alert("Opening camera permissions for Firefox.");
        // Firefox uses its settings page for camera permissions
        window.location.href = 'about:preferences#privacy';
    } else {
        alert("Please check your browser's permissions settings for the camera.");
    }
}


// Capture the image from the video feed
function captureImage() {
    const video = document.getElementById("video");
    const canvas = document.getElementById("canvas");
    const context = canvas.getContext("2d");
    const imagePreview = document.getElementById("image_preview");

    // Set canvas dimensions to match the video feed
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    // Draw the current frame from the video feed onto the canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Convert the canvas to an image
    const imageData = canvas.toDataURL("image/png");

    // Hide the video and show the captured image
    video.style.display = "none";
    imagePreview.style.display = "block";
    imagePreview.src = imageData;

    // Optionally, stop the video stream
    const stream = video.srcObject;
    const tracks = stream.getTracks();
    tracks.forEach(track => track.stop());

    // Hide the capture button after image capture
    document.getElementById("captureBtn").style.display = "none";
}
