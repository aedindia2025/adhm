   $(document).ready(function () {
        // var table_id 	= "user_type_datatable";
        go();
    });
    // document.getElementById('currentYear').textContent = new Date().getFullYear();
    var company_name 	= sessionStorage.getItem("company_name");
    var company_address	= sessionStorage.getItem("company_name");
    var company_phone 	= sessionStorage.getItem("company_name");
    var company_email 	= sessionStorage.getItem("company_name");
    var company_logo 	= sessionStorage.getItem("company_name");
    
    var form_name 		= 'User Type';
    var form_header		= '';
    var form_footer 	= '';
    var table_name 		= '';
    var table_id 		= 'grievance_datatable';
    var action 			= "datatable";
    function go(){
        // alert("hii");
        init_datatable(table_id,form_name,action);
    }
    function init_datatable(table_id='',form_name='',action='') {
        // alert("hii");
        
        var district_name = $('#district_id').val();
        var taluk_name = $('#taluk_id').val();
        var hostel_name = $('#hostel_id').val();
        var from_date = $('#form_date').val();
        var to_date   = $('#to_date').val();
        var status   = $('#grievance_category').val();
        
        var table = $("#"+table_id);
        var data      = {
            "from_date" : from_date,
            "to_date"   : to_date,
            "status" : status,
            "district_name": district_name,
            "taluk_name": taluk_name,
            "hostel_name": hostel_name,
            "action"    : action, 
        };
        var ajax_url = sessionStorage.getItem("folder_crud_link");
    
        var datatable = table.DataTable({
        ordering : true,
       // searching : true,
        "searching" : false,
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
			title: 'Grievance List'
		},
		{
			extend: 'csvHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Grievance List',
			filename: 'grievance_category'
		},
		{
			extend: 'excelHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Grievance List',
			filename: 'grievance_category'
		},
		{
			extend: 'pdfHtml5',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Grievance List',
			filename: 'grievance_category'
		},
		{
			extend: 'print',
			exportOptions: {
				columns: ':not(:last-child)'
			},
			title: 'Grievance List'
		}
		]
        });
    }

    function grievance_category_cu() {
        // alert("jiii");
    var internet_status = is_online();
    var data = new FormData();

    if (!internet_status) {
        sweetalert("no_internet");
        return false;
    }
    var unique_id = document.getElementById('unique_id').value;
    
    var student_name = document.getElementById('student_name').value;
    var grievance_no = document.getElementById('gr_no').value;
    var csrf_token = document.getElementById('csrf_token').value;
    var std_reg_no = document.getElementById('std_reg_no').value;
    var district_name = document.getElementById('district_name').value;
    var taluk_name = document.getElementById('taluk_name').value;
    var hostel_name = document.getElementById('hostel_name').value;   
    var tahsildar_name = document.getElementById('tahsildar_name').value;
    var hostel_id = document.getElementById('hostel_id').value;   
    var grievance_category = document.getElementById('grievance_category').value;   
     var description = document.getElementById('description').value;
     var district_id = document.getElementById('district_id').value;
     var taluk_id = document.getElementById('taluk_id').value;
     var hostel_main_id = document.getElementById('hostel_main_id').value;

     var file_name = document.getElementById('file_name').value;

    

    var image_s = $("#test_file");

    var files = document.getElementById('test_file').files;

    if (image_s != '') {
		var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf|\.xlsx|\.xls)$/i;  // Regular expression for allowed extensions
			for (var i = 0; i < image_s.length; i++) {
				{
					data.append("test_file", document.getElementById('test_file').files[i]);
				}
			}
		} else {
			data.append("test_file", '');
		}
        
        var action = "createupdate";

       
        data.append("student_name", student_name);
        data.append("gr_no", grievance_no);
        data.append("csrf_token",csrf_token);
        data.append("std_reg_no", std_reg_no);
        data.append("district_name", district_name);
        data.append("taluk_name", taluk_name);
        data.append("hostel_name", hostel_name);
        data.append("tahsildar_name", tahsildar_name);
        data.append("hostel_id", hostel_id);
        data.append("grievance_category", grievance_category);
        data.append("description", description);
        data.append("district_id", district_id);
        data.append("taluk_id", taluk_id);
        data.append("hostel_main_id", hostel_main_id);
        data.append("unique_id", unique_id);
        data.append("action", "createupdate");

        var ajax_url = sessionStorage.getItem("folder_crud_link");
        var url = sessionStorage.getItem("list_link");

        if((image_s !='')||(file_name !='')){
        
        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            // beforeSend 	: function() {
            // 	$(".createupdate_btn").attr("disabled","disabled");
            // 	$(".createupdate_btn").text("Loading...");
            // },
            success	: function(data) {

                // alert(data);
                
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


    } else {
        sweetalert("form_alert");
    }
}

function grievance_print(unique_id="") {
	// alert(unique_id);
	
	var external_window = window.open('folders/grievance_category/print.php?unique_id='+unique_id, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
// external_window.print();
// external_window.print();
}


    
    function grievance_category_delete(unique_id = "") {

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
    
         
            var district_name = $('#district_id').val();
            var taluk_name = $('#taluk_id').val();
            var hostel_name = $('#hostel_id').val();
        
        
        
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var data = {
                // "student_id"	: student_id,
                "district_name": district_name,
                "taluk_name": taluk_name,
                "hostel_name": hostel_name,
                // "current_date":current_date,
                "action": 'datatable',
        
            };
        
        
            init_datatable(table_id,form_name,action,data);
        
        }
        
    

    
  
    
    
        function get_zone_name() {
            // alert("hii");
            var district_name = document.getElementById('district_name').value;
            // alert(district_name);
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            if (district_name) {
                var data = {
                    "district_name": district_name,
                    "action": "get_zone_name"
                }
        
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    success: function (data) {
        // alert(data);
                        if (data) {
                            $("#zone_name").html(data);
                        }
                    }
                });
            }
        }
    
        function generate_otp(){
            var otp = Math.floor(1000 + Math.random() * 9000);
            
            $("#otp_no").val(otp);
            $("#verify_otps").val(otp);
            var student_name = $("#student_name").val();
            var contact_number   = $("#contact_number").val();
    
            document.getElementById('student').value = student_name;
            num = [];
            var num = contact_number.split("");
            var mynum = '******'+num[7]+num[8]+num[9];
            document.getElementById('mobile').value = mynum;
    
             otps = [];
            var otps = otp.toString().split("");
    
            // alert(otps[0]+otps[1]+otps[2]+otps[3]);
            
            document.getElementById('otp1').value = otps[0];
            document.getElementById('otp2').value = otps[1];
            document.getElementById('otp3').value = otps[2];
            document.getElementById('otp4').value = otps[3];
            get_ids();
    
            
        }
    
    function get_ids(){
        
        $("#application_no").empty();
                                $("#app_no").empty();
        var user_acc_year = $("#user_acc_year").val();
        var district_name = $("#district_name").val();
                    var zone_name = $("#zone_name").val();
                    var hostel_name = $("#hostel_name").val();
                    var data = {
                        "user_acc_year" : user_acc_year,
                        "district_name": district_name,
                        "zone_name": zone_name,
                        "hostel_name": hostel_name,
    
                        "action": "get_id_name"
                    }
                    var ajax_url = sessionStorage.getItem("folder_crud_link");
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data,
                        success: function (data) {
            // alert(data);
                            if (data) {
                                $("#application_no").val(data);
                                $("#app_no").append(data);
                                
                            }
                        }
                    });
                // }
    }
        
        
        function verify(){
            
            var is_form = form_validity_check("was-validated");
            
                if (is_form) {
                    
                    $('.modal').modal('show');
    }else{
                    sweetalert("form_alert");
                    // $('#demo').model('hide');	
                }
        }
        // unique_id = ""
        function verify_otp(unique_id=""){
            
            var otp = $("#otp_no").val();
            
            
            var a = document.getElementById('otp1').value;
            var b = document.getElementById('otp2').value;
            var c = document.getElementById('otp3').value;
            var d = document.getElementById('otp4').value;
            var verify_otp = a+b+c+d;
            // alert(verify_otp);
            if(otp == verify_otp){
                
            // //    debugger;
                // var academic_year = $("#user_acc_year").val();
                // var student_name = $("#student_name");
                // var standard   = $("#standard");
                // var district_name = $("#district_name");
                // var zone_name = $("#zone_name");
                // var hostel_name = $("#hostel_name");
                // var contact_number = $("#contact_number");
                
                
                var internet_status  = is_online();
            
                if (!internet_status) {
                    sweetalert("no_internet");
                    return false;
                }
            
                // var is_form = form_validity_check("was-validated");
            
                // if (is_form) {
            
                    var data 	 = $(".was-validated").serialize();
                    data 		+= "&unique_id="+unique_id+"&action=createupdate";
            
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
                        success		: function(data) {
            
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
            
                            sweetalert(msg);
                            $(".modal").modal('hide');
                            new_external_window_print(event,'folders/apply_application_form/print.php','print');
                        },
                        error 		: function(data) {
                            alert("Network Error");
                        }
                    });
            
            
                // } else {
                // 	sweetalert("form_alert");
                // }
            // alert("otp verified");
            // sweetalert("otp");
            }else{
    
                sweetalert("otp_verify");
                // alert("Please Enter valid OTP");
            }
        
        }
    {/* <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> */}
    
        function new_external_window_print(event, url,print) {
            // alert("hii");
            var application_no=document.getElementById('application_no').value;
            
        
            //  alert();
            var link = url+ '?print=' + print+'&application_no='+application_no;
            onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
        }
        
        function new_external_window_prints(event, id) {
            // alert("hii");
            
            alert(id);
            var application_no=id;
        var url ='folders/apply_application_form/print.php?application_no='+application_no;
            //  alert();
            // var link = url+ '?print=' + print+'&application_no='+application_no;
            // var link = url;
            onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
        }
        function get_hostel_name() {
            //  alert("hii");
            var zone_name = document.getElementById('zone_name').value;
            // alert(zone_name);
            var ajax_url = sessionStorage.getItem("folder_crud_link");
            if (zone_name) {
                var data = {
                    "zone_name": zone_name,
                    "action": "get_hostel_name"
                }
        
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: data,
                    success: function (data) {
    //  alert(data);
                        if (data) {
                            $("#hostel_name").html(data);
                        }
                    }
                });
            }
        }
    
        
    