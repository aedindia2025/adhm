$(document).ready(function () {
	// var table_id 	= "hostel_name_datatable";
	init_datatable(table_id,form_name,action);
	upgrade_datatable('hostel_upgrade_datatable',form_name,'hostel_upgrade_datatable');
	get_hostel_location_type();
	get_urban_type();
	get_ownership();
	get_upgrade_fields();
	
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


function get_upgrade_fields(){

	var hostel_upgrade = $("#hostel_upgrade").val();

	if(hostel_upgrade == 'Yes'){
		document.getElementById('upgrade_fields').style.display = 'inline-flex';
	}else{
		document.getElementById('upgrade_fields').style.display = 'none';

	}
}

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
	dom: 'Bfrtip',
	searching: false,
	responsive: false,
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
	var rental_reason		= document.getElementById('rental_reason').value;
	var ownership		= document.getElementById('ownership').value;
	var building_status		= document.getElementById('building_status').value;
	var hybrid_hostel		= document.getElementById('hybrid_hostel').value;
	var unique_id 			= document.getElementById('unique_id').value;
	if(unique_id){
	var hostel_upgrade 			= document.getElementById('hostel_upgrade').value;
}
	var is_active 			= document.getElementById('is_active').value;

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

	if((image_s != '' || file_name !='') && hostel_name != '' && hostel_id != '' && district_name != '' && special_tahsildar != '' && assembly_const != '' && parliment_const != '' && address != '' && hostel_location != '' && hostel_type != '' && gender_type != '' && yob != '' && sanctioned_strength != '' && distance_btw_phc != '' && phc_name != '' && distance_btw_ps != '' && ps_name != '' && ownership != '' && building_status != '' && staff_count != '' && hybrid_hostel != ''){

    var actions = "createupdate";
// alert(hybrid_hostel);

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
	data.append("hybrid_hostel", hybrid_hostel);
	data.append("ownership", ownership);
	data.append("rental_reason", rental_reason);
	data.append("building_status", building_status);
	data.append("hostel_upgrade", hostel_upgrade);
	data.append("is_active", is_active);
	data.append("csrf_token", csrf_token);

	data.append("unique_id", unique_id);
	data.append("action", actions);

	var media_url = "http://103.186.220.140/adw_biometric/folders/assembly_constituency/crud.php";
        var ajax_url = sessionStorage.getItem("folder_crud_link");
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
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				if(msg == 'no_count'){
					sweetalert(msg);
					url 		= '';
					if (unique_id) {
						$(".createupdate_btn").text("Update");
					} else {
						$(".createupdate_btn").text("Save");
					}
					$(".createupdate_btn").removeAttr("disabled","disabled");
				}else{
					if(msg == "form_alert"){
						sweetalert("form_alert");
					}else{
								
	
					if (!status) {
						url 	= '';
						$(".createupdate_btn").text("Error");
						console.log(error);
					} else {
						if (msg=="already") {
							// Button Change Attribute
							url 		= '';
	
							$(".createupdate_btn").removeAttr("disabled","disabled");
							if (unique_id) {
								$(".createupdate_btn").text("Update");
							} else {
								$(".createupdate_btn").text("Save");
							}
						}
					}
				}
				
			}
			
				sweetalert(msg,url);
			},
			error 		: function(data) {
				alert("Network Error");
			}
		});


    } else {
        sweetalert("form_alert");
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


function hostel_creation_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
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
				success : function(data) {

					var obj     = JSON.parse(data);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;

					if (!status) {
						url 	= '';
						
					} else {
						init_datatable(table_id,form_name,action);
					}
					sweetalert(msg,url);
				}
			});

		} else {
			// alert("cancel");
		}
	});
}


function submit_sub(unique_id = "") {
	event.preventDefault();
	
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

	var go_no 		= document.getElementById('go_no').value;
    var go_date 			= document.getElementById('go_date').value;
    var go_abstract 		= document.getElementById('go_abstract').value;
	
    var old_hostel_name 	= document.getElementById('old_hostel_name').value;
    var old_sanc_cnt 		= document.getElementById('old_sanc_cnt').value;
	var old_hostel_type 	= document.getElementById('old_hostel_type').value;
    var old_hostel_gender 			= document.getElementById('old_hostel_gender').value;
    var hostel_upgrade 			= document.getElementById('hostel_upgrade').value;
   
	
	var unique_id 			= document.getElementById('unique_id').value;
	

	var go_attachment = document.getElementById("go_attachment").value;
	
	var data = new FormData();

	var image_s = $("#go_attachment");

	var files = document.getElementById('go_attachment').files;

	const fileInput = document.getElementById('go_attachment');
	const file = fileInput.files[0];


const allowedFileTypes = [
'image/jpeg', 'image/png', 'image/gif', // Images
'application/pdf',                     // PDF
'application/msword',                  // DOC
'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // DOCX

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
			
				data.append("go_attachment", document.getElementById('go_attachment').files[i]);
			}
		}
	 else {
		data.append("go_attachment", '');
	}


	
	if(go_no != '' && go_date != '' && go_abstract != '' && go_attachment != '' && image_s != '' && old_hostel_name != '' && old_sanc_cnt != '' && old_hostel_type != '' && old_hostel_gender != ''){
		if(old_sanc_cnt != '0'){
    var actions = "hostel_upgrade_sub";
// alert(hybrid_hostel);

	data.append("go_no", go_no);
	data.append("go_date", go_date);
	data.append("go_abstract", go_abstract);
	
	data.append("old_hostel_name", old_hostel_name);
	data.append("old_hostel_type", old_hostel_type);
	data.append("old_sanc_cnt", old_sanc_cnt);
	data.append("old_hostel_gender", old_hostel_gender);
	data.append("hostel_upgrade", hostel_upgrade);
	
	

	data.append("unique_id", unique_id);
	data.append("action", actions);


        var ajax_url = sessionStorage.getItem("folder_crud_link");
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
			
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				
				$("#go_no").val('');
				$("#go_date").val('');
				$("#go_abstract").val('');
				$("#go_attachment").val('');
				$("#old_sanc_cnt").val('');
				$("#old_hostel_name").val('');
				$("#old_hostel_type").val('');
		$("#old_hostel_type").val(null).trigger("change");
		$("#old_hostel_gender").val(null).trigger("change");


				if (!status) {
					url 	= '';
                    $(".createupdate_btn").text("Error");
                    console.log(error);
				} else {
					if (msg=="already") {
						// Button Change Attribute
						url 		= '';

						$(".createupdate_btn").removeAttr("disabled","disabled");
						if (unique_id) {
							$(".createupdate_btn").text("Update");
						} else {
							$(".createupdate_btn").text("Save");
						}
					}
				}
		

				sweetalert(msg);
	upgrade_datatable('hostel_upgrade_datatable',form_name,'hostel_upgrade_datatable');

			},
			error 		: function(data) {
				alert("Network Error");
			}
		});
	}else{
		sweetalert("greater_zero");
	}

    } else {
        sweetalert("form_alert");
    }
}


function upgrade_datatable(table_id='',form_name='',action='') {

	
	var table = $("#"+table_id);
	var hostel_unique_id = $('#unique_id').val();
	

	var data 	  = {
		"hostel_unique_id" : hostel_unique_id,
		
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
	
	searching: false,
	lengthChange: false	
	
	});
}

function print_pdf(file_name) {
	var pdfUrl = "../adhmAdmin/uploads/hostel_upgrade_docs/" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmAdmin/uploads/hostel_upgrade_docs/' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function hostel_upgrade_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	
var hostel_unique_id = $("#unique_id").val();

	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {

			var data = {
				"unique_id" 	: unique_id,
				"hostel_unique_id" 	: hostel_unique_id,
				
				"action"		: "hostel_upgrade_delete"
			}

			$.ajax({
				type 	: "POST",
				url 	: ajax_url,
				data 	: data,
				success : function(data) {

					var obj     = JSON.parse(data);
					var msg     = obj.msg;
					var status  = obj.status;
					var error   = obj.error;
					var sub_cnt   = obj.sub_cnt;

					if(sub_cnt == '0'){
						window.location.reload();
					}else{

					if (!status) {
						url 	= '';
						
					} else {
						
	upgrade_datatable('hostel_upgrade_datatable',form_name,'hostel_upgrade_datatable');

					}
				}
					sweetalert(msg);
				}
			});

		} else {
			// alert("cancel");
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

	var building_status = $("#building_status").val();
	
	
	var rental_reason = document.getElementById("rental_div");
		
	if(building_status === "66fa42230e30690883" || building_status === "66fa42230e30690885"){

		rental_reason.style.display = "block";

	}else{
		rental_reason.style.display = "none";
	}
}



function showLoader() {
	$("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
	$("#loader").css("display", "none");
}


$("#export_hostel").click(function () {
	
	// Show the loader
	showLoader();
	$("#export_hostel").prop("disabled", true);

	// Use AJAX to request the file
	$.ajax({
		url: "folders/hostel_creation/excel.php",
		type: "GET",
		xhrFields: {
			responseType: 'blob' // Important to get the file as a blob
		},
		success: function (data) {
			// Create a link element to trigger the download
			var link = document.createElement('a');
			var url = window.URL.createObjectURL(data);
			link.href = url;
			link.download = 'Hostel_Creation_Report.xls'; // Set the filename
			document.body.appendChild(link);
			link.click();
			window.URL.revokeObjectURL(url);
			document.body.removeChild(link);

			// Hide the loader
			hideLoader();
			$("#export_hostel").prop("disabled", false);

		},
		error: function () {
			// Hide the loader if there's an error
			hideLoader();
			alert("An error occurred while generating the report.");
			$("#export_hostel").prop("disabled", false);

		}
	});
});

function print_view_image(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="../adhmHostel/uploads/hostel_creation' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf_go(file_name) {
	var pdfUrl = "../adhmHostel/uploads/hostel_creation/" + file_name;
	
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}



function add_staff_count() {
	
    var internet_status  = is_online();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }

	var warden_cnt 		= document.getElementById('warden_cnt').value;
    var cook_cnt 			= document.getElementById('cook_cnt').value;
    var sweeper_cnt 		= document.getElementById('sweeper_cnt').value;
	
    var watchman_cnt 	= document.getElementById('watchman_cnt').value;
    var helper_cnt 		= document.getElementById('helper_cnt').value;
	
   
	
	var unique_id 			= document.getElementById('unique_id').value;
	


		var data = {
			"warden_cnt": warden_cnt,
			"cook_cnt": cook_cnt,
			"sweeper_cnt": sweeper_cnt,
			"watchman_cnt": watchman_cnt,
			"helper_cnt": helper_cnt,
			"unique_id": unique_id,
			"action": "store_staff_count"
		}

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");
		// if((image_s != '') || (file_name !='')){
        // console.log(data);
        $.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success		: function(data) {

				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
				var total_staff_count   = obj.total_staff_count;

				$('#sanc_staff_count').val(total_staff_count);
				$('#addModal').modal('hide');
		
				sweetalert(msg);
	

			},
			error 		: function(data) {
				alert("Network Error");
			}
		});

}
