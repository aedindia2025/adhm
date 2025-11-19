$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id, form_name, action);
	staff_filter();
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
var table_id = 'staff_registration_datatable';
var action = "datatable";

function hashPassword(password) {
	return CryptoJS.SHA256(password).toString(CryptoJS.enc.Hex);
}

function init_datatable(table_id = '', form_name = '', action = '') {

	var table = $("#" + table_id);

	var district_name = $('#district_name').val();

	var taluk_name = $('#taluk_name').val();
	var designation = $('#department_new').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "student_id"	: student_id,
		"district_name_new": district_name,
		"taluk_name_new": taluk_name,
		"department_new": designation,
		// "current_date":current_date,
		"action": 'datatable',

	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var datatable = table.DataTable({

		"ajax": {
			url: ajax_url,
			type: "POST",
			data: data
		},
		"searching": false,
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Registration List'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Registration List',
			filename: 'staff_registration_list'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Registration List',
			filename: 'staff_registration_list'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Registration List',
			filename: 'staff_registration_list'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Staff Registration List'
		}
		]
	});
}

function staff_registration_cu(unique_id = "") {
	// alert('ji');
	var internet_status = is_online();
	var data = new FormData();

	if (!internet_status) {
		sweetalert("no_internet");
		return false;
	}
	// var is_form = form_validity_check("was-validated");
	var staff_name = $('#staff_name').val();
	// alert(staff_name);
	var staff_id = $('#staff_id').val();
	var dob = $('#dob').val();
	var father_name = $('#father_name').val();
	var age = $('#age').val();
	var gender_name = $('#gender_name').val();
	var academic_year = $('academic_year').val();

	// console.log(academic_year);
	var mobile_num = $('#mobile_num').val();


	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	// var designation = $('#department_new').val();
	var address = $('#address').val();
	// var aadhaar_no = $('#aadhaar_no').val();
	var doj = $('#doj').val();
	var department = $('#department').val();
	var designation = $('#department_new').val();
	var email_id = $('#email_id').val();
	var district_office = $('#district_name_new').val();
	var taluk_office = $('#taluk_name_new').val();
	// alert(taluk_office);
	var warden_hostel_name = $('#hostel_warden').val();

	var tash_hostel_name = $('#host_tash').val();
	var unique_id = $('#unique_id').val();
	var hostel_name = $('#hostel_name').val();
	// alert(tash_hostel_name);

	var academic_year = $('#academic_year').val();

	var user_name = $('#user_name').val();
	var password = $('#password').val();
	var hashedPassword = hashPassword(password);

	var confirm_password = $('#confirm_password').val();
	var biometric_id = $('#biometric_id').val();
	var user_type = $('#user_type').val();
	var csrf_token = $('#csrf_token').val();
	var img_file = $("#img_file").val();
	var image = $("#test_file1").val();

	// $("#image_file").val(image);
	// console.log(tah_hostel_name);
	if (warden_hostel_name != '') {
		hostel_name = warden_hostel_name;
		// alert(hostel_name +' hi');
	}
	if (tash_hostel_name != '') {
		hostel_name = tash_hostel_name;
		// alert(hostel_name +' hello');

	}
	var image_s = $("#test_file1");

	var files = document.getElementById('test_file1').files;

	const fileInput = document.getElementById('test_file1');
	const file = fileInput.files[0];


const allowedFileTypes = [
'image/jpeg', 'image/png', 'image/gif', // Images

];
const maxFileSize = 5 * 1024 * 1024; // 5MB
if(file){
if (!allowedFileTypes.includes(file.type)) {
sweetalert('invalid_ext');
return false;
}
}

	if (image_s != '') {

		for (var i = 0; i < image_s.length; i++) {

			data.append("test_file1", document.getElementById('test_file1').files[i]);

		}
	} else {
		data.append("test_file1", '');
	}




	if (staff_name != '' && user_name != '' && password != '' && confirm_password != '' && department != '' && (image != '' || img_file != '')) {




		data.append("staff_name", staff_name);
		data.append("staff_id", staff_id);
		data.append("dob", dob);
		data.append("father_name", father_name);
		data.append("age", age);
		data.append("gender_name", gender_name);
		data.append("academic_year", academic_year);
		data.append("mobile_num", mobile_num);
		data.append("district_name", district_name);
		data.append("taluk_name", taluk_name);
		data.append("district_name", district_name);
		data.append("address", address);
		// data.append("aadhaar_no", aadhaar_no);
		data.append("doj", doj);
		data.append("department", department);
		data.append("department_new", designation);
		data.append("email_id", email_id);
		data.append("district_office", district_office);
		data.append("taluk_office", taluk_office);
		data.append("hostel_name", hostel_name);
		data.append("user_name", user_name);
		data.append("password", password);
		data.append("hashedPassword", hashedPassword);
		data.append("confirm_password", confirm_password);
		data.append("biometric_id", biometric_id);
		data.append("user_type", user_type);
		data.append("csrf_token", csrf_token);
		data.append("unique_id", unique_id);
		// data.append("is_active", is_active);
		data.append("action", "createupdate");

		// data 		+= "&unique_id="+unique_id+"&action=createupdate";
		var ajax_url = sessionStorage.getItem("folder_crud_link");
		var url = sessionStorage.getItem("list_link");
		console.log(data);
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',


			success: function (data) {
				// alert(data);
				var obj = JSON.parse(data);
				var msg = obj.msg;
				var status = obj.status;
				var error = obj.error;


				if (msg == "form_alert") {
					sweetalert("form_alert");
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
	}
	else {
		sweetalert("form_alert");
	}
}

function nextTab() {
	var activeTab = document.querySelector('.nav-link.active');
	var nextTab = activeTab.parentElement.nextElementSibling.querySelector('a');
	if (nextTab) {
		nextTab.click(); // Activate the next tab
	}
}

function staff_registration_delete(unique_id = "") {
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


// function next(tabIndex) {
//     var currentTab = document.querySelector('.tab-pane.active');
//     var nextTab;

//     // Determine the next tab based on the current tab index
//     if (tabIndex === 1) {
//         nextTab = document.getElementById('arrow-contact');
//     } else if (tabIndex === 2) {
//         nextTab = document.getElementById('arrow-third');
//     }

//     // If the next tab exists, activate it
//     if (nextTab) {
//         // Deactivate the current tab
//         currentTab.classList.remove('active');
//         currentTab.classList.remove('show');

//         // Activate the next tab
//         nextTab.classList.add('active');
//         nextTab.classList.add('show');

// 		var nextTabHeading = document.querySelector('.nav-link[href="arrow-contact' + nextTab.id + '"]');
//         if (nextTabHeading) {
//             // Deactivate all tab headings
//             document.querySelectorAll('.nav-link').forEach(function(tabLink) {
//                 tabLink.classList.remove('active');
//             });

//             // Activate the next tab heading
//             nextTabHeading.classList.add('active');
//     }
// }
// }

function next(tabIndex) {
	// Deactivate the current tab
	var currentTab = document.querySelector('.tab-pane.active');
	currentTab.classList.remove('active');
	currentTab.classList.remove('show');

	// Determine the next tab based on the current tab index
	var nextTabId;
	if (tabIndex === 1) {
		nextTabId = 'arrow-contact';
	} else if (tabIndex === 2) {
		nextTabId = 'arrow-third';
	}

	// Activate the next tab content
	var nextTab = document.getElementById(nextTabId);
	nextTab.classList.add('active');
	nextTab.classList.add('show');

	// Activate the corresponding tab heading
	var nextTabHeading = document.querySelector('.nav-link[href="#' + nextTabId + '"]');
	if (nextTabHeading) {
		// Deactivate all tab headings
		document.querySelectorAll('.nav-link').forEach(function (tabLink) {
			tabLink.classList.remove('active');
		});

		// Activate the next tab heading
		nextTabHeading.classList.add('active');
	}
}



var nextButton = document.getElementById('nextButton');

// Add an event listener to the anchor tag
nextButton.addEventListener('click', function () {
	// Call your function here
	next();
});


var nextButton_1 = document.getElementById('nextButton1');

// Add an event listener to the anchor tag
nextButton.addEventListener('click', function () {
	// Call your function here
	next_1();
});

function next() {
	// var tabs = document.querySelectorAll('.tab-pane'); // Get all tab panes
	// var activeTab = document.querySelector('.tab-pane.active');
	// var currentIndex = Array.from(tabs).indexOf(activeTab); // Get the index of the active tab

	// // Calculate the index of the next tab, wrapping around to the first tab if necessary
	// var nextIndex = (currentIndex + 1) % tabs.length;

	// // Deactivate the current tab
	// activeTab.classList.remove('active');
	// activeTab.classList.remove('show');

	// // Activate the next tab
	// tabs[nextIndex].classList.add('active');
	// tabs[nextIndex].classList.add('show');

	var activeTab = document.querySelector('.tab-pane.active');
	var nextTab = activeTab.nextElementSibling;

	// If there is a next tab, activate it
	if (nextTab !== null) {
		activeTab.classList.remove('active');
		activeTab.classList.remove('show');
		nextTab.classList.add('active');
		nextTab.classList.add('show');
	}
}

// function next() {
//     alert(); // This line is for debugging purposes

//     // Get the tab element with the ID "arrow-overview"
//     var tab = document.querySelector('#user_credentials');

//     // Show the tab
//     if (tab) {
//         var tabInstance = new bootstrap.Tab(tab);
//         tabInstance.show();
//     }
// }

function get_tashil() {
	var hostel_name = $("#hostel_tash").val();
	$("#host_tash").val(hostel_name)
}

function go_staff_filter() {

	init_datatable(table_id, form_name, action);

}
// function validate_adhaar_number() {
// 	alert();
// 	var aadhaarInput = document.getElementById("aadhaar_no").value;
// 	var regex = /^[a-zA-Z]{1,12}$/; // Regular expression to match alphabetic characters and limit to 12 characters
// 	if (!regex.test(aadhaarInput)) {
// 		alert("Please enter a valid Aadhaar number with alphabets only and maximum length of 12 characters.");
// 		return false;
// 	}
// 	return true;
// }


function validate_aadhaar_number() {
	var inputAadhaarNumber = document.getElementById('aadhaar_no').value;

	if (inputAadhaarNumber.length !== 12 || isNaN(inputAadhaarNumber)) {
		// Check if Aadhaar number is not 12 digits or is not a number
		Swal.fire("Enter a valid 12-digit Aadhaar number");
	}
}

function onAadharKeyPress() {
	var aadhaarNo = document.getElementById("aadhaar_no").value;
	var aadhaarError = document.getElementById("aadhaarError");



	// Validate using Verhoeff algorithm
	if (!verhoeffCheck(aadhaarNo)) {
		aadhaarError.textContent = "Invalid Aadhaar number.";
		return;
	}

	// If all checks pass, clear error message
	aadhaarError.textContent = "";
}

function verhoeffCheck(num) {
	var d = 0;
	var p = 1;
	var inv;

	var verhoeffD = [
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

	// Verhoeff P table
	var verhoeffP = [
		[0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
		[1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
		[5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
		[8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
		[9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
		[4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
		[2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
		[7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
	];


	for (var i = num.length - 1; i >= 0; i--) {
		inv = p ^ parseInt(num.charAt(i));
		p = verhoeffD[inv][d];
		d = verhoeffP[inv][d];
	}

	return d === 0;
}

// Verhoeff D table


function validatePassword() {

	var password = document.getElementById("password").value;
	var confirmPassword = document.getElementById("confirm_password").value;
	var passwordError = document.getElementById("passwordError");

	if (password !== confirmPassword) {
		passwordError.textContent = "Passwords do not match!";
	} else {
		passwordError.textContent = "";
	}
}


function password_vali() {
	var password = document.getElementById("password").value;
	// var confirmPassword = document.getElementById("confirm_password").value;
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

	var dobInput = document.getElementById("dob").value;

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
			if (data) {
				$("#taluk_name").html(data);

			}
		}
	});
}

function get_taluk() {

	var district_name = $('#district_name_new').val();
	// alert(district_name);
	var data = "district_name_new=" + district_name + "&action=get_district_new_name";
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

// function get_taluk(){

// 	var district_name_new = $('#district_name_new').val();

// 	var data = "district_name_new=" + district_name_new + "&action=get_district_new_name";
// 	var ajax_url = sessionStorage.getItem("folder_crud_link");

// 	$.ajax({
// 		type: "POST",
// 		url: ajax_url,
// 		data: data,
// 		success: function (data) {
// 			if (data) {
// 				$("#taluk_name").html(data);
// 			}
// 		}
// 	});
// }

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



function password_check() {


	var password = $('#password').val();
	var confirm_password = $('#confirm_password').val();

	if (password !== confirm_password) {
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



