$(document).ready(function () {
	// var table_id 	= "hostel_name_datatable";
	init_datatable(table_id, form_name, action);
	get_hostel_location_type();
	get_urban_type();
});

var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");

var form_name = 'Hostel Creation';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'hostel_creation_datatable';
var action = "datatable";

function init_datatable(table_id = '', form_name = '', action = '') {
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
		buttons: [
			'copy', 'csv', 'excel', 'pdf', 'print'
		]
	});
}

function hostel_creation_cu(unique_id = "") {
	var internet_status = is_online();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var hostel_name = document.getElementById('hostel_name').value;
	var hostel_id = document.getElementById('hostel_id').value;
	var district_name = document.getElementById('district_name').value;
	var taluk_name = document.getElementById('taluk_name').value;
	var special_tahsildar = document.getElementById('special_tahsildar').value;
	var assembly_const = document.getElementById('assembly_const').value;
	var parliment_const = document.getElementById('parliment_const').value;
	var address = document.getElementById('address').value;
	var hostel_location = document.getElementById('hostel_location').value;
	var urban_type = document.getElementById('urban_type').value;
	var corporation = document.getElementById('corporation').value;
	var municipality = document.getElementById('municipality').value;
	var town_panchayat = document.getElementById('town_panchayat').value;
	var block = document.getElementById('block').value;
	var village_name = document.getElementById('village_name').value;
	var hostel_type = document.getElementById('hostel_type').value;
	var gender_type = document.getElementById('gender_type').value;
	var yob = document.getElementById('yob').value;
	var sanctioned_strength = document.getElementById('sanctioned_strength').value;
	var distance_btw_phc = document.getElementById('distance_btw_phc').value;
	var phc_name = document.getElementById('phc_name').value;
	var distance_btw_ps = document.getElementById('distance_btw_ps').value;
	var ps_name = document.getElementById('ps_name').value;
	var staff_count = document.getElementById('staff_count').value;
	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;
	var unique_id = document.getElementById('unique_id').value;
	var is_active = document.getElementById('is_active').value;
	var image_s = document.getElementById("test_file");


	var data = new FormData();
	if (image_s != '') {
		for (var i = 0; i < image_s.files.length; i++) {
			data.append("test_file", document.getElementById('test_file').files[i]);
		}
	}
	else {
		data.append("test_file", '');
	}
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
	data.append("block", block);
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
	data.append("latitude", latitude);
	data.append("longitude", longitude);
	data.append("unique_id", unique_id);
	data.append("action", actions);

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	// console.log(data);
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		cache: false,
		contentType: false,
		processData: false,
		method: 'POST',
		beforeSend: function () {
			$(".createupdate_btn").attr("disabled", "disabled");
			$(".createupdate_btn").text("Loading...");
		},
		success: function (data) {

			var obj = JSON.parse(data);
			var msg = obj.msg;
			var status = obj.status;
			var error = obj.error;

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
		},
		error: function (data) {
			alert("Network Error");
		}
	});


	// } else {
	//     sweetalert("form_alert");
	// }
}

function hostel_creation_delete(unique_id = "") {

	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {

				var data = {
					"unique_id": unique_id,
					"action": "delete"
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

function get_assembly() {

	var district_name = $("#taluk_name").val();


	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");

	if (district_name) {
		var data = {
			"taluk_name": taluk_name,
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
		$("#urban_type").val(null).trigger("change");

	}
	else if (hostel_location == 1) {
		//rural
		ruralFieldsTypeBlock.style.display = "block";
		ruralFieldsTypeVillage.style.display = "block";
		urbanFieldsType.style.display = "none";
		corporationField.style.display = "none";
		municipalityField.style.display = "none";
		town_panchayatField.style.display = "none";

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
		$('#town_panchayat').val(null).trigger("change");
		$('#municipality').val(null).trigger("change")
	}
}


function fetchLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(
			function (position) {
				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;

				document.getElementById("latitude").value = latitude;
				document.getElementById("longitude").value = longitude;
			},
			function (error) {
				if (error.code === error.PERMISSION_DENIED) {
					var enableLocation = confirm("To use this feature, please enable location services. Do you want to enable location services now?");
					if (enableLocation) {
						window.open("https://support.google.com/chrome/answer/142065?co=GENIE.Platform%3DDesktop&hl=en", "_blank");
					}
				} else {
					switch (error.code) {
						case error.POSITION_UNAVAILABLE:
							alert("Location information is unavailable.");
							break;
						case error.TIMEOUT:
							alert("The request to get user location timed out.");
							break;
						case error.UNKNOWN_ERROR:
							alert("An unknown error occurred.");
							break;
					}
				}
			}
		);
	} else {
		alert("Geolocation is not supported by this browser.");
	}
}

function hwHostel_print(unique_id = "") {


	var external_window = window.open('folders/hostel_creation/hwHostel_print.php?unique_id=' + unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
	external_window.print();
	// external_window.print();
}