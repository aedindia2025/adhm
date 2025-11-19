$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	var designation = $("#designation").val();
	if(designation){
	get_warden_category();
	}
});
var company_name = sessionStorage.getItem("company_name");
var company_address = sessionStorage.getItem("company_name");
var company_phone = sessionStorage.getItem("company_name");
var company_email = sessionStorage.getItem("company_name");
var company_logo = sessionStorage.getItem("company_name");
var form_name = 'User Type';
var form_header = '';
var form_footer = '';
var table_name = '';
var table_id = 'establishment_registration_datatable';
var action = "datatable";


function get_warden_category(){
	var designation = $("#designation").val();
	if(designation == "679344dd0298475852" || designation == "679344eba32e272880"){
		document.getElementById('wardencategory_div').style.display = 'block';
	}else{
	
		$('#warden_category').val('').trigger('change');
		document.getElementById('wardencategory_div').style.display = 'none';
		
	}
}

function validatePassword(value) {

	var password = document.getElementById("password").value;
	var confirmPassword = value;
	var passwordError = document.getElementById("passwordError");

	if (password !== confirmPassword) {
		passwordError.textContent = "Passwords doesn't match!";
	} else {
		passwordError.textContent = "";
	}
}


function password_vali() {
	var password = document.getElementById("password").value;
	// var confirmPassword = document.getElementById("con_pass").value;
	var passwordError = document.getElementById("password_vali");

	// Regular expressions for password validation
	var upperCaseRegex = /[A-Z]/;
	var lowerCaseRegex = /[a-z]/;
	var digitRegex = /[0-9]/;
	var specialCharRegex = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

	// Check if password meets all requirements
	if (
		password.length >= 8 &&
		upperCaseRegex.test(password) &&
		lowerCaseRegex.test(password) &&
		digitRegex.test(password) &&
		specialCharRegex.test(password)
	) {
		passwordError.textContent = "";
	} else {
		passwordError.textContent = "Password must contain at least 8 characters including one uppercase letter, one lowercase letter, one numeric digit, and one special character.";
	}
}


function init_datatable(table_id = '', form_name = '', action = '') {
	var table = $("#" + table_id);
	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	var designation = $('#department_new').val();


	var data = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"department_new": designation,
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
			title: 'Establishment'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Establishment',
			filename: 'establishment'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Establishment',
			filename: 'establishment'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Establishment',
			filename: 'establishment'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Establishment'
		}
		]
	});
}

function onAadharKeyPress() {
	var aadhaarNo = document.getElementById("aadhaar_no").value;

	var aadhaarError = document.getElementById("error-aadhaar-no");

	// Validate using Verhoeff algorithm
	if (!verhoeffCheck(aadhaarNo)) {

		aadhaarError.textContent = "Invalid Aadhaar number.";
		document.getElementById('aadhaar_no').style.borderColor = 'red';
		if(aadhaarNo.length == '12'){
			$("#aadhaar_no").val('');
		}
		
		
		// $("#aadhaar_no").val('');
	} else {
		aadhaarError.textContent = ""; // Clear error message if Aadhaar number is valid
		document.getElementById('aadhaar_no').style.borderColor = '';
		
		
	}
}

function verhoeffCheck(num) {
	var verhoeffTable = [
		[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
		[1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
		[2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
		[3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
		[4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
		[5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
		[6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
		[7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
		[8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
		[9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
	];

	var verhoeffPermutation = [
		[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
		[1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
		[5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
		[8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
		[9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
		[4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
		[2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
		[7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
	];

	var c = 0;
	var numArray = num.split('').map(Number).reverse();

	for (var i = 0; i < numArray.length; i++) {
		c = verhoeffTable[c][verhoeffPermutation[(i % 8)][numArray[i]]];
	}

	return (c === 0);
}



function establishment_registration_cu(unique_id = "") {

	var internet_status = is_online();
	var data = new FormData();
	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}

	var staff_name = $('#staff_name').val();
	var ifhrms_id = $('#ifhrms_id').val();
	var gender_name = $('#gender_name').val();
	var dob = $('#dob').val();
	
	var mobile_num = $('#mobile_num').val();
	var district_name = $('#district_name').val();
	
	var designation = $('#designation').val();
	var warden_category = $('#warden_category').val();
	
	var district_office = $('#district_office').val();
	var taluk_office = $('#taluk_office').val();
	var hostel_name = $('#hostel_office').val();
	
	var csrf_token = $("#csrf_token").val();
	
	var aadhaar_no = $('#aadhaar_no').val();
	var unique_id = $('#unique_id').val();
	var img = $('#img').val();
	var cur_date = $('#cur_date').val();
	// var staff_count = $('#staff_count').val();
	

	// Define a function to switch tabs, display error messages, and focus
	function showTabAndFocus(tab_id, input_id, error_id, message) {
		$('a[href="#' + tab_id + '"]').tab('show'); // Show the tab
		$('#' + error_id).text(message); // Display the error message
		setTimeout(function () {
			$('#' + input_id).focus(); // Focus the input field after the tab is shown
		}, 200);
	}

	// Clear all error messages before validation
	$('.error-message').text('');

	if (!staff_name) {
		showTabAndFocus('arrow-overview', 'staff_name', 'error-staff-name', "Staff Name is required");
		return false;
	}
	if (!ifhrms_id) {
		showTabAndFocus('arrow-overview', 'ifhrms_id', 'error-ifhrms-id', "IFHRMS ID is required");
		return false;
	}
	if (!gender_name) {
		showTabAndFocus('arrow-overview', 'gender_name', 'error-gender-name', "Gender is required");
		return false;
	}
	if (!dob) {
		showTabAndFocus('arrow-overview', 'dateofbirth', 'error-dateofbirth', "Date of birth is required");
		return false;
	}
	if (dob > cur_date) {
		showTabAndFocus('arrow-overview', 'dateofbirth', 'error-dateofbirth', "Date of birth is Greater than Current date");
		return false;
	}
	
	if (!mobile_num) {
		showTabAndFocus('arrow-overview', 'mobile_num', 'error-mobile-num', "Mobile number is required");
		return false;
	}
	if (!district_name) {
		showTabAndFocus('arrow-overview', 'district_name', 'error-district-name', "District name is required");
		return false;
	}

	if (!aadhaar_no) {
		showTabAndFocus('arrow-overview', 'aadhaar_no', 'error-aadhaar-no', "Aadhaar Number is required");
		document.getElementById('aadhaar_no').style.borderColor = 'red';
		return false;
	}
	if (aadhaar_no.length < 12) {
		showTabAndFocus('arrow-overview', 'aadhaar_no', 'error-aadhaar-no', "Enter Valid Aadhaar Number");
		document.getElementById('aadhaar_no').style.borderColor = 'red';
		return false;
	}
	
	if (!designation) {
		showTabAndFocus('arrow-contact', 'designation', 'error-designation', "Designation is required");
		return false;
	}

	if(designation == "679344dd0298475852" || designation == "679344eba32e272880"){
		if(!warden_category){
			showTabAndFocus('arrow-contact', 'warden_category', 'error-warden_category', "Warden Category is required");
			return false;
		}
	}
	
	

	var image = $("#image_file").val();
    var image_s = $("#image_file");

    const fileInput = document.getElementById('image_file');
            const file = fileInput.files[0];


    const allowedFileTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/jpg' // Images
    ];
    // const maxFileSize = 5 * 1024 * 1024; // 5MB
if(file){
    if (!allowedFileTypes.includes(file.type)) {
        sweetalert('invalid_ext');
        return false;
    }
}


    var files = document.getElementById('image_file').files;

    if (image != '') {
        // Regular expression for allowed extensions
        for (var i = 0; i < image_s.length; i++) {
            
            data.append("image_file", document.getElementById('image_file').files[i]);

        }
    } else {
        data.append("image_file", '');
    }

	if(img == '' && image == ''){
		sweetalert('form_alert');
		return false;
	}



	if (staff_name != '' && gender_name != '' && dob != '' && mobile_num != '' && district_name != ''  && designation != '' && aadhaar_no != '' && csrf_token != '' && ifhrms_id != '' ) {

			data.append("staff_name", staff_name);
		
			data.append("gender_name", gender_name);
			data.append("dob", dob);
			data.append("mobile_num", mobile_num);
			data.append("district_name", district_name);
			data.append("designation", designation);
			data.append("warden_category", warden_category);
			
			data.append("district_office", district_office);
			data.append("taluk_office", taluk_office);
			data.append("hostel_office", hostel_name);
			data.append("ifhrms_id", ifhrms_id);
			data.append("csrf_token" , csrf_token);
			data.append("aadhaar_no", aadhaar_no);
			data.append("unique_id", unique_id);
			data.append("staff_count", staff_count);
			data.append("action", "createupdate");

		

		// data 		+= "&unique_id="+unique_id+"&action=createupdate";
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");
		
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
				if (msg == 'count_exceed') {
										sweetalert(msg, url);
				}

				if (msg == "form_alert") {
					sweetalert("form_alert");
					$(".createupdate_btn").removeAttr("disabled", "disabled");
							if (unique_id) {
								$(".createupdate_btn").text("Update");
							} else {
								$(".createupdate_btn").text("Save");
							}
				} else {
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
				}
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


function establishment_registration_delete(unique_id = "") {
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url = sessionStorage.getItem("list_link");
	var csrf_token = $("#csrf_token").val();

	confirm_delete('delete')
		.then((result) => {
			if (result.isConfirmed) {
				var data = {
					"unique_id": unique_id,
					"csrf_token" : csrf_token,
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

function nextTab() {
	var activeTab = document.querySelector('.nav-link.active');
	var nextTab = activeTab.parentElement.nextElementSibling.querySelector('a');
	if (nextTab) {
		nextTab.click(); // Activate the next tab
	}
}
function goToListPage() {
	window.location.href = 'index.php?file=establishment_registration/list';
}
function personaldetails() {
	window.location.href = 'index.php?file=establishment_registration/model';
}


function get_tashil() {
	var hostel_name = $("#hostel_tash").val();
	$("#host_tash").val(hostel_name)
}

function go_staff_filter() {

	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	var designation = $('#department_new').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "student_id"	: student_id,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"department_new": designation,
		// "current_date":current_date,
		"action": 'datatable',

	};
	// alert(data);

	init_datatable(table_id, form_name, action, data);

}


function check_phone_number() {
	var inputPhonenumber = document.getElementById('mobile_num').value;

	if (inputPhonenumber.length < 2) {
		var phoneNumberValue = Number(inputPhonenumber);
		if (phoneNumberValue <= 9 && phoneNumberValue >= 6) {
			// Allow only 6, 7, 8, 9
		} else {
			Swal.fire("Enter Valid Number");
		}
	}

	if (inputPhonenumber.length > 10) {
		inputPhonenumber = inputPhonenumber.slice(0, 10);
		document.getElementById('mobile_num').value = inputPhonenumber;
	}
}

//   function calculateAge() {
function calculateAge() {

	var dobInput = document.getElementById("dateofbirth").value;

	var dob = new Date(dobInput);
	var ageDate = new Date(Date.now() - dob.getTime());
	var age = Math.abs(ageDate.getUTCFullYear() - 1970);

	// Display the calculated age 
	document.getElementById("age").value = age;
}


function validateEmail() {
	var emailInput = document.getElementById("email_id");
	var email = emailInput.value.trim();

	// Regular expression pattern for email validation
	var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	if (pattern.test(email)) {
		// Valid email address
		emailInput.classList.remove("invalid");
		emailInput.classList.add("valid");
	} else {
		// Invalid email address
		emailInput.classList.remove("valid");
		emailInput.classList.add("invalid");
		alert("Please enter a valid email address.");
	}
}

function taluk() {

	var district_name = $('#district_name').val();


	var data = "district_name=" + district_name + "&action=district_name";
	var ajax_url = sessionStorage.getItem("folder_crud_link");


	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			// alert(data);	
			if (data) {
				$("#taluk_name").html(data);
			}
		}
	});
}

function get_taluk() {

	var district_name = $('#district_name_new').val();
	// alert(district_name);
	var data = "district_name_new=" + district_name + "&action=district_name";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#taluk_name_new").html(data);
			}
		}
	});
}
// function get_hostel(){
// 	var taluk_name = $('#taluk_name').val();
// 	var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";
// 	var ajax_url = sessionStorage.getItem("folder_crud_link");
// 	$.ajax({
// 		type: "POST",
// 		url: ajax_url,
// 		data: data,
// 		success: function (data) {
// 			if (data) {
// 				$("#hostel_name").html(data);
// 			}
// 		}
// 	});
// }

function get_taluk() {

	var district_name_new = $('#district_name_new').val();

	var data = "district_name_new=" + district_name_new + "&action=get_district_new_name";
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

function get_hostel_name() {

	var taluk_name_new = $('#taluk_name_new').val();
	// alert(taluk_name_new);

	var data = "taluk_name_new=" + taluk_name_new + "&action=get_hostel_by_new_name";
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		success: function (data) {
			if (data) {
				$("#hostel_warden").html(data);
				$("#hostel_tash").html(data);
			}
		}
	});
}


function get_job_div() {
	var employment_course = $('#employment_course').val();
	var textBoxContainer = document.getElementById("textBoxContainer");

	// Check if the selected value requires showing the text box
	if (employment_course === "employment" || employment_course === "course") {
		textBoxContainer.style.display = "block"; // Show the text box container
	} else {
		textBoxContainer.style.display = "none"; // Hide the text box container
	}
}


function password_check() {


	var password = $('#password').val();
	var con_pass = $('#con_pass').val();

	if (password !== con_pass) {
		alert('Password do not match');
	}
	if (password.length < 8) {
		alert("Password must be at least 8 characters long");
		return false;
	}

	return true;

}

// function togglePasswordVisibility(inputId) {
//     var passwordInput = document.getElementById(inputId);
//     var eyeIcon = document.getElementById(inputId === 'password' ? 'eyeIcon' : 'confirmEyeIcon');

//     if (passwordInput.type === "password") {
//         passwordInput.type = "text";
//         eyeIcon.classList.remove("bi-eye");
//         eyeIcon.classList.add("bi-eye-slash");
//     } else {
//         passwordInput.type = "password";
//         eyeIcon.classList.remove("bi-eye-slash");
//         eyeIcon.classList.add("bi-eye");
//     }
// }


