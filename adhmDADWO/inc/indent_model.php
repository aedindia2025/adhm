
                    <?php include '../folders/indent_count/indent_count.js'; ?>

<style>
  
</style>
<?php
$firstDay = date('Y-m-01');                        // 2025-01-01
$lastDay  = date('Y-m-t');                         // 2025-01-31 (auto-calculated)
?>

<div class="modal fade" id="indentModal" tabindex="-1" aria-labelledby="indentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #00afef; color: #fff;">
                <h5 class="modal-title" id="indentModalLabel">
                    <i class="mdi mdi-clipboard-text-outline me-2"></i>
                    Indent Raise
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form id="indentForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">District Name</label>
                                <div class="form-control-plaintext"><?php echo $_SESSION['district_name'];?></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Month/Year</label>
                                <div class="form-control-plaintext"><?php echo date("F Y");?></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">From Date</label>
                                <input type="date" class="form-control"
       id="from_indent_date"
       min="<?php echo $firstDay; ?>"
       max="<?php echo $lastDay; ?>" value="<?php echo date('Y-m-d') ?>">

                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">To Date</label>
                               <input type="date" class="form-control"
       id="to_indent_date" 
       min="<?php echo $firstDay; ?>"
       max="<?php echo $lastDay; ?>" value="<?php echo date('Y-m-d') ?>">

                            </div>
                        </div>
                    </div>

                    <!-- <div class="table-responsive"> -->
                        <div style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-bordered table-striped" id="hostelsTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">Select All</th>
                                    <th>S.No</th>
                                    <th>Hostel ID</th>
                                    <th>Taluk</th>
                                    <th>Hostel Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="submitIndent()">
                    <i class="mdi mdi-check me-1"></i>Raise Indent
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize DataTable when modal opens
function submitIndent() {
    let selectedIds = [];

    $(".row-check:checked").each(function () {
    selectedIds.push("'" + $(this).val() + "'"); 
});

    let finalString = selectedIds.join(",");

    if (selectedIds.length === 0) {
        alert("Please select at least one hostel.");
        return;
    }

    console.log("Selected Unique IDs:", selectedIds);
   
return;
    // send to PHP
    $.ajax({
        url: "yourCrudPage.php",
        type: "POST",
        data: { hostel_ids: selectedIds },
        success: function(response){
            console.log(response);
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


function loadHostelsData() {
    // Show loading
    alert();
    $('#hostelsTable tbody').html('<tr><td colspan="5" class="text-center">Loading hostels...</td></tr>');
    
    $.ajax({
        type: "POST",
        url: "indent_crud.php",
        data: {
            action: "datatable"
        },
        success: function(data) {
            try {
                const response = JSON.parse(data);
                if (response.status && response.data) {
                    displayHostelsTable(response.data);
                } else {
                    $('#hostelsTable tbody').html('<tr><td colspan="5" class="text-center">No hostels found</td></tr>');
                }
            } catch (e) {
                $('#hostelsTable tbody').html('<tr><td colspan="5" class="text-center">Error loading data</td></tr>');
            }
        },
        error: function() {
            $('#hostelsTable tbody').html('<tr><td colspan="5" class="text-center">Failed to load hostels</td></tr>');
        }
    });
}

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