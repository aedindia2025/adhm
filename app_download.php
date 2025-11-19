<?php include 'header.php' ?>
<?php include 'function.php' ?>

<style>

.load {
		text-align: center;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		display: none;

	}

	i.mdi.mdi-loading.mdi-spin {
		font-size: 75px;
		color: #17a8df;
	}
	.home-bt h3 i {
		background: #fff;
		color: #00aff0;
		border-radius: 50px;
		padding: 4px 7px;
		margin-left: 9px;
	}

	.home-bt h3 {
		color: #fff;
		text-align: end;
		display: inline-block;
		padding: 9px 5px 9px 14px;
		border-radius: 50px;
		font-size: 16px;
		background: linear-gradient(to right, #25bff9, #0890c3);
	}

	h3.form-hed {
		color: #0a93c7;
		font-size: 22px;
		border: 0px solid #14a4d97d;
		border-bottom: 0px solid #14a4d97d;
		padding: 20px;
		border-radius: 0.3125rem;
		background-color: #ffffff;
		font-weight: 800;

		margin: 0px;
	}

	.form-img img {
		width: 100%;
	}

	.form-control:focus {
		box-shadow: none;
		border-color: #13a2d7;
	}

	.normal-imput input {
		color: #000;
		margin-bottom: 0px;
		border: 0px;
		border-bottom: 1px solid #dee2e6;
		border-radius: 0px;
		font-weight: 700;
		padding: 5px;

	}

	.normal-imput label {
		color: #505050;
		margin-bottom: 0px;
	}

	.normal-imput select {
		color: #000;
		margin-bottom: 15px;
		border: 0px;
		border-bottom: 1px solid #dee2e6;
		border-radius: 0px;
		font-weight: 700;
		padding: 5px;

	}

	.comm-btn {
		background: linear-gradient(to right, #25bff9, #0890c3);
		border: 0px;
		padding: 10px;
		color: #fff;
		border-radius: 5px;
		font-weight: 600;
		font-size: 15px;
		outline: 0;
	}

	.comm-btn:hover {
		background: linear-gradient(to right, #0890c3, #25bff9);
	}

	table.app-table {
		width: 100%;
	}

	.sep-mar {
		border: 1px solid #14a4d97d;
		margin: 0px 10px 10px;

	}

	.model-design img {
		width: 51%;
	}

	div#inputs input {
		width: 32px;
		height: 32px;
		text-align: center;
		border: none;
		border-bottom: 1.5px solid #d2d2d2;
		margin: 0px 10px;
		outline: none;
		font-size: 21px;
		font-weight: 700;
	}

	div#inputs input:focus {
		border-bottom: 1.5px solid #13a2d7;
		outline: none;
	}

	.out-mar {
		border: 1px dashed #0f9bd0;
		margin: 10px;
		background: aliceblue;
	}
</style>

<?php 
   include "config/dbconfig.php";
//    include "assets/js/common.js";
//    include "config/common_fun.php";

$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

?>

<div class="container">
	<div class="row mt-4">
		<div class="col-md-6">
			<div class="ad-logo">
				<a href="index.php"><img src="img/ad-logo.png"></a>
			</div>
		</div>
		<div class="col-md-6 home-bt text-end">
				<a href="index.php"><h3>Home <i class="mdi mdi-home"></i></h3></a>
			<!-- <h3>Home <i class="mdi mdi-home"></i></h3> -->
		</div>
	</div>

	<div class="card mt-4">
		<div class="row ">
			<div class="col-md-12">
				<div class="form-img">
					<img src="img/new-form.png">
				</div>
				<h3 class="form-hed">விண்ணப்பப் பதிவிறக்கம் / Application Download</h3>
			</div>
		</div>
		<div class="card-body sep-mar">
			<div class="row spel-app normal-imput ">
			       <div class="col-md-6">
				   <div class="row">
			       <div class="col-md-6">
					<label class="form-label" for="aadhar_no">மாணாக்கரின் ஆதார் எண்ணினை பதிவிடவும்  / Enter the student Aadhaar Number</label>
                      </div>
					  <div class="col-md-6"><input type="text" id="aadhar_no" name="aadhar_no" class="form-control" maxlength="12"
					   oninput="valid_aadhar_number(this)"></div>
					  
					  
					  <!-- <div class="col-md-6"><label class="form-label" for="dob">பிறந்த தேதி / DOB</label></div>
					  <div class="col-md-6"><input type="date" id="dob" name="dob" class="form-control"></div> -->
					  </div>
					  </div>
					 
					   <!-- <div class="col-md-6">
				   <div class="row">
					  <div class="col-md-6"><label class="form-label" for="app_number">விண்ணப்பதாரர் எண் / Applicant No</label></div>
					  <div class="col-md-6"><input type="text" id="app_number" name="app_number" class="form-control"></div>
					 </div>
					 
					  </div> -->
					  
					   <!-- <div class="col-md-12">
						<span id="admissionMessage" style="color: red; display: none;font-weight: 700;font-size: 15px;">You can proceed with the application only after you've received admission to the college.</span>
					  </div> -->
			
			
				<!--<div class="col-md-7">
					<div class="row">
                           
					<div class="col-md-12">
							<table class="app-table normal-imput">
								<tr>
									<td class="col1"><label class="form-label" for="amc_name">கல்வி ஆண்டு / Academic Year</label></td>
									<td class="col2"><input type="text" id="amc_name"name="amc_name" readonly
											class="form-control" readonly="" value="2023-2024"></td>
								</tr>
                                <tr>
									<td class="col1"><label class="form-label" for="dob">பிறந்த தேதி / DOB</label></td>
									<td class="col2"><input type="date" id="dob" name="dob" class="form-control">
									</td>
								</tr>
                                
								<tr>
									<td  colspan="2">
									<span id="admissionMessage" style="color: red; display: none;font-weight: 700;font-size: 15px;">You can proceed with the application only after you've received admission to the college.</span>

									</td>
								</tr>

							</table>
						</div>
					</div>
				</div>-->
				<!-- <div class="col-md-5">
					<div class="row ">
						<div class="col-md-12">
							<table class="app-table normal-imput">
                                <tr>
                                    <td class="col1"><label class="form-label" for="app_number">விண்ணப்பதாரர் எண் / Applicant No</label></td>
                                    <td class="col2"><input type="text" id="app_number" name="app_number" class="form-control">
                                    </td>
                                </tr>
							</table>
						</div>
					</div>
				</div> -->
			</div>
			<div class="row">
				<div class="col-md-12 mt-3 align-self-center text-center">
					<input type="button" data-bs-toggle="modal" data-bs-target="#warning-alert-modal" class="comm-btn"
                    onclick="get_uuid()" value="Search">
				</div>
			</div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-12 load" id="loader">
		<i class="mdi mdi-loading mdi-spin"></i>
	</div>
</div>


<?php include 'footer.php' ?>
<script>
function valid_aadhar_number(input) {

const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
];

// Filter out characters that are not in the allowedChars array
input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}
// document.addEventListener('contextmenu', function(event) {
//     event.preventDefault();
//               });

//               document.onkeydown = function(e)
//     {
//         if(event.keyCode == 123)
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
//         {
//             return false;
//         }
//     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
//     {
//       return false;
//     }
//     }

function showLoader() {
		$("#loader").css("display", "inline-block"); // or "block" depending on your preference
	}

	function hideLoader() {
		$("#loader").css("display", "none");
	}


	function get_uuid() {

var aadhar_no = $("#aadhar_no").val();
// showLoader();

if (aadhar_no != '') {




	var ajax_url = "crud_v1.php";
	var data = {
		"aadhar_no": aadhar_no,
		"action": "download_check_aadhar"
	};

	showLoader();

	$.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		dataType: 'json', // Parse response as JSON

		success: function (response) {

			if (response.data) {
				// Handle success
				
				//console.log(response.data.RESPONSE);

				var status = response.data.RESPONSE.STATUS;
				var uuid = response.data.RESPONSE.UUID;
				
if(uuid != ''){
				var data = {
					"uuid": uuid,
					"action": "app_download"
				};

				$.ajax({
            type: "POST",
			url: 'app_crud.php',
			data: data,
                   success	: function(data) {
					hideLoader();
                       var obj     = JSON.parse(data);
                       var msg     = obj.msg;
                       var s1_unique_id = obj.s1_unique_id;
                       var status  = obj.status;
                       var error   = obj.error;
					   

					
                        if(msg == 'found') {
						
                           
                           window.location.href = 'print_app.php?unique_id='+s1_unique_id;

                        } else {
                            log_sweetalert("not_found");
                        }
                   },
                   error 		: function(data) {
					hideLoader();
                       alert("Network Error");
                   }
               });
			}else{
				hideLoader();
				log_sweetalert('not_found');
			   }
			}
		},
		error 		: function(data) {
			hideLoader();
                       alert("Network Error");
                   }

			});
       

} else {
	hideLoader();
	sweetalert("form_alert");
	$("#gen_otp").prop("disabled", false);
}

}


function status_verify() {
  
showLoader();

var app_number = $('#app_number').val();

var date = $('#dob').val();


if (app_number != '' && date !='') {

    var data = {
        "app_number" : app_number,
        "date": date,       
        "action": "app_download"
    }
		
              
        $.ajax({
            type: "POST",
			url: 'app_crud.php',
			data: data,
                   success	: function(data) {
       
                       var obj     = JSON.parse(data);
                       var msg     = obj.msg;
                       var std_app_no = obj.std_app_no;
                       var s1_unique_id = obj.s1_unique_id;
                       var status  = obj.status;
                       var error   = obj.error;
					   hideLoader();

					
                        if(msg == 'found') {
						
                            //  var external_window = window.open('app_download_print.php?unique_id=' + s1_unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
                            // external_window.print();
                            // print_pdf(s1_unique_id);
                           window.location.href = 'print_app.php?unique_id='+s1_unique_id;

                        } else {
                            log_sweetalert("not_found");
                        }
                   },
                   error 		: function(data) {
                       alert("Network Error");
                   }
               });
       
       
           } else{
			hideLoader();
			log_sweetalert("form_alert");
           }
       }
          

       function print_pdf(file_name) {
    var pdfUrl = "app_download_print.php";
    var link = document.createElement("a");
    link.href = pdfUrl;
    link.setAttribute("download", "your_application.pdf");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}


	   function log_sweetalert(msg='',url='',callback ='',title='') {

switch (msg) {
  case "found":
	Swal.fire({
		icon: 'success',
		title: 'Successfully Saved',
		// text: 'Modal with a custom image.',  
		//imageUrl:'img/emoji/success.webp',
		// imageWidth: 250,
		// imageHeight: 200,
		imageAlt: 'Custom image',
		showConfirmButton: true,
		timer: 3000,
		timerProgressBar: true,
		willClose: () => {
			if (url) {
				window.location = url;
			}
		}
	});
  break;

  case "not_found":
	Swal.fire({
		icon: 'info',
		title: 'No Application is Registered for the provided Aadhar Number. Please Verify the Number and try again',
		imageAlt: 'Custom image',
		showConfirmButton: true,
		timer: 3000,
		timerProgressBar: true,
		willClose: () => {
			if (url) {
				window.location = url;
			}
		}
	});
  break;

  case "aadhaar_not_found":
	Swal.fire({
		icon: 'info',
		title: 'No Application is Registered for the provided Aadhar Number. Please Verify the Number and try again',
		imageAlt: 'Custom image',
		showConfirmButton: true,
		timer: 3000,
		timerProgressBar: true,
		willClose: () => {
			if (url) {
				window.location = url;
			}
		}
	});
  break;

  case "form_alert":
      Swal.fire({
         icon: 'info',
        title: 'Fill Out All Mandatory Fields',
        // imageUrl:'img/emoji/form_fill.webp',
        showConfirmButton: true,
        timer: 6000,
        timerProgressBar: true
      })
    break;


}
	   }
       




</script>