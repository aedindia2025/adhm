<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" />
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" />
<style>
    .table100 th {
        font-size: 15px;
        color: #262424;
        background-color: #e4dede !important;
        padding: 10px !important;
    }

    .table100 tr td {

        padding: 10px !important;
    }

    .table100 tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    a.new-li i {
        color: #F04100;
        font-size: 17px;
        padding: 3px;
        display: inline-flex;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 1px solid #b7b7b7;
        margin: 0px 2px;
    }

    a.new-li i:hover {
        background: #00aff0;
        color: #fff;
    }
    .dt-right {
    text-align: right !important;
}
.dt-center {
    text-align: center !important;
}

.load {
        text-align: center;
        position: absolute;
        top: 17%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;

    }

    i.mdi.mdi-loading.mdi-spin {
        font-size: 75px;
        color: #17a8df;
    }
</style>
<!-- <link href="../../assets/datatable/datatable.bootstrap.min.css" />
           <link href="../../assets/datatable/bootstrap.min.css" /> -->
<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }
}

$taluk_name_list = taluk_name("",$_SESSION['district_id']);
$taluk_name_list = select_option($taluk_name_list, "Select Taluk", $taluk_name);

// $hostel_name_list = hostel_name();
// $hostel_name_list = select_option_host($hostel_name_list, "Select Hostel", $hostel_name);

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
                            <form class="d-flex">
                                <!-- <?php echo btn_add($btn_add); ?> -->
                                <input type="hidden" id="csrf_token" name="csrf_token"
                                    value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <input type="hidden" id="district_id" name="district_id"
                                    value="<?php echo $_SESSION['district_id']; ?>">
                            </form>
                        </div>
                        <h4 class="page-title">Establishment Report</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row mb-2">

                            

                                
                                <div class="col-md-3">
                                <label class="form-label" for="example-select">Taluk Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="taluk_name" name="taluk_name" onchange=get_hostel()>
                                        <?php echo $taluk_name_list; ?>
                                       
                                    </select>
                                </div>
                                <div class="col-md-3">
                                <label class="form-label" for="example-select">Hostel Name</label>
                                    <select class="form-control select2" data-toggle="select2" id="hostel_name" name="hostel_name">
                                        <?php echo $hostel_name_list;?>
                                        
                                    </select>
                                </div>
                                
                                <div class="col-md-2 align-self-center mt-3">
                                    <div class="page-title-right">
                                        <button class="btn btn-primary" onclick="filter_records()" style="float:left;">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div></div>
</div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                        <button type="button" id="export_establishment" name="export_establishment"
                                class="btn   waves-effect waves-light wavw  mb-1"
                                style="background: #337734;color: #fff;font-size:16px;">
                                <i class="ri-file-excel-2-fill"
                                    style="font-size: 22px; padding-right: 10px;line-height: 0px;vertical-align: middle;"></i>Export
                            </button>
                            <br>
                            <div class="row">
                                <div class="col-md-12 load" id="loader">
                                    <i class="mdi mdi-loading mdi-spin"></i>
                                </div>
                            </div>
                            <table id="establishment_report_datatable" class="table  w-100 table100">
                                <thead>
                                    <tr>
                                        <th>S No</th>
                                        <th>Taluk</th>
                                        <th>Hostel Name</th>
                                        <th>Warden</th>
                                        <th>Warden Incharge</th>
                                        <th>Cook</th>
                                        <th>Deputation Cook</th>
                                        <th>Watchman</th>
					<th>Sweeper</th>
                                        
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
    var table = $('#stu_count_datatable').DataTable({
        "processing": true,
        "serverSide": false,
        "ajax": {
            "url": "../../../adw_report_data/dadwo_student_occupancy.php",
            "type": "POST",
            "data": function (d) {
                // Add CSRF token and filter params to the request
                d.csrf_token = $('#csrf_token').val();
                d.district_name = $('#district_id').val(); // District filter
               
            },
            "dataSrc": function (json) {
                // Process and return data for the DataTable
                return json;
            }
        },
        "columns": [
            { "data": null, "title": "S.No", "render": function (data, type, row, meta) { return meta.row + 1; } },
            { "data": "hostel_type", "title": "Hostel Type" },
            { "data": "total_cnt", "title": "Total Capacity" },
            { "data": "cnt", "title": "Current Occupancy" },
            { "data": "rate", "title": "Occupancy Rate (%)" }
         
        ],
        "dom": 'Bfrtip',
        "searching": false,
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "pageLength": 10,
        "order": [[1, 'asc']],
        "responsive": true
    });

    // Filter button click event
    $('#filter_btn').click(function (event) {
        event.preventDefault(); // Prevent default form submission
        table.ajax.reload(); // Reload DataTable with filters
    });

});

</script>
