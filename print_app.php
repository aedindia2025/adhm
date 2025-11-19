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
	<input type="hidden" id="unique_id" value="<?php echo $_GET['unique_id'];?>">
    <div class="container">
        <div class="position-relative py-43 py-lg-80">
            <div class="d-grid gap-10 text-center">
                <h4 class="heading-section-4 text-white mb-0">விண்ணப்பப் பதிவிறக்கம் / Application Download</h4>
            </div>
            


        </div>
    </div>
</div>



<div class="container">
    <div class="curv-box">
	
	<div class="row">
	<table id="app_download_datatable" class="mb-0 table table-striped">

								<thead>
								<th >S.No</th>
								<th>Applied Date</th>
								<th>Application No</th>
								<th>Student Name</th>
								<th>Download Application</th>
								</thead>
							</table>
	
	<!--<center><button type="button" id="back_button" class="  btn btn-primary" onclick="window.print()" style="margin-top: 20px;">Print</button></center>--->
	
	
	
	
	
	
	
	
	</div>
	
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
	<script src="app_download.js"></script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


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