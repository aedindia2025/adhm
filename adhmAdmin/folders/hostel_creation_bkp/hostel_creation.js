$(document).ready(function () {
	// var table_id 	= "hostel_name_datatable";
	init_datatable(table_id,form_name,action);
	get_hostel_location_type();
	get_urban_type();
get_ownership();
});

var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");

var form_name 		= 'Hostel Creation';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'hostel_creation_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	
	var table = $("#"+table_id);
	var district_name = $('#district_id').val();
	var taluk_name = $('#taluk_id').val();

	var data 	  = {
		"district_name" : district_name,
		"taluk_name" : taluk_name,
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
	dom: 'Blfrtip',
	searching: false,
	buttons: [{
		extend: 'copyHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Hostel Creation'
	},
	{
		extend: 'csvHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Hostel Creation',
		filename: 'hostel_creation'
	},
	{
		extend: 'excelHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Hostel Creation',
		filename: 'hostel_creation'
	},
	{
		extend: 'pdfHtml5',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Hostel Creation',
		filename: 'hostel_creation'
	},
	{
		extend: 'print',
		exportOptions: {
			columns: ':not(:last-child)'
		},
		title: 'Hostel Creation'
	}
	]
	});
}

function hostel_creation_cu(unique_id = "") {
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

	var hostel_name 		= document.getElementById('hostel_name').value;
    var hostel_id 			= document.getElementById('hostel_id').value;
    var district_name 		= document.getElementById('district_name').value;
	var taluk_name 			= document.getElementById('taluk_name').value;
    var special_tahsildar 	= document.getElementById('special_tahsildar').value;
    var assembly_const 		= document.getElementById('assembly_const').value;
	var parliment_const 	= document.getElementById('parliment_const').value;
    var address 			= document.getElementById('address').value;
    var hostel_location 	= document.getElementById('hostel_location').value;
	var urban_type 			= document.getElementById('urban_type').value;
    var corporation 		= document.getElementById('corporation').value;
    var municipality 		= document.getElementById('municipality').value;
	var town_panchayat 		= document.getElementById('town_panchayat').value;
    var block_name 			= document.getElementById('block_name').value;
    var village_name 		= document.getElementById('village_name').value;

	var hostel_type 		= document.getElementById('hostel_type').value;
    var gender_type 		= document.getElementById('gender_type').value;
    var yob 				= document.getElementById('yob').value;
	var sanctioned_strength = document.getElementById('sanctioned_strength').value;
    var distance_btw_phc 	= document.getElementById('distance_btw_phc').value;
    var phc_name 			= document.getElementById('phc_name').value;
	var distance_btw_ps 	= document.getElementById('distance_btw_ps').value;
    var ps_name 			= document.getElementById('ps_name').value;
    var staff_count 		= document.getElementById('staff_count').value;
	var file_name 			= document.getElementById('file_name').value; 
	var csrf_token 			= document.getElementById('csrf_token').value;
	var unique_id 			= document.getElementById('unique_id').value;
	var is_active 			= document.getElementById('is_active').value;
	// var rental_reason		= document.getElementById('rental_reason').value;
	// var hostel_status		= document.getElementById('hostel_status').value;
	// var building_status		= document.getElementById('building_status').value;
	var hybrid_hostel		= document.getElementById('hybrid_hostel').value;

	var image_s = document.getElementById("test_file");
	
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

if(file){
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

	if((image_s != '' || file_name !='') && hostel_name != '' && hostel_id != '' && district_name != '' && special_tahsildar != '' && assembly_const != '' && parliment_const != '' && address != '' && hostel_location != '' && hostel_type != '' && gender_type != '' && yob != '' && sanctioned_strength != '' && distance_btw_phc != '' && phc_name != '' && distance_btw_ps != '' && ps_name != '' && staff_count != '' && hybrid_hostel != ''){

    var actions = "createupdate";


	data.append("hostel_name", hostel_name);
	data.append("hostel_id", hostel_id);
	data.append("district_name", district_name);
	data.append("taluk_name", taluk_name);
	data.append("special_tahsildar", special_tahsildar);
	data.append("assembly_const", assembly_const);
	data.append("parliment_const", parliment_const);
	data.append("address", address);
	data.append("hostel_location", hostel_location);
	data.append("hostel_name", hostel_name);
	data.append("urban_type", urban_type);
	data.append("corporation", corporation);
	data.append("municipality", municipality);
	data.append("town_panchayat", town_panchayat);
	data.append("block_name", block_name);
	data.append("village_name", village_name);
	data.append("hostel_type", hostel_type);
	data.append("gender_type", gender_type);
	data.append("yob", yob);
	data.append("sanctioned_strength", sanctioned_strength);
	data.append("distance_btw_phc", distance_btw_phc);
	data.append("phc_name", phc_name);
	data.append("distance_btw_ps", distance_btw_ps);
	data.append("ps_name", ps_name);
	data.append("staff_count", staff_count);
	data.append("is_active", is_active);
	data.append("csrf_token", csrf_token);
	data.append("hybrid_hostel", hybrid_hostel);
	// data.append("hostel_status", hostel_status);
	// data.append("rental_reason", rental_reason);
	// data.append("building_status", building_status);

	data.append("unique_id", unique_id);
	data.append("action", actions);


        var ajax_url = sessionStorage.getItem("folder_crud_link");
		var media_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/hostel_creation/crud.php";

        var url      = sessionStorage.getItem("list_link");

		// if((image_s != '') || (file_name !='')){
        // console.log(data);
        $.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},
			success		: function(response) {
				var obj     = JSON.parse(response);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				var app_unique_id = obj.data?.unique_id; // Get unique_id from application server
	
					if (status) {
						if (msg !== "already") {
							// Forward to media server if successful
							            data.append("unique_id", app_unique_id);
            
            // Append all the necessary data from the first FormData object
           // for (var pair of data.entries()) {
             //   mediaData.append(pair[0], pair[1]);
            //}

							$.ajax({
								type: "POST",
								url: media_url,
								data: data,
								contentType: false,
								processData: false,

								success: function (data) { 
									console.log("Operation synced with media server.");
								},
								error: function () {
									console.error("Failed to sync with media server.");
								}
							});
						}
	
						sweetalert(msg, url);
					} else {
						$(".createupdate_btn").text("Error");
						console.error(error);
					}
	
					$(".createupdate_btn").removeAttr("disabled");
					$(".createupdate_btn").text(unique_id ? "Update" : "Save");
				},
				error: function () {
					alert("Network Error");
				}
			});
		} else {
			sweetalert("form_alert");
		}
	}


function hostel_creation_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var media_url = "https://nallosaims.tn.gov.in/adw_biometric/folders/hostel_creation/crud.php";

	var url      = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();


	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
				"csrf_token" : csrf_token,
				"action"		: "delete"
			}

			$.ajax({
				type 	: "POST",
				url 	: ajax_url,
				data 	: data,
				success : function(response) {

					var obj     = JSON.parse(response);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;

					if (status) {
                        // Forward to media server if successful
                        $.ajax({
                            type: "POST",
                            url: media_url,
                            data: data,
                            success: function () {
                                console.log("Operation synced with media server.");
                            },
                            error: function () {
                                console.error("Failed to sync with media server.");
                            }
                        });

                        init_datatable(table_id, form_name, action);
                    } else {
                        console.error(error);
                    }

                    sweetalert(msg, url);
                },
                error: function () {
                    alert("Network Error");
                }
            });
        }
    });
}

function get_taluk_name() {

	var district_name = $("#district_name").val();


	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	
	if (district_name) {
		var data = {
			"district_name": district_name,
			"action": "get_taluk_name"
		}

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
}


function get_taluk(){
    

	var district_name = $('#district_id').val();
	
	var data = "district_id=" + district_name + "&action=district_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk_id").html(data);
			}
		}
	});
	
	}
	 
	
	function get_hostel(){
	var taluk_name = $('#taluk_id').val();
	
	var data = "taluk_id=" + taluk_name + "&action=get_hostel_by_taluk_name";
	
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
	
	
			if (data) {
				$("#hostel_id").html(data);
			}
		}
	});
	
	}

	function go_filter() {

		// var student_id = $('#student_id').val();
		var district_name = $('#district_id').val();
		// var current_date = $('#current_date').val();
	
		var taluk_name = $('#taluk_id').val();

	
	
	
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var data = {
			// "student_id"	: student_id,
			"district_name": district_name,
			"taluk_name": taluk_name,
			"action": 'datatable',
	
		};
	// alert(data);
	
		init_datatable(table_id,form_name,action,data);
	
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

function get_hostel_location_type(){
	
	var hostel_location = $("#hostel_location").val();
	
	var urbanFieldsType = document.getElementById("urbanFieldsType");
	var ruralFieldsTypeBlock = document.getElementById("ruralFieldsTypeBlock");
	var ruralFieldsTypeVillage = document.getElementById("ruralFieldsTypeVillage");

	var corporationField = document.getElementById("corporationField");
	var municipalityField = document.getElementById("municipalityField");
	var town_panchayatField = document.getElementById("town_panchayatField");

	// if(unique_id==''){
	
	if(hostel_location == 2){
		//urban
		urbanFieldsType.style.display = "block";
		ruralFieldsTypeBlock.style.display = "none";
		ruralFieldsTypeVillage.style.display = "none";
		// $("#urban_type").val(null).trigger("change");
		$("#block_name").val(null).trigger("change");
		$("#village_name").val(null).trigger("change");

	}
	else if(hostel_location == 1){
		//rural
		ruralFieldsTypeBlock.style.display = "block";
		ruralFieldsTypeVillage.style.display = "block";
		urbanFieldsType.style.display 		= "none";
		corporationField.style.display 		= "none";
		municipalityField.style.display 	= "none";
		town_panchayatField.style.display 	= "none";
		$("#urban_type").val(null).trigger("change");
		// $("#block_name").val(null).trigger("change");
		// $("#village_name").val(null).trigger("change");
		$('#municipality').val(null).trigger("change");
		$('#corporation').val(null).trigger("change");
		$('#town_panchayat').val(null).trigger("change");

	}
// }

}

function get_urban_type(){
	var urban_type = $("#urban_type").val();
	var corporationField = document.getElementById("corporationField");
	var municipalityField = document.getElementById("municipalityField");
	var town_panchayatField = document.getElementById("town_panchayatField");
	

	if(urban_type == 1){
		//corporation
		corporationField.style.display 		= "block";
		municipalityField.style.display 	= "none";
		town_panchayatField.style.display 	= "none";
		$('#town_panchayat').val(null).trigger("change");
		$('#municipality').val(null).trigger("change")

	}
	else if(urban_type == 2){
		//municipality
		corporationField.style.display 		= "none";
		municipalityField.style.display 	= "block";
		town_panchayatField.style.display 	= "none";
		$('#town_panchayat').val(null).trigger("change");
		$('#corporation').val(null).trigger("change")
	}
	else if(urban_type == 3){
		//town_panchayat
		corporationField.style.display 		= "none";
		municipalityField.style.display 	= "none";
		town_panchayatField.style.display 	= "block";
		$('#corporation').val(null).trigger("change");
		$('#municipality').val(null).trigger("change")
	}
}


function taluk() {

	var district_id = $('#district_id').val();


	var data = "district_id=" + district_id + "&action=district_id";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			
			if (data) {
				$("#taluk_id").html(data);

			}
		}
	});
}

function get_ownership() {

	var selectElement = document.getElementById('hostel_status');
    var selectedValue = selectElement.value; // Gets the value of the selected option
    var selectedText = selectElement.options[selectElement.selectedIndex].text; // Gets the label of the selected option
	
	// alert(selectedText);
	
	var rental_reason = document.getElementById("rental_div");
		
	if(selectedText === "Rented" || selectedText === "Rental" || selectedText === "Rent"){

		rental_reason.style.display = "block";

	}else{
		rental_reason.style.display = "none";
	}
}