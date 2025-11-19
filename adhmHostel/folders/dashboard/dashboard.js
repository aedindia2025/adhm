$(document).ready(function () {

    //    ticket_Filter();
    get_applied_count();
    get_accept_count();
    get_approved_count();
    get_rejected_count();
    get_dropout_count();
    get_total_hostels();
    get_total_students();
    get_total_staff();
    student_applied_leave_details();
    staff_applied_leave_details();
    hostel_vaccancy();
    district_wise_count();
    get_total_staffs();
    get_students_attendance();
    get_staff_attendance();
    get_face_and_finger();
    get_biometric_details();
    //    renderProductChart();
    get_application_count();


});
function get_month_details() {

    get_region_details();
    get_top_most_completed();
    get_top_most_complaints();
    registered_complaints();
    top_most_completed();

}

function get_application_count() {

    get_applied_count();
    get_accept_count();
    get_approved_count();
    get_rejected_count();
    get_dropout_count();
    get_total_students();
    get_total_hostels();
    get_total_staffs();
    get_total_staff();
    hostel_vaccancy()

}

function ticket_Filter() {
    init_datatable(table_id, form_name, action);
}


function get_applied_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "get_applied_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var applied_cnt = obj.applied_cnt;


            $('#appl_cnt').html(applied_cnt);
        }
    });
}

function get_accept_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {


        "action": "get_accept_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var accp_cnt = obj.accp_cnt;

            $('#accp_cnt').html(accp_cnt);
        }
    });
}

function get_approved_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {


        "action": "get_approved_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var approved_cnt = obj.approved_cnt;

            $('#appr_cnt').html(approved_cnt);
        }
    });
}

function get_rejected_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {


        "action": "get_rejected_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var rejected_cnt = obj.rejected_cnt;

            $('#rej_cnt').html(rejected_cnt);
        }
    });
}

function get_dropout_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {


        "action": "get_dropout_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var dropout = obj.dropout_cnt;

            $('#dropout_cnt').html(dropout);
        }
    });
}

function get_taluk() {

    var district_name = $('#district_name').val();

    var data = "district_name=" + district_name + "&action=district_name";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
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
        success: function (data) {


            if (data) {
                $("#hostel_name").html(data);
            }
        }
    });

}

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
    // alert(ajax_url);
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

function get_task_details() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    var region_name = $("#region_name").val();
    var user_type_unique_id = $('#user_type_unique_id').val();
    var branch_name = $('#branch_name').val();

    var cate = $('#cate').val();
    var branch_id = $('#branch_id').val();
    $('#opening_complaints').empty();
    $('#new_complaints').empty();
    $('#completed_complaints').empty();
    $('#pending_complaints').empty();
    var data = {
        "region_name": region_name,
        "user_type_unique_id": user_type_unique_id,
        "branch_name": branch_name,
        "cate": cate,
        "branch_id": branch_id,
        "action": "task_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var pending_complaints = obj.pending_complaints;


            if (pending_complaints == null) {

                var pending_count = '0';
            } else {
                var pending_count = obj.pending_complaints;
            }

            var opening_complaints = obj.opening_complaints;

            if (opening_complaints == null) {

                var opening_count = 0;
            } else {
                var opening_count = obj.opening_complaints;
            }

            var new_complaints = obj.new_complaints;

            if (new_complaints == null) {
                var new_count = 0;
            } else {
                var new_count = new_complaints;
            }

            var completed_complaints = obj.completed_complaints;

            if (completed_complaints == null) {
                var completed_count = 0;
            } else {
                var completed_count = completed_complaints;
            }

            $('#opening_complaints').html(opening_count);
            $('#new_complaints').html(new_count);
            $('#completed_complaints').html(completed_count);
            $('#pending_complaints').html(pending_count);
        }
    });
}

function overall_complaint_status() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "over_complaint_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var total_comp = obj.total_comp;
            var pending_comp = obj.pending_comp;
            var progressing_comp = obj.progressing_comp;
            var completed_comp = obj.completed_comp;
            var cancel_comp = obj.cancel_comp;

            $('#pending_comp').html(pending_comp);
            $('#progressing_comp').html(progressing_comp);
            $('#completed_comp').html(completed_comp);
            $('#total_comp').html(total_comp);
            $('#cancel_comp').html(cancel_comp);
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
function get_total_hostels() {

    var url = sessionStorage.getItem("list_link")

    var data =
    {

        "action": "total_hostels",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var hostel_count = obj.hostel_count;
            //    alert(hostel_count);
            $('#total_hostel').text(hostel_count);
            $('#total_hostel_1').text(hostel_count);
        }
    });
}

function get_total_students() {


    var url = sessionStorage.getItem("list_link")

    var data =
    {

        "action": "total_students",

    };

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var student_cnt = obj.student_name;

            $('#total_students').text(student_cnt);
        }
    });
}

function hostel_vaccancy() {


    var url = sessionStorage.getItem("list_link")

    var data =
    {

        "action": "hostel_vaccancy",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var hostel_vaccancy = obj.hostel_vaccancy;

            $('#total_hostel_vaccancy').text(hostel_vaccancy);
        }
    });
}


function get_total_staff() {


    var url = sessionStorage.getItem("list_link")

    var data =
    {

        "action": "total_staff_strength",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var staff_cnt = obj.staff_cnt;

            $('#total_staff').text(staff_cnt);
        }
    });
}

function student_applied_leave_details() {
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
            // var no_of_days        = obj.no_of_days;
            var no_of_student_name = obj.no_of_student_name;

            $('#no_of_student_name').html(no_of_student_name);


        }
    });
}

function staff_applied_leave_details() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "staff_applied_leave_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            // var no_of_days        = obj.no_of_days;
            var staff_name = obj.staff_name;

            $('#staff_name').html(staff_name);


        }
    });
}

// function district_wise_count()  
// {
//     var ajax_url = sessionStorage.getItem("folder_crud_link");
//     var url      = sessionStorage.getItem("list_link");


//     var data = {

//                 "action"    : "district_wise_count"
//             }

//             $.ajax({
//                 type    : "POST",
//                 url     : ajax_url,
//                 data    : data,
//                 success : function(data) 
//                 {
//                     var obj     = JSON.parse(data);
//                     var data          = obj.data;
//                     // var no_of_days        = obj.no_of_days;
//                     var district_names = obj.district_names;
//                     alert(district_names);

//                     $('#district_names').html(district_names);


//                 }
//             });
// }

// function district_wise_count() {
//     var ajax_url = sessionStorage.getItem("folder_crud_link");

//     var data = {
//         "action": "district_wise_count"
//     };

//     $.ajax({
//         type: "POST",
//         url: ajax_url,
//         data: data,
//         success: function(data) {
//             var obj = JSON.parse(data);
//             var data = obj.data;
//             var district_names = obj.district_names;

//             // Clear any existing content
//             // $('#hostel_name').empty();

//             // Color classes to use
//             var colors = ["text-primary", "text-danger", "text-success", "text-warning", "text-secondary", "text-info", "text-dark"];

//             // Iterate over the data and dynamically create the elements
//             for (var i = 0; i < data.length; i++) {
//                 var hostel = data[i];
//                 var colorClass = colors[i % colors.length]; // Cycle through colors

//                 // Create the HTML structure
//                 var p = $('<p></p>');
//                 var icon = $('<i class="mdi mdi-square"></i>').addClass(colorClass);
//                 var name = $('<span></span>').text(district_names);
//                 var count = $('<span class="float-end"></span>').text(hostel.registered_count);

//                 // Append the elements
//                 p.append(icon).append(' ').append(name).append(count);
//                 $('#hostel_name').append(district_names);
//             }
//         }
//     });
// }

function district_wise_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (!ajax_url) {
        console.error("AJAX URL is missing");
        return;
    }

    var data = {
        "action": "district_wise_count"
    };

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (response) {
            console.log("AJAX request successful");
            console.log(response);

            var obj;
            try {
                obj = JSON.parse(response);
            } catch (e) {
                console.error("Error parsing JSON response:", e);
                return;
            }

            var district_names = obj.district_names;
            var reg_district = obj.reg_district;
            if (!Array.isArray(district_names) || !Array.isArray(reg_district) || district_names.length !== reg_district.length) {
                console.error("district_names or reg_district is not valid");
                return;
            }

            // drawBarChart(district_names, reg_district);
            // Clear any existing content
            $('#district_names').empty();
            // $('.dropdown-menu').empty();

            // Color classes to use
            var colors = ["text-primary", "text-danger", "text-success", "text-warning", "text-secondary", "text-info", "text-dark"];

            // Iterate over the data and dynamically create the elements
            for (var i = 0; i < district_names.length; i++) {
                var district_name = district_names[i];
                var count = reg_district[i];

                var colorClass = colors[i % colors.length]; // Cycle through colors

                // // Create the dropdown item
                // var dropdownItem = $('<a class="dropdown-item"></a>');
                // dropdownItem.text(district_name + ' (' + count + ')');
                // dropdownItem.addClass(colorClass);
                // $('.dropdown-menu').append(dropdownItem);

                // Create the list item
                var listItem = $('<p></p>');
                var icon = $('<i class="mdi mdi-square"></i>').addClass(colorClass);
                var name = $('<span></span>').text(district_name);
                var countSpan = $('<span class="float-end"></span>').text(count);
                listItem.append(icon).append(' ').append(name).append(countSpan);
                $('#district_names').append(listItem);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed:", textStatus, errorThrown);
        }
    });
}

// Call the function to load the data
$(document).ready(function () {
    district_wise_count();
});

// function drawBarChart(labels, data) {
//     var ctx = document.getElementById('my_Chart').getContext('2d');
//     var myChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: labels,
//             datasets: [{
//                 label: 'District-wise Count',
//                 data: data,
//                 backgroundColor: 'rgba(54, 162, 235, 0.2)',
//                 borderColor: 'rgba(54, 162, 235, 1)',
//                 borderWidth: 1
//             }]
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             }
//         }
//     });
// }



// Call the function to load the data


function new_external_window_print(event, url, status) {

    var district_name = $('#district_name').val();

    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
    var link = url + '?status=' + status + '&district_name=' + district_name + '&taluk_name=' + taluk_name + '&hostel_name=' + hostel_name;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}

function new_external_window_print_new(event, url) {

    var link = url;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover','height=650,width=1050,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}

function get_total_staffs() {
    total_warden_strength();
    total_warden_incharge_strength();
    total_cook_strength();
    total_cook_deputation_strength();
    total_watchman_strength();
    total_watchman_deputation_strength();
    total_sweeper_strength();
    total_sweeper_deputation_strength();
}

function total_warden_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_warden_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var warden_cnt = obj.warden_count;

            $('#warden_cnt').html(warden_cnt);
        }
    });
}
function total_warden_incharge_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_warden_incharge_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var warden_inc_cnt = obj.warden_incharge_count;

            $('#warden_inc_cnt').html(warden_inc_cnt);
        }
    });
}
function total_cook_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_cook_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var cook_cnt = obj.cook_count;

            $('#cook_cnt').html(cook_cnt);
        }
    });
}
function total_cook_deputation_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_cook_deputation_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var cook_dep_cnt = obj.cook_deputation_count;

            $('#cook_dep_cnt').html(cook_dep_cnt);
        }
    });
}
function total_watchman_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_watchman_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var watchman_cnt = obj.watchman_count;

            $('#watchman_cnt').html(watchman_cnt);
        }
    });
}
function total_watchman_deputation_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_watchman_deputation_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var watchman_dep_cnt = obj.watchman_deputation_count;

            $('#watchman_dep_cnt').html(watchman_dep_cnt);
        }
    });
}
function total_sweeper_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_sweeper_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var sweeper_cnt = obj.sweeper_count;

            $('#sweeper_cnt').html(sweeper_cnt);
        }
    });
}
function total_sweeper_deputation_strength() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {

        "action": "total_sweeper_deputation_strength"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var sweeper_dep_cnt = obj.sweeper_deputation_count;

            $('#sweeper_dep_cnt').html(sweeper_dep_cnt);
        }
    });
}

function get_students_attendance() {


    var url = sessionStorage.getItem("list_link");

    var from_date = $("#std_att_from_date").val();
    // var to_date = $("#std_att_to_date").val();

    var data =
    {
        "action": "student_attendance",
        "from_date": from_date,
        // "to_date": to_date
    };

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var student_cnt = obj.student_name;

            $('#std_app_count').text(student_cnt);
        }
    });
}

function get_staff_attendance() {

    var url = sessionStorage.getItem("list_link");

    var from_date = $("#stf_att_from_date").val();
    // var to_date = $("#stf_att_to_date").val();

    var data =
    {
        "action": "staff_attendance",
        "from_date": from_date,
        // "to_date": to_date
    };

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var staff_name = obj.staff_name;

            $('#stf_app_count').text(staff_name);
        }
    });
}

function get_face_and_finger() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {
        "action": "get_face_and_finger"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var face_reg = obj.face_reg;
            var face_not_reg = obj.face_not_reg;
            var finger_reg = obj.finger_reg;
            var finger_not_reg = obj.finger_not_reg;


            $('#std_face_reg').html(face_reg);
            $('#std_face_not_reg').html(face_not_reg);
            $('#std_finger_reg').html(finger_reg);
            $('#std_finger_not_reg').html(finger_not_reg);
        }
    });
}

function get_biometric_details() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var data = {
        "action": "get_biometric_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var std_pushed = obj.std_pushed;
            var students_approved = obj.students_approved;

            $('#std_pushed').html(std_pushed);
            $('#students_approved').html(students_approved);
        }
    });
}