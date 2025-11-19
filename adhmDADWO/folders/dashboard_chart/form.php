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
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
            <div class="col-md-12">
                  <h6 class="mb-4 text-16">Total Number of Student</h6>
                </div>
              
              <div id="chartContainer_1" style="display:none;">
                <div id="student_chart" style="width: 100%; height: 500px;"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="row mb-4">
                <div class="col-md-12">
                  <h6 class="mb-4 text-16">Admissions Over Time</h6>
                </div>

                <div class="col-md-9">
                  <select name="date_type" id="date_type" class="select2 form-control" onchange="get_date_type();">
                    <?php echo $date_type_option; ?>
                  </select>
                </div>

                <div class="col-md-3" style="align-self: center;">
                  <button type="submit" id="resetButton" class="btn btn-primary" onclick="datewise_chart()">GO</button>
                </div>
                <div class="col-md-6" id="from_date_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="from_date" type="date" value="<?php echo $from_date; ?>">
                  <input class="form-control" id="district_id" type="hidden"
                    value="<?php echo $_SESSION['district_id']; ?>">
                </div>
                <div class="col-md-6" id="to_date_div" style="display:none;margin-top:10px;">
                  <input class="form-control date" id="to_date" type="date" value="<?php echo $to_date; ?>">
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

              <div id="chartContainer" style="margin-top:20px;">
                <div id="datewise_chart" style="width: 100%; height: 400px;"></div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <div class="row">

        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="col-md-12">
                <h6 class="mb-4 text-16">District wise Hostel Occupancy Rate</h6>
              </div>
              <div id="actualchart_1"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-4 text-16">Pending Application</h6>
              <div id="chartdiv-four"></div>
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
              <h6 class="mb-4 text-16">Gender Distribution</h6>
              <div id="chartdiv-five"></div>
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

      <div class="row">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <h6 class="mb-4 text-16">Count - New vs Renewal Admissions</h6>
                </div>
              </div>
              <div id="chartdiv-six"></div>
            </div>
          </div>
        </div>

      </div>



    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Chart code -->


  <script>

    $(document).ready(function () {
      load_chart_data();
    });


    function load_chart_data() {


      var district_id = $("#district_id").val();  // Ensure this ID matches the element's ID in your HTML
      // Optional: For debugging, to check if district_id is correctly retrieved

      var requestData = {
        "district_id": district_id,  // Send the district_id
        "action": "get_chart_data"  // Specify the action
      };

      $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {
          console.log("Response Data: ", responseData); // Log response data
          var obj = JSON.parse(responseData);

          // Check if the response has the expected structure
          console.log("Parsed Object: ", obj); // Log parsed object

          var counts = [
            obj.school_count,
            obj.iti_count,
            obj.college_ug_count,
            obj.college_pg_count,
            obj.diplomo_count
          ];

          var maxValue = Math.max(...counts);
          console.log("Maximum Value: ", maxValue); // Log the maximum value

          $('#chartdiv-three').empty();

          am5.ready(function () {
            // Create root element
            var root = am5.Root.new("chartdiv-three");

            root._logo.dispose();

            // Set themes
            root.setThemes([am5themes_Animated.new(root)]);

            // Create chart
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
              panX: true,
              panY: true,
              wheelX: "panX",
              wheelY: "zoomX",
              scrollbarX: am5.Scrollbar.new(root, { orientation: "horizontal" }),
              scrollbarY: am5.Scrollbar.new(root, { orientation: "vertical" }),
              pinchZoomX: true,
              paddingLeft: 0
            }));

            // Add cursor
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

            // Create axes
            var xRenderer = am5xy.AxisRendererX.new(root, {
              minGridDistance: 15,
              minorGridEnabled: true
            });

            // Setting x-axis labels horizontal by setting rotation to 0
            xRenderer.labels.template.setAll({
              rotation: 0, // Change rotation to 0 to make labels horizontal
              centerY: am5.p50,
              centerX: am5.p50
            });

            xRenderer.grid.template.setAll({
              visible: false
            });

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
              maxDeviation: 0.3,
              categoryField: "category",
              renderer: xRenderer,
              tooltip: am5.Tooltip.new(root, {})
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
              maxDeviation: 0.3,
              renderer: am5xy.AxisRendererY.new(root, {}),
              min: 0, // Set the minimum value of y-axis
              max: maxValue + 1000 // Set the maximum value of y-axis
            }));

            // Create series
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
              xAxis: xAxis,
              yAxis: yAxis,
              valueYField: "value",
              categoryXField: "category",
              adjustBulletPosition: false,
              tooltip: am5.Tooltip.new(root, {
                labelText: "{valueY}"
              })
            }));

            series.columns.template.setAll({
              width: 0.5
            });

            series.bullets.push(function () {
              return am5.Bullet.new(root, {
                locationY: 1,
                sprite: am5.Circle.new(root, {
                  radius: 5,
                  fill: series.get("fill")
                })
              });
            });

            // Set data
            var data = [];
            var names = ["School", "ITI", "College - UG", "College - PG", "Diplomo"];
            var counts = [obj.school_count, obj.iti_count, obj.college_ug_count, obj.college_pg_count, obj.diplomo_count];

            for (var i = 0; i < names.length; i++) {
              data.push({ category: names[i], value: counts[i] });
            }

            // Sort data in ascending order based on value
            data.sort(function (a, b) {
              return b.value - a.value; // Ascending order
            });

            // Set sorted data to axes and series
            xAxis.data.setAll(data);
            series.data.setAll(data);

            // Animate on load
            series.appear(1000);
            chart.appear(1000, 100);
          }); // end am5.ready()
        }, // end success function
        error: function (jqXHR, textStatus, errorThrown) {
          console.error("AJAX Error: ", textStatus, errorThrown); // Log any AJAX errors
        }
      }); // end ajax call
    }

  </script>


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
            $district_id = $_SESSION['district_id'];
            $columns_list = [
              "@a:=@a+1 s_no",
              "district_name",
              "(select COUNT(id) as count FROM std_app_s WHERE is_delete = 0 AND application_type = 1 AND district_name.unique_id = std_app_s.hostel_district_1) as new_count",
            ];

            $table_details_list = [
              $table_main . ", (SELECT @a:= " . $start . ") AS a ",
              $columns_list
            ];
            $where_list = "district_name.unique_id='" . $district_id . "'";
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
            $district_id = $_SESSION['district_id'];

            $columns_list = [
              "@a:=@a+1 s_no",
              "district_name",
              "(select COUNT(id) as count FROM std_app_s WHERE is_delete = 0 AND application_type = 2 AND district_name.unique_id = std_app_s.hostel_district_1) as renewal_count",
            ];

            $table_details_list = [
              $table_main . ", (SELECT @a:= " . $start . ") AS a ",
              $columns_list
            ];

            $where_list = "district_name.unique_id='" . $district_id . "'";

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


    function pending_application_chart_data() {

      var district_id = $('#district_id').val();
      var requestData = {
        "district_id": district_id,
        "action": "pending_application_chart_data"
      };

      $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {
          console.log("Response Data: ", responseData); // Log response data
          var obj = JSON.parse(responseData);

          // Check if the response has the expected structure
          console.log("Parsed Object: ", obj); // Log parsed object

          if (obj.status) {
            var data = [{
              name: "New",
              steps: obj.new_count
            },
            {
              name: "Renewal",
              steps: obj.renewal_count
            }
            ];

            data.sort(function (a, b) {
              return b.steps - a.steps;
            });

            // Calculate the maximum value from the data to set the y-axis range
            var maxSteps = Math.max.apply(Math, data.map(function (item) {
              return item.steps;
            }));

            // Optional: You can round up the max value to the nearest 1000 or any appropriate value
            var yAxisMax = Math.ceil(maxSteps / 1000) * 1000;

            $('#chartdiv-two').empty();

            am5.ready(function () {

              var root = am5.Root.new("chartdiv-four");
              root._logo.dispose();

              // Set themes
              root.setThemes([
                am5themes_Animated.new(root)
              ]);

              // Create chart
              var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                  panX: false,
                  panY: false,
                  wheelX: "none",
                  wheelY: "none",
                  paddingBottom: 50,
                  paddingTop: 40,
                  paddingLeft: 0,
                  paddingRight: 0
                })
              );

              // Create axes
              var xRenderer = am5xy.AxisRendererX.new(root, {
                minorGridEnabled: true,
                minGridDistance: 60
              });
              xRenderer.grid.template.set("visible", false);

              var xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, {
                  paddingTop: 40,
                  categoryField: "name",
                  renderer: xRenderer
                })
              );

              var yRenderer = am5xy.AxisRendererY.new(root, {});
              yRenderer.grid.template.set("strokeDasharray", [3]);

              var yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                  min: 0,  // Minimum value of the y-axis
                  max: yAxisMax,  // Dynamically set the maximum value based on data
                  renderer: yRenderer
                })
              );

              // Add series
              var series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                  name: "New",
                  xAxis: xAxis,
                  yAxis: yAxis,
                  valueYField: "steps",
                  categoryXField: "name",
                  sequencedInterpolation: true,
                  calculateAggregates: true,
                  maskBullets: false,
                  tooltip: am5.Tooltip.new(root, {
                    dy: -30,
                    pointerOrientation: "vertical",
                    labelText: "{valueY}"
                  })
                })
              );

              series.columns.template.setAll({
                strokeOpacity: 0,
                cornerRadiusBR: 10,
                cornerRadiusTR: 10,
                cornerRadiusBL: 10,
                cornerRadiusTL: 10,
                maxWidth: 50,
                fillOpacity: 0.8
              });

              // Set the series data from the AJAX response
              series.data.setAll(data); // Use the dynamically fetched data
              xAxis.data.setAll(data); // Update xAxis with the new data

              // Modal handling on column click
              series.columns.template.events.on("click", function (e) {
                var dataItem = e.target.dataItem;
                var category = dataItem.get("categoryX");
                var count = dataItem.get("valueY");

                if (category == "New") {
                  // Show modal for 'New' category
                  var modal = new bootstrap.Modal(document.getElementById('countModal1'), {});
                  modal.show();
                } else if (category == "Renewal") {
                  // Populate modal content for 'Renewal' category
                  $("#modalContent").html(`Category: ${category}<br>Count: ${count}`);
                  $("#countModalLabel").text(`${category} Details`);

                  // Show modal for 'Renewal' category
                  var modal = new bootstrap.Modal(document.getElementById('countModal2'), {});
                  modal.show();
                }
              });

              var currentlyHovered;

              series.columns.template.events.on("pointerover", function (e) {
                handleHover(e.target.dataItem);
              });

              series.columns.template.events.on("pointerout", function (e) {
                handleOut();
              });

              function handleHover(dataItem) {
                if (dataItem && currentlyHovered != dataItem) {
                  handleOut();
                  currentlyHovered = dataItem;
                  var bullet = dataItem.bullets[0];
                  bullet.animate({
                    key: "locationY",
                    to: 1,
                    duration: 600,
                    easing: am5.ease.out(am5.ease.cubic)
                  });
                }
              }

              function handleOut() {
                if (currentlyHovered) {
                  var bullet = currentlyHovered.bullets[0];
                  bullet.animate({
                    key: "locationY",
                    to: 0,
                    duration: 600,
                    easing: am5.ease.out(am5.ease.cubic)
                  });
                }
              }

              var circleTemplate = am5.Template.new({});

              series.bullets.push(function (root, series, dataItem) {
                var bulletContainer = am5.Container.new(root, {});
                var circle = bulletContainer.children.push(
                  am5.Circle.new(
                    root,
                    {
                      radius: 34
                    },
                    circleTemplate
                  )
                );

                var maskCircle = bulletContainer.children.push(
                  am5.Circle.new(root, { radius: 27 })
                );

                var imageContainer = bulletContainer.children.push(
                  am5.Container.new(root, {
                    mask: maskCircle
                  })
                );

                var image = imageContainer.children.push(
                  am5.Picture.new(root, {
                    templateField: "pictureSettings",
                    centerX: am5.p50,
                    centerY: am5.p50,
                    width: 60,
                    height: 60
                  })
                );

                return am5.Bullet.new(root, {
                  locationY: 0,
                  sprite: bulletContainer
                });
              });

              // heatrule
              series.set("heatRules", [{
                dataField: "valueY",
                min: am5.color(0xe5dc36),
                max: am5.color(0x5faa46),
                target: series.columns.template,
                key: "fill"
              },
              {
                dataField: "valueY",
                min: am5.color(0xe5dc36),
                max: am5.color(0x5faa46),
                target: circleTemplate,
                key: "fill"
              }
              ]);

              // Make stuff animate on load
              series.appear();
              chart.appear(1000, 100);
            });
          } else {
            console.log("Error: " + obj.msg);
          }
        }
      });
    }

 

    $(document).ready(function () {
      pending_application_chart_data();
      new_renewal_count();
    });
  </script>



  <script>

    function pie_chart_data() {

      var district_id = $("#district_id").val();  // Ensure this ID matches the element's ID in your HTML
      // Optional: For debugging, to check if district_id is correctly retrieved

      var requestData = {
        "district_id": district_id,  // Send the district_id
        "action": "get_chart_data"  // Specify the action
      };

      $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {
          console.log("Response Data: ", responseData); // Log response data
          var obj = JSON.parse(responseData);

          // Check if the response has the expected structure
          console.log("Parsed Object: ", obj); // Log parsed object

          $('#chartdiv-two').empty();

          am5.ready(function () {
            // Create root element
            var root = am5.Root.new("chartdiv-two");

            root._logo.dispose();

            // Set themes
            root.setThemes([
              am5themes_Animated.new(root)
            ]);

            // Create chart
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
              layout: root.verticalLayout,
              radius: am5.percent(50) // Adjust the radius here for a smaller chart
            }));

            // Create series
            var series = chart.series.push(am5percent.PieSeries.new(root, {
              valueField: "value",
              categoryField: "category"
            }));

            // Set data from the response
            series.data.setAll([
              { value: obj.school_count, category: "School" },
              { value: obj.iti_count, category: "ITI" },
              { value: obj.college_ug_count, category: "UG" },
              { value: obj.college_pg_count, category: "PG" },
              { value: obj.diplomo_count, category: "Diploma" },
            ]);

            // Customize labels to show actual values with categories
            series.labels.template.setAll({
              text: "{value} {category}", // Display the actual value and category
            });

            // Customize tooltips to show actual value + category
            series.slices.template.setAll({
              tooltipText: "{value} {category}" // Show value and category in tooltip
            });

            // Enable tooltips
            series.slices.template.set("tooltipText", "{value} {category}");

            // Play initial series animation
            series.appear(1000, 100);
            chart.logo.disabled = true;
          }); // end am5.ready()
        },
        error: function (xhr, status, error) {
          console.error("AJAX Error: ", status, error); // Log any AJAX errors
        }
      });
    }

    $(document).ready(function () {
      pie_chart_data();
    });
  </script>


  <script>
   
    $(document).ready(function () {
      occupancy_chart_data();
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