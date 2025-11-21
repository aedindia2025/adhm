<?php include '../folders/indent_count/indent_count.js'; ?>

<style>
    .prm {
        margin-top: 5px;
    }

    .foot {
        float: right;
    }
</style>

<?php
$firstDay = date('Y-m-01');
$lastDay = date('Y-m-t');
?>

<style>
    .modal-new {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(240, 240, 240, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        display: none;
        z-index: 9999;
    }

    /* Modal content box */
    .modal-content-new {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 350px;
    }

    .loader-new {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        margin-bottom: 20px;
    }

    .loader-new div {
        width: 6px;
        height: 40px;
        background: #333;
        animation: bounce 1.2s infinite ease-in-out;
    }

    .loader-new div:nth-child(1) {
        animation-delay: -0.4s;
    }

    .loader-new div:nth-child(2) {
        animation-delay: -0.2s;
    }

    .loader-new div:nth-child(3) {
        animation-delay: 0s;
    }

    .loader-new div:nth-child(4) {
        animation-delay: -0.2s;
    }

    .loader-new div:nth-child(5) {
        animation-delay: -0.4s;
    }

    @keyframes bounce {
        0%,
        100% {
            transform: scaleY(0.4);
        }
        50% {
            transform: scaleY(1);
        }
    }
</style>

<div class="modal fade" id="indentModal" tabindex="-1" aria-labelledby="indentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #c4cacdff; color: #0d0d0dff;">
                <h5 class="modal-title" id="indentModalLabel">
                    <i class="mdi mdi-clipboard-text-outline me-2"></i>
                    Raise Indent
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="indentForm">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold">District Name</label>
                                <div class="form-control-plaintext"><?php echo $_SESSION['district_name']; ?></div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Month/Year</label>
                                <div class="form-control-plaintext"><?php echo date("F Y"); ?></div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="table-responsive"> -->
                    <div style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-striped" id="hostelsTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)"></th>
                                    <th>S.No</th>
                                    <th>Hostel ID</th>
                                    <th>Taluk</th>
                                    <th>Hostel Name</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </form>
            </div>

            <div class="row mb-2 foot justify-content-end">
                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label fw-bold">From Date</label>
                        <input type="date" class="form-control"
                            id="from_indent_date"
                            min="<?php echo $firstDay; ?>"
                            max="<?php echo $lastDay; ?>" value="<?php echo date('Y-m-d') ?>">

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <label class="form-label fw-bold">To Date</label>
                        <input type="date" class="form-control"
                            id="to_indent_date"
                            min="<?php echo $firstDay; ?>"
                            max="<?php echo $lastDay; ?>" value="<?php echo date('Y-m-d') ?>">

                    </div>
                </div>
                <div class="col-md-2 mt-3">
                    <button type="button" class="btn btn-primary prm" onclick="submitIndent()">
                        <i class="mdi mdi-check me-1"></i>Raise Indent
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #c4cacdff; color: #0d0d0dff;">
                <h5 class="modal-title">Indent Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="response" id="response"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal-new" id="modal-new">
        <div class="modal-content-new">
            <div class="loader-new">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="welcome-text">Loading please wait...</div>
        </div>
</div>

<script>
    // Initialize DataTable when modal opens
    // function submitIndent() {
    //     let selectedIds = [];
    //     var from_indent_date = $('#from_indent_date').val();
    //     var to_indent_date = $('#to_indent_date').val();

    //     $(".row-check:checked").each(function() {
    //         selectedIds.push("'" + $(this).val() + "'");
    //     });

    //     let finalString = selectedIds.join(",");

    //     if (selectedIds.length === 0) {
    //         alert("Please select at least one hostel.");
    //         return;
    //     }

    //     console.log("Selected Unique IDs:", selectedIds);
    //     var data = {
    //         'hostel_ids': selectedIds,
    //         'action': 'indent_raise',
    //         'from_indent_date': from_indent_date,
    //         'to_indent_date': to_indent_date
    //     };

    //     // send to PHP
    //     $.ajax({
    //         url: "inc/indent_crud.php",
    //         type: "POST",
    //         data: data,
    //         // success: function(response) {
    //         //     console.log(response);
    //         // }
    //         dataType: "json",
    //     success: function (response) {

    //         // Build professional output HTML
    //         let html = `${response.html}`;

    //         // Insert into the response div
    //         $("#response").html(html);
    //     },
    //     error: function (xhr, status, error) {
    //         console.log("Error:", error);
    //         $("#response").html("<p style='color:red;'>An error occurred while generating indent.</p>");
    //     }
    //     });
    // }


    function submitIndent() {
    let selectedIds = [];
    var from_indent_date = $('#from_indent_date').val();
    var to_indent_date = $('#to_indent_date').val();

    $(".row-check:checked").each(function () {
        selectedIds.push("'" + $(this).val() + "'");
    });

    if (selectedIds.length === 0) {
        alert("Please select at least one hostel.");
        return;
    }

    var data = {
        'hostel_ids': selectedIds,
        'action': 'indent_raise',
        'from_indent_date': from_indent_date,
        'to_indent_date': to_indent_date
    };

    // SHOW THE LOADER
    document.getElementById('modal-new').style.display = 'inline-flex';

    $.ajax({
        url: "inc/indent_crud.php",
        type: "POST",
        data: data,
        dataType: "json",

        success: function (response) {

            // Insert HTML into modal body
            $("#response").html(response.html);

            // Close the current modal
            $("#indentModal").modal("hide");

            // Open the response modal
            $("#responseModal").modal("show");
        },

        error: function (xhr, status, error) {
            $("#response").html("<p style='color:red;'>An error occurred while generating indent.</p>");
            $("#indentModal").modal("hide");
            $("#responseModal").modal("show");
        },
        complete: function () {
            // ALWAYS hide loader (success or failure)
            document.getElementById('modal-new').style.display = 'none';
        }
    });
}

    function toggleSelectAll(checkbox) {
        let isChecked = checkbox.checked;
        document.querySelectorAll(".row-check").forEach(cb => {
            cb.checked = isChecked;
        });
    }

    function toggleRowCheckbox() {
        let total = document.querySelectorAll(".row-check").length;
        let checked = document.querySelectorAll(".row-check:checked").length;

        document.getElementById("selectAll").checked = (total === checked);
    }


    var table_id = 'hostelsTable';
    var action = 'datatable';



    function datatable(table_id = "", form_name = "", action = "") {

        var table = $("#" + table_id);
        var data = {
            "action": action,
        };

        var ajax_url = 'inc/indent_crud.php';
        var datatable = table.DataTable({
            paging: false,
            ordering: false,
            searching: true,
            responsive: false,


            // searching: true,
            "ajax": {
                url: ajax_url,
                type: "POST",
                data: data
            },
            dom: 'frtip',

        });
    }



    function displayHostelsTable(hostels) {
        let html = '';
        hostels.forEach((hostel, index) => {
            html += `
            <tr>
                <td><input type="checkbox" class="form-check-input" value="${hostel.unique_id}"></td>
                <td>${index + 1}</td>
                <td>${hostel.hostel_id}</td>
                <td>${hostel.hostel_name}</td>
                <td>${hostel.taluk_name || 'N/A'}</td>
            </tr>
        `;
        });

        $('#hostelsTable tbody').html(html);

        // Initialize DataTable
        $('#hostelsTable').DataTable({
            "paging": true,
            "pageLength": 10,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    }



    // Clean up DataTable when modal closes
    $('#indentModal').on('hidden.bs.modal', function() {
        if ($.fn.DataTable.isDataTable('#hostelsTable')) {
            $('#hostelsTable').DataTable().destroy();
        }
    });
</script>