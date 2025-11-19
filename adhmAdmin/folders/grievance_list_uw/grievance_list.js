
    





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
        var from_date = $('#form_date').val();
        var to_date   = $('#to_date').val();
        var status   = $('#grievance_category').val();

        var district_name   = $('#district_name').val();
        var taluk_name   = $('#taluk_name').val();
        var hostel_name   = $('#hostel_name').val();
        var academic_year   = $('#academic_year').val();
        
        var table = $("#"+table_id);
        var data      = {
            "from_date" : from_date,
            "to_date"   : to_date,
            "status" : status,
            "district_name" : district_name,
            "taluk_name"   : taluk_name,
            "hostel_name" : hostel_name,
            "academic_year"   : academic_year,
            "action"    : action, 
        };
        var ajax_url = sessionStorage.getItem("folder_crud_link");
    
        var datatable = table.DataTable({
        ordering : true,
        searching : true,
        "searching" : true,
        "ajax"		: {
            url 	: ajax_url,
            type 	: "POST",
            data 	: data
        },
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
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
    function apply_application_form_cu(unique_id = "") {
    //    debugger;
        var academic_year  = $("#user_acc_year").val();
        var student_name   = $("#student_name");
        var standard       = $("#standard");
        var district_name  = $("#district_name");
        var zone_name      = $("#zone_name");
        var hostel_name    = $("#hostel_name");
        var contact_number = $("#contact_number");
        
        
        var internet_status  = is_online();
    
        if (!internet_status) {
            sweetalert("no_internet");
            return false;
        }
    
        var is_form = form_validity_check("was-validated");
    
        if (is_form) {
    
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
    
    function apply_application_form_delete(unique_id = "") {
    
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
    
    // function get_student_details(student_name = "") {
        
    //     	var ajax_url = sessionStorage.getItem("folder_crud_link");
    // 	if (student_name) {
    // 		$.ajax({
    // 			type : "POST",
    // 			url  : ajax_url,
    // 			data : {
    // 				student_name : student_name,
    // 				action   : "student_details"
    // 			},
    // 			success : function (res) {
    // 				console.log(res);
    
    // 				var details = JSON.parse(res);
    
    // 				$("#student_name").val(details['student_name']);
    // 				$("#district_name").val(details['district_name']);
    // 				$("#zone_name").val(details['zone_name']);
    // 				$("#hostel_name").val(details['hostel_name']);
    // 			}
    // 		});
    // 	}	
    // }
    
    
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
    
        
    