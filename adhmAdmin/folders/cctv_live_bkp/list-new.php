 <?php
  $district_name_list = district_name();
  $district_name_list = select_option($district_name_list, "Select District", $district_name);
  $taluk_name_list = taluk_name();
  $taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);
  $hostel_name_list = hostel_name();
  $hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);
  $academic_year = academic_year();
  $academic_year = select_option($academic_year, "Select Academic Year", $academic_year);
  ?>
  <style>
  .iframe {
    position: absolute;
    width: 52%;
    height: 51%;
    left: 0;
    top: 0;
    background: #4b4b4b;
}
.video_wrapper {
    position: absolute;
    width: 54%;
    height: 41%;
    left: 0;
    top: 0;
    background: #4b4b4b;
}
#myvideo{
  display: flex;
  overflow: scroll;
}
.card{
  width: 327%;
}

.box {
    margin-bottom: 10px;
}
  </style>
 <?php include 'header.php' ?>
 
 <div class="content-page">
   <div class="content">
     <!-- Start Content-->
     <div class="container-fluid">
       <!-- start page title -->
       <!-- <div class="row">
         <div class="col-12">
        
           <div class="page-title-box">
             <h4 class="page-title">Hostel Details</h4>
           </div>
           <div class="page-title-right">
                            <form class="d-flex">
                            <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
         </div>
       </div> -->
       <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <form class="d-flex">
                            <?php echo btn_add($btn_add); ?>
                            </form>
                        </div>
                        <h4 class="page-title">CCTV Live</h4>
                    </div>
                </div>
            </div>
       <!-- end page title -->
       <div class="row mb-3">
         <div class="col-md-2 fm">
           <label class="form-label" for="example-select">District Name</label>
           <select class="select2 form-control" id="district_name" name="district_name" onchange="taluk_name()">
             <?php echo $district_name_list; ?>
           </select>
         </div>
         <div class="col-md-2 fm">
           <label class="form-label" for="example-select">Taluk Name</label>
           <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()">
             <?php echo $taluk_name_list ?>
           </select>
         </div>
         <div class="col-md-2 fm">
           <label class="form-label" for="example-select">Hostel Name</label>
           <select class="select2 form-control" id="hostel_name" name="hostel_name">
             <?php echo $hostel_name_list ?>
           </select>
         </div>
         <div class="col-md-2 fm">
           <label class="form-label" for="example-select">Academic Year</label>
           <select class="select2 form-control" id="academic_year" name="academic_year">
             <?php echo $academic_year; ?>
           </select>
         </div>
         <!-- <div class="col-md-3">
           <div class="page-title-right">
             <form class="d-flex">
               <a href=""> <button class="btn btn-primary" style="float: right;">Filter</button></a>
             </form>
           </div>
         </div> -->
         <div class="col-md-2">
           <div class="page-title-right">
             
                <button class="btn btn-primary" style="float: right; margin-top: 24px;
    margin-right: 66px; " onclick="myfilter()">Go</button>
             
           </div>
         </div>
       </div>
       
         
           <div class="card" style=" width: 100%;">
		   <div class="card-body">
		   <div class="row">
		      <div class="col-md-4">
			  <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
			   <div class="col-md-4">
			   <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
			   <div class="col-md-4">
			   <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
			   <div class="col-md-4">
			   <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
			   <div class="col-md-4">
			   <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
			   <div class="col-md-4">
			   <div class="box">
						<video controls>
					  <source src="https://www.w3schools.com/html/movie.mp4" type="video/mp4">
					  
					</video>
			</div>
			  </div>
		   </div >
		   </div>
          
           </div>
         </div>
       </div>
     </div>
  
 <?php include 'footer.php' ?>
 <script>
  $(document).ready(function () {
	// var table_id 	= "cctv_live_datatable";
	myfilter();
	// init_datatable(table_id,form_name,action);
});
  function myfilter(){
    // alert("hii");
    $("#myvideo").empty();
    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
    var academic_year = $('#academic_year').val();
    var data = "district_name=" + district_name + "&taluk_name=" + taluk_name +"&hostel_name=" + hostel_name +"&academic_year=" + academic_year +"&action=get_video";
    var ajax_url = sessionStorage.getItem("folder_crud_link");

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