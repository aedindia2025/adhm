<?php
//    $password = '3sc3RLrpd17';
//     $enc_method = 'aes-256-cbc';
//     $enc_password = substr(hash('sha256', $password, true), 0, 32);
//     $enc_iv = 'av3DYGLkwBsErphc';

//     // $folder_name_dec = str_replace(' ', '+', $_GET['page']);

//      $d = openssl_decrypt(base64_decode($_GET['district_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
//      $t = openssl_decrypt(base64_decode($_GET['taluk_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
//      $h = openssl_decrypt(base64_decode($_GET['hostel_name']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);
//      $ac = openssl_decrypt(base64_decode($_GET['academic_year']), $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv);

$district_name_list = district_name($_SESSION["district_id"]);
$district_name_list = select_option_acc($district_name_list, 'Select District', $_GET['district_name']);

$taluk_name_list = taluk_name($_SESSION['taluk_id']);
$taluk_name_list = select_option_acc($taluk_name_list, 'Select Taluk', $_GET['taluk_name']);

$hostel_name_list = hostel_name($_SESSION['hostel_id']);
$hostel_name_list = select_option_acc($hostel_name_list, 'Select Hostel', $_GET['hostel_name']);

$academic_year = academic_year();
$academic_year = select_option($academic_year, 'Select Academic Year', $_GET['academic_year']);

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

  #myvideo {
    /* display: ruby; */
    margin-top: 29px;
  }

  div#myvideo span {
    margin: 0px 14px !important;
  }

  div#myvideo iframe {

    margin-bottom: 14px;
  }

  .pagination {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
  }

  .pagination li {
    margin-right: 10px;
  }

  .pagination a {
    text-decoration: none;
    color: #337ab7;
  }

  .pagination a:hover {
    color: #23527c;
  }
</style>
<style>
  .container {
    width: 50%;
    margin: auto;
  }

  .link {
    padding: 10px 15px;
    text-decoration: none;
    margin-left: 10px;
    border: 1px solid #ccc;
  }

  .product {
    border: 1px solid #ccc;
    padding: 10px;
  }

  .active {
    background: green;
    color: white;
  }
</style>
<?php include 'header.php'; ?>

<div id="container">

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
                  <!-- <?php echo btn_add($btn_add); ?> -->
                </form>
              </div>
              <h4 class="page-title">CCTV Live</h4>
            </div>
          </div>
        </div>

        <input type="hidden" name="get_val" id="get_val" value="<?php echo $_GET['name']; ?>">
        <input type="hidden" name="get_lmt" id="get_lmt" value="<?php echo $_GET['get_lmt']; ?>">
        <!-- end page title -->
        <div class="row mb-3">
          <div class="col-md-2 fm">
            <label class="form-label" for="example-select">District Name</label>
            <select class="select2 form-control" id="district_name" name="district_name" disabled>
              <?php echo $district_name_list; ?>
              <!-- <input type="text" readonly class="form-control" id="district_name" name="district_name"  value="<?php echo $district_name_list; ?>"> -->
            </select>
          </div>
          <div class="col-md-2 fm">
            <label class="form-label" for="example-select">Taluk Name</label>
            <select class="select2 form-control" id="taluk_name" name="taluk_name" onchange="get_hostel()" disabled>
              <?php echo $taluk_name_list; ?>
            </select>
          </div>
          <div class="col-md-2 fm">
            <label class="form-label" for="example-select">Hostel Name</label>
            <select class="select2 form-control" id="hostel_name" name="hostel_name" disabled>
              <?php echo $hostel_name_list; ?>
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