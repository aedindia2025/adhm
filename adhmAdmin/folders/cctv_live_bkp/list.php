<?php include 'filter.php';?>
        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <!-- 21:9 aspect ratio -->
              <!-- <img src="assets/images/cam-01.jpg"> -->
              <div id="container">
                <?php if($_GET['name'] != ''){?>
<h4 id="txt" style="text-align:center;display:none"> If You Want See Any Video Please Select District Name</h4>
<?php }else{
  ?>
  <h4 id="txt" style="text-align:center"> If You Want See Any Video Please Select District Name</h4>
  <?php }?>
              <div  class="item" id="myvideo">
                <!-- <table id="carrier_path_datatable"> -->

  <!-- </table> -->

              

  <!-- </div>  -->
 
 
  <!-- <div id="pagination_links">
    <a href="#" class="prev" onclick="myfilter('prev')">Previous</a>
    <span id="pagination_current_page"></span>
    <a href="#" class="next" onclick="myfilter('next')">Next</a>
</div> -->
  </div>
              <!-- <div><iframe width="640" height="480" src="https://rtsp.me/embed/t5HG8NDa/" frameborder="0" allowfullscreen></iframe><p align="right">powered by <a href="https://rtsp.me" title ='RTSP.ME - Free website RTSP video steaming service' target="_blank" >rtsp.me</a></p></div>
                </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 align-self-center">
                    <h4 class="header-title">Hostel Name : Hostel - I</h4>
                  </div>
                  <div class="col-md-12 mt-0">
                    <a herf="" class="btn-ser">View Details</a>
                  </div>
                </div>
              </div>
            </div>
          </div>  -->
            <!-- <div class="col-xl-4">
            <div class="card">  -->
              <!-- 21:9 aspect ratio -->
                <!-- <img src="assets/images/cam-02.jpg">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 align-self-center">
                    <h4 class="header-title">Hostel Name : Hostel - II</h4>
                  </div>
                  <div class="col-md-12 mt-0">
                    <a herf="" class="btn-ser">View Details</a>
                  </div>
                </div>
              </div>
            </div>
          </div>  -->
          <!-- <div class="col-xl-4">
            <div class="card">  -->
              <!-- 21:9 aspect ratio -->
              <!-- <img src="assets/images/cam-03.jpg">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 align-self-center">
                    <h4 class="header-title">Hostel Name : Hostel - III</h4>
                  </div>
                  <div class="col-md-12 mt-0">
                    <a herf="" class="btn-ser">View Details</a>
                  </div>
                </div>
              </div> -->
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
    var get_val = $('#get_val').val();
    if(get_val != ''){
      $("#txt").hide();
      myfilter();
    }
	// myfilter();
  // init_datatable(table_id,form_name,action);
});




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
    var ajax_url = sessionStorage.getItem("folder_crud_link");
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