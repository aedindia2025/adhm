$(document).ready(function () {
//    ticket_Filter();
//    get_applied_leave();
   get_holiday_details();
   get_notification_details();
   get_leave_details();
    // get_task_details();
    // get_region_details();
    // get_top_most_completed();
    // get_top_most_complaints();
    // registered_complaints();
    // top_most_completed();
    // overall_complaint_status();
    // sourcewise_complaints();
});
function get_month_details(){
   
    get_region_details();
    get_top_most_completed();
    get_top_most_complaints();
    registered_complaints();
    top_most_completed();
   
}
function ticket_Filter()
{  
    init_datatable(table_id,form_name,action);
}

function get_applied_leave(){
    // var month = $("#month_filter").val();
    var data = 
        {
            "action"           : "get_leave",
            "month"            : month
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        success:function(data)
        {
            var obj   = JSON.parse(data);
            var data     = obj.data;
            $('#leave_date').val(data);
        }
   });
}



function get_region_details(){
    $("#loading-image").show();
    var month = $("#month_filter").val();
    var user_type_unique_id = $('#user_type_unique_id').val();
   ajax_url;
    var data = 
        {
            "action"           : "region_details",
            "user_type_unique_id":user_type_unique_id,
            
            "month"            : month
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    alert(ajax_url);
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        success:function(data)
        {
            $("#loading-image").hide();
            var obj   = JSON.parse(data);
            var data     = obj.data;
            $('#region_details_div').html(data);
        }
   });
}

function get_leave_details(){
    // $("#loading-image").show();
    // var month = $("#month_filter").val();
    // var user_type_unique_id = $('#user_type_unique_id').val();
   
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    var data = 
        {
            "action"           : "applied_leave_details",
            

          
        };
    // var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        success:function(data)
        {
            // $("#loading-image").hide();
            var obj   = JSON.parse(data);
            var data     = obj.data;
            var from_date = obj.from_date;
            var no_of_days  = obj.no_of_days;
            var approval_status  = obj.approval_status;

            var button = document.getElementById("approval_status");
            if (approval_status) {
                
                if (approval_status == 'Pending'){
                    
                    button.style.backgroundColor = "navyblue";
                   

                } else if (approval_status == 'Approved'){
                    button.style.backgroundColor = "green";

                } else if(approval_status === 'Rejected') {
                    button.style.backgroundColor = "red";
                }
                button.style.display = "block";
            } else {
                
                button.style.display = "none";
            }

            if(no_of_days !=null){

                    $('#no_of_days').html(no_of_days);
            }
            else{
                $('#no_of_days').html('0');
            }
            // var button = document.getElementById("approval_status");
            // if (approval_status) {
             
            //     button.style.display = "block";
            
            //     if (approval_status === '1') {

            //         button.style.backgroundColor = "blue";
            //     } else if (approval_status === '2') {
            //         button.style.backgroundColor = "green";
            //     } else {
            //         button.style.backgroundColor = "red";
            //     }
            // } else {
            //     // If approval_status is falsy, hide the button
            //     button.style.display = "none";
            // }
            

            $('#approval_status').html(approval_status);
            $('#from_date').html(from_date);


        }
   });
}

// function get_holiday_details()  
// {
    
//     var ajax_url = sessionStorage.getItem("folder_crud_link");
//     var url      = sessionStorage.getItem("list_link");
//     //     var fil_date = $("#filter_date").val();
//     //    alert(fil_date);
         
//        var data = {
//                 // "region_name":region_name,
//                 // "user_type_unique_id":user_type_unique_id,
//                 // "branch_name" :branch_name,
//                 // "cate" : cate,
//                 // "branch_id" : branch_id,
//                 "action"    : "holiday_details"
//             }
//             // task_details

//             $.ajax({
//                 type    : "POST",
//                 url     : ajax_url,
//                 data    : data,
//                 success : function(data) 
//                 {
//                     var obj     = JSON.parse(data);

//                     var holiday_data = obj.holiday_details;
//                     var date         = obj.date;
//                     var holiday      = obj.holiday;


                    
        
//             $('#no_of_days').html(no_of_days);
                    
//                     var holiday_li = "";
//                     Object.keys(holiday_data).forEach(function(key, index) {
//                         var iClassName = index % 2 === 0 ? "text-primary" : "link-danger";
//                         // 
//                         var dClassName = index % 3 === 0 ? "text-primary" : (index % 3 === 1 ? "link-success" : "link-danger");


//                         // holiday_li += '<li class=" mb-1  psd"><p class=" mb-1 font-13"><i class="mdi mdi-calendar"></i>' + holiday_data[key].date +
//                         // '</p><h5 class="'+ h5ClassName +'">'+ holiday_data[key].holiday +'</h5></li>';
// holiday_li += '<div class=" align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center "><div class="flex-shrink-0 me-2"><h4 class="'+ iClassName +'"><i class="uil-calender widget-icon  bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my-0"><h5 class="'+ dClassName +'">'+ holiday_data[key].holiday+'</h5></h5></div><p class="mb-0 fw-semibold"></p>'+holiday_data[key].date+'</div></div>';
                    
//                         $('#holiday_list').html(holiday_li);

//                     });
                    

//                 }
//             });
// }

function get_holiday_details()  
{
    
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    //     var fil_date = $("#filter_date").val();
    //    alert(fil_date);
         
       var data = {
                // "region_name":region_name,
                // "user_type_unique_id":user_type_unique_id,
                // "branch_name" :branch_name,
                // "cate" : cate,
                // "branch_id" : branch_id,
                "action"    : "holiday_details"
            }
            // task_details

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);

                    var holiday_data = obj.holiday_details;
                    var date         = obj.date;
                    var holiday      = obj.holiday;


                    
        
            
                    
                    var holiday_li = "";
                    Object.keys(holiday_data).forEach(function(key, index) {
                        var iClassName = index % 2 === 0 ? "text-primary" : "link-danger";
                        // 
                        var dClassName = index % 3 === 0 ? "text-primary" : (index % 3 === 1 ? "link-success" : "link-danger");


                        // holiday_li += '<li class=" mb-1  psd"><p class=" mb-1 font-13"><i class="mdi mdi-calendar"></i>' + holiday_data[key].date +
                        // '</p><h5 class="'+ h5ClassName +'">'+ holiday_data[key].holiday +'</h5></li>';
holiday_li += '<div class="align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center "><div class="flex-shrink-0 me-2"><h4 class="'+ iClassName +'"><i class="uil-calender widget-icon  bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my-0"><h5 class="'+ dClassName +'">'+ holiday_data[key].holiday+'</h5></h5></div><p class="mb-0 fw-semibold"></p>'+holiday_data[key].date+'</div></div>';
                    
                        $('#holiday_list').html(holiday_li);

                    });
                    

                }
            });
}

function get_notification_details()  
{
//  alert('hi');   
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    //     var fil_date = $("#filter_date").val();
    //    alert(fil_date);
         
       var data = {
                // "region_name":region_name,
                // "user_type_unique_id":user_type_unique_id,
                // "branch_name" :branch_name,
                // "cate" : cate,
                // "branch_id" : branch_id,
                "action"    : "notification_details"
            }
            // task_details

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);

                    var notification_data = obj.notification_details;
                    var date         = obj.date;
                    var title      = obj.title;
                    var content      = obj.content;


                    
                    var notification_li = "";
                    Object.keys(notification_data).forEach(function(key, index) {
                        var h5ClassName = index % 2 === 0 ? "text-primary" : "link-danger";
                        var dClassName = index % 3 === 0 ? "text-primary" : (index % 3 === 1 ? "link-success" : "link-danger");
                        // var h5ClassName = index === 3 ? "text-primary" : "link-green";

     notification_li += '<div class="align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center br-noti"><div class="flex-shrink-0 me-2"><i class="mdi mdi-information-outline widget-icon rounded-circle"></i> </div><div class="flex-grow" style="margin-left:2px;"><h5 class="fw-semibold my-0 "><h4 class="'+ h5ClassName +'"><i class=" bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my margin-left:20px;"><h5 class="'+ dClassName +'">'+ notification_data[key].title+'</h5></h5> </div><p class="mb-0 fw-semibold"> '+notification_data[key].date +' </p></div> <div class="noti-des"><p class="mb-0">'+ notification_data[key].content+'</p></div></div>';
                    
                        // $('#notification_list').html(notification_li);


                        // notification_li += '<div class=" align-items-center border border-light rounded p-1 mb-1"><div class="d-flex align-items-center "><div class="flex-shrink-0 me-2"><h4 class="'+ h5ClassName +'"><i class="uil-calender widget-icon  bg-warning-lighten text-warning" rounded-circle"></i></h4></h5></div><div class="flex-grow-1"><h5 class="fw-semibold my-0"><h5 class="'+ dClassName +'">'+ notification_data[key].date+'</h5></h5></div><p class="mb-0 fw-semibold"></p>'+ notification_data[key].title +'</div></div>'+ notification_data[key].content +'</h5></li>';;
                    
                        $('#notification_list').html(notification_li);

                    });
                    

                }
            });
}



function get_top_most_completed(){
    var month = $("#month_filter").val();
    var data = 
        {
            "action"           : "top_most_completed",
            "month"            : month
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        success:function(data)
        {
            var obj   = JSON.parse(data);
            var data     = obj.data;
            $('#top_most_completed').html(data);
        }
   });
}

function get_top_most_complaints(){
    var month = $("#month_filter").val();
    var data = 
        {
            "action"           : "top_most_complaints",
            "month"            : month
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        success:function(data)
        {
            var obj   = JSON.parse(data);
            var data     = obj.data;
            $('#top_most_complaints').html(data);
        }
   });
}

function get_task_details()  
{
    
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
        var region_name = $("#region_name").val();
         var user_type_unique_id = $('#user_type_unique_id').val();
          var branch_name = $('#branch_name').val();
         
          var cate = $('#cate').val();
          var branch_id = $('#branch_id').val();
          $('#opening_complaints').empty();
          $('#new_complaints').empty();
          $('#completed_complaints').empty();
          $('#pending_complaints').empty();
    var data = {
                "region_name":region_name,
                "user_type_unique_id":user_type_unique_id,
                "branch_name" :branch_name,
                "cate" : cate,
                "branch_id" : branch_id,
                "action"    : "task_details"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);

                    var pending_complaints      = obj.pending_complaints;
                    

                    if(pending_complaints == null){
                        
                        var pending_count = '0';
                    }else{
                        var pending_count = obj.pending_complaints;
                    }
                    
                    var opening_complaints      = obj.opening_complaints;
                    
                    if(opening_complaints == null){
                        
                        var opening_count = 0;
                    }else{
                        var opening_count = obj.opening_complaints;
                    }
                    
                    var new_complaints          = obj.new_complaints;
                
                    if(new_complaints == null){
                        var new_count = 0;
                    }else{
                        var new_count = new_complaints;
                    }
                    
                    var completed_complaints    = obj.completed_complaints;
                   
                    if(completed_complaints == null){
                        var completed_count = 0;
                    }else{
                        var completed_count = completed_complaints;
                    }
                    
                    $('#opening_complaints').html(opening_count);
                    $('#new_complaints').html(new_count);
                    $('#completed_complaints').html(completed_count);
                    $('#pending_complaints').html(pending_count);
                }
            });
}

function overall_complaint_status()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "over_complaint_details"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var total_comp          = obj.total_comp;
                    var pending_comp        = obj.pending_comp;
                    var progressing_comp    = obj.progressing_comp;
                    var completed_comp      = obj.completed_comp;
                    var cancel_comp         = obj.cancel_comp;

                    $('#pending_comp').html(pending_comp);
                    $('#progressing_comp').html(progressing_comp);
                    $('#completed_comp').html(completed_comp);
                    $('#total_comp').html(total_comp);
                    $('#cancel_comp').html(cancel_comp);
                }
            });
}


function sourcewise_complaints()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "sourcewise_complaints"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var app          = obj.app;
                    var web        = obj.web;
                    var admin_portal    = obj.admin_portal;
                    var chatbot      = obj.chatbot;
                   
                    $('#web').html(web);
                    $('#admin').html(admin_portal);
                    $('#chatbot').html(chatbot);
                    $('#app').html(app);
                   
                }
            });
}

function new_external_window_print(event, url,status) {
     
        var link = url+ '?status=' + status;
		// window.location=link;
		onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }


function showMonthPicker() {
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"];
        var list = "<select id='tempMonthPicker' onchange='pickMonth(this.value);'>";
        for (var i = 0; i < monthNames.length; i++) {
            list += "<option value='" + (i+1) + "'>" + monthNames[i] + "</option>";
        }
        list += "</select>";
    
        // Display the list somewhere, e.g., insert it after the input field.
        document.getElementById("monthInput").insertAdjacentHTML('afterend', list);
    }
    
    function pickMonth(value) {
        // var monthInput = document.getElementById("monthInput");
        var tempPicker = document.getElementById("tempMonthPicker");
    
        // Assuming you want to display the month name, but store the month number
        monthInput.value = value; // Or use the month name from an array or object mapping
    
        // Remove the temporary picker after selection
        tempPicker.parentNode.removeChild(tempPicker);
    }


