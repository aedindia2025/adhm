<?php include 'filter.php';?>
<style>

.load {
		text-align: center;
		position: absolute;
		top: 20%;
		left: 50%;
		transform: translate(-50%, -50%);
		display: none;

	}

	i.mdi.mdi-loading.mdi-spin {
		font-size: 75px;
		color: #17a8df;
	}

  #scrollDiv {
    height: 1029px;
    overflow-y: auto;
    border: 1px solid #ccc;
    padding: 10px;
}

/* Custom scrollbar */
#scrollDiv::-webkit-scrollbar {
    width: 8px;
}

#scrollDiv::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#scrollDiv::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
}

#scrollDiv::-webkit-scrollbar-thumb:hover {
    background: #555;
}

</style>
        <div class="row">
          <div class="col-xl-6">
            <div class="card">
            
              <div id="container">
              <div id="scrollDiv">
              <div class="row">
	<div class="col-md-12 load" id="loader">
		<i class="mdi mdi-loading mdi-spin"></i>
	</div>
</div>
              <div  class="item" id="cctv_list">
              <h4>Select District To List the Hostel Here</h4>
  </div> 
  </div>
 
 
 
  </div>
              
            </div>
          </div>

          <div class="col-xl-6">
            <div class="card" id="cam_div" style="display:none">
            
              <div id="container">
               
              <div  class="item" id="cam_1" ></div><br>
              <div  class="item" id="cam_2" ></div> 
</div>
              
            </div>
          </div>


        </div>
      </div>
    </div>
  </div>
 </div>
 <!-- <div id="pagination"></div> -->

 <?php include 'footer.php'; ?>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <script>


    
  
  $(document).ready(function () {
    // var get_val = $('#get_val').val();
    // if(get_val != ''){
    //   $("#txt").hide();
    //   myfilter();
    // }
	// myfilter();
  // init_datatable(table_id,form_name,action);
});

function showLoader() {
		$("#loader").css("display", "inline-block"); // or "block" depending on your preference
	}

	function hideLoader() {
		$("#loader").css("display", "none");
	}

function get_list(action){
    // alert("hii");
   showLoader();
    $("#myvideo").empty();
    $("#cctv_list").empty();
    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
    var academic_year = $('#academic_year').val();
   
    

    var data = "district_name=" + district_name + "&taluk_name=" + taluk_name +"&hostel_name=" + hostel_name +"&academic_year=" + academic_year +"&action=cctv_list";
    var ajax_url = 'https://nallosaims.tn.gov.in/adw_biometric/folders/cctv_live/crud.php';
    if(district_name ||  taluk_name || hostel_name || academic_year != ''){
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
      hideLoader();
     
        var obj     = JSON.parse(data);
				var msg     = obj.msg;
        var datas     = obj.data;
				var status  = obj.status;
				var error   = obj.error;
        document.getElementById("scrollDiv").style.display = "block";
           $("#cctv_list").append(datas);
         
       }
     });
    }else{
      $("#cctv_list").append("<h4 style='text-align:center'>Please Select District</h4>");
    }
       
  }


  function myfilter(action){
    // alert("hii");
    $("#txt").hide();
    $("#myvideo").empty();
    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
    var academic_year = $('#academic_year').val();
    var get_val = $('#get_val').val();
    var get_lmt = $('#get_lmt').val();
    // if (action === 'prev') {
    //     currentPage--;
    // } else if (action === 'next') {
    //     currentPage++;
    // }

    var data = "district_name=" + district_name + "&taluk_name=" + taluk_name +"&hostel_name=" + hostel_name +"&academic_year=" + academic_year +"&get_val="+ get_val +"&get_lmt="+ get_lmt +"&action=get_video";
    var ajax_url = 'https://nallosaims.tn.gov.in/adw_biometric/folders/cctv_live/crud.php';
    if(district_name ||  taluk_name || hostel_name || academic_year != ''){
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
        // alert(data);
     
        var obj     = JSON.parse(data);
				var msg     = obj.msg;
        var datas     = obj.data;
				var status  = obj.status;
				var error   = obj.error;

           $("#myvideo").append(datas);
         
       }
     });
    }else{
      $("#myvideo").append("<h4 style='text-align:center'>Please Select District</h4>");
    }
       
  }
   function taluk_name() {
     // alert(hi);
     var district_name = $('#district_name').val();
     var data = "district_name=" + district_name + "&action=district_name";
     var ajax_url = sessionStorage.getItem("folder_crud_link");
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
         if (data) {
           $("#taluk_name").html(data);
         }
       }
     });
   }

   function get_hostel() {
     var taluk_name = $('#taluk_name').val();
     var data = "taluk_name=" + taluk_name + "&action=get_hostel_by_taluk_name";
     var ajax_url = sessionStorage.getItem("folder_crud_link");
     $.ajax({
       type: "POST",
       url: ajax_url,
       data: data,
       success: function(data) {
         if (data) {
           $("#hostel_name").html(data);
         }
       }
     });
   }
 </script>