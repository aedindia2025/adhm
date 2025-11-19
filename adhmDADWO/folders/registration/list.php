<style>
  a.btn.btn-action.specl2 {
    padding: 0px;
  }

   table#registration_datatable {
        width: 100%;
        display: block;
        overflow: scroll;
    }

  .switch {
    position: relative;
    width: 14rem;
    padding: 0 1rem;
    font-family: verdana;

    &:before { 
      content: '  ';
      position: absolute;
      left: 0;
      z-index: -1;
      width: 100%;
      height: 3rem;
      background: #000;
      border-radius: 30px;
    }

    &__label {
      display: inline-block;
      width: 2rem;
      padding: 1rem;
      text-align: center;
      cursor: pointer;
      transition: color 200ms ease-out;

      &:hover {
        color: white;
      }
    }

    &__indicator {
      width: 4rem;
      height: 4rem;
      position: absolute;
      top: -.5rem;
      left: 0;
      background: blue;
      border-radius: 50%;
      transition: transform 600ms cubic-bezier(.02, .94, .09, .97),
        background: 300ms cubic-bezier(.17, .67, .14, 1.03),
      transform: translate3d(1rem, 0, 0);
    }

    input#one:checked~.switch__indicator {
      background: PaleGreen;
      transform: translate3d(1.2rem, 0, 0);
    }

    input#two:checked~.switch__indicator {
      background: MediumTurquoise;
      transform: translate3d(5.5rem, 0, 0);
    }

    input#three:checked~.switch__indicator {
      background: PaleVioletRed;
      transform: translate3d(10.6rem, 0, 0);
    }

    input[type="radio"] {

      &:not(:checked),
      &:checked {
        display: none;
      }
    }
  }
</style>

<?php 
$academic_year_options = academic_year();
$academic_year_options = select_option_acc($academic_year_options);

$hostel_name_list = hostel_name('','',$_SESSION['district_id']);
$hostel_name_list = select_option_host($hostel_name_list, "Select Hostel");

$batch_no_options = batch_no('', $_SESSION['district_id']);
$batch_no_options = select_option($batch_no_options, 'Select');



?>

<div class="content-page">
  <div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <div class="page-title-right">
            </div>
            <h4 class="page-title">Registration</h4>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
               <div class="row mb-3  was-validated">
                  <div class="col-md-3">
                      <label for="academic_year" class="form-label">Academic Year </label>
                      <select class="select2 form-control" name="academic_year" id="academic_year" >
                          <?php echo $academic_year_options; ?>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <label for="academic_year" class="form-label">Hostel Name</label>
                      <select class="select2 form-control" name="hostel_name" id="hostel_name" onchange="get_batch_no()">
                          <?php echo $hostel_name_list; ?>
                      </select>
                  </div>
                  <div class="col-md-3">
                      <label for="approval_status" class="form-label">Batch No</label>
                      <select name="batch_no" id="batch_no" class="select2 form-control">
                          
                      </select>
                  </div>
                  <div class="col-md-3 mt-3 align-self-center">
                      <div class="page-title-right">
                          <button class="btn btn-primary" onclick="filter()">Filter</button>
                      </div>
                  </div>
              </div>
              <input type="hidden" id="csrf_token" name="csrf_token"  >
              <table id="registration_datatable" class="table nowrap w-100">
                <thead>
                  <tr>
                    <th>S.no</th>
                    <th>Date</th>
                    <th>Hostel Name</th>
                    <th>Batch No</th>
                    <th>Total</th>
                    <th>Approved</th>
                    <th>Rejected</th>
                    <th>Status</th>
		    <th>Batch Status</th>
                    <th>Batch Submitted Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>