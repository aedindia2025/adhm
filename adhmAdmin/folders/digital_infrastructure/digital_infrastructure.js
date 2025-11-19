$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);

	sub_list_datatable("digital_infra_datatable");
	buildings_sub_datatable("buildings_sub_datatable");

	changeLandType();
	exisiting_demolished();
	facility_type();


});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Feedback';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'digital_infrastructure_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {

	// alert();
	var table = $("#" + table_id);
	var data = {
		"action": action,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		searching: false,

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Digital Infrastructure'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Digital Infrastructure',
			filename: 'digital_infrastructure'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Digital Infrastructure',
			filename: 'digital_infrastructure'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Digital Infrastructure',
			filename: 'digital_infrastructure'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Digital Infrastructure'
		}
		]
	});
}


function digital_infrastructure_cu(unique_id = "") {
	// alert('');
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var acc_year = document.getElementById('acc_year').value;
	var csrf_token = document.getElementById('csrf_token').value;
	var hostel_name = document.getElementById('hostel_name').value;
	var hostel_id = document.getElementById('hostel_id').value;
	var taluk_name = document.getElementById('taluk').value;
	alert(taluk_name);
	var district = document.getElementById('district').value;
	alert(district);
	var land_type = document.getElementById('land_type').value;
	var owner_of_land = document.getElementById('owner_of_land').value;
	var reg_of_land = document.getElementById('reg_of_land').value;
	var area_of_land = document.getElementById('area_of_land').value;
	var con_area_land = document.getElementById('con_area_land').value;
	var existing_demolished = document.getElementById('existing_demolished').value;
	var no_floors = document.getElementById('no_floors').value;
	var toilet_each_floor = document.getElementById('toilet_each_floor').value;
	var compound_wall = document.getElementById('compound_wall').value;
	var water_facilities = document.getElementById('water_facilities').value;
	var living_area = document.getElementById('living_area').value;
	var living_area_size = document.getElementById('living_area_size').value;
	var no_of_rooms = document.getElementById('no_of_rooms').value;
	var room_size = document.getElementById('room_size').value;
	var no_of_kitchen = document.getElementById('no_of_kitchen').value;
	var kitchen_size = document.getElementById('kitchen_size').value;
	var demolished = document.getElementById('demolished').value;
	var update_unique_id = document.getElementById('update_unique_id').value;
	var unique_id = document.getElementById('unique_id').value;
	var land_doc_name = document.getElementById("land_pic").value;

	var data = new FormData();

	var image_s = $("#doc_file");
	var image_r = $("#doc_file").val();

	const fileInput = document.getElementById('doc_file');
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


	var files = document.getElementById('doc_file').files;

	if (image_s != '') {
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i;  // Regular expression for allowed extensions

		for (var i = 0; i < image_s.length; i++) {

			data.append("doc_file", document.getElementById('doc_file').files[i]);

		}
	} else {
		data.append("doc_file", '');
	}


	

		data.append("acc_year", acc_year);
		data.append("csrf_token", csrf_token);
		data.append("hostel_name", hostel_name);
		data.append("hostel_id", hostel_id);
		data.append("taluk_name", taluk_name);
		data.append("district", district);
		data.append("land_type", land_type);
		data.append("owner_of_land", owner_of_land);
		data.append("reg_of_land", reg_of_land);
		data.append("area_of_land", area_of_land);
		data.append("con_area_land", con_area_land);
		data.append("existing_demolished", existing_demolished);
		data.append("no_floors", no_floors);
		data.append("toilet_each_floor", toilet_each_floor);
		data.append("compound_wall", compound_wall);
		data.append("water_facilities", water_facilities);
		data.append("living_area", living_area);
		data.append("living_area_size", living_area_size);
		data.append("no_of_rooms", no_of_rooms);
		data.append("room_size", room_size);
		data.append("no_of_kitchen", no_of_kitchen);
		data.append("kitchen_size", kitchen_size);
		data.append("land_doc_name", land_doc_name);
		data.append("demolished", demolished);
		data.append("update_unique_id", update_unique_id);
		data.append("unique_id", unique_id);
		data.append("action", "createupdate");

		// var is_form = form_validity_check("was-validated");
		// if (is_form) {

		//     var data 	 = $(".was-validated").serialize();
		// data 		+= "&action=createupdate";

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");

		$.ajax({
			type: "POST",
			url: 'folders/digital_infrastructure/crud.php',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			// beforeSend 	: function() {
			// 	$(".createupdate_btn").attr("disabled","disabled");
			// 	$(".createupdate_btn").text("Loading...");
			// },
			success: function (data) {
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;

				// if (msg == "form_alert") {
				// 	sweetalert("form_alert");
				// 	$(".createupdate_btn").removeAttr("disabled", "disabled");
				// 	if (unique_id) {
				// 		$(".createupdate_btn").text("Update");
				// 	} else {
				// 		$(".createupdate_btn").text("Save");
				// 	}
				// } else {
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
				// }
				sweetalert(msg, url);
			},
			error: function (data) {
				alert("Network Error");
			}
		});



	// } else {
	// 	sweetalert("form_alert");
	// }
}


function goTolist() {
	// alert("jij");
	window.location.href = 'index.php?file=digital_infrastructure/list';
}

function digital_infrastructure_delete(unique_id = "") {



	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();


	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"action": "delete",
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

						if (!status) {
							url = '';

						} else {
							init_datatable(table_id, form_name, action);
						}
						sweetalert(msg, url);
					}
				});

			} else {
				// alert("cancel");
			}
		});
}

function changeLandType() {
	var landType = $('#land_type').val();

	if (landType == "not_own_land") {
		$('#own_land_div').css('display', 'none')
		$('#own_land_div1').css('display', 'none');
	} else if (landType == "own_land") {
		$('#own_land_div').css('display', 'block')
		$('#own_land_div1').css('display', 'block');
	}
}

function exisiting_demolished() {
	var existing_demolished = $('#existing_demolished').val();
	//alert(existing_demolished);
	if (existing_demolished == "existing") {
		$('#demolished_div').css('display', 'none');
		$('#compound_waal_div').css('display', 'block');
		$('#water_fac_div').css('display', 'block');
		$('#ground_floor_div').css('display', 'block')
		$('#datatableSub').css('display', 'block');
	}
	else if (existing_demolished == "demolished") {
		$('#demolished_div').css('display', 'block');
		$('#compound_waal_div').css('display', 'none');
		$('#water_fac_div').css('display', 'none');
		$('#ground_floor_div').css('display', 'none');
		$('#datatableSub').css('display', 'none');

	}

}


function save_facilities() {
	var facilities_type = document.getElementById('facilities_type').value;
	var facilities = document.getElementById('facilities').value;
	var quantity = document.getElementById('quantity').value;
	var description = document.getElementById('description').value;
	var csrf_token = document.getElementById('csrf_token').value;
	var form_unique_id = document.getElementById('unique_id').value;


	var data = "facilities_type=" + facilities_type + "&facilities=" + facilities + "&quantity=" + quantity + "&description=" + description + "&csrf_token=" +csrf_token;
	data += "&form_main_unique_id=" + form_unique_id + "&action=facilities_add_update";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = "";

	// console.log(data);
	$.ajax({
		type: "POST",
		url: 'folders/digital_infrastructure/crud.php',
		data: data,
		beforeSend: function () {
			$(".digital_infra_add_update_btn").attr("disabled", "disabled");
			$(".digital_infra_add_update_btn").text("Loading...");
		},
		success: function (data) {

			var obj = JSON.parse(data);
			var msg = obj.msg;
			var status = obj.status;
			var error = obj.error;

			if (!status) {
				$(".digital_infra_add_update_btn").text("Error");
				// console.log(error);
			} else {


				$(".digital_infra_add_update_btn").removeAttr("disabled", "disabled");
				if (unique_id && msg == "already") {
					$(".digital_infra_add_update_btn").text("Update");
				} else {
					$(".digital_infra_add_update_btn").text("Add");
					$(".digital_infra_add_update_btn").attr("onclick",
						"digital_infra_add_update('')");
				}
				// Init Datatable
				sub_list_datatable("digital_infra_datatable");
			}
			$("#facilities_type").val(null).trigger('change')
			$("#facilities").val(null).trigger('change');
			$("#quantity").val("");
			$("#description").val("");

			var div_contents = document.getElementById("div1");
			var elements = div_contents.getElementsByTagName("select");
			var elements = getElementById("tax");
			for (i = 0; i < elements.length; i++) {
				elements[i].selectedIndex = -1;
			}
			sweetalert(msg, url);
		},
		error: function (data) {
			alert("Network Error");
		}
	});
}



function sub_list_datatable(table_id = "", form_name = "", action = "") {
	var form_main_unique_id = $("#unique_id").val();
	// alert(form_main_unique_id);
	var table = $("#" + table_id);
	var data = {
		"form_main_unique_id": form_main_unique_id,
		"action": table_id,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		ordering: true,
		searching: true,
		"searching": false,
		"paging": false,
		"ordering": false,
		"info": false,
		"ajax": {
			url: 'folders/digital_infrastructure/crud.php',
			type: "POST",
			data: data
		}
	});
}

function digital_infra_details_delete(unique_id = "") {
	if (unique_id) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");
		var csrf_token = $("#csrf_token").val();

		confirm_delete('delete')
			.then((result) => {
				if (result.isConfirmed) {

					var data = {
						"unique_id": unique_id,
						"csrf_token" : csrf_token,
						"action": "digital_infra_details_delete"
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

							if (!status) {
								url = '';
							} else {
								sub_list_datatable("digital_infra_datatable");
							}
							sweetalert(msg, url);
						}
					});

				}

			});
	}
}


function buildings_sub_add_update() {
	var no_floors = $("#no_floors").val();
	var toilet_each_floor = $("#toilet_each_floor").val();
	var living_area = $("#living_area").val();
	var living_area_size = $("#living_area_size").val();
	var no_of_rooms = $("#no_of_rooms").val();
	var room_size = $("#room_size").val();
	var no_of_kitchen = $('#no_of_kitchen').val();
	var kitchen_size = $('#kitchen_size').val();
	var csrf_token = $("#csrf_token").val()
	var form_unique_id = $("#unique_id").val();

	// alert();
	var update_unique_id = document.getElementById('update_unique_id').value;


	var ajax_url = "crud.php";

	if (no_floors != '' && toilet_each_floor != '' && living_area != '' && living_area_size != '' && no_of_rooms != '' && room_size != '' && no_of_kitchen != '' && kitchen_size != '') {
		var data = new FormData();

		data.append("no_floors", no_floors);
		data.append("toilet_each_floor", toilet_each_floor);
		data.append("living_area", living_area);
		data.append("living_area_size", living_area_size);
		data.append("no_of_rooms", no_of_rooms);
		data.append("room_size", room_size);
		data.append("no_of_kitchen", no_of_kitchen);
		data.append("kitchen_size", kitchen_size);
		data.append("csrf_token", csrf_token);
		data.append("update_unique_id", update_unique_id);
		data.append("form_unique_id", form_unique_id);
		data.append("action", "buildings_sub_add_update");
		// data.append("unique_id", unique_id);

		$.ajax({
			type: "POST",
			url: 'folders/digital_infrastructure/crud.php',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',

			success: function (data) {

				var obj = JSON.parse(data);
				var msg = obj.msg;
				// var std_app_no = obj.std_app_no;
				var status = obj.status;
				var error = obj.error;

				if (!status) {
					url = '';
					$(".createupdate_btn").text("Error");
					console.log(error);
				} else {

					$(".createupdate_btn").text("Add");
					$("#no_floors").val(null).trigger('change');
					$("#toilet_each_floor").val("");
					$("#living_area").val("");
					$("#living_area_size").val("");
					$("#no_of_rooms").val("");
					$("#room_size").val("");
					$("#no_of_kitchen").val("");
					$('#kitchen_size').val("");

				}

				buildings_sub_datatable("buildings_sub_datatable");

			},
			error: function (data) {
				alert("Network Error");
			}
		});


	} else {
		sweetalert("form_alert");
	}
}


function buildings_sub_datatable(table_id = "", form_name = "", action = "") {
	var form_main_unique_id = $("#unique_id").val();
	// alert(form_main_unique_id);
	var table = $("#" + table_id);
	var data = {
		"form_main_unique_id": form_main_unique_id,
		"action": table_id,
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");

	var datatable = table.DataTable({
		ordering: true,
		searching: true,
		"searching": false,
		"paging": false,
		"ordering": false,
		"info": false,
		"ajax": {
			url: 'folders/digital_infrastructure/crud.php',
			type: "POST",
			data: data
		}
	});
}



function buildings_sub_delete(unique_id = "") {

	if (unique_id) {

		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");
		var csrf_token = $("#csrf_token").val();
		

		confirm_delete('delete')
			.then((result) => {
				if (result.isConfirmed) {

					var data = {
						"unique_id": unique_id,
						"csrf_token" : csrf_token,
						"action": "buildings_sub_delete"
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

							if (!status) {
								url = '';
							} else {
								buildings_sub_datatable("buildings_sub_datatable");
							}
							$("#status_option").val(null).trigger('change');
							$("#status_description").val("");
							sweetalert(msg, url);
						}
					});

				} else {
					// alert("cancel");
				}
			});
	}
}

function get_asset_name() {

	var facilities_type = $("#facilities_type").val();

	var ajax_url = sessionStorage.getItem("folder_crud_link");

	if (facilities_type) {
		var data = {
			"facilities_type": facilities_type,
			"action": "get_asset_name",
		}

		$.ajax({
			type: "POST",
			url: "folders/digital_infrastructure/crud.php",
			data: data,
			success: function (data) {

				if (data) {
					$("#facilities").html(data);
				}
			}
		});
	}
}

function assetInfra_print(unique_id = "") {


	var external_window = window.open('folders/digital_infrastructure/assetInfra_print.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}

$(document).ready(function () {
	$('.next').click(function () {
		var $active = $('.nav-pills .nav-link.active');
		var $next = $active.closest('li').next('li').find('a[data-toggle="tab"]');
		if ($next.length) {
			$next.tab('show');
		}
	});

	$('.previous').click(function () {
		var $active = $('.nav-pills .nav-link.active');
		var $prev = $active.closest('li').prev('li').find('a[data-toggle="tab"]');
		if ($prev.length) {
			$prev.tab('show');
		}
	});
});