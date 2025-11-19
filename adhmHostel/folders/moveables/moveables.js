$(document).ready(function () {
    init_datatable(table_id, form_name, action);
    onloader();
    other_brand();
    equipment_type();
    get_asset_name();

    var tabType = getQueryParam('tab_type');  // Get the 'tab_type' from URL
    
    if (tabType === 'digital') {
        $('a[href="#basictab2"]').tab('show'); 
        $('a[href="#basictab1"]').tab('hide'); // Activate the Digital tab
    } else if (tabType === 'kitchen') {
        $('a[href="#basictab1"]').tab('show'); 
        $('a[href="#basictab2"]').tab('hide'); // Activate the Kitchen tab
    }
    
});
// Function to read query parameters
function getQueryParam(param) {
    var urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}



var form_name = 'Feedback';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'moveables_datatable';
var table_id_sub = 'moveables_sub_datatable';
var table_id_sub1 = 'moveables_digit_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
    var table = $("#" + table_id);
    var list_type = $('#list_type').val();
    var list_category = $('#list_category').val();
    // var list_asset = $('#list_asset').val();
    var data = {
        "list_type": list_type,
        "list_category": list_category,
        // "list_asset": list_asset,
        "action": action
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    var datatable = table.DataTable({
        destroy: true,
        searching: false,
        "paging": true,
        "ordering": true,
        "info": false,
        "ajax": {
            url: ajax_url,
            type: "POST",
            data: data
        },
        dom: 'Bfrtip',
        searching: false,
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: 'Moveables'
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: 'Moveables',
                filename: 'moveables'
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: 'Moveables',
                filename: 'moveables'
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: 'Moveables',
                filename: 'moveables'
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)'
                },
                title: 'Moveables'
            }
        ]

    });
}

// function moveable_kitchen() {
//     $('#totalqty').empty();
//     $('#totalamount').empty();
//     var internet_status = is_online();
//     var unique_id = $("#unique_id").val();
//     var screen_unique_id = $("#screen_unique_id").val();
//     var category = $("#category").val();
//     var asset = $("#asset").val();
//     var capacity = $("#capacity").val();
//     var unit = $("#unit").val();
//     var p_year = $("#p_year").val();
//     // var kitchen_sub_id = $("#kitchen_sub_id").val();

//     var is_form = form_validity_check("was-validated");

//     if (is_form) {
//         // var data = $(".was-validated").serialize();
//         var data = "p_year=" + p_year + "&capacity=" + capacity + "&asset=" + asset + "&category=" + category + "&unit="+ unit+ "&screen_unique_id=" + screen_unique_id + "&action=moveable_kitchen";
//         var ajax_url = sessionStorage.getItem("folder_crud_link");
//         var url = '';

//         $.ajax({
//             type: "POST",
//             url: ajax_url,
//             data: data,
//             // cache: false,
//             // contentType: false,
//             // processData: false,
//             method: 'POST',

//             success: function (data) {


//                 // $("#btn").text("Add");
//                 $("#category").val(null).trigger('change');
//                 $("#asset").val(null).trigger('change');
//                 $("#capacity").val("");
//                 $("#unit").val(null).trigger('change');
//                 $("#p_year").val("");
//                 // moveables_sub_datatable("moveables_sub_datatable", '', "moveables_sub_datatable");

//                 // alert(data);
//                 var obj = JSON.parse(data);
//                 var msg = obj.msg;

//                 var status = obj.status;
//                 var error = obj.error;
//                 sweetalert(msg);
//                 if (!status) {
//                     url = '';
//                     $(".btn").text("Error");
//                     console.log(error);
//                 } else {

//                     // $(".btn").text("Add");
//                     // $("#item_name").val(null).trigger('change');
//                     // $("#qty").val("");
//                     // $("#unit").val(null).trigger('change');
//                     // $("#rate").val("");
//                     // $("#amount").val("");
//                 }
//                 // moveables_sub_datatable("moveables_sub_datatable",'',"moveables_sub_datatable");

//             },
//             error: function (data) {
//                 alert("Network Error");
//             }
//         });


//     } else {
//         sweetalert("form_alert");
//     }
//     clearFormField();
// }

function moveable_kitchen() {
    $('#totalqty').empty();
    $('#totalamount').empty();

    // Retrieve form values
    var unique_id = $("#unique_id").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var category = $("#category").val();
    var asset = $("#asset").val();
    var capacity = $("#capacity").val();
    var unit = $("#unit").val();
    var big_small = $("#big_small").val();
    var p_year = $("#p_year").val();

    // Check form validity
    var is_form_valid = form_validity_check("was-validated1");

    // alert(unique_id);
    // alert(screen_unique_id);
    // alert(category);
    // alert(asset);
    // alert(capacity);
    // alert(unit);
    // alert(big_small);
    // alert(p_year);
    // alert(is_form_valid);


    if (is_form_valid) {
        // Serialize form data and append additional parameters
        var data = "p_year=" + p_year + "&capacity=" + capacity + "&asset=" + asset + "&category=" + category + "&unit=" + unit + "&big_small=" + big_small + "&screen_unique_id=" + screen_unique_id + "&unique_id=" + unique_id + "&action=moveable_kitchen";

        // Get the AJAX URL from session storage
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");
        // Perform AJAX request to check if asset already exists
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json', // Expect JSON response
            success: function (response) {
                if (response.status && response.msg === "already_exists") {
                    // Show popup
                    Swal.fire({
                        title: 'Entry Already Exists',
                        // text: 'An entry with this asset and screen_unique_id already exists. Do you want to continue?',
                        text: 'An entry with this asset already exists. Would you like to update the capacity?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, continue!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // update the entry popup
                            // updateEntry();
                            // alert("working");
                            $('#capacityModal').modal('show');
                        }
                    });
                } else {

                    // saveEntry();

                    // Continue saving the entry if no existing entry found
                    //
                }
                var msg = response.msg;
                sweetalert(msg, url);

            },
            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error(xhr.responseText);
                alert("Network Error: " + error);
            }
        });
    } else {
        sweetalert("form_alert");
    }
}

function saveEntry() {
    // Perform the actual save operation here
    // Retrieve form values
    var unique_id = $("#unique_id").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var category = $("#category").val();
    var asset = $("#asset").val();
    var capacity = $("#capacity").val();
    var unit = $("#unit").val();
    var p_year = $("#p_year").val();

    // Serialize form data and append additional parameters
    var data = "p_year=" + p_year + "&capacity=" + capacity + "&asset=" + asset + "&category=" + category + "&unit=" + unit + "&screen_unique_id=" + screen_unique_id + "&action=moveable_kitchen";

    // Get the AJAX URL from session storage
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    // Perform AJAX request to save the entry
    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        dataType: 'json',
        success: function (response) {
            console.log(response.data);

            if (response.data) {
                // $("#btn").text("Add");
                $("#category").val(null).trigger('change');
                $("#asset").val(null).trigger('change');
                $("#capacity").val("");
                $("#unit").val(null).trigger('change');
                $("#p_year").val("");
                moveables_sub_datatable("moveables_sub_datatable", '', "moveables_sub_datatable");
                // Handle success
            } else {
                // Handle error if response.data is not valid
                console.log(response.data.message);
            }

            // Handle the message from the original response
            var msg = response.data.msg;
            var status = response.data.status;
            var error = response.data.error;
            // alert(status);

            if (status != 'success') {
                $(".btn").text("Error");
                console.log(error);
            } else {
                // Reset or update UI elements
                $(".btn").text("Add");
                $("#category").val(null).trigger('change');
                $("#asset").val(null).trigger('change');
                $("#capacity").val("");
                $("#unit").val(null).trigger('change');
                $("#p_year").val("");
            }

            // Optionally, call additional functions here
            // stock_in_add_update();
        },
        error: function (xhr, status, error) {
            // Handle AJAX error
            console.error(xhr.responseText);
            alert("Network Error: " + error);
        }
    });
}



function updateEntry() {
    // Perform the actual save operation here
    // Retrieve form values
    var asset = $("#asset").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var newCapacity = $("#newCapacity").val();
    alert("screen Unique: " + screen_unique_id);
    alert("asset: " + asset);
    alert("New Capacity: " + newCapacity);
    // Serialize form data and append additional parameters
    // Get the AJAX URL from session storage
    var data = "newCapacity=" + newCapacity + "&asset=" + asset + "&screen_unique_id=" + screen_unique_id + "&action=update_kitchen";
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    // Perform AJAX request to save the entry
    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response.data) {
                alert("updated");
                // $('#capacityModal').modal('hidden');
                // $("#btn").val("Add");
                // $("#category").val(null).trigger('change');
                // $("#asset").val(null).trigger('change');
                // $("#capacity").val("");
                // $("#unit").val(null).trigger('change');
                // $("#p_year").val("");
                // $("#newCapacity").val("");
                moveables_sub_datatable("moveables_sub_datatable", '', "moveables_sub_datatable");
                // Handle success
            } else {
                // Handle error if response.data is not valid
                console.log(response.data.message);
            }

            // Handle the message from the original response
            var msg = response.data.msg;
            var status = response.data.status;
            var error = response.data.error;

            if (status != 'success') {
                $(".btn").text("Error");
                console.log(error);
            } else {
                // Reset or update UI elements
                $(".btn").text("Add");
                $("#category").val(null).trigger('change');
                $("#asset").val(null).trigger('change');
                $("#capacity").val("");
                $("#unit").val(null).trigger('change');
                $("#p_year").val("");
            }

            // Optionally, call additional functions here
            // stock_in_add_update();
        },
        error: function (xhr, status, error) {
            // Handle AJAX error
            console.error(xhr.responseText);
            console.log("Network Error: " + error);
        }
    });
}

function moveable_add_update() {
    // $('#totalqty').empty();
    // $('#totalamount').empty();
    var internet_status = is_online();
    var unique_id = $("#unique_id").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var hostel_id = $("#hostel_id").val();

    var is_form = form_validity_check("was-validated");

    if (is_form) {

        var data = $(".was-validated").serialize();
        data += "&unique_id=" + unique_id + "&hostel_id=" + hostel_id + "&screen_unique_id=" + screen_unique_id + "&action=moveable_add_update";

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,

            method: 'POST',

            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;

                var status = obj.status;
                var error = obj.error;

                if (msg == "more_than") {
                    sweetalert(msg, url);

                }
                if (!status) {
                    url = '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
                } else {
                    if (msg == "already") {
                        // Button Change Attribute
                        url = '';

                        $(".createupdate_btn").removeAttr("disabled", "disabled");
                        if (unique_id) {
                            $(".createupdate_btn").text("Update");
                        } else {
                            $(".createupdate_btn").text("Save");
                        }
                    }
                }

                sweetalert(msg, url);
                init_datatable("moveables_datatable", '', "datatable");
                // moveables_sub_datatable("moveables_sub_datatable",'',"moveables_sub_datatable");
            },
            error: function (data) {
                alert("Network Error");
            }
        });

        //         if (!status) {
        //             url = '';
        //             $(".btn").text("Error");
        //             console.log(error);
        //         } else {

        //             // $(".btn").text("Add");
        //             // $("#item_name").val(null).trigger('change');
        //             // $("#qty").val("");
        //             // $("#unit").val(null).trigger('change');
        //             // $("#rate").val("");
        //             // $("#amount").val("");


        //         }

        //         moveables_sub_datatable("moveables_sub_datatable",'',"moveables_sub_datatable");

        //     },
        //     error: function (data) {
        //         alert("Network Error");
        //     }
        // });

    } else {
        sweetalert("form_alert");
    }
}

// function moveables_sub_datatable(table_id_sub='',form_name='',action='') {
//     // stock_tot_qty_amt();
//     var table = $("#"+table_id_sub);
//     var screen_unique_id = $("#screen_unique_id").val();

//     var data 	  = {
//         "action"	: action, 
//         "screen_unique_id"	: screen_unique_id, 
//     };
//     var ajax_url = sessionStorage.getItem("folder_crud_link");

//     var datatable = table.DataTable({
//         destroy: true,
//         "searching": false,
//         "paging": false,
//         "ordering": false,
//         "info": false,
//     "ajax"		: {
//         url 	: ajax_url,
//         type 	: "POST",
//         data 	: data
//     }
//     // "columnDefs": [
//     //     // Example: aligning first and third columns to the left
//     //     { "targets": [0, 2,4,5], "className": "dt-right" },
//     //     { "targets": [6], "className": "dt-center" }
//     //     // Adjust column indices as needed
//     // ]
//         // dom: 'Bfrtip',
//         // buttons: [
//         // 	'copy', 'csv', 'excel', 'pdf', 'print'
//         // ]
//     });
// }

function kitchen_sub_delete(val) {

    var data = "id=" + val + " &action=sub_delete";

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = '';

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        method: 'POST',
        success: function (data) {


            var obj = JSON.parse(data);
            var msg = obj.msg;
            var data = obj.data;
            var status = obj.status;
            var error = obj.error;

            if (msg == "success_delete") {
                sweetalert(msg);
                moveables_sub_datatable("moveables_sub_datatable", '', "moveables_sub_datatable");
                // location.reload();

            }
        }
    });
}

function get_unit_name(asset = "") {
    if (asset) {
        var ajax_url = sessionStorage.getItem("folder_crud_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: {
                asset: asset,
                action: "get_unit_name"
            },
            success: function (res) {
                console.log(res);

                var details = JSON.parse(res);

                $("#unit").val(details['unit']);
                $("#small").val(details['capacity']);

                if (details['capacity'].toLowerCase() === 'big/small') {
                    $(".big-small").show();
                    $("#big_small").prop('required', true);  // Make the field required
                } else {
                    $(".big-small").hide();
                    $("#big_small").prop('required', false); // Remove the required attribute
                }
            }
        
        });
    }
}

function big_small(asset = "") {
    if (asset) {
        var ajax_url = sessionStorage.getItem("folder_crud_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: {
                asset: asset,
                action: "get_capacity_name"
            },
            success: function (res) {
                console.log(res);

                var details = JSON.parse(res);

                $("#big_small").val(details['big_small']);
            }
        });
    }
}

function get_asset_name() {

    // alert();
    var category = $('#category').val();
    var asset = $('#asset').val();

    var data = "category=" + category + "&asset=" + asset + "&action=get_asset_name";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data) {
                $("#asset").html(data);
            }
        }
    });

}

// function digital_equipment() {
//     $('#totalqty').empty();
//     $('#totalamount').empty();
//     var internet_status = is_online();
//     var unique_id = $("#unique_id").val();
//     var screen_unique_id = $("#screen_unique_id").val();
//     var digital_category = $("#digital_category").val();
//     var type_of_equipment = $("#type_of_equipment").val();
//     var no_of_dev = $("#no_of_dev").val();
//     var location_dev = $("#location_dev").val();
//     var spe_devices = $("#spe_devices").val();
//     var other_brand = $("#other_brand").val();
//     var brand = $("#brand").val();
//     var size = $("#size").val();
//     var procurement_year = $("#procurement_year").val();
//     var cableConnection = $('input[name="customRadio1"]:checked').val();

//     // alert(unique_id);
//     // alert(screen_unique_id);
//     // alert(digital_category);
//     // alert(type_of_equipment);
//     // alert(no_of_dev);
//     // alert(location_dev);
//     // alert(spe_devices);
//     // alert(other_brand);
//     // alert(brand);
//     // alert(size);
//     // alert(procurement_year);
//     // alert(cableConnection);

//     // alert(cableConnection);
//     // alert(type_of_equipment);
//     // alert(digital_category);
//     // alert(unique_id);
//     // alert("is_form_before");
//     var is_form = form_validity_check("was-validated");

//     console.log(is_form);

//     // if(unique_id){
//     //     var action = "moveable_digital";
//     // }else{
//     //     var action = "edit_already_digital_assert";
//     // }
//     if (is_form) {
//         var data = $(".was-validated").serialize();
//         data += "&cableConnection=" + cableConnection + "&brand=" + brand + "&size=" + size + "&procurement_year=" + procurement_year + "&location_dev=" + location_dev + "&no_of_dev=" + no_of_dev + "&type_of_equipment=" + type_of_equipment + "&digital_category=" + digital_category + "&spe_devices=" + spe_devices + "&other_brand=" + other_brand + "&screen_unique_id=" + screen_unique_id + "&unique_id=" + unique_id + "&action=moveable_digital";
//         console.log(data);
//         var ajax_url = sessionStorage.getItem("folder_crud_link");
//         var url = sessionStorage.getItem("list_link");


//         $.ajax({
//             type: "POST",
//             url: ajax_url,
//             data: data,
//             // cache: false,
//             // contentType: false,
//             // processData: false,
//             method: 'POST',


//             success: function (data) {

//                 var obj = JSON.parse(data);
//                 var msg = obj.msg;
//                 var status = obj.status;
//                 var error = obj.error;

//                 if (msg == "not_found") {

//                     saveEntry_digital();

//                 }

//                 // alert(msg);
//                 if (!status) {
//                     url = '';
//                     $(".createupdate_btn").text("Error");
//                     console.log(error);
//                 } else {
//                     if (msg == "already") {
//                         // Button Change Attribute
//                         url = '';

//                         $(".createupdate_btn").removeAttr("disabled", "disabled");
//                         if (unique_id) {
//                             $(".createupdate_btn").text("Update");
//                         } else {
//                             $(".createupdate_btn").text("Save");
//                         }
//                     }
//                 }
//                 // alert(msg);
//                 sweetalert(msg, url);
//             },

//             error: function (data) {
//                 alert("Network Error");
//             }
//         });
//     } else {
//         sweetalert("form_alert");
//     }
//     // clearFormFields();
// }

function digital_equipment() {
    // Retrieve form values
    var unique_id = $("#unique_id").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var digital_category = $("#digital_category").val();
    var type_of_equipment = $("#type_of_equipment").val();
    var no_of_dev = $("#no_of_dev").val();
    var location_dev = $("#location_dev").val();
    var spe_devices = $("#spe_devices").val();
	var loc_dev = $('#loc_dev').val();
    // loc_dev = loc_dev.substring(1);
    // alert(loc_dev);
    var size = $("#size").val();
    var procurement_year = $("#procurement_year").val();
    var cableConnection = $('input[name="customRadio1"]:checked').val();

    if (loc_dev != '') {
		loc_dev = loc_dev;
		// alert(hostel_name +' hello');

	}
    
    // Handle brand selection
    var selectedOption = $('#options').val();
    var brand = '';
    var other_brand = '';

    if (selectedOption === 'others') {
        brand = selectedOption;
        other_brand = $('#othertext').val();
    } else {
        brand = selectedOption;
    }

    // Check form validity
    var is_form_valid = form_validity_check("was-validated2");

    if (is_form_valid) {
        // Serialize form data
        var formData = $(".was-validated2").serializeArray();

        // Convert array to object to avoid duplicate keys
        var formDataObject = {};
        formData.forEach(function(item) {
            if (!formDataObject[item.name]) {
                formDataObject[item.name] = item.value;
            }
        });

        // Add additional fields manually
        formDataObject.cableConnection = cableConnection;
        formDataObject.brand = brand;
        formDataObject.other_brand = other_brand;
        formDataObject.size = size;
        formDataObject.procurement_year = procurement_year;
        // formDataObject.location_dev = location_dev;
        formDataObject.location_dev = loc_dev;
        formDataObject.no_of_dev = no_of_dev;
        formDataObject.type_of_equipment = type_of_equipment;
        formDataObject.digital_category = digital_category;
        formDataObject.spe_devices = spe_devices;
        formDataObject.screen_unique_id = screen_unique_id;
        formDataObject.unique_id = unique_id;
        formDataObject.action = unique_id ? "moveable_digital" : "moveable_digital";  // Adjust action if needed

        // Convert object to query string
        var data = $.param(formDataObject);

        // AJAX URL from session storage
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        // Check if URL is available
        if (!ajax_url) {
            console.error("AJAX URL is missing");
            Swal.fire({
                title: 'Error!',
                text: 'AJAX URL is missing',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }

        // AJAX request
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function(response) {
                console.log("AJAX URL: ", ajax_url);
                console.log("Raw response: ", response);

                if (!response) {
                    console.error("Empty response received");
                    Swal.fire({
                        title: 'Error!',
                        text: 'No response from the server',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                try {
                    var obj = JSON.parse(response);
                    var msg = obj.msg;
                    var status = obj.status;
                    var error = obj.error;

                    if (msg === "not_found") {
                        saveEntry_digital();
                    }

                    if (!status) {
                        $(".createupdate_btn").text("Error");
                        console.log(error);
                    } else {
                        $(".createupdate_btn").removeAttr("disabled");
                        $(".createupdate_btn").text(unique_id ? "Update" : "Save");
                    }

                    sweetalert(msg, url);

                } catch (e) {
                    console.error("Error parsing response: ", e);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to parse server response',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error: ", textStatus, errorThrown);
                Swal.fire({
                    title: 'Network Error!',
                    text: 'Please check your internet connection',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
        
    } else {
        sweetalert("form_alert");
    }
}



function moveables_delete(unique_id = "", firstPart = "") {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    var csrf_token = $("#csrf_token").val();

    confirm_delete('delete')
        .then((result) => {
            if (result.isConfirmed) {

                var data = {
                    "unique_id": unique_id,
                    "firstPart": firstPart,
                    "action": "digital_delete",
                    "csrf_token": csrf_token
                }

                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    success: function (data) {

                        var obj = JSON.parse(data);
                        var msg = obj.msg;
                        var status = obj.status;
                        var error = obj.error;
                        if (msg == "success_delete") {
                            sweetalert(msg);

                        }

                        if (!status) {
                            url = '';

                        } else {
                            init_datatable(table_id_sub1, form_name, action);
                        }
                        sweetalert(msg, url);
                        init_datatable(table_id, form_name, action);
                    }
                });

            } else {
                // alert("cancel");
            }
        });
}


function saveEntry_digital() {
    $('#totalqty').empty();
    $('#totalamount').empty();
    var internet_status = is_online();
    var unique_id = $("#unique_id").val();
    var screen_unique_id = $("#screen_unique_id").val();
    var digital_category = $("#digital_category").val();
    var type_of_equipment = $("#type_of_equipment").val();
    var no_of_dev = $("#no_of_dev").val();
    var location_dev = $("#location_dev").val();
    var spe_devices = $("#spe_devices").val();
    // alert(digital_category);
    // alert(type_of_equipment);

    var brand = $("#brand").val();
    var size = $("#size").val();
    var procurement_year = $("#procurement_year").val();
    var cableConnection = $('input[name="customRadio1"]:checked').val();
    // alert(cableConnection);

    var is_form = form_validity_check("was-validated");

    //    alert('tttttt');
    if (is_form) {
        var data = $(".was-validated").serialize();
        data += "&cableConnection=" + cableConnection + "&brand=" + brand + "&size=" + size + "&procurement_year=" + procurement_year + "&location_dev=" + location_dev + "&no_of_dev=" + no_of_dev + "&type_of_equipment=" + type_of_equipment + "&digital_category=" + digital_category + "&spe_devices=" + spe_devices + "&screen_unique_id=" + screen_unique_id + "&unique_id=" + unique_id + "&action=moveable_digital";
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            // cache: false,
            // contentType: false,
            // processData: false,
            method: 'POST',


            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;
                var status = obj.status;
                var error = obj.error;

                // alert(msg);
                if (!status) {
                    url = '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
                } else {
                    if (msg == "already") {
                        // Button Change Attribute
                        url = '';

                        $(".createupdate_btn").removeAttr("disabled", "disabled");
                        if (unique_id) {
                            $(".createupdate_btn").text("Update");
                        } else {
                            $(".createupdate_btn").text("Save");
                        }
                    }
                }
                alert(msg);
                sweetalert(msg, url);
            },

            error: function (data) {
                alert("Network Error");
            }
        });


    } else {
        sweetalert("form_alert");
    }
}


function equipment_type() {

    // alert('test');
    var digital_category = $('#digital_category').val();
    var type_of_equipment = $('#type_of_equipment').val();
    // alert(digital_category);

    var data = "digital_category=" + digital_category + "&type_of_equipment=" + type_of_equipment + "&action=type_of_equipment";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data) {
                $("#type_of_equipment").html(data);

                // // Check if digital_category is 'tv' or 'computer'
                if (digital_category === '66cc136163d4742652' || digital_category === '66cc136b9023e52900') {
                    $(".sh").show();
                    $(".sz").show();// Show the field
                } else {
                    $(".sh").hide();
                    $(".sz").hide(); // Hide the field
                }
                if (digital_category === '66cc136163d4742652') {
                    $(".ccn").show(); // Show the field
                } else {
                    $(".ccn").hide(); // Hide the field
                }

            }
        }
    });
}

function onloader(){

    var digital_category = $('#digital_category').val();
    // Check if digital_category is 'tv' or 'computer'
    if (digital_category === '66cc136163d4742652' || digital_category === '66cc136b9023e52900') {
        $(".sh").show();
        $(".sz").show();// Show the field
    } else {
        $(".sh").hide();
        $(".sz").hide(); // Hide the field
    }
    if (digital_category === '66cc136163d4742652') {
        $(".ccn").show(); // Show the field
    } else {
        $(".ccn").hide(); // Hide the field
    }

}

function other_brand(){
    var options = $('#options').val();
    if(options == "others"){
        $(".specification").show(); // Show the field
    }else{
        $(".specification").hide(); // Hide the field
    }
}

function new_brand() {
    // alert('test');
    var brand = $('#brand').val();

    if (brand === 'other') {
        $(".ebn").show();// Show the field
    } else {
        $(".ebn").hide(); // Hide the field
    }
}

// function clearFormFields() {
//     // Clear all text inputs
//     document.querySelectorAll('#basictab2 input[type="text"]').forEach(input => {
//         input.value = '';
//     });

//     // Clear all select fields
//     document.querySelectorAll('#basictab2 select').forEach(select => {
//         select.selectedIndex = 0; // Set to the first option or empty selection
//     });

//     // Clear radio buttons
//     document.querySelectorAll('#basictab2 input[type="radio"]').forEach(radio => {
//         radio.checked = false;
//     });

//     // Optionally reset other form elements if needed
// }

function get_list_category_name() {

    var list_type = $('#list_type').val();

    var data = "list_type=" + list_type + "&action=list_category_type";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data) {
                $("#list_category").html(data);
                if (list_type != "") {
                    document.getElementById('list_category').disabled = false;
                } else {
                    document.getElementById('list_category').disabled = true;
                    document.getElementById('list_category').value = '';
                    // document.getElementById('list_asset').disabled = true;
                    // document.getElementById('list_asset').value = '';
                }
            }
        }
    });
}

function get_list_asset_name() {

    // alert();
    var list_type = $('#list_type').val();
    var list_category = $('#list_category').val();
    // alert(list_category);

    var data = "list_type=" + list_type + "&list_category=" + list_category + "&action=list_asset_type";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data) {
                $("#list_asset").html(data);
                // if(list_category != "" && list_type != ""){
                //     document.getElementById('list_asset').disabled = false;
                // }else{
                //     document.getElementById('list_asset').disabled = true;
                //     document.getElementById('list_asset').value = '';
                // }
            }
        }
    });

}

function go_filter() {


    var list_type = $('#list_type').val();
    var list_category = $('#list_category').val();
    // var list_asset = $('#list_asset').val();
    // alert(list_type);
    // alert(list_category);
    // alert(list_asset);


    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var data = {
        "list_type": list_type,
        "list_category": list_category,
        // "list_asset": list_asset,
        "action": 'datatable',
    };


    init_datatable(table_id, form_name, action, data);

}


function get_location() {
	var location_dev = $("#location_dev").val();
	$("#loc_dev").val(location_dev)
}