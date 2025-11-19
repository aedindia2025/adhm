<?php include '../../config/common_fun.php'; ?>
<?php
$district_name_list = district_name();
$district_name_list = select_option($district_name_list, "Select District", $district_name);

$taluk_name_list = taluk_name();
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

$hostel_name_list = hostel_name();
$hostel_name_list = select_option($hostel_name_list, "Select Hostel", $hostel_name);


$from_date = date('Y-m-d');
$to_date = date('Y-m-d');

$date_type = '1';

$date_type_option = [
  "1" => [
    "unique_id" => "1",
    "value" => "Past Week",
  ],
  "2" => [
    "unique_id" => "2",
    "value" => "Past Month",
  ],
  "3" => [
    "unique_id" => "3",
    "value" => "Custom",
  ],

];
$date_type_option = select_option_1($date_type_option, "Select", $date_type);
?>
<style>
  div#datewise_chart {
    width: 100%;
    height: 500px;
  }

  canvas#Chart1 {
    margin-top: 25px;
  }

  .load {
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: none;

  }

  i.mdi.mdi-loading.mdi-spin {
    font-size: 75px;
    color: #17a8df;
  }

  #chartdiv-bar {
    width: 100%;
    height: 600px;
  }

  #actualchart {
    width: 100%;
    height: 455px;
  }

  #chartdiv-three {
    width: 100%;
    height: 500px;
  }

  #chartdiv-two {
    width: 100%;
    height: 500px;
  }

  #chartdiv-four {
    width: 100%;
    height: 500px;
  }

  #chartdiv-five {
    width: 100%;
    height: 500px;
  }

  #chartdiv-six {
    width: 100%;
    height: 500px;
  }

  #chartdiv-seven {
    width: 100%;
    height: 500px;
  }

  #chartdiv-eight {
    width: 100%;
    height: 500px;
  }
</style>
<!-- Resources -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Responsive.js"></script>
<div class="content-page mt-3">
  <div class="content">
    <!-- Start Content-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <h4 class="page-title">Total Number of Student</h4>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div id="chart_Container_1">
                <div id="student_chart" style="width: 100%; height: 500px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <?php include "map.php" ?>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h6 class="mb-4 text-16">Admissions Over Time</h6>
                </div>
                <div class="col-md-10">
                  <select name="date_type" id="date_type" class="select2 form-control" onchange="get_date_type();">
                    <?php echo $date_type_option; ?>
                  </select>
                </div>
                <div class="col-md-2" style="align-self: center;">
                  <button type="submit" class="btn btn-primary" onclick="datewise_chart()">GO</button>
                </div>
                <div class="col-md-6" id="from_date_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="from_date" type="date" value="<?php echo $from_date; ?>">
                </div>
                <div class="col-md-6" id="to_date_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="to_date" type="date" value="<?php echo $to_date; ?>">
                  <input class="form-control" type="text" id="district_id" name="district_id"
                    value="<?php echo $_SESSION['district_id']; ?>">
                </div>
                <div class="col-md-6" id="from_week_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="from_week" name="from_week" type="week"
                    value="<?php echo $from_date; ?>">
                </div>
                <div class="col-md-6" id="to_week_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="to_week" name="to_week" type="week"
                    value="<?php echo $to_date; ?>">
                </div>
              </div>
              <div id="chartContainer_d" style="margin-top:20px;">
                <div id="datewise_chart" style="width: 100%; height: 400px;"></div>
              </div>

            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="col-md-12">
                <h6 class="mb-4 text-16">District wise Hostel Occupancy Rate</h6>
              </div>
              <div id="actualchart"></div>
            </div>
          </div>
        </div>

      </div>
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <h4 class="page-title">Student Demographics</h4>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div id="chartdiv-three"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div id="chartdiv-two"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-4 text-16">Pending Application</h6>
              <div id="chartdiv-four"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-4 text-16">Gender Distribution</h6>
              <div id="chartdiv-five"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h6 class="mb-4 text-16">Count - New vs Renewal Admissions</h6>
                </div>
                <div class="col-md-10">
                  <select name="district" id="district" class="select2 form-control">
                    <?php echo $district_name_list; ?>
                  </select>
                </div>
                <div class="col-md-2" style="align-self: center;">
                  <!-- <button type="submit" class="btn btn-primary" onclick="new_renewal_count()">GO</button> -->
                  <button type="submit" class="btn btn-primary" onclick="applyFilter()">GO</button>
                  <!-- onclick="district_filter()" -->
                </div>
              </div>
              <div id="chartdiv-six"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-4 text-16">Application Processing Time</h6>
              <div id="chartdiv-seven"></div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <div class="modal fade" id="countModal1" tabindex="-1" aria-labelledby="countModalLabel1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="countModalLabel1">Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- <p id="modalContent">Loading details...</p> -->
          <table class="table" id="districtCountsTable_1">
            <thead>
              <tr>
                <th>S.No</th>
                <th>District</th>
                <th>New Count</th>
              </tr>
            </thead>
            <?php
            $start = 0;
            $table_main = "district_name";

            $columns_list = [
              "@a:=@a+1 s_no",
              "district_name",
              "(select COUNT(id) as count FROM std_app_s WHERE is_delete = 0 AND application_type = 1 AND district_name.unique_id = std_app_s.hostel_district_1) as new_count",
            ];

            $table_details_list = [
              $table_main . ", (SELECT @a:= " . $start . ") AS a ",
              $columns_list
            ];

            $result = $pdo->select($table_details_list, $where_list);
            // print_r($result);
            
            if ($result->status) {

              $res_array = $result->data;

              $table_data = "";
              if (count($res_array) == 0) {
                $table_data .= "<tr>";

                $table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
                $table_data .= "</tr>";
              } else {
                foreach ($res_array as $key => $value) {

                  $table_data .= "<tr>";

                  $table_data .= "<td>" . $value['s_no'] . "</td>";
                  $table_data .= "<td>" . $value['district_name'] . "</td>";
                  $table_data .= "<td style = 'text-align : left'>" . $value['new_count'] . "</td>";
                  $table_data .= "</tr>";
                }
              }
            }

            // }
            ?>

            <tbody id="districtCountsBody">
              <?php echo $table_data; ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="countModal2" tabindex="-1" aria-labelledby="countModalLabel2" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="countModalLabel2">Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- <p id="modalContent">Loading details...</p> -->
          <table class="table" id="districtCountsTable_2">
            <thead>
              <tr>
                <th>S.No</th>
                <th>District</th>
                <th>Renewal Count</th>
              </tr>
            </thead>
            <?php
            $start = 0;
            $table_main = "district_name";

            $columns_list = [
              "@a:=@a+1 s_no",
              "district_name",
              "(select COUNT(id) as count FROM std_app_s WHERE is_delete = 0 AND application_type = 2 AND district_name.unique_id = std_app_s.hostel_district_1) as renewal_count",
            ];

            $table_details_list = [
              $table_main . ", (SELECT @a:= " . $start . ") AS a ",
              $columns_list
            ];

            $result = $pdo->select($table_details_list, $where_list);
            // print_r($result);
            
            if ($result->status) {

              $res_array = $result->data;

              $table_data = "";
              if (count($res_array) == 0) {
                $table_data .= "<tr>";

                $table_data .= "<td colspan=9; style='text-align:center'>NO DATA FOUND</td>";
                $table_data .= "</tr>";
              } else {
                foreach ($res_array as $key => $value) {

                  $table_data .= "<tr>";

                  $table_data .= "<td>" . $value['s_no'] . "</td>";
                  $table_data .= "<td>" . $value['district_name'] . "</td>";
                  $table_data .= "<td style = 'text-align : left'>" . $value['renewal_count'] . "</td>";
                  $table_data .= "</tr>";
                }
              }
            }

            // }
            ?>

            <tbody id="districtCountsBody">
              <?php echo $table_data; ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script> 
    $(document).ready(function () {
     // pending_application_chart_data();
    });
  </script>
  <script>
    $(document).ready(function () {
      //pie_chart_data();
    });
  </script>
  <script>
    
    // Call the function to load the chart data when needed
    $(document).ready(function () {
     // occupancy_chart_data();
    });
  </script>

  <footer class="footer">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <script>document.write(new Date().getFullYear())</script> © Adi Dravidar Welfare Department -
          Managed by <a href="https://aedindia.com/">Ascent e Digit Solutions </a>
        </div>
        <div class="col-md-4">
          <div class="text-md-end footer-links d-none d-md-block">

            <a href="javascript: void(0);">Support</a>
            <a href="javascript: void(0);">Contact Us</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>