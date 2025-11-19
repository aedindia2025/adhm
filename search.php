<?php include 'header.php'; ?>

<style>
    .otp-text h4 {
        text-align: center;
        margin-top: 4px;
        margin-bottom: 22px;
        color: #000000;
    }

    .otp-text p {
        text-align: center;
        margin-bottom: 0px;
    }

    ul.l-one li {
        width: 20%;
        padding: 15px;
        border-right: 1px solid #cfc2e7;
    }

    ul.l-one li:last-child {
        border-right: 0px solid #cfc2e7;
    }

    ul.l-one {
        list-style: none;
        display: -webkit-inline-box;
        background: #fff;
        box-shadow: 0px 10px 10px 0px rgba(100, 200, 255, 0.1);
        border-style: solid;
        border-width: 15px 0px 6px 0px;
        border-color: #c3a9f2;

    }

    .box {
        padding: 15px 0px;
        background: #fff;

    }

    .box img {

        margin: 0px auto;
        text-align: center;
        display: block;

    }

    a.a-btn:hover {
        background: #1c6c30;
    }

    .main-img img {
        text-align: center;
        /* display: block; */
        margin: 0px auto;
        padding: 5px 27px;
    }

    .box h5 {
        font-weight: 700;
        text-align: center;
        font-size: 16px;
        margin: 20px 0px 7px;
    }

    .hole-part {

        padding: 100px 0px;
    }

    .hole-part {
        padding: 0px 100px;
        margin-top: -190px;
    }

    a.a-btn {
        text-align: center;
        background: #00afef;
        margin: 0px auto;
        display: table;
        padding: 6px 22px;
        border-radius: 50px;
        z-index: 999999999999999;
        position: relative;
        text-decoration: unset;
        color: #fff;
        text-transform: uppercase;
        font-weight: 900;
        font-size: 13px;
        margin-top: 17px;
    }

    .box span {
        font-size: 14px;
        color: #555;
        text-align: center;
        margin: 0px auto;
        display: block;
    }

    .hole-unit {
        background: #00afef;
        padding: 0px 27px;
        position: fixed;
        left: 0;
        bottom: 0;
        width: 101%;
        text-align: center;
        border-bottom: 24px solid #ffff;
    }

    .hole-unit h3 {
        color: #ffff;
        margin: 0px;
        font-size: 19px;
        padding: 15px 0px;
    }

    .br-right {
        border-right: 3px solid #fff;
    }

    .logo {
        background: #fff;
        padding: 10px;
        z-index: 999999;
    }

    .hole-unit h3 img {
        width: 25px;
    }


    .img-top {
        position: absolute;
        background-color: #00afef;
        width: 100%;
        max-height: 350px;
        top: 0;
        left: 0;
        overflow: hidden;
        z-index: -1;
        height: 330px;
    }

    .d-grid.gap-10.text-center {
        padding-top: 30px !important;
        padding-bottom: 30px !important;
    }

    h4.heading-section-4.text-white.mb-0 {
        font-size: 28px;
    }

    .curv-box {
        background: #fff;
        box-shadow: 0px 7px 22px rgba(143, 134, 196, 0.07) !important;
        padding: 25px 45px;
        border-radius: 0px;
        border: 0px solid #ccc;
    }

    h2.hedline {
        font-size: 23px;
        margin-bottom: 37px;
        font-weight: 800;
        color: #00afef;
        margin-top: 0px;
    }

    form.row.new-form label {
        font-size: 1rem;
        font-weight: 500;
        color: #363848;
        margin-bottom: 10px;
    }

    form.row.new-form input {
        font-weight: 400;
        background-color: #FBFBFB;
        color: #363848;
        padding: 11px 24px;
        border: 1px solid #C2C2C2;
        border-radius: 5px !important;
    }

    form.row.new-form Select {
        font-weight: 400;
        background-color: #FBFBFB;
        color: #363848;
        padding: 11px 24px;
        border: 1px solid #C2C2C2;
        border-radius: 5px !important;
        width: 100%;
    }

    .mb-r {
        margin-bottom: 21px;
    }

    button.btn.btn-primary.fw-semiBold {
        color: #fff;
        background-color: #00afef;
        border-color: #00afef;
        padding: 9px 24px;
    }

    a.btn.btn-cultured.text-philippine-gray.fw-semiBold {
        background-color: #00afef;
        border-color: #00afef;
    }

    a.btn.btn-cultured.text-philippine-gray.fw-semiBold {
        background-color: #00afef;
        border-color: #00afef;
        margin-right: 28px;
        padding: 9px 24px;
    }

    a.abck {

        background: #00afef;
        padding: 10px;
        border-radius: 50px;
        color: #fff;
        font-size: 20px;
    }

    .ad-logo {
        text-align: justify;
    }

    .otp-btn {
        text-align: center;
        margin-top: 18px;
    }

    div#inputs {
        text-align: center;
    }

    div#inputs input {
        border: 1px solid #bcb0b080;
        margin: 0 5px;
        text-align: center;
        font-size: 32px;
        cursor: not-allowed;
        pointer-events: none;
        width: 40px;
        height: 40px;
        border-radius: 5px;
    }

    div#inputs input:focus {
        border-bottom: 3px solid #00afef;
        outline: none;
    }

    div#inputs input:nth-child(1) {
        cursor: pointer;
        pointer-events: all;
    }

    a.btn.rounded-pill.btn-primary.btn-colour {
        background: #00afef;
        border-color: #00afef;
        padding: 10px 29px;
    }

    .modal-body.outline-manual {
        border: 1px solid #9275c9;
    }

    a.back_home {
        background-color: #00afef;
        padding: 8px 12px;
        border-radius: 8px;
        color: #fff;
    }

    h3 {
        display: flex;
        flex-direction: row;
    }

    .card-header {
        font-size: 25px;
    }
.status	table {
  border-collapse: collapse;
  width: 100%;
}

.status td, th {
    border: 0px solid #f4f3f3;
    text-align: left;
    padding: 8px;
    color: #000;
}


.status h4 {
    color: #000;
    background: #e2e2e2;
    padding: 10px;
    text-align: center;
    font-size: 15px;
    margin-top: 0px;
}
.box-1 {
    border: 1px solid #ccc;
    padding: 9px;
}
td.bold {
    font-weight: 800;
    color: #000;
}
.b-1 p {
    color: #fff;
    font-size: 20px;
    font-weight: 500;
    margin-bottom: 0px;
}
.b-1 h5 {
    color: #fff;
    font-size: 15px;
    border-bottom: 1px dashed #d7cdcd;
    padding-bottom: 12px;
}
.b-1 {
    background: #00afef;
    padding: 10px;
    text-align: center;
}
td.bold.gren span {
    color: #F44336;
}
td.bold.gren {
    color: #4CAF50;
    font-size: 18px;
}
.reson{
	font-size: 15px;
    color: #000;
    font-weight: 600;
    margin-top: 9px;
}
.status {
    margin-bottom: 6px;
}
.hed-top h4 {
    background: #00afef;
    padding: 10px;
    text-align: center;
}
@media print{
 .hed-top h4 {

  background: #00afef;
    padding: 10px;
    text-align: center;
    -webkit-print-color-adjust: exact !important;
  }
}
</style>
<?php

include 'config/dbconfig.php';

if (isset($_GET['unique_id'])) {
    if (!empty($_GET['unique_id'])) {
        $unique_id = $_GET['unique_id'];

        $where_1 = [
            'unique_id' => $unique_id,
        ];

        $table_1 = 'std_app_s';

        $columns_1 = [
            'std_name',
            '(select mobile_no from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as mobile_no',
            "(select amc_year from academic_year_creation where academic_year_creation.unique_id = std_app_s.academic_year) as academic_year",
            '(select dob from std_app_s2 where std_app_s2.s1_unique_id = std_app_s.unique_id) as dob',
            'std_app_no',
            'entry_date',
            "(select hostel_name from  hostel_name where hostel_name.unique_id = std_app_s.hostel_1) as hostel_name",
            'unique_id',
            'batch_no',
            'batch_cr_date',
            'status',
            'status_upd_date as approved_date',
            // "(select staff_name from staff_registration where std_app_s.hostel_1 = staff_registration.hostel_name and designation = '65f3191aa725518258') as warden_name",
        ];
        // $std_name="std_name";_1
        // print_r($std_name);

        $table_details_1 = [
            $table_1,
            $columns_1,
        ];

        $result_values = $pdo->select($table_details_1, $where_1);

        // print_r($result_values);die();

        if ($result_values->status) {
            $result_values = $result_values->data;
            // $std_name      = strtoupper($result_values[0]["std_name"]);
            $std_name = $result_values[0]['std_name'];
            $std_mobile_no = $result_values[0]['mobile_no'];

            // $std_mobile_no          = $result_values[0]["mobile_no"];
            $academic_year = $result_values[0]['academic_year'];
            $class = $result_values[0]['class'];
            $batch_no = $result_values[0]['batch_no'];
            $batch_date = strtotime($result_values[0]['batch_cr_date']);
            $batch_cr_date = date('d-m-Y', $batch_date);

            $hostel_name = $result_values[0]['hostel_name'];
            $std_app_no = $result_values[0]['std_app_no'];
            $dob = $result_values[0]['dob'];
            $status = $result_values[0]['status'];
            $entry_date = strtotime($result_values[0]['entry_date']);
            $updated = $result_values[0]['approved_date'];
            $created_date = date('d-m-Y', $entry_date);
            // $unique_id          = $result_values[0]["unique_id"];
            $p1_unique_id = $result_values[0]['unique_id'];

            $is_active = $result_values[0]['is_active'];

            if (is_null($batch_no) || $batch_no == '') {
                $batch_no = 'Pending';
                $batch_no_color = 'blue';
            } else {
                $batch_no = 'Acknowledged';
                $batch_no_color = 'green';
            }

            // $status          = $result_values[0]["status"];
            if ($status == '0') {
                $status = 'Pending';
                $status_color = 'blue';
            }
            if ($status == '1') {
                $status = 'Approved';
                $status_color = 'green';
            }
            if ($status == '2') {
                $status = 'Rejected';
                $status_color = 'Red';
            }

            $btn_text = 'Update';
            $btn_action = 'update';
        } else {
            $btn_text = 'Error';
            $btn_action = 'error';
            $is_btn_disable = "disabled='disabled'";
        }

        $where_2 = [
            'unique_id' => $unique_id,
        ];

        $table_2 = 'std_app_s';

        $columns_2 = [
        "(select staff_name from staff_registration where std_app_s.hostel_1 = staff_registration.hostel_name and designation = '65f3191aa725518258' and is_delete = '0') as warden_name",
        'status_upd_date',
        ];
        // $std_name="std_name";_1
        // print_r($std_name);

        $table_details_2 = [
            $table_2,
            $columns_2,
        ];

        $result_values_2 = $pdo->select($table_details_2, $where_2);

        // print_r($result_values_2);die();

        if ($result_values_2->status) {
            $result_values = $result_values_2->data;
            $warden_name = $result_values[0]['warden_name'];
            // $warden_name = 'warden_name';
if($result_values[0]['status_upd_date']){
            $upd_date = strtotime($result_values[0]['status_upd_date']);
            $status_upd_date = date('d-m-Y', $upd_date);
}else{
 $status_upd_date = '-';

}
        }
    }
}

?>
<div class=" logo">
    <div class="row">
        <div class="col-md-6">
            <div class="ad-logo">
                <img src="img/ad-logo.png">
            </div>
        </div>
        <div class="col-md-6 align-self-center home-icon" style="text-align: end;">
            <a href="index.php" class="back_home"><i class="mdi mdi-home"></i></a>
        </div>
    </div>
</div>
<div class="heder-top">
    <div class="img-top">
      
    </div>
    <div class="container">
        <div class="position-relative py-43 py-lg-80">
            <div class="d-grid gap-10 text-center">
                <h4 class="heading-section-4 text-white mb-0">விண்ணப்ப நிலை / Application Status</h4>
            </div>
            


        </div>
    </div>
</div>



<div class="container">
    <div class="curv-box">
	
	<div class="row">
	<div class="col-md-6 status">
	<div class="box-1">
	<h4>தனிப்பட்ட தகவல் / Personal Info</h4>
		<table>
  <tr>
    <td>மாணவர் பெயர் / Applicant Name</td>
    <td class="bold"><?php echo $std_name; ?></td>
   </tr>
  <tr>
    <td>கல்வியாண்டு / Academic Year</td>
    <td class="bold"><?php echo $academic_year; ?></td>
   </tr>
 
    <tr>
    <td>பிறந்த தேதி / DOB</td>
    <td class="bold"><?php echo $dob; ?></td>
  </tr>
  <tr>
    <td>விண்ணப்பதாரர் எண் / Applicant No</td>
    <td class="bold"><?php echo $std_app_no; ?></td>
    </tr>
	<tr>
    <td>தொலைபேசி எண் / Phone Number</td>
    <td class="bold"><?php echo $std_mobile_no; ?></td>
    </tr>
 
</table>
	</div>
	</div>
	<div class="col-md-6 status">
	<div class="box-1">
	<h4>விண்ணப்பதாரர் விவரங்கள் / Applicant Details</h4>
		<table>
		 <tr>
    <td>விடுதி பெயர் / Hostel Name</td>
    <td class="bold"><?php echo $hostel_name; ?></td>
   </tr>
     <tr>
    <td> சமர்ப்பிக்கப்பட்ட தேதி /  Date of Submission </td>
    <td class="bold"><?php echo $created_date; ?></td>
   </tr>
  <tr>
    <td> ஒப்புதல் தேதி / Date of Approval  </td>
    <td class="bold"><?php echo $status_upd_date; ?></td>
   </tr>
   <tr>
   <td></td>
   </tr>
    <tr>
	 <td></td>
   </tr>
 

 
</table>
	</div>
	</div>
	</div>
	

	
	
	
	<div class="row">
	<div class="col-md-12 status">
	<div class="box-1">
	<h4>வார்டன் நிலை / Acknowledgement Warden Status</h4>
	<div class="row">
	<div class="col-md-12">
	
	</div>
	<hr>
	<div class="col-md-12 status">
	<table>
    <?php if ($batch_no != 'Pending') { ?>
  <tr>
    <td>தேதி / Date</td>
    <td class="bold"><?php echo $batch_cr_date; ?></td>
   </tr>
   
    <tr>
   
        <td>வார்டன் பெயர் / Warden Name</td>
        <td class="bold"><?php echo $warden_name; ?></td>
        
    </tr>
    <?php } ?>

    <tr>
   <td>நிலை / Status</td>
    <td class="bold"><span style="color: <?php echo $batch_no_color; ?>"><?php echo $batch_no; ?></span></td>
    <!-- <td><?php echo $batch_no; ?></td> -->
   </tr>
   </table>
	</div>
	</div>

	
	</div>
	</div>
	</div>
	
	<?php if ($batch_no == 'Acknowledged') {?>

	<div class="row">
	<div class="col-md-12 status">
	<div class="box-1">
	<h4>DADWO நிலை / DADWO Status</h4>
	<div class="row">
	<div class="col-md-12">
	
	</div>
	<hr>
	<div class="col-md-12 status">
	<table>
        <?php if ($status != 'Pending') {?>
  <tr>
    <td>தேதி / Date</td>
    <td class="bold"><?php echo $status_upd_date; ?></td>
   </tr>
   <?php } ?>
    <tr>
   <td>நிலை /   Status</td>
    <td class="bold"><span style="color: <?php echo $status_color; ?>"><?php echo $status; ?></span></td>
   </tr>
    <tr>
    <?php if ($status == 'Rejected') {?>
   <td>Reason or Description <p class="reson"><?php echo $reason; ?> </p></td>
   <?php }?>
   
   </tr>
   </table>
	</div>
	</div>

	
	</div>
	</div>
	</div>
	<?php }?>
	
	<!--<center><button type="button" id="back_button" class="  btn btn-primary" onclick="window.print()" style="margin-top: 20px;">Print</button></center>--->
	
	
	
	
	
	
	
	
	
	
    </div>
</div>


<!-- <script>
      document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
              });

              document.onkeydown = function(e)
    {
        if(event.keyCode == 123)
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
        {
            return false;
        }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
    {
      return false;
    }
    }
</script> -->


<?php include 'footer.php'; ?>