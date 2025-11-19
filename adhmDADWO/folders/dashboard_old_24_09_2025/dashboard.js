$(document).ready(function () {


    get_holiday_details();
    get_notification_details();
    get_application_count();
    get_vacancy_count();  
    get_total_hostels();
    get_total_students();
    get_total_staff();
    applied_leave_details();
    get_attendance_details();
    // new_renewal_chart();
    // get_date_type();


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


function filter(){
    new_renewal_chart();
}

function get_date_type() {
    // alert();
    var date_type = $('#date_type').val();
    // alert(date_type);
    if (date_type == 3) {
        document.getElementById('from_date_div').style.display = 'block';
        document.getElementById('to_date_div').style.display = 'block';
    }
    else {
        document.getElementById('from_date_div').style.display = 'none';
        document.getElementById('to_date_div').style.display = 'none';
    }
}

function showLoader() {
// alert();
    $("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
    $("#loader").css("display", "none");
}

function new_renewal_chart(){
    showLoader();
   
  let chart = null;

    var date_type = $("#date_type").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var district_id = $("#district_id").val();
    var requestData = {
        "action": "get_application_counts",
        "date_type": date_type,
        "to_date": to_date,
        "from_date": from_date,
    };
    $.ajax({
        url: "folders/dashboard/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {
            hideLoader();
            var new_count = '';
            var renewal_cnt = '';
            var obj = JSON.parse(data);
             new_count = obj.new_count;
             renewal_cnt = obj.renewal_cnt;
            // var ?istirct_name = obj.?istirct_name;
            
//Grouped Stacked Bars
var options = {
    series: [
        {
            name: 'New Application',
            group: 'application',
            data: new_count
        },
        {
            name: 'Renewal Application',
            group: 'application',
            data: renewal_cnt
        },
       
    ],
    chart: {
        type: 'bar',
        height: 200,
        stacked: true,
    },
    stroke: {
        width: 1,
        colors: ['#fff']
    },
    // dataLabels: {
    //     formatter: (val) => {
    //         return val / 1000
    //     }
    // },
    plotOptions: {
        bar: {
            horizontal: true
        }
    },
    xaxis: {
        categories: [district_id],
        labels: {
            formatter: (val) => {
                return val
            }
        }
    },
    fill: {
        opacity: 1,
    },
    colors: getChartColorsArray("groupedStackedChart"),
    legend: {
        position: 'top',
        horizontalAlign: 'left'
    }
};

chart = new ApexCharts(document.querySelector("#groupedStackedChart"), options);
chart.render();
        }
    })
    hideLoader();
}

function get_vacancy_count()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "get_vacancy_count"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var tot_cap          = obj.tot_cap;
                    var old_std        = obj.old_std;
                    var approved_cnt    = obj.approved_cnt;
                    var hos_vac      = obj.hos_vac;
                    

                    $('#tot_cap').html(tot_cap);
                    $('#old_std').html(old_std);
                    $('#new_std').html(approved_cnt);
                    $('#hos_vac').html(hos_vac);
                    // $('#cancel_comp').html(cancel_comp);
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


function get_total_hostels(){

  
    var url      = sessionStorage.getItem("list_link");
   
    var data = 
        {
            "action"  : "total_hostels",
            
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
   
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        // hostel_count:hostel_count,
        success:function(data)
        {
            $("#loading-image").hide();
            var obj   = JSON.parse(data);
            var data     = obj.data;
            var hostel_count     = obj.hostel_count;
           
            $('#total_hostel').text(hostel_count);
        }
   });
}

function get_total_students(){

  
    var url      = sessionStorage.getItem("list_link");
   
    var data = 
        {
            "action"  : "total_students",
            
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
   
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        // hostel_count:hostel_count,
        success:function(data)
        {
            $("#loading-image").hide();
            var obj   = JSON.parse(data);
            var data     = obj.data;
            var student_cnt     = obj.student_name;
           
            $('#total_students').text(student_cnt);
        }
   });
}




function get_total_staff(){

  
    var url      = sessionStorage.getItem("list_link");
   
    var data = 
        {
            "action"  : "total_staff_strength",
            
        };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
   
    $.ajax({
        url: ajax_url,
        type:'POST',
        data: data,
        // hostel_count:hostel_count,
        success:function(data)
        {
            $("#loading-image").hide();
            var obj   = JSON.parse(data);
            var data     = obj.data;
            var staff_cnt     = obj.staff_cnt;

            $('#total_staff').text(staff_cnt);
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

function get_application_count()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "get_application_count"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var applied_cnt          = obj.applied_cnt;
                    var accp_cnt        = obj.accp_cnt;
                    var approved_cnt    = obj.approved_cnt;
                    var rejected_cnt      = obj.rejected_cnt;
                    var pen_warden_pr      = obj.pen_warden_pr;
                    var pen_dadwo_pr      = obj.pen_dadwo_pr;
                    

                    $('#appl_cnt').html(applied_cnt);
                    $('#accp_cnt').html(accp_cnt);
                    $('#appr_cnt').html(approved_cnt);
                    $('#rej_cnt').html(rejected_cnt);
                    $('#pen_warden_pr').html(pen_warden_pr);
                    $('#pen_dadwo_pr').html(pen_dadwo_pr);
                    // $('#cancel_comp').html(cancel_comp);
                }
            });
}

function get_attendance_details()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "get_attendance_details"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var total_strength          = obj.total_strength;
                    var present        = obj.present;
                    var absent    = obj.absent;
                   
                    

                    $('#total_strength').html(total_strength);
                    $('#present').html(present);
                    $('#absent').html(absent);
                  
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

function applied_leave_details()  
{
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url      = sessionStorage.getItem("list_link");
    
    
    var data = {
                "action"    : "applied_leave_details"
            }

            $.ajax({
                type    : "POST",
                url     : ajax_url,
                data    : data,
                success : function(data) 
                {
                    var obj     = JSON.parse(data);
                    var data          = obj.data;
                    // var no_of_days        = obj.no_of_days;
                    var no_of_student_name = obj.no_of_student_name;
                  
                    $('#no_of_student_name').html(no_of_student_name);
                    
                   
                }
            });
}

function new_external_window_print(event, url,status) {
     
        var link = url+ '?status=' + status;
		// window.location=link;
		onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }
      function new_external_window_print_new(event, url,status) {
     
        var link = url ;
		// window.location=link;
		onmouseover = window.open(link, 'onmouseover', 'height=650,width=1050,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
    }


