$(document).ready(function () {
	// var table_id 	= "user_type_datatable";
	init_datatable(table_id,form_name,action);
});
var company_name 	= sessionStorage.getItem("company_name");
var company_address	= sessionStorage.getItem("company_name");
var company_phone 	= sessionStorage.getItem("company_name");
var company_email 	= sessionStorage.getItem("company_name");
var company_logo 	= sessionStorage.getItem("company_name");
var form_name 		= 'User Type';
var form_header		= '';
var form_footer 	= '';
var table_name 		= '';
var table_id 		= 'establishment_registration_datatable';
var action 			= "datatable";

function init_datatable(table_id='',form_name='',action='') {
	var table = $("#"+table_id);
	var district_name = $('#district_name').val();

	var taluk_name = $('#taluk_name').val();

	var hostel_name = $('#hostel_name').val();
	
	var designation = $('#department_new').val();


	var data 	  = {
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"department_new": designation,
		"action"	: action, 
	};
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var datatable = table.DataTable({
		searching: false,	
	"ajax"		: {
		url 	: ajax_url,
		type 	: "POST",
		data 	: data
	},
		dom: 'Bfrtip',
		buttons: [{
			extend: 'copyHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'establishment'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'establishment',
			filename: 'establishment'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'establishment',
			filename: 'establishment'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'establishment',
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
function establishment_registration_cu(unique_id = "") {

    var internet_status  = is_online();
    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
    // var is_form = form_validity_check("was-validated");
	var staff_name = $('#staff_name').val();
	var father_name	= $('#father_name').val();
	var gender_name = $('#gender_name').val();
	var age			= $('#age').val();
	


	
	var mobile_num = $('#mobile_num').val();


	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	// var designation = $('#department_new').val();
	var address	= $('#address').val();
	var aadhaar_no = $('#aadhaar_no').val();
	var doj 		= $('#doj').val();
	var department = $('#department').val();
	var designation = $('#department_new').val();
	var email_id 	= $('#email_id').val();
	var district_office = $('#district_office').val();
	var taluk_office	= $('#taluk_office').val();
	var hostel_name = $('#hostel_office').val();
	// alert(hostel_name);
	// var current_date	=$('#current_date').val();
	var user_name = $('#user_name').val();
	var password	= $('#password').val();
	var confirm_password = $('#confirm_password').val();
	var biometric_id	= $('#biometric_id').val();
	var unique_id = $('#unique_id').val();



		var data ={

			"staff_name":staff_name,
			"father_name":father_name,
			"age"		:age,
			"gender_name": gender_name,
			
			"mobile_num" : mobile_num,
			"district_name" : district_name,
			"taluk_name"	: taluk_name,
			"address"		: address,
			"aadhaar_no"	: aadhaar_no,
			"doj"			: doj,
			"department"	: department,
			"department_new"   :   designation,
			"email_id"		: email_id,
			"district_office" : district_office,
			"taluk_office"	: taluk_office,
			"hostel_office"	: hostel_name,
			// "current_date": current_date,
			"user_name"		:user_name,
			"password"		: password,
			"confirm_password" : confirm_password,
			"biometric_id" : biometric_id,
			"unique_id"		: unique_id,
			"action"		: "createupdate"

		}

        // data 		+= "&unique_id="+unique_id+"&action=createupdate";
        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url      = sessionStorage.getItem("list_link");
        // console.log(data);
        $.ajax({
			type 	: "POST",
			url 	: ajax_url,
			data 	: data,
			beforeSend 	: function() {
				$(".createupdate_btn").attr("disabled","disabled");
				$(".createupdate_btn").text("Loading...");
			},	
			success: function(data) {
				var obj     = JSON.parse(data);
				var msg     = obj.msg;
				var status  = obj.status;
				var error   = obj.error;
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
				sweetalert(msg,url);
			},
			error 		: function(data) {
				alert("Network Error");
			}
		});
    // } else {
    //     sweetalert("form_alert");
    // }
}
function establishment_registration_delete(unique_id = "") {
	var ajax_url = sessionStorage.getItem("folder_crud_link");
	var url      = sessionStorage.getItem("list_link");
	
	confirm_delete('delete')
	.then((result) => {
		if (result.isConfirmed) {
			var data = {
				"unique_id" 	: unique_id,
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

function go_staff_filter(){

	init_datatable(table_id,form_name,action);

}

function view_print(unique_id="") {
	// alert(unique_id);
	
	var external_window = window.open('folders/establishment_registration/print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');

// external_window.print();
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

function get_tashil(){
	var hostel_name = $("#hostel_tash").val();
	$("#host_tash").val(hostel_name)
}

function go_staff_filter(){

	var district_name = $('#district_name').val();
	var taluk_name = $('#taluk_name').val();
	var hostel_name = $('#hostel_name').val();
	var designation = $('#department_new').val();

	// var ajax_url = sessionStorage.getItem("folder_crud_link");

	var data = {
		// "student_id"	: student_id,
		"district_name": district_name,
		"taluk_name": taluk_name,
		"hostel_name": hostel_name,
		"department_new": designation,
		// "current_date":current_date,
		"action": 'datatable',

	};
// alert(data);

	init_datatable(table_id,form_name,action,data);

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

function taluk(){
	
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

function get_taluk(){
	
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
function get_hostel(){
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

function get_taluk(){
	
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

function get_hostel_name(){

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
    alert();
    var employment_course = $('#employment_course').val();
    var textBoxContainer = document.getElementById("textBoxContainer");

    // Check if the selected value requires showing the text box
    if (employment_course === "employment" || employment_course === "course") {
        textBoxContainer.style.display = "block"; // Show the text box container
    } else {
        textBoxContainer.style.display = "none"; // Hide the text box container
    }
}


function password_check(){
	
	
	var password = $('#password').val();
	var confirm_password = $('#confirm_password').val();

	if(password!==confirm_password){
		alert('Password do not match');
	}
	if (password.length < 8) {
        alert("Password must be at least 8 characters long");
        return false; 
    }

    return true; 
	
}

