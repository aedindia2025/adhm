$(document).ready(function () {

    datewise_chart();
    load_chart_data();
    get_processing_times();
    student_chart();
   // student_occupancy();
    pie_chart_data();
    pending_application_chart_data();
    occupancy_chart_data();
});





function filter() {


    // document.getElementById('chart_div').style.display = 'none';

    var date_type = $("#date_type").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var requestData = {
        "action": "get_application_counts",
        "date_type": date_type,
        "to_date": to_date,
        "from_date": from_date,
    };
    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {


            // document.getElementById('chart_div').style.display = 'block';
            var obj = JSON.parse(data);
            var new_count = obj.new_count;
            var renewal_cnt = obj.renewal_cnt;
            var district_name = obj.district_name;

            new_renewal_chart_filter(new_count, renewal_cnt, district_name);

        }

    })
}

function get_date_type() {
    // alert();
    var date_type = $('#date_type').val();
    // alert(date_type);
    if (date_type == 3) {
        document.getElementById('from_week_div').style.display = 'block';
        document.getElementById('to_week_div').style.display = 'block';
    }
    else {
        document.getElementById('from_week_div').style.display = 'none';
        document.getElementById('to_week_div').style.display = 'none';
        // filter();
    }
}

function load_chart_data() {

    var requestData = {
      "action": "get_chart_data"
    };

    $.ajax({
      url: "folders/dashboard_chart/crud.php",
      type: 'POST',
      data: requestData,
      success: function (responseData) {
        console.log("Response Data: ", responseData); // Log response data
        var obj = JSON.parse(responseData);

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
            max: maxValue + 2000 // Set the maximum value of y-axis
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

  function pending_application_chart_data() {
    var requestData = {
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
          }, {
            name: "Renewal",
            steps: obj.renewal_count
          }];
          data.sort(function (a, b) {
            return b.steps - a.steps;
          });

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
                min: 0,
                max: 50000,
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
            series.data.setAll(data);  // Use the dynamically fetched data
            xAxis.data.setAll(data);   // Update xAxis with the new data

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
            series.set("heatRules", [
              {
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
  function pie_chart_data() {
    var requestData = {
      "action": "get_chart_data"
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
          //chart.logo.disabled = true;
        }); // end am5.ready()
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error: ", status, error); // Log any AJAX errors
      }
    });
  }
  function occupancy_chart_data() {
    var requestData = {
      "action": "occupancy_chart_data"
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

        // Extract data from the response object
        var occupancyData = obj.occupancy || []; // Occupancy data
        var availabilityData = obj.availability || []; // Availability data
        var districtNames = obj.district_name || []; // District names

        // Create an array of objects for sorting
        var combinedData = occupancyData.map((occupancy, index) => ({
          occupancy: occupancy,
          availability: availabilityData[index] || 0, // Default to 0 if not available
          district: districtNames[index] || ''
        }));

        // Sort the combined data based on occupancy in descending order
        combinedData.sort((a, b) => b.occupancy - a.occupancy);

        // Separate sorted data back into arrays
        var sortedOccupancyData = combinedData.map(item => item.occupancy);
        var sortedAvailabilityData = combinedData.map(item => item.availability);
        var sortedDistrictNames = combinedData.map(item => item.district);

        // Define colors and chart options
        var colors = ["#727cf5", "#e3eaef"];
        var options = {
          chart: {
            height: 435,
            type: 'bar',
            stacked: true
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '20%'
            }
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 0,
            colors: ['transparent']
          },
          series: [{
            name: 'Occupancy',
            data: sortedOccupancyData // Use sorted occupancy data
          }, {
            name: 'Availability',
            data: sortedAvailabilityData // Use sorted availability data
          }],
          zoom: {
            enabled: false
          },
          legend: {
            show: false
          },
          colors: colors,
          xaxis: {
            categories: sortedDistrictNames, // Use sorted district names
            axisBorder: {
              show: false
            },
            labels: {
              rotate: -90, // Rotate labels vertically
              style: {
                fontSize: '12px'
              }
            }
          },
          yaxis: {
            stepSize: 40,
            labels: {
              formatter: function (value) {
                return value;
              },
              offsetX: -15
            }
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (value) {
                return value;
              }
            }
          }
        };

        // Render the chart after the data has been processed
        var chart = new ApexCharts(document.querySelector("#actualchart"), options);
        chart.render();
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error: ", status, error); // Handle errors
      }
    });
  }

function showLoader() {

    $("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
    $("#loader").css("display", "none");
}

var root_date; // Declare a global variable for the root object

function datewise_chart() {
    showLoader();
    document.getElementById('chartContainer_d').style.display = 'none';

    var date_type = $('#date_type').val();


    if (date_type == 2) {
        var action = "month_wise_counts";
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

    } else if (date_type == 1) {
        var action = "date_wise_counts";
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

    } else if (date_type == 3) {
        var from_date = $('#from_week').val();
        var to_date = $('#to_week').val();
        // alert(from_date);
        // alert(to_date);    
        var action = "custome_wise_counts";

    }

    var requestData = {
        "action": action,
        "date_type": date_type,
        "to_date": to_date,
        "from_date": from_date,
    };
    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {
            hideLoader();
            document.getElementById('chartContainer_d').style.display = 'block';

            var obj = JSON.parse(data);
            var entry_date = obj.entry_date;
            var new_cnt = obj.new_cnt;
            var renewel = obj.renewel;

            // Debug: Check the data
            console.log('Entry Date:', entry_date);
            console.log('New Count:', new_cnt);
            console.log('Renewel:', renewel);

            am5.ready(function () {
                if (root_date) {
                    root_date.dispose();
                }

                root_date = am5.Root.new("datewise_chart");

                root_date._logo.dispose();

                root_date.setThemes([am5themes_Animated.new(root_date)]);

                var chart = root_date.container.children.push(am5xy.XYChart.new(root_date, {
                    panX: true,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    paddingLeft: 0,
                    layout: root_date.verticalLayout
                }));

                chart.get("colors").set("colors", [
                    am5.color(0xBF6240), // Color for new_cnt
                    am5.color(0x409DBF)  // Color for renewel
                ]);

                chart.set("scrollbarX", am5.Scrollbar.new(root_date, {
                    orientation: "horizontal"
                }));

                var xRenderer = am5xy.AxisRendererX.new(root_date, {
                    minGridDistance: 8,
                    minorGridEnabled: true
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root_date, {
                    categoryField: "entry_date",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root_date, {
                        themeTags: ["axis"],
                        animationDuration: 200
                    })
                }));

                xRenderer.grid.template.setAll({
                    location: 1
                });

                xAxis.data.setAll(entry_date.map(function (date) {
                    return { "entry_date": date };
                }));

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root_date, {
                    min: 0,
                    renderer: am5xy.AxisRendererY.new(root_date, {
                        strokeOpacity: 0.1
                    })
                }));

                var legend = chart.children.push(am5.Legend.new(root_date, {
                    nameField: "name",
                    fillField: "color",
                    strokeField: "color",
                    centerX: am5.percent(50),
                    x: am5.percent(50)
                  }));
                  
                  legend.data.setAll([{
                    name: "New Applications",
                    color: am5.color(0xBF6240)
                  }, {
                    name: "Renewel Applications",
                    color: am5.color(0x409DBF)
                  }]);

                // Series for new_cnt
                var series0 = chart.series.push(am5xy.ColumnSeries.new(root_date, {
                    name: "New Count",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "new_cnt",
                    categoryXField: "entry_date",
                    clustered: false,
                    tooltip: am5.Tooltip.new(root_date, {
                        labelText: "New: {valueY}"
                    })
                }));

                series0.columns.template.setAll({
                    width: am5.percent(30),
                    tooltipY: 0,
                    strokeOpacity: 0
                });

                // Series for renewel
                var series1 = chart.series.push(am5xy.ColumnSeries.new(root_date, {
                    name: "Renewel",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "renewel",
                    categoryXField: "entry_date",
                    clustered: false,
                    tooltip: am5.Tooltip.new(root_date, {
                        labelText: "Renewel: {valueY}"
                    })
                }));

                series1.columns.template.setAll({
                    width: am5.percent(20),
                    tooltipY: 0,
                    strokeOpacity: 0
                });

                // Map data for both series
                var chartData = entry_date.map(function (date, index) {
                    return {
                        "entry_date": date,
                        "new_cnt": parseInt(new_cnt[index]),
                        "renewel": parseInt(renewel[index])
                    };
                });

                series0.data.setAll(chartData);
                series1.data.setAll(chartData);
                var legend = chart.children.push(am5.Legend.new(root, {
                    centerX: am5.percent(50),
                    x: am5.percent(50),
                    layout: root.horizontalLayout
                }));

                legend.data.setAll(chart.series.values); // Add series to the legend


                var cursor = chart.set("cursor", am5xy.XYCursor.new(root_date, {}));

                
                chart.appear(1000, 100);
                series0.appear();
                series1.appear();
            }); // end am5.ready()
        }
    });

}


$(document).ready(function () {
    new_renewal_count(); // Initialize the chart on page load
});

var root_new_ren; // Declare a global variable for the root object

function new_renewal_count() {
    // Get the selected district value from the dropdown
    var district = $('#district').val();

    // AJAX request to fetch filtered data based on the district
    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: {
            "district": district,
            "action": "new_renewal_count"
        },
        success: function (responseData) {
            // Parse the JSON response
            var obj = JSON.parse(responseData);

            // Prepare the data for the chart
            var data = [
                { year: "School", new: obj.school_new, renewal: obj.school_renewal },
                { year: "ITI", new: obj.iti_new, renewal: obj.iti_renewal },
                { year: "Diploma", new: obj.diploma_new, renewal: obj.diploma_renewal },
                { year: "UG", new: obj.ug_new, renewal: obj.ug_renewal },
                { year: "PG", new: obj.pg_new, renewal: obj.pg_renewal }
            ];

            // Sort data in descending order based on the sum of new + renewal
            data.sort(function (a, b) {
                var sumA = a.new + a.renewal;
                var sumB = b.new + b.renewal;
                return sumB - sumA; // Sort in descending order
            });

            // Find the maximum value from both 'new' and 'renewal'
            var maxValue = Math.max(
                ...data.map(item => Math.max(item.new, item.renewal))
            );

            console.log("Maximum value:", maxValue);

            // Clear the existing chart before updating with new data
            $('#chartdiv-six').empty();

            // Dispose of the previous chart if it exists
            // if (chartRoot) {
            //     chartRoot.dispose();
            // }

            // Call amCharts to update the chart with the new data
            am5.ready(function () {

                if (root_new_ren) {
                    root_new_ren.dispose();
                }

                root_new_ren = am5.Root.new("chartdiv-six");
                // alert("am5_chart");

                root_new_ren._logo.dispose();

                root_new_ren.setThemes([am5themes_Animated.new(root_new_ren)]);

                // Create XYChart
                var chart = root_new_ren.container.children.push(am5xy.XYChart.new(root_new_ren, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    paddingLeft: 0,
                    layout: root_new_ren.verticalLayout
                }));

                // Create Y-axis (category axis)
                var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root_new_ren, {
                    categoryField: "year",
                    renderer: am5xy.AxisRendererY.new(root_new_ren, {
                        inversed: true,
                        cellStartLocation: 0.1,
                        cellEndLocation: 0.9,
                        minorGridEnabled: true
                    })
                }));

                yAxis.data.setAll(data);

                // Create X-axis (value axis)
                var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root_new_ren, {
                    renderer: am5xy.AxisRendererX.new(root_new_ren, {
                        strokeOpacity: 0.1,
                        minGridDistance: 50
                    }),
                    min: 0,
                    max: maxValue + 1000
                }));

                // Create series (new and renewal)
                function createSeries(field, name) {
                    var series = chart.series.push(am5xy.ColumnSeries.new(root_new_ren, {
                        name: name,
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueXField: field,
                        categoryYField: "year",
                        sequencedInterpolation: true,
                        tooltip: am5.Tooltip.new(root_new_ren, {
                            pointerOrientation: "horizontal",
                            labelText: "[bold]{name}[/]\n{categoryY}: {valueX}"
                        })
                    }));

                    series.columns.template.setAll({
                        height: am5.p100,
                        strokeOpacity: 0
                    });

                    series.bullets.push(function () {
                        return am5.Bullet.new(root_new_ren, {
                            locationX: 1,
                            locationY: 0.5,
                            sprite: am5.Label.new(root_new_ren, {
                                centerY: am5.p50,
                                text: "{valueX}",
                                populateText: true
                            })
                        });
                    });

                    series.data.setAll(data);
                    series.appear();

                    return series;
                }

                // Add series for new and renewal counts
                createSeries("new", "New");
                createSeries("renewal", "Renewal");

                // Add legend
                var legend = chart.children.push(am5.Legend.new(root_new_ren, {
                    centerX: am5.p50,
                    x: am5.p50
                }));    
                legend.data.setAll(chart.series.values);

                // Add cursor
                var cursor = chart.set("cursor", am5xy.XYCursor.new(root_new_ren, {
                    behavior: "zoomY"
                }));
                cursor.lineY.set("forceHidden", true);
                cursor.lineX.set("forceHidden", true);

                // Animate chart appearance
                chart.appear(1000, 100);
            });
        }
    });
}

// This function will be triggered when the GO button is clicked
function applyFilter() {
    new_renewal_count(); // Call the function to re-fetch data and update the chart
}


$(document).ready(function () {

    function boys_girls_count() {
        // Define the request data, including the action
        var requestData = {
            action: "boys_girls_count"  // This is where the action is passed
        };

        // Perform the AJAX request
        $.ajax({
            url: "folders/dashboard_chart/crud.php",  // URL to handle the request
            type: 'POST',
            data: requestData,  // Pass the action to the server
            success: function (responseData) {
                // Call the function to render the pie chart with the response data
                renderPieChart(responseData);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX error: " + textStatus + " : " + errorThrown);
            }
        });
    }

    // Call the function to fetch and render the chart
    boys_girls_count();
});


function renderPieChart(responseData) {
    var obj = JSON.parse(responseData);

    // Extract values from the response
    var boys_value = obj.boys;
    var girls_value = obj.girls;

    // Empty the chart container if needed
    $('#chartdiv-five').empty();

    am5.ready(function () {

        // Create root element
        var root = am5.Root.new("chartdiv-five");

        root._logo.dispose();

        // Set themes
        root.setThemes([
            am5themes_Animated.new(root)
        ]);

        // Create chart
        var chart = root.container.children.push(
            am5percent.PieChart.new(root, {
                layout: root.verticalLayout
            })
        );

        // Create series 0
        var series0 = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                alignLabels: false,
                radius: am5.percent(100),
                innerRadius: am5.percent(80)
            })
        );

        series0.states.create("hidden", {
            startAngle: 180,
            endAngle: 180
        });

        series0.slices.template.setAll({
            fillOpacity: 0.5,
            strokeOpacity: 0,
            templateField: "settings"
        });

        series0.slices.template.states.create("hover", { scale: 1 });
        series0.slices.template.states.create("active", { shiftRadius: 0 });

        series0.labels.template.setAll({
            templateField: "settings"
        });

        series0.ticks.template.setAll({
            templateField: "settings"
        });

        series0.labels.template.setAll({
            textType: "circular",
            radius: 30
        });

        // Set dynamic data with color settings
        series0.data.setAll([
            {
                category: "Boys",
                value: boys_value,
                settings: { fill: am5.color(0x89CFF0) } // Baby blue color
            },
            {
                category: "Girls",
                value: girls_value,
                settings: { fill: am5.color(0xFFC0CB) } // Pink color
            }
        ]);

        // Create series 1
        var series1 = chart.series.push(
            am5percent.PieSeries.new(root, {
                radius: am5.percent(95),
                innerRadius: am5.percent(85),
                valueField: "value",
                categoryField: "category",
                alignLabels: false
            })
        );

        series1.states.create("hidden", {
            startAngle: 180,
            endAngle: 180
        });

        series1.slices.template.setAll({
            templateField: "sliceSettings",
            strokeOpacity: 0
        });

        series1.labels.template.setAll({
            textType: "circular"
        });

        series1.labels.template.adapters.add("radius", function (radius, target) {
            var dataItem = target.dataItem;
            var slice = dataItem.get("slice");
            return -(slice.get("radius") - slice.get("innerRadius")) / 2 - 10;
        });

        series1.slices.template.states.create("hover", { scale: 1 });
        series1.slices.template.states.create("active", { shiftRadius: 0 });

        series1.ticks.template.setAll({
            forceHidden: true
        });

        // Set dynamic data with color settings
        series1.data.setAll([
            {
                category: "Boys",
                value: boys_value,
                sliceSettings: { fill: am5.color(0x89CFF0) } // Baby blue color
            },
            {
                category: "Girls",
                value: girls_value,
                sliceSettings: { fill: am5.color(0xFFC0CB) } // Pink color
            }
        ]);

    }); // end am5.ready()
}
function student_occupancy() {
    showLoader();
    document.getElementById('chartContainer').style.display = 'none';

    var action = "get_student_occupancy";

    var requestData = {
        "action": action,
    };

    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {
            hideLoader();
            document.getElementById('chartContainer').style.display = 'block';

            var obj = JSON.parse(data);
            var district_name = obj.district_name;
            var cnt = obj.cnt;

            // Debug: Check the data
            console.log('district_name:', district_name);
            console.log('cnt:', cnt);
            // console.log('Renewel:', renewel);

            am5.ready(function () {
                if (root) {
                    root.dispose();
                }

                root = am5.Root.new("actualchart");

                root._logo.dispose();

                root.setThemes([am5themes_Animated.new(root)]);

                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: true,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    paddingLeft: 0,
                    layout: root.verticalLayout
                }));

                chart.get("colors").set("colors", [
                    am5.color(0x409DBF), // Color for cnt
                    am5.color(0x409DBF)  // Color for renewel
                ]);

                chart.set("scrollbarX", am5.Scrollbar.new(root, {
                    orientation: "horizontal"
                }));

                var xRenderer = am5xy.AxisRendererX.new(root, {
                    minGridDistance: 8,
                    minorGridEnabled: true
                });


                // Rotate labels on X-axis by 90 degrees
                xRenderer.labels.template.setAll({
                    rotation: -90, // Rotate labels by 90 degrees
                    centerY: am5.p50,
                    centerX: am5.p50,
                    horizontalCenter: "middle",
                    verticalCenter: "top",
                    textAlign: "center",
                    fontSize: "12px",  // Adjust font size here if needed
                    fill: am5.color(0x000000) // Adjust text color here if needed
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "district_name",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root, {
                        themeTags: ["axis"],
                        animationDuration: 200
                    })
                }));

                xRenderer.grid.template.setAll({
                    location: 1
                });

                xAxis.data.setAll(district_name.map(function (date) {
                    return { "district_name": date };
                }));

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    min: 0,
                    renderer: am5xy.AxisRendererY.new(root, {
                        strokeOpacity: 0.1
                    })
                }));

                // Series for cnt
                var series0 = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "District Name",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "cnt",
                    categoryXField: "district_name",
                    clustered: false,
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "New: {valueY}"
                    })
                }));

                series0.columns.template.setAll({
                    width: am5.percent(30),
                    tooltipY: 0,
                    strokeOpacity: 0
                });



                // Map data for both series
                var chartData = district_name.map(function (date, index) {
                    return {
                        "district_name": date,
                        "cnt": parseInt(cnt[index])
                    };
                });

                series0.data.setAll(chartData);

                var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));

                chart.appear(1000, 100);
                series0.appear();
            }); // end am5.ready()
        }
    });
}



function student_chart() {
    showLoader();

    document.getElementById('chart_Container_1').style.display = 'none';

    var action = "get_student_count";

    var requestData = {
        "action": action,
    };

    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {
            hideLoader();
            document.getElementById('chart_Container_1').style.display = 'block';

            var obj = JSON.parse(data);
            var district_name = obj.district_name;
            var cnt = obj.cnt;

            am5.ready(function () {
                
                root = am5.Root.new("student_chart");

                root._logo.dispose();

                root.setThemes([am5themes_Animated.new(root)]);

                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: true,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    paddingLeft: 0,
                    layout: root.verticalLayout
                }));

                chart.get("colors").set("colors", [
                    am5.color(0x409DBF), // Color for cnt
                    am5.color(0x409DBF)  // Color for renewel
                ]);

                chart.set("scrollbarX", am5.Scrollbar.new(root, {
                    orientation: "horizontal"
                }));

                var xRenderer = am5xy.AxisRendererX.new(root, {
                    minGridDistance: 8,
                    minorGridEnabled: true
                });


                // Rotate labels on X-axis by 90 degrees
                xRenderer.labels.template.setAll({
                    rotation: -90, // Rotate labels by 90 degrees
                    centerY: am5.p50,
                    centerX: am5.p50,
                    horizontalCenter: "middle",
                    verticalCenter: "top",
                    textAlign: "center",
                    fontSize: "12px",  // Adjust font size here if needed
                    fill: am5.color(0x000000) // Adjust text color here if needed
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "district_name",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root, {
                        themeTags: ["axis"],
                        animationDuration: 200
                    })
                }));

                xRenderer.grid.template.setAll({
                    location: 1
                });

                xAxis.data.setAll(district_name.map(function (date) {
                    return { "district_name": date };
                }));

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    min: 0,
                    renderer: am5xy.AxisRendererY.new(root, {
                        strokeOpacity: 0.1
                    })
                }));

                // Series for cnt
                var series0 = chart.series.push(am5xy.ColumnSeries.new(root, {
                    name: "District Name",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "cnt",
                    categoryXField: "district_name",
                    clustered: false,
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{valueY}"
                    })
                }));

                series0.columns.template.setAll({
                    width: am5.percent(30),
                    tooltipY: 0,
                    strokeOpacity: 0
                });


                // Map data for both series
                var chartData = district_name.map(function (date, index) {
                    return {
                        "district_name": date,
                        "cnt": parseInt(cnt[index])
                    };
                });

                series0.data.setAll(chartData);

                var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));

                chart.appear(1000, 100);
                series0.appear();
            }); // end am5.ready()
        }
    });
}


function get_processing_times() {
    var requestData = {
        "action": "get_processing_times"
    };

    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {
            console.log("Raw Response Data:", responseData);
            var obj = JSON.parse(responseData);
            var newTime = parseFloat(obj.new_time) || 0;         // Ensure it's a number
            var renewalTime = parseFloat(obj.renewal_time) || 0; // Ensure it's a number

            console.log("New Time:", newTime);
            console.log("Renewal Time:", renewalTime);

            $('#chartdiv-seven').empty();  // Clear any previous chart content

            am5.ready(function () {
                // Create root element
                var root = am5.Root.new("chartdiv-seven");
                root._logo.dispose();

                // Set themes
                root.setThemes([am5themes_Animated.new(root)]);

                // Create chart
                var chart = root.container.children.push(am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    layout: root.verticalLayout
                }));

                var data = [{
                    category: "New",
                    time: newTime,  // Use dynamic time for "New" applications
                    columnSettings: {
                        fill: am5.color(0xFFB6C1)  // Peach color
                    }
                }, {
                    category: "Renewal",
                    time: renewalTime,  // Use dynamic time for "Renewal" applications
                    columnSettings: {
                        fill: am5.color(0xFF9B9B)  // Similar color to peach
                    }
                }];

                // Create axes
                var xRenderer = am5xy.AxisRendererX.new(root, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minGridDistance: 50
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "category",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root, {})
                }));

                xRenderer.grid.template.setAll({
                    location: 1
                });

                xRenderer.labels.template.setAll({
                    multiLocation: 0.5
                });

                xAxis.data.setAll(data);

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {
                        strokeOpacity: 0.1
                    }),
                    title: am5.Label.new(root, {
                        text: "Processing Time (days)",
                        fontSize: 15,
                        paddingTop: 10
                    })
                }));

                // Add series
                var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "time",
                    categoryXField: "category"
                }));

                series.columns.template.setAll({
                    tooltipText: "{categoryX}: {valueY} days",
                    width: am5.percent(70),  // Reduced width
                    tooltipY: 0,
                    strokeOpacity: 0,
                    templateField: "columnSettings"
                });

                series.data.setAll(data);

                // Make stuff animate on load
                series.appear();
                chart.appear(1000, 100);

            }); // end am5.ready()
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}

// Declare global variables
var root_gpt;
var chartInstance; // New global variable for the chart instance

function get_processing_times_mine() {
    var requestData = {
        "action": "get_processing_times"
    };

    $.ajax({
        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {
            // Log the raw response data

            console.log("Raw Response Data:", responseData);

            try {
                var obj = JSON.parse(responseData);
                //console.log("Parsed Object:", obj); // Log the parsed object

                // Ensure the new_time and renewal_time are present and are numbers
                var newTime = obj.new_time || 0;         // Default to 0 if null or undefined
                var renewalTime = obj.renewal_time || 0; // Default to 0 if null or undefined

                //console.log("New Time:", newTime, "Renewal Time:", renewalTime); // Log the times

                am5.ready(function () {
                    if (root_gpt) {
                        root_gpt.dispose();
                    }

                    // Create root_gpt element (now global)
                    root_gpt = am5.Root.new("chartdiv-seven");

                    root_gpt._logo.dispose();

                    // Set themes
                    root_gpt.setThemes([
                        am5themes_Animated.new(root_gpt)
                    ]);

                    // Create chart and assign to global variable
                    chartInstance = root_gpt.container.children.push(am5xy.XYChart.new(root_gpt, {
                        panX: false,
                        panY: false,
                        wheelX: "panX",
                        wheelY: "zoomX",
                        layout: root_gpt.verticalLayout
                    }));

                    var data = [{
                        category: "New",
                        time: newTime,  // Use the variable with the assigned value
                        columnSettings: {
                            fill: am5.color(0xFFB6C1)  // Peach color
                        }
                    }, {
                        category: "Renewal",
                        time: renewalTime,  // Use the variable with the assigned value
                        columnSettings: {
                            fill: am5.color(0xFF9B9B)  // Similar color to peach
                        }
                    }];

                    // Create axes
                    var xRenderer = am5xy.AxisRendererX.new(root_gpt, {
                        cellStartLocation: 0.1,
                        cellEndLocation: 0.9,
                        minGridDistance: 50
                    });

                    var xAxis = chartInstance.xAxes.push(am5xy.CategoryAxis.new(root_gpt, {
                        categoryField: "category",
                        renderer: xRenderer,
                        tooltip: am5.Tooltip.new(root_gpt, {})
                    }));

                    xRenderer.grid.template.setAll({
                        location: 1
                    });

                    xRenderer.labels.template.setAll({
                        multiLocation: 0.5
                    });

                    xAxis.data.setAll(data);

                    var yAxis = chartInstance.yAxes.push(am5xy.ValueAxis.new(root_gpt, {
                        renderer: am5xy.AxisRendererY.new(root_gpt, {
                            strokeOpacity: 0.1
                        }),
                        title: am5.Label.new(root_gpt, {
                            text: "Processing Time (minutes)",
                            fontSize: 15,
                            paddingTop: 10
                        })
                    }));

                    // Add series
                    var series = chartInstance.series.push(am5xy.ColumnSeries.new(root_gpt, {
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "time",
                        categoryXField: "category"
                    }));

                    series.columns.template.setAll({
                        tooltipText: "{categoryX}: {valueY} minutes",
                        width: am5.percent(70),  // Reduced width
                        tooltipY: 0,
                        strokeOpacity: 0,
                        templateField: "columnSettings"
                    });

                    series.data.setAll(data);

                    // Add export menu
                    var exporting = am5plugins_exporting.Exporting.new(root_gpt, {
                        menu: am5plugins_exporting.ExportingMenu.new(root_gpt, {})
                    });

                    // Make stuff animate on load
                    series.appear();
                    chartInstance.appear(1000, 100);

                }); // end am5.ready()

            } catch (e) {
                console.error("Error parsing JSON:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
        }
    });
}





function new_renewal_chart() {

    // alert('jii');

    showLoader();
    document.getElementById('chart_div').style.display = 'none';

    var date_type = $("#date_type").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();

    // alert("test1");
    var requestData = {



        "action": "get_application_counts",
        "date_type": date_type,
        "to_date": to_date,
        "from_date": from_date,
    };
    $.ajax({

        url: "folders/dashboard_chart/crud.php",
        type: 'POST',
        data: requestData,
        success: function (data) {
            hideLoader();

            document.getElementById('chart_div').style.display = 'block';
            var obj = JSON.parse(data);
            var new_count = obj.new_count;
            var renewal_cnt = obj.renewal_cnt;
            var district_name = obj.district_name;


            //Bar with Negative Values
            var options = {
                series: [{
                    name: 'New Application',
                    data: new_count
                },
                {
                    name: 'Renewal Application',
                    data: renewal_cnt
                }
                ],
                chart: {
                    type: 'bar',
                    height: 1000,
                    stacked: true
                },
                colors: ['#008FFB', '#FF4560'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '80%',
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 1,
                    colors: ["#fff"]
                },

                grid: {
                    xaxis: {
                        lines: {
                            show: false
                        }
                    }
                },
                yaxis: {
                    min: -2000,
                    max: 2000,
                    title: {
                        // text: 'Age',
                    },
                },
                tooltip: {
                    shared: false,
                    x: {
                        formatter: function (val) {
                            return val
                        }
                    },
                    y: {
                        formatter: function (val) {
                            return Math.abs(val)
                        }
                    }
                },
                colors: getChartColorsArray("negativeValuesChart"),

                xaxis: {
                    categories: district_name,
                    // title: {
                    //     text: 'Percent'
                    // },
                    labels: {
                        formatter: function (val) {
                            return Math.abs(Math.round(val))
                        }
                    }
                },
            };
            // if(typeof chart !== 'undefined'){
            // chart.destroy();
            // }
            var chart = new ApexCharts(document.querySelector("#negativeValuesChart"), options);
            chart.render();

        }

    })
}

function new_renewal_chart_filter(new_count, renewal_cnt, district_name) {
    // alert(new_count);
    // alert(renewal_cnt);
    // alert(district_name);
    // showLoader();
    // document.getElementById('chart_div').style.display = 'block';
    // document.getElementById('chart_div').style.display = 'none';

    //Bar with Negative Values
    var options = {
        series: [{
            name: 'New Application',
            data: new_count
        },
        {
            name: 'Renewal Application',
            data: renewal_cnt
        }
        ],
        chart: {
            type: 'bar',
            height: 1000,
            stacked: true
        },
        colors: ['#008FFB', '#FF4560'],
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '80%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },

        grid: {
            xaxis: {
                lines: {
                    show: false
                }
            }
        },
        yaxis: {
            min: -2000,
            max: 2000,
            title: {
                // text: 'Age',
            },
        },
        tooltip: {
            shared: false,
            x: {
                formatter: function (val) {
                    return val
                }
            },
            y: {
                formatter: function (val) {
                    return Math.abs(val)
                }
            }
        },
        colors: getChartColorsArray("negativeValuesChart"),

        xaxis: {
            categories: district_name,
            // title: {
            //     text: 'Percent'
            // },
            labels: {
                formatter: function (val) {
                    return Math.abs(Math.round(val))
                }
            }
        },
    };
    // if(typeof chart !== 'undefined'){
    //     chart.destroy();
    // }
    var chart = new ApexCharts(document.querySelector("#negativeValuesChart"), options);
    chart.render();

    // new_renewal_chart();

}





function get_applied_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();

    var data = {

        "district_name": district_name,
        "taluk_name": taluk_name,
        "hostel_name": hostel_name,
        "action": "get_applied_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var applied_cnt = obj.applied_cnt;
            // var accp_cnt = obj.accp_cnt;
            // var approved_cnt = obj.approved_cnt;
            // var rejected_cnt = obj.rejected_cnt;


            $('#appl_cnt').html(applied_cnt);
            // $('#accp_cnt').html(accp_cnt);
            // $('#appr_cnt').html(approved_cnt);
            // $('#rej_cnt').html(rejected_cnt);
        }
    });
}

function get_accept_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();

    var data = {

        "district_name": district_name,
        "taluk_name": taluk_name,
        "hostel_name": hostel_name,
        "action": "get_accept_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            // var applied_cnt = obj.applied_cnt;
            var accp_cnt = obj.accp_cnt;
            // var approved_cnt = obj.approved_cnt;
            // var rejected_cnt = obj.rejected_cnt;


            // $('#appl_cnt').html(applied_cnt);
            $('#accp_cnt').html(accp_cnt);
            // $('#appr_cnt').html(approved_cnt);
            // $('#rej_cnt').html(rejected_cnt);
        }
    });
}

function get_approved_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();

    var data = {

        "district_name": district_name,
        "taluk_name": taluk_name,
        "hostel_name": hostel_name,
        "action": "get_approved_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            // var applied_cnt = obj.applied_cnt;
            // var accp_cnt = obj.accp_cnt;
            var approved_cnt = obj.approved_cnt;
            // var rejected_cnt = obj.rejected_cnt;


            // $('#appl_cnt').html(applied_cnt);
            // $('#accp_cnt').html(accp_cnt);
            $('#appr_cnt').html(approved_cnt);
            // $('#rej_cnt').html(rejected_cnt);
        }
    });
}

function get_rejected_count() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");

    var district_name = $('#district_name').val();
    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();

    var data = {

        "district_name": district_name,
        "taluk_name": taluk_name,
        "hostel_name": hostel_name,
        "action": "get_rejected_count"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            // var applied_cnt = obj.applied_cnt;
            // var accp_cnt = obj.accp_cnt;
            // var approved_cnt = obj.approved_cnt;
            var rejected_cnt = obj.rejected_cnt;


            // $('#appl_cnt').html(applied_cnt);
            // $('#accp_cnt').html(accp_cnt);
            // $('#appr_cnt').html(approved_cnt);
            $('#rej_cnt').html(rejected_cnt);
        }
    });
}


function renderProductChart() {
    var requestData = {
        action: "fetch_chart_data" // Adjust this to the actual action you need
    };

    $.ajax({
        url: "folders/dashboard/crud.php",
        type: 'POST',
        data: requestData,
        success: function (responseData) {

            var obj = JSON.parse(responseData);
            var reject_reason = obj.reject_reason;
            var count = obj.count;

            var projectionData = Array(count.length).fill(500);

            var productColors = $("#high-performing-product_1").data("colors");
            var defaultColors = ["#00E396", "#008FFB"]; // Add your default colors here

            var productOptions = {
                chart: {
                    height: 256,
                    type: "bar",
                    stacked: true
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "20%"
                    }
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 0,
                    colors: ["transparent"]
                },
                series: [
                    { name: "Actual", data: count },
                    { name: "Projection", data: projectionData }
                ],
                zoom: { enabled: false },
                legend: { show: false },
                colors: productColors ? productColors.split(",") : defaultColors,
                xaxis: {
                    categories: reject_reason,
                    axisBorder: { show: false }
                },
                yaxis: {
                    tickAmount: 5,
                    min: 0,
                    max: 500,
                    labels: {
                        formatter: function (value) {
                            return value;
                        },
                        offsetX: -15
                    }
                },
                fill: { opacity: 1 },
                tooltip: {
                    y: {
                        formatter: function (value) {
                            return value;
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#high-performing-product_1"), productOptions);
            chart.render();
        },
        error: function (error) {
            console.error("Error fetching chart data: ", error);
        }
    });
}

function get_taluk() {


    var district_name = $('#district_name').val();

    var data = "district_name=" + district_name + "&action=district_name";

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
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
        success: function (data) {


            if (data) {
                $("#hostel_name").html(data);
            }
        }
    });

}
function get_region_details() {
    $("#loading-image").show();
    var month = $("#month_filter").val();
    var user_type_unique_id = $('#user_type_unique_id').val();
    ajax_url;
    var data =
    {
        "action": "region_details",
        "user_type_unique_id": user_type_unique_id,

        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    // alert(ajax_url);
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#region_details_div').html(data);
        }
    });
}

function get_top_most_completed() {
    var month = $("#month_filter").val();
    var data =
    {
        "action": "top_most_completed",
        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#top_most_completed').html(data);
        }
    });
}

function get_top_most_complaints() {
    var month = $("#month_filter").val();
    var data =
    {
        "action": "top_most_complaints",
        "month": month
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            $('#top_most_complaints').html(data);
        }
    });
}

function get_task_details() {

    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");
    var region_name = $("#region_name").val();
    var user_type_unique_id = $('#user_type_unique_id').val();
    var branch_name = $('#branch_name').val();

    var cate = $('#cate').val();
    var branch_id = $('#branch_id').val();
    $('#opening_complaints').empty();
    $('#new_complaints').empty();
    $('#completed_complaints').empty();
    $('#pending_complaints').empty();
    var data = {
        "region_name": region_name,
        "user_type_unique_id": user_type_unique_id,
        "branch_name": branch_name,
        "cate": cate,
        "branch_id": branch_id,
        "action": "task_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);

            var pending_complaints = obj.pending_complaints;


            if (pending_complaints == null) {

                var pending_count = '0';
            } else {
                var pending_count = obj.pending_complaints;
            }

            var opening_complaints = obj.opening_complaints;

            if (opening_complaints == null) {

                var opening_count = 0;
            } else {
                var opening_count = obj.opening_complaints;
            }

            var new_complaints = obj.new_complaints;

            if (new_complaints == null) {
                var new_count = 0;
            } else {
                var new_count = new_complaints;
            }

            var completed_complaints = obj.completed_complaints;

            if (completed_complaints == null) {
                var completed_count = 0;
            } else {
                var completed_count = completed_complaints;
            }

            $('#opening_complaints').html(opening_count);
            $('#new_complaints').html(new_count);
            $('#completed_complaints').html(completed_count);
            $('#pending_complaints').html(pending_count);
        }
    });
}

function overall_complaint_status() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "over_complaint_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var total_comp = obj.total_comp;
            var pending_comp = obj.pending_comp;
            var progressing_comp = obj.progressing_comp;
            var completed_comp = obj.completed_comp;
            var cancel_comp = obj.cancel_comp;

            $('#pending_comp').html(pending_comp);
            $('#progressing_comp').html(progressing_comp);
            $('#completed_comp').html(completed_comp);
            $('#total_comp').html(total_comp);
            $('#cancel_comp').html(cancel_comp);
        }
    });
}


function sourcewise_complaints() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "sourcewise_complaints"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var app = obj.app;
            var web = obj.web;
            var admin_portal = obj.admin_portal;
            var chatbot = obj.chatbot;

            $('#web').html(web);
            $('#admin').html(admin_portal);
            $('#chatbot').html(chatbot);
            $('#app').html(app);

        }
    });
}
function get_total_hostels() {




    var url = sessionStorage.getItem("list_link");

    var data =
    {
        "action": "total_hostels",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var hostel_count = obj.hostel_count;
            //    alert(hostel_count);
            $('#total_hostel').text(hostel_count);
        }
    });
}

function get_total_students() {


    var url = sessionStorage.getItem("list_link");

    var data =
    {
        "action": "total_students",

    };

    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var student_cnt = obj.student_name;

            $('#total_students').text(student_cnt);
        }
    });
}

function hostel_vaccancy() {


    var url = sessionStorage.getItem("list_link");

    var data =
    {
        "action": "hostel_vaccancy",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var hostel_vaccancy = obj.hostel_vaccancy;

            $('#total_hostel_vaccancy').text(hostel_vaccancy);
        }
    });
}


function get_total_staff() {


    var url = sessionStorage.getItem("list_link");

    var data =
    {
        "action": "total_staff_strength",

    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    $.ajax({
        url: ajax_url,
        type: 'POST',
        data: data,
        // hostel_count:hostel_count,
        success: function (data) {
            $("#loading-image").hide();
            var obj = JSON.parse(data);
            var data = obj.data;
            var staff_cnt = obj.staff_cnt;

            $('#total_staff').text(staff_cnt);
        }
    });
}

function student_applied_leave_details() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "applied_leave_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            // var no_of_days        = obj.no_of_days;
            var no_of_student_name = obj.no_of_student_name;

            $('#no_of_student_name').html(no_of_student_name);


        }
    });
}

function staff_applied_leave_details() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var url = sessionStorage.getItem("list_link");


    var data = {
        "action": "staff_applied_leave_details"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            var obj = JSON.parse(data);
            var data = obj.data;
            // var no_of_days        = obj.no_of_days;
            var staff_name = obj.staff_name;

            $('#staff_name').html(staff_name);


        }
    });
}


function district_wise_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (!ajax_url) {
        console.error("AJAX URL is missing");
        return;
    }

    var data = {
        "action": "district_wise_count"
    };

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (response) {
            // console.log("AJAX request successful");
            // console.log(response);

            var obj;
            try {
                obj = JSON.parse(response);
            } catch (e) {
                console.error("Error parsing JSON response:", e);
                return;
            }

            var district_names = obj.district_names;
            var reg_district = obj.reg_district;
            if (!Array.isArray(district_names) || !Array.isArray(reg_district) || district_names.length !== reg_district.length) {
                console.error("district_names or reg_district is not valid");
                return;
            }

            // drawBarChart(district_names, reg_district);
            // Clear any existing content
            $('#district_names').empty();
            // $('.dropdown-menu').empty();

            // Color classes to use
            var colors = ["text-primary", "text-danger", "text-success", "text-warning", "text-secondary", "text-info", "text-dark"];

            // Iterate over the data and dynamically create the elements
            for (var i = 0; i < district_names.length; i++) {
                var district_name = district_names[i];
                var count = reg_district[i];

                var colorClass = colors[i % colors.length]; // Cycle through colors

                // // Create the dropdown item
                // var dropdownItem = $('<a class="dropdown-item"></a>');
                // dropdownItem.text(district_name + ' (' + count + ')');
                // dropdownItem.addClass(colorClass);
                // $('.dropdown-menu').append(dropdownItem);

                // Create the list item
                var listItem = $('<p></p>');
                var icon = $('<i class="mdi mdi-square"></i>').addClass(colorClass);
                var name = $('<span></span>').text(district_name);
                var countSpan = $('<span class="float-end"></span>').text(count);
                listItem.append(icon).append(' ').append(name).append(countSpan);
                $('#district_names').append(listItem);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed:", textStatus, errorThrown);
        }
    });
}





function district_wise_count() {
    var ajax_url = sessionStorage.getItem("folder_crud_link");

    if (!ajax_url) {
        console.error("AJAX URL is missing");
        return;
    }

    var data = {
        "action": "district_wise_count"
    };

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (response) {
            // console.log("AJAX request successful");
            // console.log(response);

            var obj;
            try {
                obj = JSON.parse(response);
            } catch (e) {
                console.error("Error parsing JSON response:", e);
                return;
            }

            var district_names = obj.district_names;
            var reg_district = obj.reg_district;
            if (!Array.isArray(district_names) || !Array.isArray(reg_district) || district_names.length !== reg_district.length) {
                console.error("district_names or reg_district is not valid");
                return;
            }

            // drawBarChart(district_names, reg_district);
            // Clear any existing content
            $('#district_names').empty();
            // $('.dropdown-menu').empty();

            // Color classes to use
            var colors = ["text-primary", "text-danger", "text-success", "text-warning", "text-secondary", "text-info", "text-dark"];

            // Iterate over the data and dynamically create the elements
            for (var i = 0; i < district_names.length; i++) {
                var district_name = district_names[i];
                var count = reg_district[i];

                var colorClass = colors[i % colors.length]; // Cycle through colors

                // // Create the dropdown item
                // var dropdownItem = $('<a class="dropdown-item"></a>');
                // dropdownItem.text(district_name + ' (' + count + ')');
                // dropdownItem.addClass(colorClass);
                // $('.dropdown-menu').append(dropdownItem);

                // Create the list item
                var listItem = $('<p></p>');
                var icon = $('<i class="mdi mdi-square"></i>').addClass(colorClass);
                var name = $('<span></span>').text(district_name);
                var countSpan = $('<span class="float-end"></span>').text(count);
                listItem.append(icon).append(' ').append(name).append(countSpan);
                $('#district_names').append(listItem);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX request failed:", textStatus, errorThrown);
        }
    });
}


// Call the function to load the data
$(document).ready(function () {
    district_wise_counts();
});

// function drawBarChart(labels, data) {
//     var ctx = document.getElementById('my_Chart').getContext('2d');
//     var myChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: labels,
//             datasets: [{
//                 label: 'District-wise Count',
//                 data: data,
//                 backgroundColor: 'rgba(54, 162, 235, 0.2)',
//                 borderColor: 'rgba(54, 162, 235, 1)',
//                 borderWidth: 1
//             }]
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             }
//         }
//     });
// }



// Call the function to load the data


function new_external_window_print(event, url, status) {

    var district_name = $('#district_name').val();

    var taluk_name = $('#taluk_name').val();
    var hostel_name = $('#hostel_name').val();
    var link = url + '?status=' + status + '&district_name=' + district_name + '&taluk_name=' + taluk_name + '&hostel_name=' + hostel_name;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover', 'height=550,width=950,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}


function new_external_window_print_new(event, url) {

    var link = url;
    // window.location=link;
    onmouseover = window.open(link, 'onmouseover', 'height=650,width=1050,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
}


