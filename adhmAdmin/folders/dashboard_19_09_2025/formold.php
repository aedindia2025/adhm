<style>
  .font-weight-bold {
    font-weight: 500 !important;
  }
.media .media-title {
    margin-top: 0;
    margin-bottom: 5px;
    font-weight: 300;
    font-size: 12px;
    color: #6c757d;
}
  .text-small {
    font-size: 19px !important;
    line-height: 20px;
    color: #403939 !important;
    margin-top: 8px;
    font-weight: 700 !important;
  }

  #ext{

    float: right;
    width: 100px;
  }
  h6{
      font-size: 15px;
  }
  #ext1{ 
    /*  margin-top: -31px;*/
    /*margin-left: 251px;*/
    /*position: relative;*/
   float: right; 
    /*width: 100px;*/
    margin-top: -34px;
    margin-left: 311px;
    position: relative;
    /* float: right; */
    width: 100px;
  }

  .container {
  /*position: relative;*/
  /*width: 400px;*/
  /*height: 400px;*/
  position: relative;
    width: 400px;
    height: 571px;
}
.container1 {
  /*position: relative;*/
  /*  width: 388px;*/
  /*  height: 400px;*/
  /*  margin-left: 14px;*/
  /*width: 452px;*/
  /*  height: 400px;*/
  /*  margin-left: 14px;*/
  width: 554px;
    height: 400px;
    margin-left: 14px;
}

/*.main-dash {*/
    /*background: #fff;*/
    /*padding: 8px;*/
    /*border: 1px solid #e2e8f0;*/
    /*border-radius: 5px;*/
/*    background: #fff;*/
/*    padding: 17px;*/.list-unstyled-border li
/*    border: 1px solid #e2e8f0;*/
/*    border-radius: 5px;*/
/*}*/
#scrollable {
  width: 100%;
  height:450px;
  overflow-y: scroll;
  overflow-x: hidden;
  z-index: 4;
}
li.media .col-4 {
    padding: 0px;
}
ul.list-one li {
    margin: 5px;
    width: 25%;
    
}
ul.list-one {
    display: flex;
    list-style: none;
    padding-left: 0px;
    margin-bottom: 0px;
}
select#month {
    border: 1px solid #ccc;
    padding: 5px 10px;
    color: #555;
    outline: 0;
}
input#year {
    border: 1px solid #ccc;
    padding: 5px 10px;
    color: #555;
    width: 100%;
    outline: 0;
}
table.optionsTable tr td {
    padding: 0px 8px;
}
div#dateFilter {
    float: right;
    margin-bottom: 20px;
}
</style>
<script src="assets/js/jquery.counterup.js"></script>
<script src="assets/js/jquery.waypoints.js"></script>

<?php

$table = "ticket_creation";

$columns = [
  "(select count(id) as open_task from ticket_creation where is_delete = 0 and entry_date < '" . $today . "' and status = '61e70158c066b8987') as opening_task",
  "(select count(id) as new_task from ticket_creation where is_delete = 0 and entry_date = '" . $today . "') as new_task",
  "(select count(id) as pending_task from ticket_creation where is_delete = 0 and entry_date <= '" . $today . "' and status = '61e70158c066b8987') as pending_task",
  "(select count(id) as completed_task from ticket_creation where is_delete = 0 and entry_date <= '" . $today . "' and status = '61d70158c066bde321') as completed_task",
  "(select count(id) as on_hold from ticket_creation where is_delete = 0 and entry_date <= '" . $today . "' and status = '61d70158c066b95654') as on_hold_task",
];

$table_details = [
  $table,
  $columns
];

$result_values = $pdo->select($table_details, $where);

if ($result_values->status) {

  $result_values = $result_values->data[0];

  $opening_task   = $result_values["opening_task"];
  $new_task       = $result_values["new_task"];
  $pending_task   = $result_values["pending_task"];
  $completed_task = $result_values["completed_task"];
  $on_hold_task   = $result_values["on_hold_task"];


} else {
  $btn_text = "Error";
  $btn_action = "error";
  $is_btn_disable = "disabled='disabled'";
}


$table_sub = "ticket_creation_sub";

$columns = [
  "(select count(id) as pending from ticket_creation_sub where is_delete = 0  and remarks = '64ddabc5ec02593935') as pending",
  "(select count(id) as on_hold from ticket_creation_sub where is_delete = 0 and remarks = '64ddaaaf0992a40367') as on_hold",
  "(select count(id) as in_progress from ticket_creation_sub where is_delete = 0 and remarks = '64ddaad88705279650') as in_progress",
  "(select count(id) as testing from ticket_creation_sub where is_delete = 0 and remarks = '64ddab60a26ea75306') as testing",
  "(select count(id) as retaken from ticket_creation_sub where is_delete = 0 and remarks = '64ddab8516d3815801') as retaken",
  "(select count(id) as completed from ticket_creation_sub where is_delete = 0 and remarks = '64ddab6d45a6393125') as completed",
  "(select count(id) as bug_raised from ticket_creation_sub where is_delete = 0 and remarks = '64e04797d97fc14845') as bug_raised",
  "(select count(id) as deployed from ticket_creation_sub where is_delete = 0 and remarks = '64e1e1c1ddbca25676') as deployed",
];

$table_details = [
  $table_sub,
  $columns
];

$result_values = $pdo->select($table_details, $where);
if ($result_values->status) {

  $result_values = $result_values->data[0];

  $pending        = $result_values["pending"];
  $on_hold        = $result_values["on_hold"];
  $in_progress    = $result_values["in_progress"];
  $testing        = $result_values["testing"];
  $retaken        = $result_values["retaken"];
  $completed      = $result_values["completed"];
  $bug_raised     = $result_values["bug_raised"];
  $deployed        = $result_values["deployed"];


} else {
  $btn_text = "Error";
  $btn_action = "error";
  $is_btn_disable = "disabled='disabled'";
}

?>

<div class="hole-das">
    
   
    <div class="">
      <div class="main-dash mb-12">
          <div class="row">
              <div class="col-md-6 align-self-center">
                  <h3>Task Details</h3>
              </div>
               <div class="col-md-6">
                  <div id='app'>
  <div id='dateFilter'>
  
    <table class='optionsTable'>
      <tr class='optionsRow'>
        <td >
    <select id='month'  placeholder="Select Year">
    <option value='1'>Select Month</option>
      <option value='1'>January</option>
      <option value='2'>February</option>
      <option value='3'>March</option>
      <option value='4'>April</option>
      <option value='5'>May</option>
      <option value='6'>June</option>
      <option value='7'>July</option>
      <option value='8'>August</option>
      <option value='9'>September</option>
      <option value='10'>October</option>
      <option value='11'>November</option>
      <option value='12'>December</option>      
    </select>
        </td><td>
    <input id='year' type='number' min='1900' max='2020'/ placeholder=" Year">
        </td><td>
    <button id='apply' class="btn btn-icon icon-left btn-primary">Go</button>
        </td>
      </tr><tr class='labelsRow'>
     
      
      </tr>
    </table>
  </div>
</div>
              </div>
          </div>
        
        <ul class="list-one">
    <!--      <li>
            <div class="das-box no-mard g4">
              <div class="iocn-bg">
                <i class="far fa-folder-open" aria-hidden="true"></i>
              </div>
              <div class="le">
                
                <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','opening');">Opening Task</h6>
                
                <h2 class="number" id="opening_task">
                  <?= $opening_task; ?>
                </h2>
              
              </div>
            </div>
          </li>-->
          <li>
            <div class="das-box no-mard g4">
              <div class="iocn-bg bg">
              <i class="far fa-folder-open" aria-hidden="true"></i>
              </div>
              <div class="le">
              <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','opening');">Opening Task</h6>
              <h3 id="new_complaints" style="color: #dd980e;"></h3>
               <h2 class="number" id="opening_task">
                  <?= $opening_task; ?>
                </h2>
                <!-- <h4>New Task</h4> -->

              </div>
            </div>
          </li>
          <li>
            <div class="das-box no-mard g1">
              <div class="iocn-bg bg-2">
                <i class="far fa-plus-square "></i>
              </div>
              <div class="le">
              <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','new');">New Task</h6>
              <h3 id="new_complaints" style="color: #dd980e;"></h3>
                <h2 class="number" id="new_task">
                  <?= $new_task; ?>
                </h2>
                <!-- <h4>New Task</h4> -->

              </div>
            </div>
          </li>
          <li>
            <div class="das-box g2 no-mard">
              <div class="iocn-bg bg-3">
                <i class="fas fa-chart-line "></i>
              </div>
              <div class="le">
              <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','pending');">Pending Task</h6>
              <h3 style="color: #f95a80;" id="pending_complaints"></h3>

                <h2 class="number" id="pending_task">
                  <?= $pending_task; ?>
                </h2>
                <!-- <h4>Pending Task</h4> -->

              </div>
            </div>
          </li>
       <!--   <li>
            <div class="das-box no-mard">
              <div class="iocn-bg bg-4">
                <i class="fas fa-exclamation-circle"></i>
              </div>
              <div class="le">
              <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','onhold');">On Hold Task</h6>
                <h3 style="color: #3ad959;" id="on_hold_task"></h3>

                <h2 class="number" id="on_hold_task">
                  <?= $on_hold_task; ?>
                </h2>
               
               
              </div>
            </div>
          </li>-->
          <li>
            <div class="das-box g3 no-mard">
              <div class="iocn-bg bg-4">
                <i class="far fa-check-circle" aria-hidden="true"></i>
              </div>
              <div class="le">
              <h6 onclick="new_external_window_print(event,'folders/dashboard/print.php','completed');">Completed Task</h6>
                <h3 style="color: #3ad959;" id="completed_complaints"></h3>

                <h2 class="number" id="completed_task">
                  <?= $completed_task; ?>
                </h2>
                <!-- <h4>Completed Task</h4> -->
              </div>
            </div>
          </li>

        </div>

      </ul>

    </div>  
    
<!------------------------------------------------------------>
 <div class="row">
        <div class="col-md-8">

<div class="">
      <div class="main-dash mb-12">
        <h3>Pending Status</h3>
        <ul class="list-one">
          <li>
            <div class="sec-box spe-marg" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','pending');">
              <img src="assets/img/chat.png" class="iconic c1">
              <div class="mt-2 font-weight-bold text-nowrap">Pending</div>
              <div class="text-small number"><?=$pending?></div>
            </div>
          </li>

          <li>
            <div class="sec-box" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','onhold');">
              <img src="assets/img/document.png" class="iconic c2">
              <div class="mt-2 font-weight-bold text-nowrap">On Hold</div>
              <div class="text-small number "><span class="text-primary"></span><?=$on_hold;?>
              </div>
            </div>
          </li>
          <li>
            <div class="sec-box spe-marg" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','in_progress');">
              <img src="assets/img/chat.png" class="iconic c1">
              <div class="mt-2 font-weight-bold text-nowrap">In Progress</div>
              <div class="text-small number"><?=$in_progress;?></div>
            </div>
          </li>

          <li>
            <div class="sec-box" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','testing');">
              <img src="assets/img/document.png" class="iconic c2">
              <div class="mt-2 font-weight-bold text-nowrap">Testing</div>
              <div class="text-small number "><span class="text-primary"></span><?=$testing;?>
              </div>
            </div>
          </li>
          <li>
            <div class="sec-box" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','retaken');">
              <img src="assets/img/user.png" class="iconic c3">
              <div class="mt-2 font-weight-bold text-nowrap">Retaken</div>
              <div class="text-small number"><span class="text-danger"></span><?=$retaken;?>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>

    
    </div>
    
    <div class="col-md-4" >
             <div class="main-dash new-bg">
        <h3 style="margin-bottom: 31px;">Completed Status</h3>
        <div class="row">

          <div class="col-md-4 mb-md-0 mb-4 text-center">
            <img src="assets/img/warning.png" class="iconic c4" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','bug_raised');">
            <div class="mt-2 font-weight-bold text-nowrap">Bug Raised</div>
            <div class="text-small number"><?=$bug_raised;?></div>
          </div>
          <div class="col-md-4 mb-md-0 mb-4 text-center">
            <img src="assets/img/sign-out.png" class="iconic c5" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','completed');">
            <div class="mt-2 font-weight-bold text-nowrap">Completed</div>
            <div class="text-small number "><span class="text-primary"></span><?=$completed;?>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <img src="assets/img/search.png" class="iconic c6" onclick="new_external_window_print(event,'folders/dashboard/pending_print.php','deployed');">
            <div class="mt-2 font-weight-bold text-nowrap">Deployed</div>
            <div class="text-small number "><span class="text-danger"></span><?=$deployed;?>
            </div>
          </div>
        </div>
      </div> 
    </div>
    
    
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <div class="row">
        

        
  
            
                    
        <div class="col-md-6 ">
           
             <div id="scrollable">        
      <div class="main-dash">
        <h3>Staff Task Status</h3>
        <ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
          <?php
           $con =  mysqli_connect("localhost", "zigma", "?WSzvxHv1LGZ", "task_management");
           if(!$con)
          {
              die('not connected');
          }
          $con_project=  mysqli_query($con, "select unique_id,staff_name from staff_incharge where is_delete = 0 and designation != '64e59e8c14d6876258'");
         
          while($row_project=  mysqli_fetch_array($con_project))
          {
              $staff_name	 = $row_project['staff_name'];
              $unique_id    = $row_project['unique_id'];


            
            $ind_project_total1 =  mysqli_fetch_array(mysqli_query($con,"select count(id) as completed_cnts from ticket_creation where is_delete = 0 and staff_name = '".$unique_id."' and status = '61d70158c066bde321' "));

              $ind_project_pending1 =  mysqli_fetch_array(mysqli_query($con,"select count(id) as overalls from ticket_creation where is_delete = 0 and staff_name = '".$unique_id."' "));
              
             $task_completed_count = $ind_project_total1['completed_cnts']; 
             $task_assigned_count = $ind_project_pending1['overalls'];
            
            // $ind_project_total =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(`time_taken`, '%H:%i')))) as worked_hrs from ticket_creation_sub where is_delete = 0 and staff_name = '".$unique_id."' and remarks = '64ddab6d45a6393125'"));
            
            $ind_project_total =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(`est_hour`, '%H:%i')))) as completed_est_hour from ticket_creation where is_delete = 0 and staff_name = '".$unique_id."' and status = '61d70158c066bde321'"));


              $ind_project_pending =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(`est_hour`, '%H:%i')))) as est_hour from ticket_creation where is_delete = 0 and staff_name = '".$unique_id."' "));
              
              
              
             $worked_hrs_completed = $ind_project_total['completed_est_hour']; 
             $est_hour_over = $ind_project_pending['est_hour'];
             
             $work_hrs_format = $worked_hrs_completed; // Assuming this is your input time duration

// Split the time duration into hours, minutes, and seconds
list($hours, $minutes, $seconds) = explode(':', $work_hrs_format);

// Convert hours and minutes to an "H:i" format
$format_wrk_hrs = sprintf("%d:%02d", $hours, $minutes);

$est_hrs_format = $est_hour_over; // Assuming this is your input time duration

// Split the time duration into hours, minutes, and seconds
list($hours, $minutes, $seconds) = explode(':', $est_hrs_format);

// Convert hours and minutes to an "H:i" format
$format_est_hrs = sprintf("%d:%02d", $hours, $minutes);
             
              
    
     if($worked_hrs_completed == ''){
        $completed = '00:00';
    }else{
         $completed = $format_wrk_hrs;
    }
    if($est_hour_over == ''){
        $count = '00:00';
    }else{
         $count = $format_est_hrs;
    }
    if($worked_hrs_completed == '' && $est_hour_over ==''){
                 $progress_bar_per = '00:00';
             }else{
                 $progress_bar_per = round(($completed/$count)*100);
             }
    
            ?>
          <li class="media">
                <div class="col-12">
              <div class="row">
                  <div class="col-5">
            <div class="media-body">
              <div class="media-title"><b><?php echo $staff_name;?>&nbsp;&nbsp;<samll>(<?php echo $task_completed_count;?>/<?php echo $task_assigned_count;?>)</samll></b></div>
              
              
            </div>
            </div>
            <div class="col-2" id="time">
                       <h6><span style="color:green;"><?php echo $completed;?></span></h6>
                    </div>
                     <div class="col-2 or-fot">
                        <h6><span style="color:orange;"><?php echo $count;?></span></h6>
                    </div>
                    
                   
                    
                    
            <div class="col-3">
                <div class="progress">
                    <?php if($progress_bar_per <= 25){?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                  <?php }else if($progress_bar_per >=26 && $progress_bar_per <=75){?>
                  <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                  <?php }else if($progress_bar_per >= 76 && $progress_bar_per <=95){?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                </div>
                <?php  }else if($progress_bar_per >= 95 && $progress_bar_per <=100){?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                </div>
                <?php }?>
                

           <!-- <div class="media-progressbar" id="ext1">
              <div class="progress-text"><?=$progress_bar_per;?>%</div>
              <div class="progress" data-height="10">
                <div class="progress-bar bg-primary" data-width="<?php echo $progress_bar_per;?>%"></div>
              </div>
            </div>--->
            
            
            <div class="media-cta">

            </div>
            </div> </div>
            </div>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    
  
       


            
        </div>
        
        
        
        <div class="col-md-6 p-0">
            

            
       
          <div class="col-md-12 ">
      
             <div id="scrollable">
      <div class="main-dash" >
        <h3>All Project Status</h3>
        <ul class="list-unstyled user-progress list-unstyled-border list-unstyled-noborder">
          <?php
           $con =  mysqli_connect("localhost", "zigma", "?WSzvxHv1LGZ", "task_management");
           if(!$con)
          {
              die('not connected');
          }
          $con_project=  mysqli_query($con, "select unique_id,project_name from project_creation where is_delete = 0");
         
          while($row_project=  mysqli_fetch_array($con_project))
          {
              $project_name = $row_project['project_name'];
              $unique_id    = $row_project['unique_id'];

              $ind_project_total =  mysqli_fetch_array(mysqli_query($con,"select count(id) as completed_cnt from ticket_creation where is_delete = 0 and project_name = '".$unique_id."' "));
              
              

           
            
            // $working_total =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(worked_hrs))) as worked_hrs from view_project_wise_report where is_delete = 0 and ticket_project_name = '".$unique_id."'and remarks = '64ddab6d45a6393125'  "));
            
            // $ind_project_total =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(`est_hour`, '%H:%i')))) as completed_est_hour from ticket_creation where is_delete = 0 and staff_name = '".$unique_id."' and status = '61d70158c066bde321'"));
            
            $working_total =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(est_hour))) as worked_hrs from ticket_creation where is_delete = 0 and project_name = '".$unique_id."'and status = '61d70158c066bde321'"));
            
            


           
            $ind_project_pending =  mysqli_fetch_array(mysqli_query($con,"select SEC_TO_TIME(SUM(TIME_TO_SEC(est_hour))) as est_hour from view_project_wise_report where is_delete = 0 and ticket_project_name = '".$unique_id."' "));
              
             $worked_hrs =  $working_total['worked_hrs']; 
              $est_hour = $ind_project_pending['est_hour'];
            // echo $worked_hrs;
            
            $timeDuration = $worked_hrs; // Assuming this is your input time duration

// Split the time duration into hours, minutes, and seconds
list($hours, $minutes, $seconds) = explode(':', $timeDuration);

// Convert hours and minutes to an "H:i" format
$format_wrk_hrs = sprintf("%d:%02d", $hours, $minutes);




$est_format = $est_hour; // Assuming this is your input time duration

// Split the time duration into hours, minutes, and seconds
list($hours, $minutes, $seconds) = explode(':', $est_format);

// Convert hours and minutes to an "H:i" format
$format_est_hrs = sprintf("%d:%02d", $hours, $minutes);

 
// echo "Hours: $hours, Minutes: $minutes";
            
             if($est_hour == '' && $worked_hrs == ''){
                 $progress_bar_per = '00:00';
             }else{
                 $progress_bar_per = round(($worked_hrs/$est_hour)*100);
             }
             
    if($worked_hrs == ''){
        $worked_hrs_cnt = '00:00';
    }else{
        
        $worked_hrs_cnt = $format_wrk_hrs;
    }
    if($est_hour == ''){
        $est_hours = '00:00';
    }else{
        
         $est_hours = $format_est_hrs;
    }
            

            

            ?>
          <li class="media">
                <div class="col-12">
              <div class="row">
                              
                  <div class="col-5">
            <div class="media-body">
              <div class="media-title"><b><?php echo $project_name;?></b></div>
            </div>
            </div>
            <div class="col-2 or-fot">
                       <h6><span style="color:green;"><?php echo $worked_hrs_cnt;?></span></h6>
                    </div>
                     <div class="col-2 or-fot">
                        <h6><span style="color:orange;"><?php echo $est_hours;?></span></h6>
                    </div>
                    
            <div class="col-3">
                <div class="progress">
                    <?php if($progress_bar_per <= 25){?>
                  <div class="progress-bar bg-danger" role="progressbar" style="width: 25%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                  <?php }else if($progress_bar_per >=26 && $progress_bar_per <=75){?>
                  <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                  <?php }else if($progress_bar_per >= 76 && $progress_bar_per <=95){?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                </div>
                <?php  }else if($progress_bar_per >= 95 && $progress_bar_per <=100){?>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"><?=$progress_bar_per;?>%</div>
                </div>
                <?php }?>
            <!--    <div class="media-progressbar" id="ext">-->
               
                     
                     
            <!--  <div class="progress-text"><?=$progress_bar_per;?>%</div>-->
            <!--  <div class="progress" data-height="10">-->
            <!--    <div class="progress-bar bg-primary" data-width="<?php echo $progress_bar_per;?>%"></div>-->
             
            <!--</div>-->
            <!--</div>  -->
            </div>
            </div>
            </div>
           
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
   </div>  
        

        
</div> </div>
    
    

</div>


<script src="assets/bundles/jquery-steps/jquery.steps.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>
  $(".number").counterUp({ time: 3000 });

  // function external_print_window(){
    
  //   var link = "print.php";
    
  //   onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
  //   event.preventDefault();
  // }
  
  
  
  var selectedMonth = '';
var selectedYear = '';

function fieldsAreEmpty() {
  return ($('#year').val() === '') &&
    ($('#month').val() === '');
}

function fieldsHaveChanged() {
    return ($('#year').val() !== selectedYear) ||
    ($('#month').val() !== selectedMonth);
}

function apply() {
  selectedYear = $('#year').val();
  selectedMonth = $('#month').val();
}

function toggleButtons() {
  var empty = fieldsAreEmpty();
  $('#clear').prop('disabled', empty);
  $('#apply').prop('disabled', empty || !fieldsHaveChanged());
}

function clearFields() {
  $('#year, #month').val('');
}

$('#year, #month').change(e => {toggleButtons();});
$('#clear').click(e => {clearFields(); toggleButtons();});
$('#apply').click(e => {apply(); toggleButtons();});
$('document').ready(e => {toggleButtons();});

 
</script>
